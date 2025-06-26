@extends('layouts.app')

@section('content')
    <div class="container d-flex justify-content-center mt-5">
        <div class="card shadow-lg border-0 rounded-lg" style="width: 28rem;">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0 text-center">{{ $board->name }}</h3>
            </div>
            <div class="card-body">
                <p class="text-muted">{{ $board->description }}</p>
            </div>
        </div>
    </div>
@endsection
