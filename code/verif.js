fetch('saisir.php?id=' + id_scanne + '&jour=' + jour_choisi)
  .then(response => response.text())
  .then(result => {
    if (result === 'repas valide') {
      // Code à exécuter si le repas est valide
    } else if (result === 'pas de repas prevu') {
      // Code à exécuter si le repas n'est pas prévu
    }
  });
