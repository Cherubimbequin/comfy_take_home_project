@extends('admin.layouts.app')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Policy Types</h4>
                            <div class="table-responsive">
                                {{ $dataTable->table() }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <script>
        function handleDelete(policyTypeId) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to delete this policy type?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Please wait while your request is being processed.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                    });

                    fetch(`/policy/type/${policyTypeId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                            },
                        })
                        .then((response) => response.json())
                        .then((data) => {
                            Swal.close(); // Close the loading indicator

                            if (data.message) {
                                Swal.fire('Deleted!', data.message, 'success').then(() => location.reload());
                            } else {
                                Swal.fire('Error!', data.error || 'An error occurred.', 'error');
                            }
                        })
                        .catch((error) => {
                            console.error('Error:', error);
                            Swal.close(); // Close the loading indicator
                            Swal.fire('Error!', 'An unexpected error occurred.', 'error');
                        });
                }
            });
        }
    </script>
@endsection
@section('scripts')
    {{ $dataTable->scripts() }}
@endsection
