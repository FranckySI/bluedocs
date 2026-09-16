<body style="margin:0;">


<style>
  :root{
    /* --- Charte graphique BlueDocs --- */
    --bd-blue-dark:#00111f;
    --bd-blue:#003366;
    --bd-blue-light:#0e5fa8;
    --bd-red:#cc0033;
    --bd-red-dark:#98001f;
    --bd-white:#ffffff;
    --bd-paper:#f4f7fb;
    --bd-ink:#0a1420;
    --bd-muted:#93a6c2;
    --bd-line:rgba(255,255,255,0.12);
 
    --bd-font-display:'Space Grotesk', sans-serif;
    --bd-font-body:'Inter', sans-serif;
    --bd-font-mono:'JetBrains Mono', monospace;
  }
  
  body{
    margin:0;
    background:var(--bd-paper);
    font-family:var(--bd-font-body);
    color:var(--bd-ink);
  }

  .bd-docs{
    max-width:1200px;
    margin:0 auto;
    padding:40px 40px 70px;
  }

  /* ---------- En-tête de page ---------- */
  .bd-docs-head{
    display:flex;
    align-items:flex-end;
    justify-content:space-between;
    flex-wrap:wrap;
    gap:16px;
    margin-bottom:26px;
  }
  .bd-eyebrow-dark{
    display:inline-block;
    font-family:var(--bd-font-mono);
    font-size:12px;
    letter-spacing:2px;
    color:var(--bd-blue);
    border:1px solid var(--bd-blue);
    border-radius:20px;
    padding:5px 14px;
    margin-bottom:12px;
  }
  .bd-docs-title{
    font-family:var(--bd-font-display);
    font-weight:700;
    text-transform:uppercase;
    font-size:clamp(24px, 3vw, 34px);
    margin:0;
  }
  .bd-docs-title .accent{ color:var(--bd-red); }
  .bd-docs-sub{
    font-size:13.5px;
    color:#5b6b83;
    margin:6px 0 0;
  }

  .bd-add-btn{
    display:inline-flex; align-items:center; gap:8px;
    background:var(--bd-red);
    color:var(--bd-white);
    text-decoration:none;
    font-size:14px; font-weight:600;
    padding:12px 20px;
    border-radius:4px;
    transition:background .2s ease;
    white-space:nowrap;
  }
  .bd-add-btn:hover, .bd-add-btn:focus-visible{ background:var(--bd-red-dark); }

  
</style>

<div class="bd-docs">
  <!-- ===== En-tête ===== -->
  <div class="bd-docs-head">
    <div>
      <span class="bd-eyebrow-dark">GESTION DOCUMENTAIRE</span>
      <h1 class="bd-docs-title">Tous les <span class="accent">documents</span></h1>
      <p class="bd-docs-sub">Consultez, modifiez ou supprimez les documents classés par catégorie.</p>
    </div>
    <a class="bd-add-btn" href="{{ route('accueil') }}">Retour à l'accueil</a>
  </div>


</body>


