<x-app-layout>


    <style>
        :root {
            /* --- Charte graphique BlueDocs --- */
            --bd-blue-dark: #00111f;
            --bd-blue: #003366;
            --bd-blue-light: #0e5fa8;
            --bd-red: #cc0033;
            --bd-red-dark: #98001f;
            --bd-white: #ffffff;
            --bd-paper: #f4f7fb;
            --bd-ink: #0a1420;
            --bd-muted: #6b7a90;
            --bd-line: #e6eaf0;
            --bd-live: #16a34a;

            --bd-font-display: 'Plus Jakarta Sans', sans-serif;
            --bd-font-body: 'Plus Jakarta Sans', sans-serif;
            --bd-font-mono: 'JetBrains Mono', monospace;
            --bd-ease: cubic-bezier(.22, 1, .36, 1);
        }

        body {
            margin: 0;
            background: #f7f7f7;
            font-family: var(--bd-font-body);
            color: var(--bd-ink);
        }

        /* Conteneur plein largeur, aligné sur le sommaire */
        .bd-dash {
            box-sizing: border-box;
            width: 100%;
            min-height: 100vh;
            padding: 0;
            background: #f7f7f7;
        }

        .bd-shell {
            box-sizing: border-box;
            min-height: 100vh;
            padding: clamp(28px, 6vh, 72px) clamp(24px, 3vw, 40px);
            background: var(--bd-white);
            animation: bd-dash-enter .75s var(--bd-ease) both;
        }

        /* ---------- En-tête de page ---------- */
        .bd-dash-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 24px;
            animation: bd-dash-enter .75s var(--bd-ease) .08s both;
        }

        .bd-eyebrow-dark {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.06em;
            color: #52627a;
            background: #f1f5f9;
            border-radius: 6px;
            padding: 4px 10px;
            margin-bottom: 12px;
        }

        .bd-eyebrow-dark::before {
            content: "";
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--bd-blue-light);
        }

        .bd-dash-title {
            margin: 0;
            font-family: var(--bd-font-display);
            font-weight: 600;
            font-size: clamp(20px, 2.2vw, 26px);
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        .bd-dash-sub {
            margin: 6px 0 0;
            font-size: 13px;
            line-height: 1.6;
            color: var(--bd-muted);
        }

        .bd-ico {
            width: 16px;
            height: 16px;
            flex: 0 0 auto;
            stroke-width: 1.8;
        }

        .bd-dash-cta {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--bd-ink);
            color: var(--bd-white);
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            line-height: 1.2;
            padding: 9px 14px;
            border: 1px solid var(--bd-ink);
            border-radius: 10px;
            white-space: nowrap;
            transition: background-color .25s var(--bd-ease), border-color .25s var(--bd-ease),
                transform .25s var(--bd-ease), box-shadow .25s var(--bd-ease);
        }

        .bd-dash-cta:hover,
        .bd-dash-cta:focus-visible {
            background: #1b2a3d;
            border-color: #1b2a3d;
            transform: translateY(-1px);
            box-shadow: 0 6px 14px -6px rgba(10, 20, 32, 0.45);
        }

        .bd-dash-cta:focus-visible,
        .bd-panel-head a:focus-visible,
        .bd-doc-name:focus-visible {
            outline: 2px solid var(--bd-blue-light);
            outline-offset: 3px;
        }

        /* ---------- Grille principale ---------- */
        .bd-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.7fr) minmax(280px, 1fr);
            gap: 20px;
            align-items: start;
        }

        .bd-panel {
            min-width: 0;
            background: var(--bd-white);
            border: 1px solid var(--bd-line);
            border-radius: 14px;
            overflow: hidden;
            animation: bd-dash-enter .75s var(--bd-ease) both;
        }

        .bd-panel:nth-child(1) {
            animation-delay: .16s;
        }

        .bd-panel:nth-child(2) {
            animation-delay: .24s;
        }

        .bd-panel-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 16px 20px;
            border-bottom: 1px solid var(--bd-line);
        }

        .bd-panel-head h2 {
            margin: 0;
            font-family: var(--bd-font-display);
            font-size: 15px;
            font-weight: 600;
            letter-spacing: -0.01em;
        }

        .bd-panel-head a {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            font-weight: 600;
            color: var(--bd-blue);
            text-decoration: none;
            transition: color .2s ease;
        }

        .bd-panel-head a svg {
            width: 14px;
            height: 14px;
            stroke-width: 1.8;
            transition: transform .3s var(--bd-ease);
        }

        .bd-panel-head a:hover {
            color: var(--bd-blue-light);
        }

        .bd-panel-head a:hover svg {
            transform: translateX(3px);
        }

        /* ---------- Tableau ---------- */
        table.bd-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .bd-table thead th {
            padding: 10px 20px;
            text-align: left;
            font-size: 12px;
            font-weight: 500;
            color: var(--bd-muted);
            background: #fafbfc;
            border-bottom: 1px solid var(--bd-line);
        }

        .bd-table tbody td {
            padding: 14px 20px;
            font-size: 13.5px;
            border-bottom: 1px solid var(--bd-line);
            vertical-align: middle;
        }

        .bd-table tbody tr {
            animation: bd-dash-enter .65s var(--bd-ease) calc(.24s + var(--i, 0) * 55ms) both;
            transition: background-color .25s ease;
        }

        .bd-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .bd-table tbody tr:hover {
            background: #fafbfc;
        }

        .bd-doc-name {
            font-weight: 600;
            color: var(--bd-ink);
            text-decoration: none;
            overflow-wrap: anywhere;
            transition: color .2s ease;
        }

        .bd-doc-name:hover {
            color: var(--bd-blue-light);
        }

        .bd-doc-meta {
            display: block;
            margin-top: 2px;
            font-family: var(--bd-font-mono);
            font-size: 11.5px;
            color: var(--bd-muted);
        }

        .bd-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 8px;
        }

        .bd-badge::before {
            content: "";
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: currentColor;
        }

        .bd-badge.add {
            background: #eff6ff;
            color: var(--bd-blue);
        }

        .bd-badge.edit {
            background: #fff1f3;
            color: var(--bd-red-dark);
        }

        .bd-badge.del {
            background: #f1f5f9;
            color: #52627a;
        }

        .bd-user-cell {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .bd-mini-avatar {
            width: 26px;
            height: 26px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--bd-font-display);
            font-weight: 700;
            font-size: 10.5px;
            color: var(--bd-white);
            background: var(--bd-blue);
            flex-shrink: 0;
            transition: transform .25s var(--bd-ease);
        }

        .bd-table tbody tr:hover .bd-mini-avatar {
            transform: scale(1.06);
        }

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
            padding: 14px 20px;
            border-top: 1px solid var(--bd-line);
            animation: bd-dash-enter .65s var(--bd-ease) calc(.24s + var(--i, 0) * 55ms) both;
            transition: background-color .25s ease;
        }

        .bd-connexions li:hover {
            background: #fafbfc;
        }

        .bd-connexions li:first-child {
            border-top: 0;
        }

        .bd-avatar {
            width: 38px;
            height: 38px;
            border-radius: 12px;
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
            transition: transform .25s var(--bd-ease);
        }

        .bd-connexions li:hover .bd-avatar {
            transform: translateY(-1px);
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
            color: var(--bd-muted);
        }

        .bd-connexion-time {
            font-family: var(--bd-font-mono);
            font-size: 11px;
            color: var(--bd-muted);
            white-space: nowrap;
        }

        .bd-status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--bd-live);
            flex-shrink: 0;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
        }

        .bd-status-dot.offline {
            background: #c7cedb;
            box-shadow: none;
        }

        .bd-empty-state {
            padding: 28px 20px !important;
            color: var(--bd-muted);
            text-align: center;
        }

        .bd-connexions-empty {
            padding: 24px 20px !important;
            color: var(--bd-muted);
            font-size: 13px;
            text-align: center;
            animation: bd-dash-enter .65s var(--bd-ease) .24s both;
        }

        @media (max-width:960px) {
            .bd-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 720px) {
            .bd-dash {
                width: 100%;
                min-height: 100vh;
                padding: 0;
            }

            .bd-shell {
                padding: 28px 16px 36px;
            }

            .bd-dash-head {
                margin-bottom: 20px;
            }

            .bd-dash-cta {
                width: 100%;
                justify-content: center;
            }

            .bd-grid {
                gap: 14px;
            }

            .bd-panel-head {
                padding: 14px 16px;
            }

            .bd-connexions li {
                padding: 12px 16px;
            }

            .bd-connexion-time {
                font-size: 10px;
            }

            .bd-table thead {
                position: absolute;
                width: 1px;
                height: 1px;
                padding: 0;
                overflow: hidden;
                clip: rect(0, 0, 0, 0);
                white-space: nowrap;
                clip-path: inset(50%);
            }

            .bd-table,
            .bd-table tbody,
            .bd-table tbody tr {
                display: block;
                width: 100%;
            }

            .bd-table tbody tr {
                padding: 12px 16px;
                border-bottom: 1px solid var(--bd-line);
            }

            .bd-table tbody tr:last-child {
                border-bottom: 0;
            }

            .bd-table tbody td {
                display: grid;
                grid-template-columns: minmax(84px, 0.35fr) minmax(0, 1fr);
                gap: 12px;
                padding: 7px 0;
                border: 0;
                overflow-wrap: anywhere;
            }

            .bd-table tbody td:first-child,
            .bd-table tbody td[colspan] {
                display: block;
            }

            .bd-table tbody td:nth-child(2)::before {
                content: "Action";
                color: var(--bd-muted);
                font-size: 11px;
            }

            .bd-table tbody td:nth-child(3)::before {
                content: "Utilisateur";
                color: var(--bd-muted);
                font-size: 11px;
            }

            .bd-table tbody td:nth-child(4)::before {
                content: "Date";
                color: var(--bd-muted);
                font-size: 11px;
            }
        }

        @keyframes bd-dash-enter {
            from {
                opacity: 0;
                transform: translateY(14px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .bd-shell,
            .bd-dash-head,
            .bd-panel,
            .bd-table tbody tr,
            .bd-connexions li,
            .bd-connexions-empty {
                animation: none;
            }

            .bd-dash-cta,
            .bd-panel-head a,
            .bd-panel-head a svg,
            .bd-doc-name,
            .bd-mini-avatar,
            .bd-avatar,
            .bd-table tbody tr,
            .bd-connexions li {
                transition: none;
            }
        }

    </style>

    <div class="bd-dash">
        <div class="bd-shell">

            <!-- ===== En-tête ===== -->
            <div class="bd-dash-head">
                <div>
                    <span class="bd-eyebrow-dark">TABLEAU DE BORD</span>
                    <h1 class="bd-dash-title">Bonjour, {{ Auth::user()->name }}</h1>
                    <p class="bd-dash-sub">Voici l'activité de votre espace documentaire aujourd'hui.</p>
                </div>
                <a class="bd-dash-cta" href="{{ route('documents.create') }}">
                    <x-lucide-plus class="bd-ico" aria-hidden="true" />
                    Ajouter un document
                </a>
            </div>


            <!-- ===== Colonnes principales ===== -->
            <div class="bd-grid">

                <!-- Dernières modifications -->
                <div class="bd-panel">
                    <div class="bd-panel-head">
                        <h2>Dernières modifications</h2>
                        <a href="{{ route('pages.document') }}">
                            Voir tout
                            <x-lucide-arrow-right aria-hidden="true" />
                        </a>
                    </div>
                    <table class="bd-table">
                            <thead>
                                <tr>
                                    <th>Document</th>
                                    <th>Action</th>
                                    <th>Utilisateur</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentDocuments as $m)
                                    @php
                                        // Extraction rapide des initiales de l'utilisateur pour l'avatar mini
                                        $words = explode(' ', $m->user_name ?? 'Anonyme');
                                        $initials = '';
                                        foreach ($words as $w) {
                                            $initials .= mb_substr($w, 0, 1);
                                        }
                                        $initials = mb_strtoupper(mb_substr($initials, 0, 2));

                                        // Détection de l'action (Ajout vs Modification)
                                        $isNew = $m->created_at == $m->updated_at;
                                    @endphp
                                    <tr style="--i: {{ min($loop->index, 14) }};">
                                        <td>
                                            <div>
                                                <a href="{{ route('documents.show', $m->slug) }}" class="bd-doc-name">
                                                    {{ $m->title }}
                                                </a>
                                                <span class="bd-doc-meta">Document texte</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($isNew)
                                                <span class="bd-badge add">Ajout</span>
                                            @else
                                                <span class="bd-badge edit">Modification</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="bd-user-cell">
                                                <div class="bd-mini-avatar"
                                                    style="background: {{ $isNew ? 'var(--bd-blue)' : 'var(--bd-red)' }};">
                                                    {{ $initials }}
                                                </div>
                                                {{ $m->user_name ?? 'Anonyme' }}
                                            </div>
                                        </td>
                                        <td>{{ $m->updated_at->format('d/m/Y, H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="bd-empty-state">
                                            Aucune activité récente.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                    </table>
                </div>


                <!-- Connexions récentes -->
                <div class="bd-panel">
                    <div class="bd-panel-head">
                        <h2>Dernières connexions</h2>
                    </div>

                    <ul class="bd-connexions">
                        @forelse ($connexions as $c)
                            <li style="--i: {{ min($loop->index, 14) }};">
                                <!-- Génération dynamique de l'avatar (Prend les 2 premières lettres du nom en majuscules) -->
                                <div class="bd-avatar"
                                    style="{{ $c->role === 'admin' ? '' : 'border-color:var(--bd-blue);' }}">
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
                        @empty
                            <li class="bd-connexions-empty">Aucune connexion récente.</li>
                        @endforelse
                    </ul>
                </div>

            </div>
        </div>
    </div>

</x-app-layout>