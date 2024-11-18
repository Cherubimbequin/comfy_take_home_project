@extends('admin.layouts.app')

@section('content')
<div class="main-panel">        
    <div class="content-wrapper">
      <div class="row"> 
        <div class="col-12 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Edit Policy Type</h4>
              {{-- <p class="card-description">
               Edit Policy Type
              </p> --}}
              <form action="{{ route('admin.policy.type.update', $policyType->id) }}" method="POST" class="forms-sample">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="policyTypeName">Policy Type Name</label>
                    <input type="text" class="form-control" id="policyTypeName" name="name" value="{{ $policyType->name }}" placeholder="Enter policy type name" required>
                </div>
                <div class="form-group">
                  <label for="policyTypePrice">Policy Type Price</label>
                  <input type="amount" class="form-control" step="0.01" min="0" id="policyTypePrice" name="price" value="{{ $policyType->name }}" placeholder="Enter policy type Price" required>
              </div>
              <div class="form-group">
                <label for="policyTypeDescription">Policy Type Description</label>
                <textarea id="description" class="form-control" id="policyTypeDescription" name="description" rows="4">{{ old('description', $policyType->description ?? '') }}</textarea>
            </div>
                <button type="submit" class="btn btn-primary mr-2">Update</button>
            </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection