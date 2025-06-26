<nav class="navbar">
    <ul>
        @if(isset($board))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('boards.show', ['board' => $board->id]) }}">Mes tableaux</a>
            </li>
        @endif
    </ul>
</nav>
