<style>
            /* ---------- Connexions récentes ---------- */
        .bd-connexions {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .bd-connexions li {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 22px;
            border-top: 1px solid #eef1f6;
        }

        .bd-connexions li:first-child {
            border-top: 0;
        }

        .bd-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--bd-font-display);
            font-weight: 700;
            font-size: 12.5px;
            color: var(--bd-white);
            background: var(--bd-blue-dark);
            border: 2px solid var(--bd-red);
            flex-shrink: 0;
        }

        .bd-connexion-info {
            flex: 1;
            min-width: 0;
        }

        .bd-connexion-name {
            font-size: 13.5px;
            font-weight: 600;
        }

        .bd-connexion-role {
            font-size: 11.5px;
            color: #8393ab;
        }

        .bd-connexion-time {
            font-family: var(--bd-font-mono);
            font-size: 11px;
            color: #8393ab;
            white-space: nowrap;
        }

</style>

<ul class="bd-connexions">
    @foreach ($connexions as $c)
        <li>
            <!-- Génération dynamique de l'avatar (Prend les 2 premières lettres du nom en majuscules) -->
            <div class="bd-avatar" style="{{ $c->role === 'admin' ? '' : 'border-color:var(--bd-blue);' }}">
                {{ strtoupper(substr($c->name, 0, 2)) }}
            </div>

            <div class="bd-connexion-info">
                <div class="bd-connexion-name">{{ $c->name }}</div>
                <div class="bd-connexion-role">
                    {{ $c->role === 'admin' ? 'Administrateur' : 'Éditeur' }}
                </div>
            </div>

            <!-- Formatage de l'heure via Carbon (Affiche l'heure ou "Hier") -->
            <span class="bd-connexion-time">
                @if ($c->last_login_at->isToday())
                    Aujourd'hui, {{ $c->last_login_at->format('H:i') }}
                @elseif ($c->last_login_at->isYesterday())
                    Hier, {{ $c->last_login_at->format('H:i') }}
                @else
                    {{ $c->last_login_at->format('d/m H:i') }}
                @endif
            </span>
        </li>
    @endforeach
</ul>