@extends('front.layout.app')

@section('main')
<section class="d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="text-center">

        <h1 class="display-1 fw-bold text-primary">404</h1>

        <h3 class="mb-3">Page Not Found</h3>

        <p class="text-muted mb-4">
            Sorry, the page you are looking for doesn’t exist or has been moved.
        </p>

        <a href="{{ route('profile.page') }}" class="btn btn-primary me-2">
            Go to Profile
        </a>

        <a href="{{ url('/') }}" class="btn btn-outline">
            Go Home
        </a>

    </div>
</section>
@endsection
