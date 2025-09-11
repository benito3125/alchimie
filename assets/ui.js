/* ===========================================================
   UI — entête, login, hubs + routage des tuiles en modales
   =========================================================== */
(function () {
  const $  = s => document.querySelector(s);
  const $$ = s => Array.from(document.querySelectorAll(s));

  /* ---------- helpers auth sûrs ---------- */
  function userIdentity(u) {
    if (!u) return 'user';
    return u.email || u.first_name || u.username || 'user';
  }
  const isAdmin  = (u) => (window.auth && auth.isAdmin(u));
  const hasRole  = (u, space, role) => (window.auth && auth.hasSpaceRole(u, space, role));
  const canTool  = (u, spec) => (window.auth && auth.canAccessTool(u, spec));

  /* ---------- Header / Footer ---------- */
  async function mountHeader() {
    await (window.auth?.ready?.() || Promise.resolve());
    const u = window.auth?.getUser?.() || null;

    const root = document.getElementById('app-header') || document.querySelector('.bar') || document.createElement('div');
    root.className = 'bar';
    root.innerHTML = `
      <div class="brand"><span class="title">Alchimie du Jeu</span></div>
      <nav class="nav">
        <a href="./home.html">Accueil</a>
        <a href="./catalog.html" class="${/catalog\.html$/.test(location.pathname)?'active':''}">Catalogue</a>
        ${u ? `<span class="badgeUser">@${userIdentity(u)}</span>
               <button id="btn-logout" class="btn small outline">Déconnexion</button>`
            : `<button id="btn-login" class="btn small">Connexion</button>`}
      </nav>`;
    if(!document.getElementById('app-header')) document.querySelector('.header')?.appendChild(root);

    const L = $('#btn-login'), O = $('#btn-logout');
    if (L) L.onclick = openLogin;
    if (O) O.onclick = async ()=>{ await auth.logout(); location.href = 'home.html'; };
  }
  function mountFooter(){
    const f = document.getElementById('app-footer');
    if(f) f.innerHTML = `© 2025 — Alchimie du Jeu`;
  }

  /* ---------- Login modal ---------- */
  function ensureLoginModal(){
    if($('#auth-modal')) return;
    document.body.insertAdjacentHTML('beforeend', `
      <div class="modal" id="auth-modal" aria-modal="true" role="dialog">
        <div class="dialog">
          <header><strong>Connexion</strong><button class="btn small outline" id="auth-close">Fermer</button></header>
          <main>
            <label>Email<br><input id="auth-user" type="email" placeholder="prenom.nom@mail.com"></label>
            <label>Mot de passe<br><input id="auth-pass" type="password" placeholder="••••••••"></label>
            <p id="auth-msg" class="help"></p>
          </main>
          <footer><button class="btn" id="auth-ok">Se connecter</button></footer>
        </div>
      </div>`);
    $('#auth-close').onclick = ()=> $('#auth-modal').classList.remove('show');
    $('#auth-ok').onclick = doLogin;
  }
  async function doLogin(){
    const u = $('#auth-user').value.trim();
    const p = $('#auth-pass').value;
    $('#auth-msg').textContent = '';
    try{
      await auth.login(u,p);
      $('#auth-modal').classList.remove('show');
      await mountHeader();
    }catch(e){
      $('#auth-msg').textContent = e.message || 'Identifiants invalides.';
    }
  }
  function openLogin(){ ensureLoginModal(); $('#auth-modal').classList.add('show'); }

  /* ---------- Modales Hubs ---------- */
  function openAdminHub(){
    const u = auth.getUser();
    if(!auth.isAdmin(u)){ alert('Accès réservé aux administrateurs.'); return; }
    const id='admin-hub';
    if(!$('#'+id)){
      document.body.insertAdjacentHTML('beforeend', `
      <div class="modal" id="${id}">
        <div class="dialog">
          <header><strong>Administration</strong><button class="btn small outline js-close">Fermer</button></header>
          <main>
            <div class="tiles">
              <a class="tile" style="text-decoration:none" href="./admin-accounts.html">
                <div class="top"><div class="badge bg-admin">👥</div><div><h3 class="t-title">Comptes</h3>
                <p class="t-sub">Gestion des comptes, rôles & espaces.</p></div></div>
              </a>
              <a class="tile" style="text-decoration:none" href="./admin-stock.html">
                <div class="top"><div class="badge bg-log">📦</div><div><h3 class="t-title">Stocks globaux</h3>
                <p class="t-sub">Paramètres & inventaire.</p></div></div>
              </a>
              <a class="tile" style="text-decoration:none" href="./admin-festival.html">
                <div class="top"><div class="badge bg-accueil">🎪</div><div><h3 class="t-title">Festival</h3>
                <p class="t-sub">Paramètres généraux (démo).</p></div></div>
              </a>
            </div>
          </main>
          <footer><button class="btn outline js-close">Fermer</button></footer>
        </div>
      </div>`);
      $$('#'+id+' .js-close').forEach(b=>b.onclick=()=>$('#'+id).classList.remove('show'));
    }
    $('#'+id).classList.add('show');
  }

  function openAccueilHub(){
    const u = auth.getUser(); if(!u){ openLogin(); return; }
    const canBene = canTool(u, {space:'accueil', minRole:'benevole'});
    const canResp = hasRole(u,'accueil','responsable') || isAdmin(u);

    const id='accueil-hub';
    if(!$('#'+id)){
      document.body.insertAdjacentHTML('beforeend', `
      <div class="modal" id="${id}">
        <div class="dialog">
          <header><strong>Accueil bénévoles</strong><button class="btn small outline js-close">Fermer</button></header>
          <main><div class="tiles" id="${id}-tiles"></div></main>
          <footer><button class="btn outline js-close">Fermer</button></footer>
        </div>
      </div>`);
      $$('#'+id+' .js-close').forEach(b=>b.onclick=()=>$('#'+id).classList.remove('show'));
    }
    const host = $('#'+id+'-tiles');
    host.innerHTML = '';

    const items = [
      {name:'Administration de l’espace',desc:'Réservé aux responsables.',icon:'🧭',cls:'bg-accueil',url:'./acceuil-admin.html?space=accueil',allow:canResp},
      {name:'Onboarding',desc:'Intégrer un bénévole.',icon:'✅',cls:'bg-accueil',url:'./accueil-onboarding.html',allow:canBene},
      {name:'Offboarding',desc:'Sortie / fin de mission.',icon:'👋',cls:'bg-accueil',url:'./accueil-offboarding.html',allow:canBene},
      {name:'Éditer les badges',desc:'Impression / édition.',icon:'🎟️',cls:'bg-accueil',url:'./accueil-badges.html',allow:canResp},
    ];
    for (const t of items){
      const a=document.createElement('a');
      a.className='tile'+(t.allow?'':' disabled'); a.style.textDecoration='none';
      a.href=t.allow?t.url:'#';
      a.innerHTML=`<div class="top"><div class="badge ${t.cls}">${t.icon}</div><div>
        <h3 class="t-title">${t.name}</h3><p class="t-sub">${t.desc}</p></div></div>`;
      a.onclick=(e)=>{ if(!t.allow){ e.preventDefault(); alert('Accès restreint.'); } };
      host.appendChild(a);
    }
    $('#'+id).classList.add('show');
  }

  function openLogistiqueHub(){
    const u = auth.getUser(); if(!u){ openLogin(); return; }
    const canResp = hasRole(u,'logistique','responsable') || isAdmin(u);
    const canRead = canResp || hasRole(u,'logistique','benevole');

    const id='logistique-hub';
    if(!$('#'+id)){
      document.body.insertAdjacentHTML('beforeend', `
      <div class="modal" id="${id}">
        <div class="dialog">
          <header><strong>Logistique</strong><button class="btn small outline js-close">Fermer</button></header>
          <main><div class="tiles" id="${id}-tiles"></div></main>
          <footer><button class="btn outline js-close">Fermer</button></footer>
        </div>
      </div>`);
      $$('#'+id+' .js-close').forEach(b=>b.onclick=()=>$('#'+id).classList.remove('show'));
    }
    const host = $('#'+id+'-tiles');
    host.innerHTML = '';

    const items = [
      {name:'Administration de l’espace',desc:'Réservé aux responsables.',icon:'🧭',cls:'bg-log',url:'./logistique-admin.html',allow:canResp},
      {name:'Gestion des stocks',desc:'Inventaire & mouvements.',icon:'📦',cls:'bg-log',url:'./logistique-stocks.html',allow:canRead},
    ];
    for (const t of items){
      const a=document.createElement('a');
      a.className='tile'+(t.allow?'':' disabled'); a.style.textDecoration='none';
      a.href=t.allow?t.url:'#';
      a.innerHTML=`<div class="top"><div class="badge ${t.cls}">${t.icon}</div><div>
        <h3 class="t-title">${t.name}</h3><p class="t-sub">${t.desc}</p></div></div>`;
      a.onclick=(e)=>{ if(!t.allow){ e.preventDefault(); alert('Accès restreint.'); } };
      host.appendChild(a);
    }
    $('#'+id).classList.add('show');
  }

  function openRepasHub(){
    const u = auth.getUser(); if(!u){ openLogin(); return; }
    const canResp = hasRole(u,'repas','responsable') || isAdmin(u);
    const canRead = canResp || hasRole(u,'repas','benevole');

    const id='repas-hub';
    if(!$('#'+id)){
      document.body.insertAdjacentHTML('beforeend', `
      <div class="modal" id="${id}">
        <div class="dialog">
          <header><strong>Repas</strong><button class="btn small outline js-close">Fermer</button></header>
          <main><div class="tiles" id="${id}-tiles"></div></main>
          <footer><button class="btn outline js-close">Fermer</button></footer>
        </div>
      </div>`);
      $$('#'+id+' .js-close').forEach(b=>b.onclick=()=>$('#'+id).classList.remove('show'));
    }
    const host = $('#'+id+'-tiles');
    host.innerHTML = '';

    const items = [
      {name:'Administration de l’espace',desc:'Réservé aux responsables.',icon:'🧭',cls:'bg-repas',url:'./repas-admin.html',allow:canResp},
      {name:'Gestion des repas',desc:'Outils opérationnels.',icon:'📊',cls:'bg-repas',url:'./repas-tools.html',allow:canRead},
    ];
    for (const t of items){
      const a=document.createElement('a');
      a.className='tile'+(t.allow?'':' disabled'); a.style.textDecoration='none';
      a.href=t.allow?t.url:'#';
      a.innerHTML=`<div class="top"><div class="badge ${t.cls}">${t.icon}</div><div>
        <h3 class="t-title">${t.name}</h3><p class="t-sub">${t.desc}</p></div></div>`;
      a.onclick=(e)=>{ if(!t.allow){ e.preventDefault(); alert('Accès restreint.'); } };
      host.appendChild(a);
    }
    $('#'+id).classList.add('show');
  }

  /* ---------- Routage tuiles -> modales ---------- */
  function wireCatalogHubs() {
    const root = document.getElementById('catalog-grid') || document;
    root.addEventListener('click', (e) => {
      const a = e.target.closest('[data-hub]');
      if (!a) return;
      e.preventDefault(); // on empêche la navigation
      const hub = a.getAttribute('data-hub');
      if (hub === 'accueil') return openAccueilHub();
      if (hub === 'logistique') return openLogistiqueHub();
      if (hub === 'administration') return openAdminHub();
      if (hub === 'repas') return openRepasHub();
    });

    // si jamais une tuile contient un <a href="..."> hérité, on neutralise
    $$('[data-hub] a[href]').forEach(a => { a.addEventListener('click', ev => ev.preventDefault(), { capture:true }); });
  }

  /* ---------- API publique UI ---------- */
  window.UI = {
    mountHeader, mountFooter,
    injectAuthModals(){ ensureLoginModal(); },
    openLogin, openAdminHub, openAccueilHub, openLogistiqueHub, openRepasHub,
    wireCatalogHubs
  };

  // réagit aux changements de session
  window.auth?.onChange?.(mountHeader);
})();
