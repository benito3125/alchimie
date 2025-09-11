// Base API choisie dynamiquement
window.API_BASE = (()=>{
  const host = location.hostname;
  // prod/preprod OVH
  if (host.endsWith('alchimiedujeu.fr')) return '/preprod/api';
  // fallback local (si tu relances un jour en local)
  return 'http://127.0.0.1:8080/api';
})();
