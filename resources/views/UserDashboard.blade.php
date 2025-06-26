<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gestion de Tâches</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet"> <!-- Pour les icônes -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7f6;
            color: #333;
        }
        .sidebar {
            background-color: #007bff;
            color: white;
            height: 100vh;
            width: 250px;
            padding-top: 20px;
            position: fixed;
        }
        .sidebar h4 {
            color: white;
            padding-left: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 12px 20px;
            font-size: 16px;
        }
        .sidebar a:hover, .sidebar .active {
            background-color: #0056b3;
        }
        .content {
            margin-left: 260px;
            padding: 20px;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
        }
        .card-header {
            background-color: #007bff;
            color: white;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            border: none;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        h1.dashboard-title {
            color: #333;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            padding: 20px 0;
        }
        .task-card {
            background-color: #ffffff;
            border: 1px solid #e1e1e1;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            margin-bottom: 15px;
        }
        .task-card .task-header {
            background-color: #28a745;
            color: white;
            padding: 12px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .task-card .task-body {
            padding: 15px;
        }
    </style>
</head>
<body>

    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar p-3">
            <h4>Gestion de Tâches</h4>
            <ul class="list-unstyled">
                <li><a href="/Dashboard" class="d-block py-2">Dashboard</a></li>
                @if(isset($board))
                    <li><a href="{{ route('boards.show', ['board' => $board->id]) }}" class="d-block py-2">Mes Tableaux</a></li>
                @endif
                <li><a href="/Modification" class="d-block py-2">Modifier le mot de passe</a></li>
                <li class="nav-item">
                    <a class="d-block py-2" href="#" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Déconnexion
                    </a>
                    <form id="logout-form" action="/logout" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>

        <!-- Contenu du Dashboard -->
        <div class="content">
            <div class="container">
                <h1 class="dashboard-title text-center">BIENVENUE SUR VOTRE DASHBOARD</h1>
                
                <!-- Bouton pour ajouter un board -->
                <a href="{{ route('boards.create') }}" class="btn btn-custom mb-3">Créer un nouveau tableau de travail</a>

                <!-- Liste des tableaux (boards) -->
                <div class="row">
                    @php
                        $user = Auth::user();
                        $workspaceId = $user->workspace ? (int) data_get($user->workspace, 'id') : null;
                        $filteredBoards = $boards->filter(function($board) use ($user,$workspaceId) {
                            return (int) data_get($board,'member_id') === (int)$user->id && $workspaceId!==null && (int)$board->workspace_id->id=== $workspaceId;
                        });
                    @endphp
                    @forelse($filteredBoards as $board)
                        <div class="col-md-4 mb-3">
                            <div class="task-card">
                                <div class="task-header">{{ $board->name }}</div>
                                <div class="task-body">
                                    <p>{{ $board->description }}</p>
                                    <a href="{{ route('boards.edit', ['board' => $board->id]) }}" class="btn btn-warning btn-sm mb-2">Modifier</a>
                                    
                                    <form action="{{ route('boards.destroy',['board'=>$board->id]) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm mb-2">Supprimer</button>
                                    </form>
                                    <a href="{{ route('invitation',['board'=>$board->id]) }}" class="btn btn-success btn-sm mb-2">Inviter un membre</a>
                                    <a href="{{ route('listes.index', $board->id) }}" class="btn btn-dark btn-sm">Ouvrir projet</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p>Aucun tableau trouvé. <a href="{{ route('boards.create') }}">Créez-en un ici.</a></p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
