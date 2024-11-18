@extends('admin.layouts.app')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">All Users</h4>
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
        function confirmBlock(userId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to block this user?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, block user!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the hidden form
                    document.getElementById('delete-form-' + userId).submit();
                }
            });
        }
    </script>
@endsection
@section('scripts')
    {{ $dataTable->scripts() }}
@endsection
