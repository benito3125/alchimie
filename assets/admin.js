(function(){
  const user = auth.requireAuth(); if (!user) return;

  // garde : admin only
  if (!auth.hasAnyRole(user, ['admin'])) {
    document.querySelector('#admin-guard').style.display='block';
    document.querySelector('#admin-guard').innerHTML =
      `<p><strong>Accès refusé.</strong> Cette page est réservée aux administrateurs.</p>
       <p><button class="btn btn-outline" onclick="location.href='catalog.html'">Retour au catalogue</button></p>`;
    return;
  }

  UI.mountHeader('Administration');
  UI.mountFooter();
  UI.injectAuthModals();

  const container = document.querySelector('#users-table');
  const ROLES = auth.ALL_ROLES;

  function render(){
    const rows = auth.listUsers();
    const table = document.createElement('table');
    table.className = 'table';
    table.style.width = '100%';
    table.style.borderCollapse = 'collapse';
    table.innerHTML = `
      <style>
        .table th,.table td{border:1px solid var(--line);padding:8px 10px;text-align:left}
        .table th{background:#fafafa}
        .actions a{margin-right:10px;cursor:pointer;color:var(--brand);font-weight:700}
      </style>
      <thead><tr>
        <th>Utilisateur</th><th>Nom</th><th>Rôles</th><th style="width:260px">Actions</th>
      </tr></thead>
      <tbody>
        ${rows.map(r=>`
          <tr data-u="${r.username}">
            <td><code>${r.username}</code></td>
            <td>${escapeHtml(r.name||'')}</td>
            <td>${(r.roles||[]).join(', ')||'—'}</td>
            <td class="actions">
              <a data-act="edit">Éditer rôles</a>
              <a data-act="reset">Réinitialiser MDP</a>
              <a data-act="delete">Supprimer</a>
            </td>
          </tr>
        `).join('')}
      </tbody>`;
    container.innerHTML = '';
    container.appendChild(table);

    table.addEventListener('click', e=>{
      const a = e.target.closest('[data-act]'); if(!a) return;
      const tr = e.target.closest('tr'); const username = tr?.dataset?.u;
      if(!username) return;

      if(a.dataset.act==='edit')  return onEditRoles(username);
      if(a.dataset.act==='reset') return onResetPassword(username);
      if(a.dataset.act==='delete')return onDeleteUser(username);
    });
  }

  function onEditRoles(username){
    const rec = auth.getUserRecord(username); if(!rec) return;
    const set  = new Set(rec.roles||[]);
    const html = `
      <div class="modal show" id="role-modal">
        <div class="dialog" role="dialog" aria-modal="true">
          <header><strong>Rôles — ${username}</strong><button class="btn btn-outline" data-close>Fermer</button></header>
          <main>
            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:8px">
              ${ROLES.map(r=>`
                <label><input type="checkbox" value="${r}" ${set.has(r)?'checked':''}> ${r}</label>
              `).join('')}
            </div>
            <div class="actions">
              <button class="btn btn-primary" data-save>Enregistrer</button>
            </div>
          </main>
        </div>
      </div>`;
    const wrap = document.createElement('div'); wrap.innerHTML = html;
    document.body.appendChild(wrap.firstElementChild);
    const modal = document.querySelector('#role-modal');

    modal.addEventListener('click', e=>{
      if(e.target===modal || e.target.hasAttribute('data-close')) modal.remove();
    });
    modal.querySelector('[data-save]').addEventListener('click', ()=>{
      const roles = Array.from(modal.querySelectorAll('input[type=checkbox]:checked')).map(i=>i.value);
      const res = auth.setRoles(username, roles);
      if(!res.ok){
        if(res.err==='last_admin') alert('Impossible de retirer le dernier rôle admin.');
        else alert('Erreur : '+res.err);
        return;
      }
      auth.refreshSession();
      UI.mountHeader('Administration'); // rafraîchit le bandeau si on s’est modifié
      modal.remove();
      render();
    });
  }

  function onResetPassword(username){
    const custom = prompt('Nouveau mot de passe (laisse vide pour auto-généré) :','');
    const res = auth.resetPassword(username, custom || undefined);
    if(!res.ok) return alert('Erreur : '+res.err);
    alert(`Mot de passe réinitialisé pour "${username}" :\n${res.password}`);
  }

  function onDeleteUser(username){
    if(!confirm(`Supprimer le compte "${username}" ?`)) return;
    const res = auth.deleteUser(username);
    if(!res.ok){
      if(res.err==='self_delete') return alert('Tu ne peux pas te supprimer toi-même.');
      if(res.err==='last_admin')  return alert('Impossible de supprimer le dernier admin.');
      return alert('Erreur : '+res.err);
    }
    render();
  }

  // Création
  document.querySelector('#btn-new').addEventListener('click', ()=>{
    const html = `
      <div class="modal show" id="new-modal">
        <div class="dialog">
          <header><strong>Créer un utilisateur</strong><button class="btn btn-outline" data-close>Fermer</button></header>
          <main>
            <div class="row">
              <label>Identifiant<br><input id="nu-id" type="text" placeholder="ex: jdupont"></label>
              <label>Nom affiché<br><input id="nu-name" type="text" placeholder="Jean Dupont"></label>
            </div>
            <div style="margin:8px 0 6px">Rôles :</div>
            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:8px" id="nu-roles">
              ${ROLES.map(r=>`<label><input type="checkbox" value="${r}"> ${r}</label>`).join('')}
            </div>
            <div class="row" style="margin-top:10px">
              <label>Mot de passe (laisser vide pour auto)<br><input id="nu-pass" type="text" placeholder="(auto)"></label>
            </div>
            <div class="actions"><button class="btn btn-primary" data-create>Créer</button></div>
          </main>
        </div>
      </div>`;
    const wrap = document.createElement('div'); wrap.innerHTML = html;
    document.body.appendChild(wrap.firstElementChild);
    const modal = document.querySelector('#new-modal');

    modal.addEventListener('click', e=>{
      if(e.target===modal || e.target.hasAttribute('data-close')) modal.remove();
    });
    modal.querySelector('[data-create]').addEventListener('click', ()=>{
      const id   = (document.querySelector('#nu-id').value||'').trim().toLowerCase();
      const name = (document.querySelector('#nu-name').value||'').trim();
      const pass = (document.querySelector('#nu-pass').value||'').trim() || undefined;
      const roles = Array.from(document.querySelectorAll('#nu-roles input[type=checkbox]:checked')).map(i=>i.value);
      if(!id) return alert('Identifiant requis.');
      const res = auth.upsertUser({ username:id, name, roles, password:pass });
      if(!res.ok) return alert('Erreur : '+res.err);
      alert(`Utilisateur "${id}" ${res.created?'créé':'mis à jour'}.\nMot de passe : ${res.password}`);
      modal.remove();
      render();
    });
  });

  function escapeHtml(s){ return String(s||'').replace(/[&<>"]/g, m=>({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;' }[m])); }

  render();
})();
