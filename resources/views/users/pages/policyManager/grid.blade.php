@extends('users.layouts.app')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<div class="main-panel">        
  <div class="content-wrapper">
      <div class="row">
          @foreach ($policyTypes as $policyType)
          <div class="col-md-4 grid-margin stretch-card">
              <div class="card shadow-sm">
                  <div class="card-body text-center">
                      <!-- Policy Icon -->
                      <div class="mb-3">
                          <i class="fas fa-shield-alt fa-3x text-primary"></i>
                      </div>

                      <!-- Policy Name -->
                      <h4 class="card-title">{{ $policyType->name }}</h4>
                      
                      <!-- Policy Price -->
                      <h5 class="text-success mb-3">
                          <i class="fas fa-tag"></i> GHS {{ number_format($policyType->price, 2) }}
                      </h5>
                      
                      <!-- Policy Description -->
                      <p class="card-description text-muted">{{ $policyType->description }}</p>
                      
                      <!-- Buy Button -->
                      <button 
                          type="button" 
                          class="btn btn-info btn-lg btn-block" 
                          onclick="window.location.href='{{ route('policy.buy', $policyType->id) }}'">
                          <i class="fas fa-shopping-cart"></i> Buy {{ $policyType->name }}
                      </button>
                  </div>
              </div>
          </div>
          @endforeach
      </div>
  </div>
</div>
@endsection