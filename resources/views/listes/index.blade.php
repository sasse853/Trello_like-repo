@extends('layouts.app')

@section('content')
<div class="board-container">
    <h1>{{ $board->name }} - Listes</h1>

    <div class="lists-container">
        @foreach ($lists as $list)
            <div class="list-card">
                <h3>{{ $list->name }}</h3>

                <ul>
                    @foreach ($list->items as $item)
                    <li class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <input type="checkbox" class="toggle-item me-2" data-id="{{ $item->id }}" 
                                {{ $item->is_completed ? 'checked' : '' }}>
                            {{ $item->name }}
                        </div>
                        <form action="{{ route('list_items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Supprimer cet élément ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">✕</button>
                        </form>
                    </li>
                @endforeach
                </ul>

                <form action="{{ route('list_items.store', $list->id) }}" method="POST" class="add-item-form">
                    @csrf
                    <input type="text" name="name" placeholder="Nouvel élément" required>
                    <button type="submit">Ajouter</button>
                </form>
            </div>
        @endforeach
    </div>

    <form action="{{ route('listes.store', $board->id) }}" method="POST" class="create-list-form">
        @csrf
        <input type="text" name="name" placeholder="Nouvelle liste" required>
        <button type="submit">Créer une liste</button>
    </form>
</div>

<style>
    .board-container {
        min-height: 100vh;
        background-image: url('https://images.unsplash.com/photo-1557804506-669a67965ba0?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        background-repeat: no-repeat;
        padding: 20px;
        position: relative;
    }

    /* Overlay pour améliorer la lisibilité */
    .board-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.3);
        z-index: -1;
    }

    .board-container h1 {
        color: white;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        margin-bottom: 30px;
    }

    .lists-container {
        display: flex;
        gap: 20px;
        overflow-x: auto;
        padding-bottom: 20px;
    }

    .list-card {
        width: 250px;
        padding: 15px;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(5px);
        flex-shrink: 0;
    }

    .list-card h3 {
        margin-top: 0;
        color: #333;
        border-bottom: 2px solid #007bff;
        padding-bottom: 10px;
    }

    .add-item-form {
        margin-top: 15px;
        display: flex;
        gap: 5px;
        align-items: center;
    }

    .add-item-form input {
        flex: 1;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .add-item-form button {
        padding: 8px 12px;
        background: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .create-list-form {
        margin-top: 30px;
        padding: 20px;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        display: flex;
        gap: 10px;
        max-width: 400px;
    }

    .create-list-form input {
        flex: 1;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .create-list-form button {
        padding: 10px 15px;
        background: #28a745;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .create-list-form button:hover,
    .add-item-form button:hover {
        opacity: 0.9;
    }
</style>

<script>
    document.querySelectorAll('.toggle-item').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            fetch(`/list-items/${this.dataset.id}/toggle`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({})
            }).then(response => response.json()).then(data => {
                console.log('Tâche mise à jour:', data);
            }).catch(error => console.error('Erreur:', error));
        });
    });
</script>
@endsection