<!-- Bouton "Saisir" -->
<button id="saisirButton">Saisir</button>

<!-- Formulaire de saisie (initiallement caché) -->
<form id="saisirForm" class="hidden" action="" method="post">
    <label for="nombre_handi">Nombre de visiteurs Handi :</label>
    <input type="text" name="nombre_handi" id="nombre_handi"><br>
    <label for="nombre_lsf">Nombre de visiteurs LSF :</label>
    <input type="text" name="nombre_lsf" id="nombre_lsf"><br>
    <label for="nombre_autres">Nombre de visiteurs Autres :</label>
    <input type="text" name="nombre_autres" id="nombre_autres"><br>
    <label for="nombre_total">Nombre de visiteurs Total :</label>
    <input type="text" name="nombre_total" id="nombre_total"><br>
    <label for="heure">Heure :</label>
    <input type="text" name="heure" id="heure"><br>
    <label for="jour">Jour :</label>
    <select name="jour" id="jour">
        <option value="Vendredi">Vendredi</option>
        <option value="Samedi">Samedi</option>
        <option value="Dimanche">Dimanche</option>
    </select><br>
    <input type="submit" value="Valider">
</form>
