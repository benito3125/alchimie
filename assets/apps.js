// assets/apps.js
export const APPS = [
  {
    id:'benevoles', name:'Gestion bénévoles',
    desc:'Planning, disponibilités et affectations.',
    icon:'B', ic:'green', href:'/benevoles/',
    requiredRoles:['staff','admin']
  },
  {
    id:'stocks', name:'Gestion des stocks',
    desc:'Inventaire, entrées/sorties et alertes.',
    icon:'S', ic:'blue', href:'/stocks/',
    requiredRoles:['stock','admin']
  },
  {
    id:'caisses', name:'Caisses',
    desc:'Points de vente, encaissements et clôtures.',
    icon:'€', ic:'orange', href:'/caisses/',
    requiredRoles:['cashier','admin']
  },
  {
    id:'scan', name:'Scan',
    desc:'Contrôle billets/badges au fil de l’eau.',
    icon:'Q', ic:'purple', href:'/scan/',
    requiredRoles:['scanner','admin']
  },
  {
    id:'stats', name:'Statistiques',
    desc:'Tableaux de bord et exports.',
    icon:'Σ', ic:'pink', href:'/stats/',
    requiredRoles:['staff','admin']
  },
  {
    id:'faq', name:'FAQ',
    desc:'Procédures et réponses rapides.',
    icon:'ℹ', ic:'green', href:'/faq/',
    requiredRoles:['*'] // visible pour tous
  },
  {
    id:'admin', name:'Administration',
    desc:'Utilisateurs, rôles et paramètres globaux.',
    icon:'A', ic:'orange', href:'/admin/',
    requiredRoles:['admin']
  },
  {
    id:'repas', name:'Repas',
    desc:'Gestion des repas',
    icon:'A', ic:'orange', href:'/scan/',
    requiredRoles:['buvette','admin']
  }
];
