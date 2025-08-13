@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-8 mx-auto">

            <!-- Carte de modification du board -->
            <div class="card shadow-lg mb-4">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0">Modifier le Board</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('boards.update', $board) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Nom :</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ $board->name }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description :</label>
                            <textarea id="description" name="description" class="form-control" rows="4">{{ $board->description }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Modifier</button>
                    </form>
                </div>
            </div>

            <!-- Carte pour inviter un collaborateur -->
            <div class="card shadow-lg mb-4">
                <div class="card-header bg-info text-white">
                    <h3 class="mb-0">Inviter un collaborateur</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('boards.invite', ['board' => $board->id]) }}" method="POST" class="row g-3 align-items-end">
                        @csrf
                        <div class="col-md-8">
                            <label for="email" class="form-label">Email du collaborateur :</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-info text-white w-100">
                                <i class="bi bi-envelope-plus me-2"></i>Inviter
                            </button>
                        </div>
                    </form>

                    @if(session('success'))
                        <div class="alert alert-success mt-3">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger mt-3">
                            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Carte pour la liste des membres -->
            <div class="card shadow-lg">
                <div class="card-header bg-secondary text-white">
                    <h3 class="mb-0">Membres du board</h3>
                </div>
                <div class="card-body">
                    @if($board->members->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($board->members as $member)
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <div class="d-flex align-items-center">
                                    @php
                                        $hash = md5($member->name);
                                        $color = '#' . substr($hash, 0, 6);
                                    @endphp
                                    <div class="avatar-circle me-2" style="background-color: {{ $color }};">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                        {{ strtoupper(substr(explode(' ', $member->name)[1] ?? '', 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="fw-medium">{{ $member->name }}</span>
                                        <span class="text-muted ms-2">({{ $member->email }})</span>
                                    </div>
                                </div>
                                <form action="{{ route('boards.members.remove', ['board' => $board->id, 'member' => $member->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer ce membre ?');">
                                        <i class="bi bi-person-x me-1"></i>Supprimer
                                    </button>
                                </form>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-people text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted mt-2">Aucun membre pour l'instant</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    body.canvas {
        background: url('https://images.unsplash.com/photo-1557682260-96773eb01377?auto=format&fit=crop&w=1920&q=80') no-repeat center center fixed;
        background-size: cover;
        animation: backgroundZoom 30s ease-in-out infinite alternate;
    }

    @keyframes backgroundZoom {
        0% {
            background-size: 100% auto;
        }
        100% {
            background-size: 110% auto;
        }
    }

    .card {
        border: none;
        border-radius: 10px;
        overflow: hidden;
    }

    .card-header {
        border-bottom: none;
        padding: 1rem 1.5rem;
    }

    .btn {
        border-radius: 5px;
        padding: 0.5rem 1rem;
    }

    .avatar-circle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: #6c757d;
        color: white;
        font-weight: bold;
        font-size: 0.9rem;
        text-transform: uppercase;
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (!document.body.classList.contains('canvas')) {
            document.body.classList.add('canvas');
        }
    });
</script>
@endpush
