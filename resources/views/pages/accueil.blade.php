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
            --bd-paper: #f7f7f7;
            --bd-ink: #0a1420;
            --bd-muted: #6b7a90;
            --bd-line: #e3e7ee;
            --bd-live: #16a34a;

            --bd-font-display: 'Space Grotesk', sans-serif;
            --bd-font-body: 'Inter', sans-serif;
            --bd-font-mono: 'JetBrains Mono', monospace;
        }

        .bd-home {
            box-sizing: border-box;
            display: flex;
            min-height: calc(100vh - 4rem);
            background: #f7f7f7;
            color: var(--bd-ink);
            font-family: var(--bd-font-body);
            padding: clamp(16px, 3vw, 40px);
        }

        .bd-frame {
            box-sizing: border-box;
            display: flex;
            flex: 1;
            flex-direction: column;
            justify-content: center;
            gap: clamp(14px, 2vh, 20px);
            width: 100%;
            max-width: 1120px;
            margin: 0 auto;
            padding: clamp(28px, 6vh, 72px) clamp(24px, 5vw, 64px);
        }

        /* ---------- Statut ---------- */
        .bd-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 11.5px;
            font-weight: 500;
            letter-spacing: 0.08em;
            color: var(--bd-muted);
            margin-bottom: 0;
        }

        .bd-live-dot {
            position: relative;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--bd-live);
        }

        .bd-live-dot::after {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background: var(--bd-live);
            animation: bd-ping 2.2s cubic-bezier(0, 0, .2, 1) infinite;
        }

        @keyframes bd-ping {
            0%   { transform: scale(1); opacity: .6; }
            75%, 100% { transform: scale(3.2); opacity: 0; }
        }

        /* ---------- Titre : léger + gras ---------- */
        .bd-title {
            font-family: var(--bd-font-display);
            font-weight: 300;
            font-size: clamp(40px, 6.6vw, 88px);
            line-height: 1.02;
            letter-spacing: -0.035em;
            margin: 0;
        }

        .bd-title strong {
            position: relative;
            display: inline-block;
            font-weight: 700;
        }

        /* Filet rouge qui se trace sous le mot en gras */
        .bd-title strong::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0.02em;
            height: 0.07em;
            background: var(--bd-red);
            transform-origin: left center;
            transform: scaleX(0);
            animation: bd-draw 1s cubic-bezier(.65, 0, .35, 1) 0.9s forwards;
        }

        @keyframes bd-draw {
            to { transform: scaleX(1); }
        }

        .bd-subtitle {
            font-size: 16px;
            line-height: 1.65;
            color: var(--bd-ink);
            max-width: 520px;
            margin: 0;
        }

        /* ---------- Journal d'activité en direct ---------- */
        .bd-activity {
            width: 100%;
            max-width: 520px;
            border: 1px solid var(--bd-line);
            border-radius: 10px;
            background: var(--bd-white);
            overflow: hidden;
        }

        .bd-activity-head {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 18px;
            font-family: var(--bd-font-mono);
            font-size: 11.5px;
            letter-spacing: 0.06em;
            color: var(--bd-muted);
            border-bottom: 1px solid var(--bd-line);
        }

        .bd-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--bd-red);
            animation: bd-pulse 2s ease-out infinite;
        }

        @keyframes bd-pulse {
            0%   { box-shadow: 0 0 0 0 rgba(204, 0, 51, 0.45); }
            70%  { box-shadow: 0 0 0 8px rgba(204, 0, 51, 0); }
            100% { box-shadow: 0 0 0 0 rgba(204, 0, 51, 0); }
        }

        .bd-activity ul {
            list-style: none;
            margin: 0;
            padding: 6px 0;
        }

        .bd-activity li {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 9px 18px;
            font-family: var(--bd-font-mono);
            font-size: 12.5px;
            color: var(--bd-ink);
            box-shadow: inset 2px 0 0 transparent;
            transition: background-color .5s ease, box-shadow .5s ease;
        }

        .bd-activity li b {
            color: var(--bd-red);
            font-weight: 500;
        }

        .bd-activity li .who {
            color: var(--bd-muted);
        }

        /* Ligne "en lecture" : un balayage doux qui parcourt le journal */
        .bd-activity li.is-live {
            background: var(--bd-paper);
            box-shadow: inset 2px 0 0 var(--bd-red);
        }

        /* ---------- Entrée au chargement (pas au scroll) ---------- */
        .bd-rise {
            opacity: 0;
            transform: translateY(14px);
            animation: bd-rise .8s cubic-bezier(.2, .7, .2, 1) forwards;
            animation-delay: calc(var(--i, 0) * 120ms);
        }

        @keyframes bd-rise {
            to { opacity: 1; transform: none; }
        }

        @media (prefers-reduced-motion: reduce) {
            .bd-rise,
            .bd-title strong::after {
                animation: none;
                opacity: 1;
                transform: none;
            }
            .bd-live-dot::after,
            .bd-dot { animation: none; }
        }

        @supports (height: 100dvh) {
            .bd-home {
                min-height: calc(100dvh - 4rem);
            }
        }

        @media (min-width: 1024px) {
            .bd-home {
                min-height: 100vh;
            }
        }

        @supports (height: 100dvh) {
            @media (min-width: 1024px) {
                .bd-home {
                    min-height: 100dvh;
                }
            }
        }

        @media (max-width: 820px) {
            .bd-frame {
                padding: 32px 20px;
            }
        }
    </style>

    <section class="bd-home">
        <div class="bd-frame">
            <span class="bd-eyebrow bd-rise" style="--i:0">
                <span class="bd-live-dot"></span>
                INTRANET · GESTION DOCUMENTAIRE
            </span>

            <h1 class="bd-title bd-rise" style="--i:1">
                Gérez les documents<br>
                <strong>facilement</strong>
            </h1>

            <p class="bd-subtitle bd-rise" style="--i:2">
                Déposez, classez et retrouvez vos documents en quelques secondes.
                Chaque ajout, modification ou suppression est tracé automatiquement.
            </p>

            <div class="bd-activity bd-rise" style="--i:3">
                <div class="bd-activity-head"><span class="bd-dot"></span> JOURNAL D'ACTIVITÉ EN DIRECT</div>
                <x-liste-connexions />
            </div>
        </div>
    </section>

    <!-- Balayage doux du journal : montre que la page est vivante -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

            let index = 0;
            setInterval(() => {
                const rows = document.querySelectorAll('.bd-activity li');
                if (!rows.length) return;
                rows.forEach(row => row.classList.remove('is-live'));
                rows[index % rows.length].classList.add('is-live');
                index++;
            }, 2200);
        });
    </script>

</x-app-layout>