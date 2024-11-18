@extends('admin.layouts.app')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Policy Type</h4>
                            <form action="{{ route('admin.policy.types.store') }}" method="POST" class="forms-sample">
                                @csrf
                                <div class="form-group">
                                    <label for="policyTypeName">Policy Type Name</label>
                                    <input type="text" class="form-control" id="policyTypeName" name="name"
                                        placeholder="Enter policy type name" required>
                                </div>
                                <div class="form-group">
                                    <label for="policyTypePrice">Policy Type Price</label>
                                    <input type="number" class="form-control" id="policyTypePrice" step="0.01"
                                        min="0" name="price" placeholder="Enter policy type Price" required>
                                </div>
                                <div class="form-group">
                                    <label for="policyTypeDescription">Policy Type Description</label>
                                    <textarea id="description" class="form-control" id="policyTypeDescription" name="description" rows="4"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary mr-2">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
