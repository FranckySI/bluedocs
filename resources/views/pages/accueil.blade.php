<x-app-layout>


<style>
  body{
    margin:0;
    background:var(--bd-paper);
    font-family:var(--bd-font-body);
    color:var(--bd-ink);
  }

  .bd-hero{
    position:relative;
    overflow:hidden;
    background:
      radial-gradient(ellipse at 20% 0%, rgba(204,0,51,0.18), transparent 55%),
      linear-gradient(160deg, var(--bd-blue-dark) 10%, var(--bd-blue) 100%);
    color:var(--bd-white);
    padding:90px 40px 60px;
    clip-path:polygon(0 0, 100% 0, 100% calc(100% - 28px), calc(100% - 28px) 100%, 0 100%);
  }

  /* motif de fond : grille technique évoquant des documents classés */
  .bd-hero::before{
    content:"";
    position:absolute; inset:0;
    background-image:
      linear-gradient(var(--bd-line) 1px, transparent 1px),
      linear-gradient(90deg, var(--bd-line) 1px, transparent 1px);
    background-size:46px 46px;
    -webkit-mask-image:radial-gradient(ellipse at 50% 30%, black 0%, transparent 70%);
    mask-image:radial-gradient(ellipse at 50% 30%, black 0%, transparent 70%);
    opacity:0.5;
    animation:bd-drift 18s linear infinite;
  }
  @keyframes bd-drift{
    from{ background-position:0 0, 0 0; }
    to{ background-position:46px 46px, -46px 46px; }
  }
  @media (prefers-reduced-motion:reduce){
    .bd-hero::before{ animation:none; }
  }

  .bd-hero-inner{
    position:relative;
    max-width:920px;
    margin:0 auto;
    text-align:center;
  }

  .bd-eyebrow{
    display:inline-block;
    font-family:var(--bd-font-mono);
    font-size:12.5px;
    letter-spacing:2px;
    color:var(--bd-muted);
    border:1px solid var(--bd-line);
    border-radius:20px;
    padding:6px 16px;
    margin-bottom:26px;
  }

  .bd-title{
    font-family:var(--bd-font-display);
    font-weight:700;
    line-height:1.03;
    text-transform:uppercase;
    font-size:clamp(34px, 6vw, 64px);
    margin:0 0 28px;
    letter-spacing:0.5px;
  }
  .bd-title .accent{ color:var(--bd-red); }

  .bd-subtitle{
    font-size:16px;
    color:var(--bd-muted);
    max-width:560px;
    margin:0 auto 40px;
    line-height:1.6;
  }

  /* CTA type "Choisir un fichier" */
  .bd-upload{
    display:inline-flex;
    align-items:stretch;
    background:var(--bd-white);
    border-radius:6px;
    overflow:hidden;
    box-shadow:0 18px 40px rgba(0,0,0,0.35);
  }
  .bd-upload span{
    display:flex; align-items:center;
    padding:0 26px;
    font-weight:600; font-size:15px;
    color:var(--bd-ink);
  }
  .bd-upload button{
    display:flex; align-items:center; justify-content:center;
    width:52px;
    border:0; cursor:pointer;
    background:var(--bd-blue-dark);
    color:var(--bd-red);
    font-size:18px;
    transition:background .2s ease;
  }
  .bd-upload button:hover, .bd-upload button:focus-visible{ background:var(--bd-red); color:var(--bd-white); }

  /* Journal d'activité en direct — élément signature */
  .bd-activity{
    position:relative;
    max-width:520px;
    margin:44px auto 0;
    text-align:left;
    border:1px solid var(--bd-line);
    border-radius:8px;
    background:rgba(255,255,255,0.04);
    backdrop-filter:blur(2px);
    overflow:hidden;
  }
  .bd-activity-head{
    display:flex; align-items:center; gap:8px;
    padding:12px 18px;
    font-family:var(--bd-font-mono);
    font-size:12px;
    letter-spacing:1px;
    color:var(--bd-muted);
    border-bottom:1px solid var(--bd-line);
  }
  .bd-dot{
    width:7px; height:7px; border-radius:50%;
    background:var(--bd-red);
    box-shadow:0 0 0 0 rgba(204,0,51,0.6);
    animation:bd-pulse 2s ease-out infinite;
  }
  @keyframes bd-pulse{
    0%{ box-shadow:0 0 0 0 rgba(204,0,51,0.5); }
    70%{ box-shadow:0 0 0 8px rgba(204,0,51,0); }
    100%{ box-shadow:0 0 0 0 rgba(204,0,51,0); }
  }
  .bd-activity ul{
    list-style:none; margin:0; padding:6px 0;
  }
  .bd-activity li{
    display:flex; justify-content:space-between; gap:12px;
    padding:9px 18px;
    font-family:var(--bd-font-mono);
    font-size:12.5px;
    color:var(--bd-white);
    opacity:0.9;
  }
  .bd-activity li b{ color:var(--bd-red); font-weight:500; }
  .bd-activity li .who{ color:var(--bd-muted); }

  /* ---------- CARTES ---------- */
  .bd-cards{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:24px;
    max-width:1200px;
    margin:-40px auto 60px;
    padding:0 40px;
    position:relative;
    z-index:2;
  }
  .bd-card{
    background:var(--bd-blue-dark);
    color:var(--bd-white);
    padding:32px 30px 34px;
    clip-path:polygon(0 0, 100% 0, 100% 100%, 26px 100%, 0 calc(100% - 26px));
    display:flex;
    flex-direction:column;
    gap:14px;
  }
  .bd-card h3{
    font-family:var(--bd-font-display);
    font-size:22px;
    margin:0;
    line-height:1.2;
  }
  .bd-card p{
    font-size:14px;
    color:var(--bd-muted);
    line-height:1.6;
    margin:0;
  }
  .bd-card-icon{ margin-bottom:6px; }

  .bd-roles{
    display:flex;
    gap:14px;
    margin-top:8px;
  }
  .bd-role{
    display:flex; flex-direction:column; align-items:center; gap:8px;
    font-size:12px; color:var(--bd-muted);
  }
  .bd-role-badge{
    width:44px; height:44px;
    border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    font-family:var(--bd-font-display);
    font-weight:700; font-size:13px;
    color:var(--bd-white);
    border:2px solid var(--bd-red);
    background:rgba(204,0,51,0.12);
  }

  @media (max-width:820px){
    .bd-cards{ grid-template-columns:1fr; margin-top:-24px; }
    .bd-hero{ padding:70px 24px 50px; }
  }
</style>

<section class="bd-hero">
  <div class="bd-hero-inner">
    <span class="bd-eyebrow">INTRANET · GESTION DOCUMENTAIRE</span>

    <h1 class="bd-title">
      Gérez les documents<br>
      facilement avec<br>
      <span class="accent">BlueDocs</span>
    </h1>

    <p class="bd-subtitle">
      Déposez, classez et retrouvez vos documents en quelques secondes.
      Chaque ajout, modification ou suppression est tracé automatiquement.
    </p>


    <div class="bd-activity">
      <div class="bd-activity-head"><span class="bd-dot"></span> JOURNAL D'ACTIVITÉ EN DIRECT</div>
      <ul>
        <li><span><b>Ajout</b> — Rapport_annuel_2026.pdf</span> <span class="who">Admin · 09:14</span></li>
        <li><span><b>Modification</b> — Contrat_fournisseur.docx</span> <span class="who">Editeur · 08:47</span></li>
        <li><span><b>Suppression</b> — Ancienne_procedure.pdf</span> <span class="who">Admin · 08:02</span></li>
      </ul>
    </div>
  </div>
</section>

<div class="bd-cards">

  <div class="bd-card">
    <svg class="bd-card-icon" width="40" height="40" viewBox="0 0 40 40" fill="none">
      <rect x="9" y="4" width="18" height="24" rx="2" stroke="#cc0033" stroke-width="2"/>
      <rect x="13" y="8" width="18" height="24" rx="2" fill="#00111f" stroke="#ffffff" stroke-width="2"/>
      <line x1="17" y1="14" x2="27" y2="14" stroke="#cc0033" stroke-width="1.6"/>
      <line x1="17" y1="19" x2="27" y2="19" stroke="#ffffff" stroke-width="1.2" opacity="0.5"/>
      <line x1="17" y1="24" x2="24" y2="24" stroke="#ffffff" stroke-width="1.2" opacity="0.5"/>
    </svg>
    <h3>BlueDocs — Gestion documentaire</h3>
    <p>Une bibliothèque centralisée pour toute l'entreprise : dépôt, mise à jour et suppression des documents, avec un historique complet de chaque action.</p>
  </div>

  <div class="bd-card">
    <h3>3 rôles, un accès adapté</h3>
    <p>Chaque utilisateur voit exactement ce dont il a besoin, sans exposer d'informations sensibles aux visiteurs.</p>
    <div class="bd-roles">
      <div class="bd-role"><div class="bd-role-badge">AD</div>Admin</div>
      <div class="bd-role"><div class="bd-role-badge">ED</div>Editeur</div>
      <div class="bd-role"><div class="bd-role-badge">VI</div>Visiteur</div>
    </div>
  </div>

</div>



</x-app-layout>