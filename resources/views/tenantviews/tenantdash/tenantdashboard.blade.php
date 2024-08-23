@extends('tenantviews.tenantlayout.tenantlayout')

@section('content')
    <!-- Main Content -->
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">
                Dashboard
            </div>
            <div class="card-body">
                <h5 class="card-title">Welcome!</h5>
                <p class="card-text">This is your dashboard {{ $tenantName }}.</p>
                <p class="card-text">You can manage users, boarding houses, and more from here.</p>
            </div>
        </div>
    </div>
@endsection
