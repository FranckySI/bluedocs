<x-app-layout>


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

  /* ---------- Barre recherche + filtres ---------- */
  .bd-toolbar{
    display:flex;
    align-items:center;
    gap:12px;
    flex-wrap:wrap;
    margin-bottom:24px;
  }
  .bd-search{
    flex:1;
    min-width:220px;
    position:relative;
  }
  .bd-search input{
    width:100%;
    padding:11px 14px 11px 38px;
    border:1px solid #dde3ec;
    border-radius:6px;
    font-size:13.5px;
    background:#ffffff url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="%238393ab" stroke-width="2"><circle cx="6.5" cy="6.5" r="5"/><line x1="10.5" y1="10.5" x2="15" y2="15"/></svg>') 12px center no-repeat;
    outline:none;
  }
  .bd-search input:focus-visible{ border-color:var(--bd-blue); }

  .bd-tabs{
    display:flex;
    gap:8px;
    flex-wrap:wrap;
  }
  .bd-tab{
    border:1px solid #dde3ec;
    background:#ffffff;
    color:#5b6b83;
    font-size:13px;
    font-weight:600;
    padding:9px 16px;
    border-radius:20px;
    cursor:pointer;
    transition:all .2s ease;
  }
  .bd-tab .count{ color:#a4b0c3; font-weight:500; margin-left:4px; }
  .bd-tab:hover{ border-color:var(--bd-blue); color:var(--bd-blue); }
  .bd-tab.is-active{
    background:var(--bd-blue-dark);
    border-color:var(--bd-blue-dark);
    color:var(--bd-white);
  }
  .bd-tab.is-active .count{ color:var(--bd-muted); }

  /* ---------- Sections catégorie ---------- */
  .bd-category{
    background:#ffffff;
    border:1px solid #e4e9f0;
    border-radius:8px;
    overflow:hidden;
    margin-bottom:22px;
  }
  .bd-category.is-hidden{ display:none; }

  .bd-category-head{
    display:flex; align-items:center; gap:10px;
    padding:16px 22px;
    border-bottom:1px solid #e4e9f0;
    background:#f7f9fc;
  }
  .bd-category-head h2{
    font-family:var(--bd-font-display);
    font-size:15.5px;
    margin:0;
  }
  .bd-category-head .count{
    font-family:var(--bd-font-mono);
    font-size:11.5px;
    color:#8393ab;
    background:#ffffff;
    border:1px solid #e4e9f0;
    border-radius:20px;
    padding:2px 10px;
  }

  table.bd-table{
    width:100%;
    border-collapse:collapse;
  }
  .bd-table thead th{
    text-align:left;
    font-family:var(--bd-font-mono);
    font-size:11px;
    letter-spacing:1px;
    text-transform:uppercase;
    color:#8393ab;
    padding:10px 22px;
  }
  .bd-table tbody td{
    padding:13px 22px;
    font-size:13.5px;
    border-top:1px solid #eef1f6;
    vertical-align:middle;
  }
  .bd-table tbody tr:hover{ background:#fafbfd; }

  .bd-doc-cell{ display:flex; align-items:center; gap:12px; }
  .bd-doc-icon{
    width:34px; height:34px;
    border-radius:6px;
    display:flex; align-items:center; justify-content:center;
    background:rgba(0,51,102,0.08);
    color:var(--bd-blue);
    font-family:var(--bd-font-mono);
    font-size:10px; font-weight:700;
    flex-shrink:0;
  }
  .bd-doc-icon.pdf{ background:rgba(204,0,51,0.08); color:var(--bd-red); }
  .bd-doc-name{ font-weight:600; display:block; }
  .bd-doc-meta{ font-size:11.5px; color:#8393ab; font-family:var(--bd-font-mono); }

  .bd-user-cell{ display:flex; align-items:center; gap:8px; }
  .bd-mini-avatar{
    width:24px; height:24px; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    font-family:var(--bd-font-display); font-weight:700; font-size:9.5px;
    color:var(--bd-white); background:var(--bd-blue);
    flex-shrink:0;
  }

  .bd-actions{ display:flex; gap:8px; justify-content:flex-end; }
  .bd-icon-btn{
    display:inline-flex; align-items:center; justify-content:center;
    width:32px; height:32px;
    border-radius:6px;
    border:1px solid #e4e9f0;
    background:#ffffff;
    text-decoration:none;
    cursor:pointer;
    transition:all .2s ease;
  }
  .bd-icon-btn.edit{ color:var(--bd-blue); }
  .bd-icon-btn.edit:hover, .bd-icon-btn.edit:focus-visible{ background:var(--bd-blue); border-color:var(--bd-blue); color:#fff; }
  .bd-icon-btn.delete{ color:var(--bd-red); }
  .bd-icon-btn.delete:hover, .bd-icon-btn.delete:focus-visible{ background:var(--bd-red); border-color:var(--bd-red); color:#fff; }
  .bd-icon-btn svg{ width:15px; height:15px; }

  @media (max-width:820px){
    .bd-docs{ padding:30px 20px 60px; }
    .bd-table thead{ display:none; }
    .bd-table tbody td{ display:block; padding:8px 18px; }
    .bd-table tbody tr{ display:block; padding:10px 0; border-top:1px solid #eef1f6; }
    .bd-actions{ justify-content:flex-start; padding-top:6px; }
  }
</style>

<div class="bd-docs">

  <!-- ===== En-tête ===== -->
  <div class="bd-docs-head">
    <div>
      <span class="bd-eyebrow-dark">GESTION DOCUMENTAIRE</span>
      <h1 class="bd-docs-title">Tous les <span class="accent">documents</span></h1>
      <p class="bd-docs-sub">Consultez, modifiez ou supprimez les documents classés par catégorie.</p>
    </div>
    <a class="bd-add-btn" href="ajouter.php">+ Ajouter un document</a>
  </div>

  <!-- ===== Recherche + filtres par catégorie ===== -->
  <!-- TODO backend : la recherche et les onglets peuvent être reliés à une requête GET (?categorie=...&q=...) -->
  <div class="bd-toolbar">
    <div class="bd-search">
      <input type="text" placeholder="Rechercher un document…" name="q">
    </div>
    <div class="bd-tabs" id="bd-tabs">
      <button class="bd-tab is-active" data-target="all">Tous <span class="count">12</span></button>
      <button class="bd-tab" data-target="contrats">Contrats <span class="count">4</span></button>
      <button class="bd-tab" data-target="rapports">Rapports <span class="count">3</span></button>
      <button class="bd-tab" data-target="rh">Ressources humaines <span class="count">3</span></button>
      <button class="bd-tab" data-target="procedures">Procédures <span class="count">2</span></button>
    </div>
  </div>

  <!-- ===== Catégorie : Contrats ===== -->
  <!-- TODO backend : foreach ($categories as $categorie) { foreach ($categorie['documents'] as $doc) { ... } } -->
  <div class="bd-category" data-category="contrats">
    <div class="bd-category-head">
      <h2>Contrats</h2>
      <span class="count">4 documents</span>
    </div>
    <table class="bd-table">
      <thead>
        <tr>
          <th>Document</th>
          <th>Ajouté par</th>
          <th>Dernière modification</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <div class="bd-doc-cell">
              <div class="bd-doc-icon pdf">PDF</div>
              <div>
                <span class="bd-doc-name">Contrat_fournisseur.pdf</span>
                <span class="bd-doc-meta">840 Ko</span>
              </div>
            </div>
          </td>
          <td><div class="bd-user-cell"><div class="bd-mini-avatar">EM</div> E. Martin</div></td>
          <td>Aujourd'hui, 08:47</td>
          <td>
            <div class="bd-actions">
              <a class="bd-icon-btn edit" href="modifier.php?id=1" title="Modifier"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg></a>
              <a class="bd-icon-btn delete" href="supprimer.php?id=1" title="Supprimer" onclick="return confirm('Supprimer ce document ?');"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg></a>
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <div class="bd-doc-cell">
              <div class="bd-doc-icon">DOC</div>
              <div>
                <span class="bd-doc-name">Contrat_prestation_2026.docx</span>
                <span class="bd-doc-meta">1.2 Mo</span>
              </div>
            </div>
          </td>
          <td><div class="bd-user-cell"><div class="bd-mini-avatar">AD</div> Admin</div></td>
          <td>Hier, 16:20</td>
          <td>
            <div class="bd-actions">
              <a class="bd-icon-btn edit" href="modifier.php?id=2" title="Modifier"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg></a>
              <a class="bd-icon-btn delete" href="supprimer.php?id=2" title="Supprimer" onclick="return confirm('Supprimer ce document ?');"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg></a>
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <div class="bd-doc-cell">
              <div class="bd-doc-icon pdf">PDF</div>
              <div>
                <span class="bd-doc-name">Bail_commercial.pdf</span>
                <span class="bd-doc-meta">2.1 Mo</span>
              </div>
            </div>
          </td>
          <td><div class="bd-user-cell"><div class="bd-mini-avatar">AD</div> Admin</div></td>
          <td>12 août 2026</td>
          <td>
            <div class="bd-actions">
              <a class="bd-icon-btn edit" href="modifier.php?id=3" title="Modifier"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg></a>
              <a class="bd-icon-btn delete" href="supprimer.php?id=3" title="Supprimer" onclick="return confirm('Supprimer ce document ?');"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg></a>
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <div class="bd-doc-cell">
              <div class="bd-doc-icon">DOC</div>
              <div>
                <span class="bd-doc-name">Avenant_contrat_marketing.docx</span>
                <span class="bd-doc-meta">610 Ko</span>
              </div>
            </div>
          </td>
          <td><div class="bd-user-cell"><div class="bd-mini-avatar" style="background:var(--bd-red);">SL</div> S. Leroy</div></td>
          <td>3 août 2026</td>
          <td>
            <div class="bd-actions">
              <a class="bd-icon-btn edit" href="modifier.php?id=4" title="Modifier"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg></a>
              <a class="bd-icon-btn delete" href="supprimer.php?id=4" title="Supprimer" onclick="return confirm('Supprimer ce document ?');"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg></a>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- ===== Catégorie : Rapports ===== -->
  <div class="bd-category" data-category="rapports">
    <div class="bd-category-head">
      <h2>Rapports</h2>
      <span class="count">3 documents</span>
    </div>
    <table class="bd-table">
      <thead>
        <tr>
          <th>Document</th>
          <th>Ajouté par</th>
          <th>Dernière modification</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <div class="bd-doc-cell">
              <div class="bd-doc-icon pdf">PDF</div>
              <div>
                <span class="bd-doc-name">Rapport_annuel_2026.pdf</span>
                <span class="bd-doc-meta">2.4 Mo</span>
              </div>
            </div>
          </td>
          <td><div class="bd-user-cell"><div class="bd-mini-avatar">AD</div> Admin</div></td>
          <td>Aujourd'hui, 09:14</td>
          <td>
            <div class="bd-actions">
              <a class="bd-icon-btn edit" href="modifier.php?id=5" title="Modifier"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg></a>
              <a class="bd-icon-btn delete" href="supprimer.php?id=5" title="Supprimer" onclick="return confirm('Supprimer ce document ?');"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg></a>
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <div class="bd-doc-cell">
              <div class="bd-doc-icon" style="background:rgba(47,174,102,0.1); color:#2fae66;">XLS</div>
              <div>
                <span class="bd-doc-name">Grille_salariale.xlsx</span>
                <span class="bd-doc-meta">310 Ko</span>
              </div>
            </div>
          </td>
          <td><div class="bd-user-cell"><div class="bd-mini-avatar">AD</div> Admin</div></td>
          <td>Hier, 15:10</td>
          <td>
            <div class="bd-actions">
              <a class="bd-icon-btn edit" href="modifier.php?id=6" title="Modifier"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg></a>
              <a class="bd-icon-btn delete" href="supprimer.php?id=6" title="Supprimer" onclick="return confirm('Supprimer ce document ?');"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg></a>
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <div class="bd-doc-cell">
              <div class="bd-doc-icon pdf">PDF</div>
              <div>
                <span class="bd-doc-name">Rapport_audit_qualite.pdf</span>
                <span class="bd-doc-meta">1.8 Mo</span>
              </div>
            </div>
          </td>
          <td><div class="bd-user-cell"><div class="bd-mini-avatar" style="background:var(--bd-red);">KB</div> K. Bernard</div></td>
          <td>28 juillet 2026</td>
          <td>
            <div class="bd-actions">
              <a class="bd-icon-btn edit" href="modifier.php?id=7" title="Modifier"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg></a>
              <a class="bd-icon-btn delete" href="supprimer.php?id=7" title="Supprimer" onclick="return confirm('Supprimer ce document ?');"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg></a>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- ===== Catégorie : Ressources humaines ===== -->
  <div class="bd-category" data-category="rh">
    <div class="bd-category-head">
      <h2>Ressources humaines</h2>
      <span class="count">3 documents</span>
    </div>
    <table class="bd-table">
      <thead>
        <tr>
          <th>Document</th>
          <th>Ajouté par</th>
          <th>Dernière modification</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <div class="bd-doc-cell">
              <div class="bd-doc-icon pdf">PDF</div>
              <div>
                <span class="bd-doc-name">Procedure_conges.pdf</span>
                <span class="bd-doc-meta">560 Ko</span>
              </div>
            </div>
          </td>
          <td><div class="bd-user-cell"><div class="bd-mini-avatar" style="background:var(--bd-red);">SL</div> S. Leroy</div></td>
          <td>Hier, 17:32</td>
          <td>
            <div class="bd-actions">
              <a class="bd-icon-btn edit" href="modifier.php?id=8" title="Modifier"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg></a>
              <a class="bd-icon-btn delete" href="supprimer.php?id=8" title="Supprimer" onclick="return confirm('Supprimer ce document ?');"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg></a>
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <div class="bd-doc-cell">
              <div class="bd-doc-icon">DOC</div>
              <div>
                <span class="bd-doc-name">Livret_accueil.docx</span>
                <span class="bd-doc-meta">1.4 Mo</span>
              </div>
            </div>
          </td>
          <td><div class="bd-user-cell"><div class="bd-mini-avatar">AD</div> Admin</div></td>
          <td>15 juillet 2026</td>
          <td>
            <div class="bd-actions">
              <a class="bd-icon-btn edit" href="modifier.php?id=9" title="Modifier"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg></a>
              <a class="bd-icon-btn delete" href="supprimer.php?id=9" title="Supprimer" onclick="return confirm('Supprimer ce document ?');"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg></a>
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <div class="bd-doc-cell">
              <div class="bd-doc-icon pdf">PDF</div>
              <div>
                <span class="bd-doc-name">Grille_evaluation_annuelle.pdf</span>
                <span class="bd-doc-meta">420 Ko</span>
              </div>
            </div>
          </td>
          <td><div class="bd-user-cell"><div class="bd-mini-avatar">EM</div> E. Martin</div></td>
          <td>2 juillet 2026</td>
          <td>
            <div class="bd-actions">
              <a class="bd-icon-btn edit" href="modifier.php?id=10" title="Modifier"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg></a>
              <a class="bd-icon-btn delete" href="supprimer.php?id=10" title="Supprimer" onclick="return confirm('Supprimer ce document ?');"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg></a>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- ===== Catégorie : Procédures ===== -->
  <div class="bd-category" data-category="procedures">
    <div class="bd-category-head">
      <h2>Procédures</h2>
      <span class="count">2 documents</span>
    </div>
    <table class="bd-table">
      <thead>
        <tr>
          <th>Document</th>
          <th>Ajouté par</th>
          <th>Dernière modification</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <div class="bd-doc-cell">
              <div class="bd-doc-icon pdf">PDF</div>
              <div>
                <span class="bd-doc-name">Procedure_securite_incendie.pdf</span>
                <span class="bd-doc-meta">980 Ko</span>
              </div>
            </div>
          </td>
          <td><div class="bd-user-cell"><div class="bd-mini-avatar">AD</div> Admin</div></td>
          <td>20 juin 2026</td>
          <td>
            <div class="bd-actions">
              <a class="bd-icon-btn edit" href="modifier.php?id=11" title="Modifier"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg></a>
              <a class="bd-icon-btn delete" href="supprimer.php?id=11" title="Supprimer" onclick="return confirm('Supprimer ce document ?');"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg></a>
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <div class="bd-doc-cell">
              <div class="bd-doc-icon">DOC</div>
              <div>
                <span class="bd-doc-name">Procedure_achat_fournitures.docx</span>
                <span class="bd-doc-meta">300 Ko</span>
              </div>
            </div>
          </td>
          <td><div class="bd-user-cell"><div class="bd-mini-avatar" style="background:var(--bd-red);">KB</div> K. Bernard</div></td>
          <td>5 juin 2026</td>
          <td>
            <div class="bd-actions">
              <a class="bd-icon-btn edit" href="modifier.php?id=12" title="Modifier"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg></a>
              <a class="bd-icon-btn delete" href="supprimer.php?id=12" title="Supprimer" onclick="return confirm('Supprimer ce document ?');"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg></a>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

</div>

<script>
  // Filtre par catégorie (front uniquement — masque/affiche les blocs déjà présents dans la page)
  document.getElementById('bd-tabs').addEventListener('click', function(e){
    var btn = e.target.closest('.bd-tab');
    if(!btn) return;

    document.querySelectorAll('.bd-tab').forEach(function(t){ t.classList.remove('is-active'); });
    btn.classList.add('is-active');

    var target = btn.getAttribute('data-target');
    document.querySelectorAll('.bd-category').forEach(function(cat){
      var show = (target === 'all' || cat.getAttribute('data-category') === target);
      cat.classList.toggle('is-hidden', !show);
    });
  });
</script>

</body>
</html>





</x-app-layout>