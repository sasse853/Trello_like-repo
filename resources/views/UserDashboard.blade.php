<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gestion de Tâches</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
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
        .navbar-custom {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            padding: 10px 20px;
        }
        
        /* Styles pour les notifications */
        .notification-bell {
            position: relative;
            cursor: pointer;
            font-size: 20px;
            color: #007bff;
            margin-right: 20px;
        }
        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #dc3545;
            color: white;
            font-size: 12px;
            font-weight: bold;
            border-radius: 50%;
            min-width: 18px;
            height: 18px;
            line-height: 18px;
            text-align: center;
            display: none;
        }
        .notification-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            width: 350px;
            max-height: 400px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        }
        .notification-header {
            padding: 15px;
            border-bottom: 1px solid #eee;
            font-weight: bold;
            color: #333;
        }
        .notification-item {
            padding: 12px 15px;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .notification-item:hover {
            background-color: #f8f9fa;
        }
        .notification-item.unread {
            background-color: #f8f9ff;
            border-left: 4px solid #007bff;
        }
        .notification-content {
            display: flex;
            align-items: center;
        }
        .notification-icon {
            margin-right: 12px;
            font-size: 16px;
            width: 20px;
        }
        .notification-text {
            flex: 1;
            font-size: 14px;
        }
        .notification-time {
            font-size: 12px;
            color: #888;
            margin-top: 4px;
        }
        .no-notifications {
            padding: 30px;
            text-align: center;
            color: #888;
        }
        .mark-all-read {
            padding: 10px 15px;
            text-align: center;
            border-top: 1px solid #eee;
            background: #f8f9fa;
            color: #007bff;
            cursor: pointer;
            font-size: 14px;
        }
        .mark-all-read:hover {
            background: #e9ecef;
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
            <!-- Navbar avec notifications -->
            <div class="navbar-custom d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Bonjour, {{ Auth::user()->name }} !</h5>
                </div>
                <div class="d-flex align-items-center">
                    <!-- Cloche de notifications -->
                    <div class="notification-container position-relative">
                        <div class="notification-bell" id="notificationBell">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge" id="notificationBadge">0</span>
                        </div>
                        
                        <!-- Dropdown des notifications -->
                        <div class="notification-dropdown" id="notificationDropdown">
                            <div class="notification-header">
                                Notifications récentes
                            </div>
                            <div id="notificationList">
                                <div class="no-notifications">
                                    <i class="fas fa-bell-slash" style="font-size: 24px; color: #ccc; margin-bottom: 10px;"></i>
                                    <p>Aucune notification</p>
                                </div>
                            </div>
                            <div class="mark-all-read" id="markAllRead" style="display: none;">
                                Marquer tout comme lu
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Script pour Echo/Pusher sera ajouté plus tard si nécessaire -->
    
    <script>
        $(document).ready(function() {
            // Configuration CSRF
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let notificationDropdown = $('#notificationDropdown');
            let notificationBell = $('#notificationBell');
            let notificationBadge = $('#notificationBadge');
            let isDropdownOpen = false;

            // Charger les notifications au démarrage
            loadNotifications();

            // Toggle dropdown
            notificationBell.click(function(e) {
                e.stopPropagation();
                if (isDropdownOpen) {
                    notificationDropdown.hide();
                    isDropdownOpen = false;
                } else {
                    loadNotifications();
                    notificationDropdown.show();
                    isDropdownOpen = true;
                }
            });

            // Fermer dropdown en cliquant ailleurs
            $(document).click(function() {
                if (isDropdownOpen) {
                    notificationDropdown.hide();
                    isDropdownOpen = false;
                }
            });

            // Empêcher la fermeture en cliquant dans le dropdown
            notificationDropdown.click(function(e) {
                e.stopPropagation();
            });

            // Marquer toutes comme lues
            $('#markAllRead').click(function() {
                $.post('/notifications/mark-all-read')
                    .done(function() {
                        loadNotifications();
                    });
            });

            // Fonction pour charger les notifications
            function loadNotifications() {
                $.get('/notifications/recent')
                    .done(function(data) {
                        updateNotificationBadge(data.unread_count);
                        renderNotifications(data.notifications);
                    })
                    .fail(function() {
                        console.error('Erreur lors du chargement des notifications');
                    });
            }

            // Fonction pour mettre à jour le badge
            function updateNotificationBadge(count) {
                if (count > 0) {
                    notificationBadge.text(count > 99 ? '99+' : count).show();
                } else {
                    notificationBadge.hide();
                }
            }

            // Fonction pour afficher les notifications
            function renderNotifications(notifications) {
                let notificationList = $('#notificationList');
                let markAllRead = $('#markAllRead');

                if (notifications.length === 0) {
                    notificationList.html(`
                        <div class="no-notifications">
                            <i class="fas fa-bell-slash" style="font-size: 24px; color: #ccc; margin-bottom: 10px;"></i>
                            <p>Aucune notification</p>
                        </div>
                    `);
                    markAllRead.hide();
                    return;
                }

                let html = '';
                let hasUnread = false;

                notifications.forEach(function(notification) {
                    let unreadClass = notification.is_read ? '' : 'unread';
                    if (!notification.is_read) hasUnread = true;

                    html += `
                        <div class="notification-item ${unreadClass}" data-id="${notification.id}">
                            <div class="notification-content">
                                <i class="${notification.icon} ${notification.color} notification-icon"></i>
                                <div class="notification-text">
                                    ${notification.message}
                                    <div class="notification-time">${notification.time_ago}</div>
                                </div>
                            </div>
                        </div>
                    `;
                });

                notificationList.html(html);
                
                if (hasUnread) {
                    markAllRead.show();
                } else {
                    markAllRead.hide();
                }

                // Gérer le clic sur une notification
                $('.notification-item').click(function() {
                    let notificationId = $(this).data('id');
                    if ($(this).hasClass('unread')) {
                        markNotificationAsRead(notificationId, $(this));
                    }
                });
            }

            // Marquer une notification comme lue
            function markNotificationAsRead(id, element) {
                $.post(`/notifications/${id}/read`)
                    .done(function() {
                        element.removeClass('unread');
                        loadNotifications(); // Recharger pour mettre à jour le badge
                    });
            }

            // Polling toutes les 30 secondes pour vérifier les nouvelles notifications
            setInterval(function() {
                $.get('/notifications/unread-count')
                    .done(function(data) {
                        updateNotificationBadge(data.count);
                    });
            }, 30000);

            // Configuration Echo pour le temps réel (optionnel)
            if (typeof Echo !== 'undefined') {
                Echo.private(`user.{{ Auth::id() }}`)
                    .listen('.notification.created', (e) => {
                        console.log('Nouvelle notification reçue:', e);
                        loadNotifications();
                        
                        // Animation de la cloche
                        notificationBell.addClass('animate__animated animate__swing');
                        setTimeout(() => {
                            notificationBell.removeClass('animate__animated animate__swing');
                        }, 1000);
                    });
            }
        });
    </script>
</body>
</html>