/* ===========================================================
   AUTH — session, droits & accès API
   =========================================================== */
(function () {
  const API_BASE = (function () {
    // si le site tourne sous /preprod, on garde le préfixe
    const m = location.pathname.match(/^\/([^/]+)\//);
    return (m && m[1] === 'preprod') ? '/preprod/api' : '/api';
  })();

  const listeners = new Set();
  let currentUser = null;
  let mePromise = null;

  function emitChange() { listeners.forEach(cb => { try { cb(currentUser); } catch {} }); }
  function onChange(cb) { listeners.add(cb); return () => listeners.delete(cb); }

  async function jfetch(url, opts = {}) {
    const res = await fetch(url, {
      credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
      ...opts
    });
    const isJSON = (res.headers.get('content-type') || '').includes('application/json');
    const body = isJSON ? await res.json() : await res.text();
    if (!res.ok) {
      const msg = (body && body.error) ? body.error : `HTTP ${res.status}`;
      throw new Error(msg);
    }
    return body;
  }

  // ---------- Normalisation des espaces
  function normalizeSpaces(spaces) {
    // map directe ? on la garde.
    if (spaces && !Array.isArray(spaces) && typeof spaces === 'object') return spaces;
    const map = {};
    if (Array.isArray(spaces)) {
      spaces.forEach(s => {
        const id = s.space_id || s.space || s.id;
        if (!id) return;
        map[id] = s.role || s.space_role || s.role_in_space || 'benevole';
      });
    }
    return map;
  }

  function decorateUser(raw) {
    if (!raw) return null;
    const u = { ...raw };
    u.roles = Array.isArray(u.roles) ? u.roles : (u.roles ? [u.roles] : []);
    u.spacesArr = Array.isArray(u.spaces) ? u.spaces.slice() : []; // on garde l’array si besoin
    u.spaces = normalizeSpaces(u.spaces);                          // mais on expose un map
    return u;
  }

  async function fetchMe() {
    const data = await jfetch(`${API_BASE}/me.php`);
    currentUser = decorateUser(data.user || null);
    emitChange();
    return currentUser;
  }

  function ready() {
    if (!mePromise) mePromise = fetchMe().catch(() => { currentUser = null; emitChange(); });
    return mePromise;
  }

  // ---------- droits
  function getUser() { return currentUser; }
  function isAdmin(u = currentUser) { return !!(u && u.roles && u.roles.includes('admin')); }
  function spaceRole(u = currentUser, space) {
    if (!u || !space) return null;
    return (u.spaces || {})[space] || null;
  }
  function hasSpaceRole(u = currentUser, space, role) {
    if (isAdmin(u)) return true;
    const r = spaceRole(u, space);
    if (!role) return !!r;
    const rank = { benevole: 1, responsable: 2 };
    return (rank[r] || 0) >= (rank[role] || 0);
  }
  function canAccessTool(u = currentUser, { space, minRole } = {}) {
    if (!u) return false;
    if (isAdmin(u)) return true;
    if (!space) return true;
    return hasSpaceRole(u, space, minRole || 'benevole');
  }

  // ---------- login / logout
  async function login(email, password) {
    await jfetch(`${API_BASE}/login.php`, {
      method: 'POST',
      body: JSON.stringify({ email, password })
    });
    await fetchMe();
    return true;
  }
  async function logout() {
    await jfetch(`${API_BASE}/logout.php`, { method: 'POST', body: '{}' }).catch(()=>{});
    currentUser = null;
    emitChange();
    return true;
  }

  // ---------- admin lecture (liste)
  async function listUsers() {
    if (!isAdmin()) throw new Error('Accès réservé aux administrateurs.');
    const res = await jfetch(`${API_BASE}/users.php`);
    return Array.isArray(res.items) ? res.items : [];
  }

  window.auth = {
    API_BASE,
    // état
    ready, onChange, getUser,
    // session
    login, logout,
    // droits
    isAdmin, spaceRole, hasSpaceRole, canAccessTool,
    // admin
    listUsers,
  };

  // démarre immédiatement la session
  ready();
})();
