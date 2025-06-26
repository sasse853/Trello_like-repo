@extends('layouts.app')

@section('content')
    <h1>{{ $board->name }}</h1>
    <p>{{ $board->description }}</p>
    <a href="{{ route('boards.edit', $board) }}">Modifier</a>
    <form action="{{ route('boards.destroy', $board) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit">Supprimer</button>
    </form>
    <a href="{{ route('boards.index') }}">Retour à la liste</a>
    <a href="{{ route('invitation') }}">Inviter un membre</a>
@endsection