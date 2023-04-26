// Chargez la bibliothèque QuaggaJS
Quagga.init({
    inputStream : {
      name : "Live",
      type : "LiveStream",
      target: document.querySelector('#preview')    // Utilisez la balise vidéo pour afficher la caméra
    },
    decoder : {
      readers : ["qrcode_reader"]                    // Utilisez le lecteur QR code
    }
  }, function(err) {
    if (err) {
      console.log(err);
      return
    }
    console.log("Scanner ready");
  
    // Démarrer la lecture de la caméra
    Quagga.start();
  });
  
  // Ajoutez un écouteur d'événements pour détecter les résultats de la lecture du QR code
  Quagga.onDetected(function(data) {
    console.log(data);
    document.querySelector('#result').innerHTML = data.codeResult.code;
  });
  