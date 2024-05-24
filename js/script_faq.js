// JavaScript pour gérer l'affichage/masquage des réponses
var questions = document.querySelectorAll('.question');
questions.forEach(function(question) {
    question.addEventListener('click', function() {
        var answer = this.nextElementSibling;
        var allAnswers = document.querySelectorAll('.answer');
        allAnswers.forEach(function(ans) {
            ans.style.display = 'none';
        });
        if (answer.style.display === 'none') {
            answer.style.display = 'block';
        } else {
            answer.style.display = 'none';
        }
    });
});

// JavaScript pour gérer l'affichage de la case "Ajouter"
var ajouterBtn = document.getElementById('ajouterBtn');
var ajouterCase = document.getElementById('ajouterCase');

ajouterBtn.addEventListener('click', function() {
    // Afficher ou masquer la case "Ajouter" en fonction de son état actuel
    ajouterCase.style.display = ajouterCase.style.display === 'none' ? 'block' : 'none';
});

// Fonction pour ajouter une question (à personnaliser selon vos besoins)
function ajouterQuestion() {
    // Récupérer les valeurs des champs
    var categorie = document.getElementById('categorie').value;
    var question = document.getElementById('question').value;
    var reponse = document.getElementById('reponse').value;

    // Envoyer les valeurs à votre backend
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "ajouter_question.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Réponse reçue du serveur, vous pouvez faire quelque chose avec si nécessaire
            console.log(xhr.responseText);
            // Rafraîchir la page
            window.location.reload();
        }
    };
    xhr.send("categorie=" + categorie + "&question=" + question + "&reponse=" + reponse);
}