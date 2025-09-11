// assets/db.js
// Démo locale : stockage dans localStorage
// Schéma volontairement simple.

(function () {
  const KEY = 'aaj:volunteers:v1';

  function load() {
    try { return JSON.parse(localStorage.getItem(KEY) || '{}'); }
    catch { return {}; }
  }
  function save(store) { localStorage.setItem(KEY, JSON.stringify(store)); }

  // Stock mémoire
  let STORE = load();

  function seed() {
    if (Object.keys(STORE).length) return;
    STORE = {
      "B1001": {
        badge: "B1001", first: "Chloé", last: "Martin",
        role: "responsable", // responsable | benevole
        tshirtSize: "M",
        tshirtCode: null,
        lanyard: { distributed: false, color: "bleu", when: null },   // responsable => bleu
        bracelet:{ distributed: false, type: "benevole", when: null },
        onboarding:{ completed: false, when: null },
        history: []
      },
      "B1002": {
        badge: "B1002", first: "Lucas", last: "Bernard",
        role: "benevole",
        tshirtSize: null,
        tshirtCode: null,
        lanyard: { distributed: false, color: "noir", when: null },   // bénévole => noir
        bracelet:{ distributed: false, type: "benevole", when: null },
        onboarding:{ completed: false, when: null },
        history: []
      },
      "B1003": {
        badge: "B1003", first: "Nora", last: "Dupuis",
        role: "benevole",
        tshirtSize: "L",
        tshirtCode: "TS-001122",
        lanyard: { distributed: true, color: "noir", when: Date.now()-3600_000 },
        bracelet:{ distributed: true, type: "benevole", when: Date.now()-3600_000 },
        onboarding:{ completed: false, when: null },
        history: [{t: Date.now()-3600_000, msg: "Lanyard noir + bracelet distribués"}]
      },
      "B2004": {
        badge: "B1004", first: "Benjamin", last: "Bouteille",
        role: "benevole",
        tshirtSize: null,
        tshirtCode: null,
        lanyard: { distributed: false, color: "bleu", when: null },   // bénévole => noir
        bracelet:{ distributed: false, type: "benevole", when: null },
        onboarding:{ completed: false, when: null },
        history: []
      }
    };
    save(STORE);
  }

  function get(badge) {
    return STORE[badge] ? JSON.parse(JSON.stringify(STORE[badge])) : null;
  }
  function upsert(vol) {
    STORE[vol.badge] = JSON.parse(JSON.stringify(vol));
    save(STORE);
  }
  function all() { return Object.values(STORE).map(v=>JSON.parse(JSON.stringify(v))); }
  function reset() { STORE = {}; save(STORE); }

  window.db = { seed, get, upsert, all, reset };
})();
