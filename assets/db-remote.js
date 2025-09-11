/* ===========================================================
   assets/db-remote.js — adapter REST pour /api/users.php
   Garde les mêmes signatures que ton code attend:
   - DB.listUsers({q})
   - DB.listSpaceMembers(space)
   - DB.getUser(username)
   - DB.createUser({username, pass, role})
   - DB.resetPassword(username, newPass)
   - DB.setGlobalRole(username, role)
   - DB.setSpaceRole(username, space, role)  // role=null => retrait
   - DB.deleteUser(username)
   =========================================================== */
(function () {
  const API = '/api/users.php';

  async function jfetch(url, opt) {
    const res = await fetch(url, Object.assign({
      headers: { 'Content-Type': 'application/json' }
    }, opt || {}));
    const data = await res.json().catch(()=> ({}));
    if (!res.ok) throw new Error(data.error || ('HTTP '+res.status));
    return data;
  }

  function listUsers({ q='' } = {}) {
    return jfetch(`${API}?action=list&q=${encodeURIComponent(q)}`);
  }
  function listSpaceMembers(space) {
    return jfetch(`${API}?action=listSpaceMembers&space=${encodeURIComponent(space)}`);
  }
  function getUser(username) {
    return jfetch(`${API}?action=get&username=${encodeURIComponent(username)}`);
  }
  function createUser(payload) {
    return jfetch(API, { method:'POST', body: JSON.stringify(Object.assign({ action:'create' }, payload)) });
  }
  function resetPassword(username, newPass='alchimie') {
    return jfetch(API, { method:'POST', body: JSON.stringify({ action:'resetPassword', username, newPass }) });
  }
  function setGlobalRole(username, role) {
    return jfetch(API, { method:'POST', body: JSON.stringify({ action:'setGlobalRole', username, role }) });
  }
  function setSpaceRole(username, space, role) {
    return jfetch(API, { method:'POST', body: JSON.stringify({ action:'setSpaceRole', username, space, role }) });
  }
  function deleteUser(username) {
    return jfetch(API, { method:'POST', body: JSON.stringify({ action:'delete', username }) });
  }

  window.DB = {
    listUsers, listSpaceMembers, getUser,
    createUser, resetPassword, setGlobalRole,
    setSpaceRole, deleteUser
  };
})();
