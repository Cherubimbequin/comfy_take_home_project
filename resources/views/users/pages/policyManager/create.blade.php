@extends('users.layouts.app')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Purchase {{ $policyType->name }}</h4>
                            <p class="card-description">{{ $policyType->description }}</p>
                            <h5>Price: GHS {{ number_format($policyType->price, 2) }}</h5>

                            <!-- Policy Form -->
                            <form id="policy-form" class="forms-sample">
                                @csrf
                                <!-- Auto-Generated Policy Number -->
                                <input type="hidden" name="policy_number" value="{{ $policyNumber }}">
                                <input type="hidden" name="policy_type_id" value="{{ $policyType->id }}">
                                <input type="hidden" name="premium_amount" value="{{ $policyType->price }}">
                                <input type="hidden" name="start_date" id="startDateField">
                                <input type="hidden" name="end_date" id="endDateField">
                                <input type="hidden" name="next_of_kin" id="nextOfKinField">

                                <div class="form-group">
                                    <label for="policyType">Policy Type</label>
                                    <input type="text" class="form-control" id="policyType"
                                        value="{{ $policyType->name }}" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="premiumAmount">Price</label>
                                    <input type="text" class="form-control" id="premiumAmount"
                                        value="GHS {{ number_format($policyType->price, 2) }}" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="startDate">Start Date</label>
                                    <input type="datetime-local" class="form-control" id="startDate" required>
                                </div>

                                <div class="form-group">
                                    <label for="endDate">End Date</label>
                                    <input type="datetime-local" class="form-control" id="endDate" required>
                                </div>

                                <div class="form-group">
                                    <label for="nextOfKin">Next of Kin</label>
                                    <input type="text" class="form-control" id="nextOfKin" required>
                                </div>

                                <button type="button" class="btn btn-primary btn-lg btn-block"
                                    onclick="showCheckoutModal()">
                                    Proceed to Payment
                                    <i class="ti-credit-card float-right"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Paystack Inline Script -->
    <script src="https://js.paystack.co/v1/inline.js"></script>
    <script>
        function showCheckoutModal() {
            // Get form values
            const nextOfKin = document.getElementById('nextOfKin').value;
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;

            // Validate form fields
            if (!nextOfKin || !startDate || !endDate) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please fill out all fields!',
                });
                return;
            }

            // Populate hidden fields for Axios submission
            document.getElementById('nextOfKinField').value = nextOfKin;
            document.getElementById('startDateField').value = startDate;
            document.getElementById('endDateField').value = endDate;

            // Confirm before proceeding
            Swal.fire({
                title: 'Confirm Details',
                html: `
                    <p>Next of Kin: <strong>${nextOfKin}</strong></p>
                    <p>Start Date: <strong>${startDate}</strong></p>
                    <p>End Date: <strong>${endDate}</strong></p>
                    <p>Do you want to proceed to payment?</p>
                `,
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Proceed to Payment',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    processPayment();
                }
            });
        }

        function processPayment() {
            const form = document.getElementById('policy-form');
            const policyNumber = form.policy_number.value;
            const amount = form.premium_amount.value * 100; // Convert to kobo
            const email = "{{ auth()->user()->email }}"; // Logged-in user's email

            const handler = PaystackPop.setup({
                key: "{{ config('services.paystack.public_key') }}",
                email: email,
                amount: amount,
                currency: 'GHS',
                metadata: {
                    custom_fields: [{
                        display_name: "Policy Number",
                        variable_name: "policy_number",
                        value: policyNumber,
                    }],
                },
                callback: function(response) {
                    // Payment successful, submit form via Axios
                    Swal.fire({
                        title: 'Processing Payment',
                        text: 'Please wait...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                    });

                    axios.post('{{ route('policy.store') }}', {
                            _token: '{{ csrf_token() }}',
                            policy_number: form.policy_number.value,
                            policy_type_id: form.policy_type_id.value,
                            premium_amount: form.premium_amount.value,
                            start_date: form.start_date.value,
                            end_date: form.end_date.value,
                            next_of_kin: form.next_of_kin.value,
                            reference: response.reference, // Paystack transaction reference
                        })
                        .then(function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.data.message,
                            }).then(() => {
                                window.location.href = '{{ route('all.policy.user') }}';
                            });
                        })
                        .catch(function(error) {
                            console.error('Error:', error.response);
                            Swal.fire({
                                icon: 'error',
                                title: 'Payment Failed',
                                text: error.response?.data?.error ||
                                    'An error occurred. Please try again.',
                            });
                        });

                },
                onClose: function() {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Payment Cancelled',
                        text: 'Transaction was not completed.',
                    });
                },
            });

            handler.openIframe();
        }
    </script>
@endsection
