@extends('layouts.app')

@section('content')
    <h1>Liste des Boards</h1>
    <a href="{{ route('boards.create') }}">Créer un Board</a>
    <ul>
        @foreach($boards as $board)
            <li>
                <a href="{{ route('boards.show', $board) }}">{{ $board->name }}</a>
                <a href="{{ route('boards.edit', $board) }}">Modifier</a>
                <form action="{{ route('boards.destroy', $board) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Supprimer</button>
                </form>
                <a href="{{ route('invitation') }}">Inviter un membre</a>
                <a href="{{ route('listes.index', $board->id) }}">Ouvrir projet</a>
            </li>
        @endforeach
    </ul>
@endsection