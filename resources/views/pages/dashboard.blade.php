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
            --bd-muted: #93a6c2;
            --bd-line: rgba(255, 255, 255, 0.12);

            --bd-font-display: 'Space Grotesk', sans-serif;
            --bd-font-body: 'Inter', sans-serif;
            --bd-font-mono: 'JetBrains Mono', monospace;
        }

        body {
            margin: 0;
            background: var(--bd-paper);
            font-family: var(--bd-font-body);
            color: var(--bd-ink);
        }

        .bd-dash {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 40px 70px;
        }

        /* ---------- En-tête de page ---------- */
        .bd-dash-head {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 32px;
        }

        .bd-eyebrow-dark {
            display: inline-block;
            font-family: var(--bd-font-mono);
            font-size: 12px;
            letter-spacing: 2px;
            color: var(--bd-blue);
            border: 1px solid var(--bd-blue);
            border-radius: 20px;
            padding: 5px 14px;
            margin-bottom: 12px;
        }

        .bd-dash-title {
            font-family: var(--bd-font-display);
            font-weight: 700;
            text-transform: uppercase;
            font-size: clamp(24px, 3vw, 34px);
            margin: 0;
        }

        .bd-dash-title .accent {
            color: var(--bd-red);
        }

        .bd-dash-sub {
            font-size: 13.5px;
            color: #5b6b83;
            margin: 6px 0 0;
        }

        .bd-dash-cta {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--bd-blue-dark);
            color: var(--bd-white);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            padding: 12px 20px;
            border-radius: 4px;
            transition: background .2s ease;
        }

        .bd-dash-cta:hover,
        .bd-dash-cta:focus-visible {
            background: var(--bd-blue-light);
        }



        /* ---------- Grille principale ---------- */
        .bd-grid {
            display: grid;
            grid-template-columns: 1.7fr 1fr;
            gap: 20px;
            align-items: start;
            margin-bottom: 20px;
        }

        .bd-panel {
            background: #ffffff;
            border: 1px solid #e4e9f0;
            border-radius: 8px;
            overflow: hidden;
        }

        .bd-panel-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 22px;
            border-bottom: 1px solid #e4e9f0;
        }

        .bd-panel-head h2 {
            font-family: var(--bd-font-display);
            font-size: 16px;
            margin: 0;
        }

        .bd-panel-head a {
            font-size: 12.5px;
            color: var(--bd-blue);
            text-decoration: none;
            font-weight: 600;
        }

        .bd-panel-head a:hover {
            color: var(--bd-red);
        }

        /* ---------- Table modifications ---------- */
        table.bd-table {
            width: 100%;
            border-collapse: collapse;
        }

        .bd-table thead th {
            text-align: left;
            font-family: var(--bd-font-mono);
            font-size: 11px;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #8393ab;
            padding: 12px 22px;
            background: #f7f9fc;
        }

        .bd-table tbody td {
            padding: 14px 22px;
            font-size: 13.5px;
            border-top: 1px solid #eef1f6;
            vertical-align: middle;
        }

        .bd-table tbody tr:hover {
            background: #fafbfd;
        }

        .bd-doc-name {
            font-weight: 600;
        }

        .bd-doc-meta {
            display: block;
            font-size: 11.5px;
            color: #8393ab;
            font-family: var(--bd-font-mono);
            margin-top: 2px;
        }

        .bd-badge {
            display: inline-block;
            font-size: 11px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
        }

        .bd-badge.add {
            background: rgba(0, 51, 102, 0.1);
            color: var(--bd-blue);
        }

        .bd-badge.edit {
            background: rgba(204, 0, 51, 0.1);
            color: var(--bd-red);
        }

        .bd-badge.del {
            background: #f1f2f5;
            color: #5b6b83;
        }

        .bd-user-cell {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .bd-mini-avatar {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--bd-font-display);
            font-weight: 700;
            font-size: 10.5px;
            color: var(--bd-white);
            background: var(--bd-blue);
            flex-shrink: 0;
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

        .bd-status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #2fae66;
            flex-shrink: 0;
        }

        .bd-status-dot.offline {
            background: #c7cedb;
        }

        /* ---------- Répartition par rôle ---------- */
        .bd-panel-body {
            padding: 20px 22px 22px;
        }

        .bd-bar-row {
            margin-bottom: 16px;
        }

        .bd-bar-row:last-child {
            margin-bottom: 0;
        }

        .bd-bar-labels {
            display: flex;
            justify-content: space-between;
            font-size: 12.5px;
            margin-bottom: 6px;
        }

        .bd-bar-labels .name {
            font-weight: 600;
        }

        .bd-bar-labels .count {
            color: #8393ab;
            font-family: var(--bd-font-mono);
        }

        .bd-bar-track {
            height: 8px;
            border-radius: 6px;
            background: #eef1f6;
            overflow: hidden;
        }

        .bd-bar-fill {
            height: 100%;
            border-radius: 6px;
            background: var(--bd-blue);
        }

        .bd-bar-fill.accent {
            background: var(--bd-red);
        }

        @media (max-width:960px) {
            .bd-stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .bd-grid {
                grid-template-columns: 1fr;
            }

            .bd-dash {
                padding: 30px 20px 60px;
            }
        }
    </style>

    <div class="bd-dash">

        <!-- ===== En-tête ===== -->
        <div class="bd-dash-head">
            <div>
                <span class="bd-eyebrow-dark">TABLEAU DE BORD</span>
                <h1 class="bd-dash-title">Bonjour, <span class="accent">{{ Auth::user()->name }}</span></h1>
                <p class="bd-dash-sub">Voici l'activité de votre espace documentaire aujourd'hui.</p>
            </div>
            <a class="bd-dash-cta" href="{{ route('documents.create') }}">+ Ajouter un document</a>
        </div>


        <!-- ===== Colonnes principales ===== -->
        <div class="bd-grid">

            <!-- Dernières modifications -->
            <div class="bd-panel">
    <div class="bd-panel-head">
        <h2>Dernières modifications</h2>
        <a href="{{ route('pages.document') }}">Voir tout</a>
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
                <tr>
                    <td>
                        <a href="{{ route('documents.show', $m->slug) }}" class="bd-doc-name" style="text-decoration: none; color: inherit; font-weight: 600;">
                            {{ $m->title }}
                        </a>
                        <span class="bd-doc-meta">Document texte</span>
                    </td>
                    <td>
                        @if($isNew)
                            <span class="bd-badge add">Ajout</span>
                        @else
                            <span class="bd-badge edit">Modification</span>
                        @endif
                    </td>
                    <td>
                        <div class="bd-user-cell">
                            <div class="bd-mini-avatar" style="background: {{ $isNew ? 'var(--bd-blue)' : 'var(--bd-red)' }};">
                                {{ $initials }}
                            </div> 
                            {{ $m->user_name ?? 'Anonyme' }}
                        </div>
                    </td>
                    <td>
                        <!-- Affiche la date proprement -->
                        {{ $m->updated_at->format('d/m/Y, H:i') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 30px; color: var(--bd-muted);">
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
                    @foreach ($connexions as $c)
                        <li>
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
                    @endforeach
                </ul>

                </ul>
            </div>

        </div>
    </div>
    </body>





</x-app-layout>
