/* ===========================================================
   Admin Comptes — liste + actions (UI côté client)
   =========================================================== */
(function () {
  const API = auth.API_BASE;               // ex: "/preprod/api"
  const $  = s => document.querySelector(s);

  const tbody = $('#users-body');
  const msg   = $('#adm-msg');
  const btnCreate = $('#btn-create');

  // ---- fetch JSON robuste (tolère du texte, remonte l’erreur lisible)
  async function jfetch(url, opts={}) {
    const res = await fetch(url, {
      credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
      ...opts
    });
    const text = await res.text();   // on lit toujours du texte
    let data = null;
    if (text.trim() !== '') {
      try { data = JSON.parse(text); } catch { data = { _raw: text }; }
    }
    if (!res.ok) {
      const err = (data && data.error) || `HTTP ${res.status}`;
      throw new Error(err);
    }
    return data;
  }

  function chipRole(r){
    return r ? `<span class="badge">${r}</span>` : '<span class="muted">—</span>';
  }

  function openModal({title, contentHTML, onOpen}) {
    const id='adm-modal';
    document.getElementById(id)?.remove();
    const el = document.createElement('div');
    el.className='modal show'; el.id=id;
    el.innerHTML=`
      <div class="dialog">
        <header><strong>${title||''}</strong>
          <button class="btn small outline js-close">Fermer</button>
        </header>
        <main>${contentHTML||''}</main>
        <footer>
          <button class="btn outline js-close">Fermer</button>
        </footer>
      </div>`;
    el.addEventListener('click', (e)=>{ if(e.target===el) el.remove(); });
    el.querySelectorAll('.js-close').forEach(b=>b.onclick=()=>el.remove());
    document.body.appendChild(el);
    onOpen && onOpen(el);
    return el;
  }

  // ---------------- charge et rend
  async function reload() {
    msg.textContent = '';
    tbody.innerHTML = `<tr><td colspan="4">Chargement…</td></tr>`;
    try{
      const { items=[] } = await jfetch(`${API}/users.php`);
      if(!items.length){
        tbody.innerHTML = `<tr><td colspan="4" class="muted">Aucun utilisateur.</td></tr>`;
        return;
      }
      tbody.innerHTML = '';
      for (const u of items) tbody.appendChild(row(u));
    }catch(e){
      tbody.innerHTML = `<tr><td colspan="4">Impossible de récupérer les comptes.</td></tr>`;
      msg.textContent = `Erreur : ${e.message || e}`;
    }
  }

  function row(u){
    const tr = document.createElement('tr');
    const name = [u.first_name||'', u.family_name||''].filter(Boolean).join(' ') || '—';
    tr.innerHTML = `
      <td><strong>${u.email}</strong></td>
      <td>${chipRole(u.role)}</td>
      <td>${name}</td>
      <td class="actions">
        <button class="btn small outline js-reset">Reset MP</button>
        <button class="btn small outline js-role">Rôle global</button>
        <button class="btn small outline js-spaces">Espaces</button>
        <button class="btn small outline danger js-del">Supprimer</button>
      </td>`;
    tr.querySelector('.js-reset').onclick  = ()=> actionReset(u);
    tr.querySelector('.js-role').onclick   = ()=> actionRole(u);
    tr.querySelector('.js-spaces').onclick = ()=> actionSpaces(u);
    tr.querySelector('.js-del').onclick    = ()=> actionDelete(u);
    return tr;
  }

  // ---------------- actions
  async function actionReset(u){
    if(!confirm(`Réinitialiser le mot de passe de ${u.email} ?`)) return;
    try{
      const r = await jfetch(`${API}/user_reset_password.php`, {method:'POST', body: JSON.stringify({ user_id: u.id })});
      alert(r.temp_password ? `Nouveau MP : ${r.temp_password}` : 'Mot de passe réinitialisé.');
    }catch(e){ alert(e.message || 'Erreur reset.'); }
  }

  function actionRole(u){
    const html = `
      <form class="list" onsubmit="return false;">
        <label>Rôle global<br>
          <select id="role-select">
            <option value="">(aucun)</option>
            <option value="admin">admin</option>
            <option value="comite">comite</option>
            <option value="visiteur">visiteur</option>
          </select>
        </label>
        <div class="actions" style="margin-top:10px">
          <button class="btn js-save">Enregistrer</button>
          <button type="button" class="btn outline js-close">Annuler</button>
        </div>
      </form>`;
    openModal({ title:`Rôle global — ${u.email}`, contentHTML: html, onOpen(el){
      el.querySelector('#role-select').value = u.role || '';
      el.querySelector('.js-save').onclick = async ()=>{
        try{
          await jfetch(`${API}/user_update_role.php`, {
            method:'POST',
            body: JSON.stringify({ user_id: u.id, role: el.querySelector('#role-select').value || null })
          });
          el.remove(); await reload();
        }catch(e){ alert(e.message || 'Erreur MAJ rôle.'); }
      };
    }});
  }

  function actionSpaces(u){
    const KNOWN = ['accueil','logistique','repas'];
    const current = (u.spaces && typeof u.spaces==='object') ? u.spaces : (u.spaces||{});
    const html = `
      <form class="list" onsubmit="return false;">
        ${KNOWN.map(s=>`
          <label>${s}<br>
            <select data-space="${s}">
              <option value="">(aucun)</option>
              <option value="benevole">benevole</option>
              <option value="responsable">responsable</option>
            </select>
          </label>
        `).join('')}
        <div class="actions" style="margin-top:10px">
          <button class="btn js-save">Enregistrer</button>
          <button type="button" class="btn outline js-close">Annuler</button>
        </div>
      </form>`;
    openModal({ title:`Espaces — ${u.email}`, contentHTML: html, onOpen(el){
      KNOWN.forEach(s=>{
        el.querySelector(`select[data-space="${s}"]`).value = current[s] || '';
      });
      el.querySelector('.js-save').onclick = async ()=>{
        const spaces = {};
        KNOWN.forEach(s=>{
          const v = el.querySelector(`select[data-space="${s}"]`).value;
          if (v) spaces[s] = v;
        });
        try{
          await jfetch(`${API}/user_spaces_set.php`, {
            method:'POST', body: JSON.stringify({ user_id: u.id, spaces })
          });
          el.remove(); await reload();
        }catch(e){ alert(e.message || 'Erreur MAJ espaces.'); }
      };
    }});
  }

  async function actionDelete(u){
    if(!confirm(`Supprimer l’utilisateur ${u.email} ?`)) return;
    try{
      await jfetch(`${API}/user_delete.php`, { method:'POST', body: JSON.stringify({ user_id: u.id }) });
      await reload();
    }catch(e){ alert(e.message || 'Erreur suppression.'); }
  }

  function randomPass(len=12){
    const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789!@$?';
    let out=''; crypto.getRandomValues(new Uint32Array(len)).forEach(n=> out+=chars[n%chars.length]);
    return out;
  }

  function actionCreate(){
    const KNOWN = ['accueil','logistique','repas'];
    const html = `
      <form class="list" onsubmit="return false;">
        <label>Email<br><input id="c-email" type="email" required></label>
        <div class="row" style="gap:8px">
          <label style="flex:1">Prénom<br><input id="c-first"></label>
          <label style="flex:1">Nom<br><input id="c-last"></label>
        </div>
        <label>Mot de passe
          <small class="muted">(laisser vide pour générer automatiquement)</small><br>
          <div class="row" style="gap:8px">
            <input id="c-pass" type="text" placeholder="••••••••" style="flex:1">
            <button type="button" class="btn outline js-gen">Générer</button>
          </div>
        </label>
        <label>Rôle global<br>
          <select id="c-role">
            <option value="">(aucun)</option>
            <option value="admin">admin</option>
            <option value="comite">comite</option>
            <option value="visiteur">visiteur</option>
          </select>
        </label>
        <fieldset><legend>Rôles par espace</legend>
          ${KNOWN.map(s=>`
            <label>${s}<br>
              <select data-space="${s}">
                <option value="">(aucun)</option>
                <option value="benevole">benevole</option>
                <option value="responsable">responsable</option>
              </select>
            </label>
          `).join('')}
        </fieldset>
        <div class="actions" style="margin-top:10px">
          <button class="btn js-save">Créer</button>
          <button type="button" class="btn outline js-close">Annuler</button>
        </div>
      </form>`;
    openModal({ title:'Créer un utilisateur', contentHTML: html, onOpen(el){
      const pass = el.querySelector('#c-pass');
      el.querySelector('.js-gen').onclick = ()=> pass.value = randomPass();

      el.querySelector('.js-save').onclick = async ()=>{
        const email = el.querySelector('#c-email').value.trim();
        const first = el.querySelector('#c-first').value.trim();
        const last  = el.querySelector('#c-last').value.trim();
        const role  = el.querySelector('#c-role').value || null;
        if(!email){ alert('Email requis'); return; }
        const spaces = {};
        KNOWN.forEach(s=>{
          const v = el.querySelector(`select[data-space="${s}"]`).value;
          if (v) spaces[s]=v;
        });
        try{
          const r = await jfetch(`${API}/user_create.php`, {
            method:'POST',
            body: JSON.stringify({
              email, first_name:first, family_name:last, role,
              spaces, password: pass.value || null
            })
          });
          if (r.temp_password) alert(`Utilisateur créé. Mot de passe: ${r.temp_password}`);
          el.remove(); await reload();
        }catch(e){ alert(e.message || 'Erreur création.'); }
      };
    }});
  }

  // -------------- bootstrap
  (async function init(){
    await auth.ready();
    const me = auth.getUser();
    if(!auth.isAdmin(me)){
      tbody.innerHTML = `<tr><td colspan="4">Accès réservé aux administrateurs.</td></tr>`;
      msg.textContent = 'Tu dois être connecté.';
      btnCreate.disabled = true;
      return;
    }
    btnCreate.onclick = actionCreate;
    reload();
  })();
})();
