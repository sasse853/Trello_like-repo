@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg p-4" style="max-width: 500px; width: 100%; background: rgba(255, 255, 255, 0.9); border-radius: 10px;">
        <h2 class="text-center mb-4">Modifier le Board</h2>
        
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
@endsection

@section('styles')
<style>
    /* Ajout de l'image de fond */
    body {
        background: url('images/image1.jpg')  no-repeat center center fixed;
        background-size: cover;
    }
</style>
@endsection
