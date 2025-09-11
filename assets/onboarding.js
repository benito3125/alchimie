/* =========================================================================
   ONBOARDING — débite le stock lors de la validation
   Dépend de window.store (assets/store.js) et lit le formulaire courant.
   ========================================================================= */

// === 1) Map des IDs utilisés par le stock ================================
// ⚠️ Remplace les valeurs par tes IDs réels (ceux visibles dans la liste
//     des stocks). Les T-shirts doivent être des articles à tailles.
const STOCK_MAP = {
  portebadge:      "porte-badge",          // quantité simple
  bracelet:        "bracelet-benevole",    // quantité simple
  lanyard:         { violet: "lanyard-violet" }, // comité uniquement
  headband: {                                // serre-tête LSF
    benevole:    "headband-orange",
    responsable: "headband-blue",
    interprete:  "headband-yellow",
  },
  tshirtBenevole:   "tshirt-benevole",      // à tailles (trackSizes: true)
  tshirtInterprete: "tshirt-interprete",    // à tailles (trackSizes: true)
};

// === 2) Lecture du formulaire d'onboarding ===============================
// Cette fonction suppose des éléments avec les IDs suivants :
// - #roleSpace (select 'benevole' | 'responsable')
// - #isLSF (checkbox)
// - #isInterprete (checkbox)
// - #isComite (checkbox) → membre du comité
// - #size (select, ex: XS,S,M,L,XL…)
// - #wantVolunteerShirt (checkbox) → 2e t-shirt optionnel pour interprète
function readOnboardingForm() {
  const g = (sel) => document.querySelector(sel);

  return {
    roleSpace:
      (g("#roleSpace") && g("#roleSpace").value) || "benevole",
    isLSF: !!(g("#isLSF") && g("#isLSF").checked),
    isInterprete: !!(g("#isInterprete") && g("#isInterprete").checked),
    isComite: !!(g("#isComite") && g("#isComite").checked),
    size: (g("#size") && g("#size").value) || "", // vide si inconnue
    wantVolunteerShirt:
      !!(g("#wantVolunteerShirt") && g("#wantVolunteerShirt").checked),
  };
}

// === 3) Construction du "panier" de distribution ========================
function buildDistribution(payload) {
  const L = [];

  // Toujours compris
  L.push({ id: STOCK_MAP.portebadge, qty: 1 });
  L.push({ id: STOCK_MAP.bracelet, qty: 1 });

  // Tour de cou violet pour le comité
  if (payload.isComite && STOCK_MAP.lanyard?.violet) {
    L.push({ id: STOCK_MAP.lanyard.violet, qty: 1 });
  }

  // Serre-tête LSF selon rôle
  if (payload.isLSF) {
    const key = payload.isInterprete
      ? "interprete"
      : payload.roleSpace === "responsable"
      ? "responsable"
      : "benevole";
    const id = STOCK_MAP.headband?.[key];
    if (id) L.push({ id, qty: 1 });
  }

  // T-shirts
  if (payload.isInterprete) {
    // t-shirt interprète noir
    if (payload.size && STOCK_MAP.tshirtInterprete) {
      L.push({ id: STOCK_MAP.tshirtInterprete, size: payload.size, qty: 1 });
    }
    // t-shirt bénévole orange optionnel
    if (payload.wantVolunteerShirt && payload.size && STOCK_MAP.tshirtBenevole) {
      L.push({ id: STOCK_MAP.tshirtBenevole, size: payload.size, qty: 1 });
    }
  } else {
    // bénévole / responsable → t-shirt bénévole (si taille connue)
    if (payload.size && STOCK_MAP.tshirtBenevole) {
      L.push({ id: STOCK_MAP.tshirtBenevole, size: payload.size, qty: 1 });
    }
  }

  return L;
}

// === 4) Validation (à appeler au clic sur "Valider l’onboarding") =======
function validateOnboarding() {
  const data = readOnboardingForm();
  const lines = buildDistribution(data);
  const res = window.store.consume(lines);

  if (!res.ok) {
    const msg = res.errors
      .map((e) => {
        if (e.type === "out" && e.size)
          return `Rupture: ${e.id} (taille ${e.size}) — dispo ${e.have}, demandé ${e.need}`;
        if (e.type === "out")
          return `Rupture: ${e.id} — dispo ${e.have}, demandé ${e.need}`;
        if (e.type === "size-required") return `Taille manquante pour ${e.id}`;
        if (e.type === "not-found") return `Article introuvable: ${e.id}`;
        return JSON.stringify(e);
      })
      .join("\n");
    alert("[Stock] Impossible de débiter tout le kit:\n" + msg);
    return false;
  }

  alert("Onboarding validé — stocks mis à jour !");
  return true;
}

// Expose quelques helpers au besoin
window.onboarding = {
  readOnboardingForm,
  buildDistribution,
  validateOnboarding,
};
