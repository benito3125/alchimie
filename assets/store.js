/* ===========================================================
   assets/store.js — Wrapper “stock” autour de DB
   =========================================================== */
(function () {
  function getAll() { return DB.listStock(); }

  function addItem({ id, label, category, space, quantity, unit = 'pcs', trackSizes, sizes }) {
    if (!id || !label) throw new Error('ID et libellé requis');
    const base = { id, label, space, category: category || '', unit };
    if (trackSizes) {
      DB.addStockItem({ ...base, trackSizes: true, sizes: sizes || {} });
    } else {
      DB.addStockItem({ ...base, trackSizes: false, quantity: Math.max(0, parseInt(quantity || 0, 10)) });
    }
  }

  function setQuantity({ id, value, size }) {
    if (!id) throw new Error('ID requis');
    if (size) DB.updateSizeQuantity(id, size, value);
    else DB.updateSimpleQuantity(id, value);
  }

  function moveItem(id, newSpace) { DB.moveStockItem(id, newSpace); }
  function removeItem(id) { DB.removeStockItem(id); }

  // Décrément, utile pour l’onboarding : 
  // ex. decrement({ id:'tshirt_bene_orange', size:'M', qty:1 }, { user:'admin', reason:'onboarding' })
  function decrement(payload, meta) { DB.decrementStock(payload, meta); }


/* ===========================================================
   assets/store.js — Gestion de stock (seed démo + localStorage)
   API utilisée par tes pages : getAll, addItem, setQuantity,
   moveItem, removeItem, decrement
   =========================================================== */
   function () {
  const LS_STOCK = 'aaj:stock:v1';

  // --- Seed de démonstration
  function seedIfNeeded() {
    if (localStorage.getItem(LS_STOCK)) return;
    const demo = [
      // T-shirts bénévoles (orange)
      { id: 'tshirt_bene_orange', label: 'T-shirt bénévole (orange)', space: 'accueil', category: 'textile', unit: 'pcs', trackSizes: true, sizes: { XS: 15, S: 60, M: 120, L: 100, XL: 45, XXL: 20 } },
      // T-shirts LSF (noir)
      { id: 'tshirt_lsf_noir', label: 'T-shirt LSF (noir)', space: 'accueil', category: 'textile', unit: 'pcs', trackSizes: true, sizes: { XS: 10, S: 30, M: 60, L: 50, XL: 25, XXL: 10 } },
      // T-shirts Interprètes (noir)
      { id: 'tshirt_interprete_noir', label: 'T-shirt Interprète (noir)', space: 'accueil', category: 'textile', unit: 'pcs', trackSizes: true, sizes: { XS: 6, S: 20, M: 40, L: 35, XL: 15, XXL: 8 } },
      // Bracelets bénévole
      { id: 'bracelet_bene', label: 'Bracelet bénévole', space: 'accueil', category: 'accessoires', unit: 'pcs', trackSizes: false, quantity: 500 },
      // Porte-badges
      { id: 'porte_badge', label: 'Porte-badge', space: 'accueil', category: 'accessoires', unit: 'pcs', trackSizes: false, quantity: 700 },
      // Serre-tête LSF
      { id: 'serre_tete_lsf_orange', label: 'Serre-tête LSF (orange)', space: 'accueil', category: 'lsf', unit: 'pcs', trackSizes: false, quantity: 40 },
      { id: 'serre_tete_lsf_bleu', label: 'Serre-tête LSF (bleu)', space: 'accueil', category: 'lsf', unit: 'pcs', trackSizes: false, quantity: 30 },
      { id: 'serre_tete_lsf_jaune', label: 'Serre-tête LSF (jaune)', space: 'accueil', category: 'lsf', unit: 'pcs', trackSizes: false, quantity: 20 },
      // Tour de cou comité
      { id: 'tour_de_cou_violet_comite', label: 'Tour de cou violet (comité)', space: 'accueil', category: 'accessoires', unit: 'pcs', trackSizes: false, quantity: 120 }
    ];
    localStorage.setItem(LS_STOCK, JSON.stringify(demo));
  }
  seedIfNeeded();

  function read() {
    try { return JSON.parse(localStorage.getItem(LS_STOCK) || '[]'); }
    catch { return []; }
  }
  function write(items) {
    localStorage.setItem(LS_STOCK, JSON.stringify(items));
    window.dispatchEvent(new CustomEvent('store:change'));
    return items;
  }

  // --- API
  function getAll() { return read().map(x => JSON.parse(JSON.stringify(x))); }

  function addItem({ id, label, category, space, quantity, unit = 'pcs', trackSizes, sizes }) {
    if (!id || !label) throw new Error('ID et libellé requis');
    const items = read();
    if (items.some(x => x.id === id)) throw new Error('ID déjà existant');
    const base = { id, label, space, category: category || '', unit };
    if (trackSizes) items.push({ ...base, trackSizes: true, sizes: sizes || {} });
    else items.push({ ...base, trackSizes: false, quantity: Math.max(0, parseInt(quantity || 0, 10)) });
    write(items);
  }

  function setQuantity({ id, value, size }) {
    const items = read();
    const it = items.find(x => x.id === id);
    if (!it) throw new Error('Article introuvable');
    if (size) {
      if (!it.trackSizes) throw new Error('Article non géré par tailles');
      if (!it.sizes[size]) it.sizes[size] = 0;
      it.sizes[size] = Math.max(0, parseInt(value, 10) || 0);
    } else {
      if (it.trackSizes) throw new Error('Article géré par tailles');
      it.quantity = Math.max(0, parseInt(value, 10) || 0);
    }
    write(items);
  }

  function moveItem(id, newSpace) {
    const items = read();
    const it = items.find(x => x.id === id);
    if (!it) throw new Error('Article introuvable');
    it.space = newSpace;
    write(items);
  }

  function removeItem(id) {
    const items = read();
    const next = items.filter(x => x.id !== id);
    if (next.length === items.length) throw new Error('Article introuvable');
    write(next);
  }

  // Décrément utilisé par l’onboarding
  // ex: decrement({ id:'tshirt_bene_orange', size:'M', qty:1 })
  //     decrement({ id:'bracelet_bene', qty:1 })
  function decrement(payload) {
    const items = read();
    const it = items.find(x => x.id === payload.id);
    if (!it) throw new Error('Article introuvable');
    const q = Math.max(0, parseInt(payload.qty, 10) || 0);
    if (it.trackSizes) {
      const size = String(payload.size || '').toUpperCase();
      if (!size) throw new Error('Taille requise');
      if (!it.sizes[size]) it.sizes[size] = 0;
      it.sizes[size] = Math.max(0, (it.sizes[size] || 0) - q);
    } else {
      it.quantity = Math.max(0, (it.quantity || 0) - q);
    }
    write(items);
  };





  window.store = { getAll, addItem, setQuantity, moveItem, removeItem, decrement };
})();
})
