// Catalogue — tuiles (démo)
window.TOOLS = [
  {
    id:'accueil', name:'Accueil bénévoles', desc:"Gestion et outils de l'accueil.",
    icon:'🤝', badge:'bg-accueil', space:'accueil', minRole:'benevole',
    url:'#accueil'
  },
  {
    id:'logistique', name:'Logistique', desc:"Outils logistiques (stocks, planning, etc.).",
    icon:'📦', badge:'bg-log', space:'logistique', minRole:'benevole',
    url:'#logistique'
  },
  {
    id:'admin', name:'Administration', desc:"Comptes, festival et stocks.",
    icon:'🛠', badge:'bg-admin', role:'admin',
    open:'admin-hub'
  },
  {
    id:'faq', name:'FAQ', desc:'Questions fréquentes (public).',
    icon:'❓', badge:'bg-faq', public:true,
    url:'#faq'
  },
  {
    id:'stats', name:'Statistiques', desc:'Tableaux publics.',
    icon:'📊', badge:'bg-stats', public:true,
    url:'#stats'
  },
  {
    id:'repas', name:'Repas', desc:'Gestion des repas sur le festival.',
    icon:'📊', badge:'bg-scan', public:true,
    url:'#repas'
  }
];
