<!DOCTYPE html>
<html>
    <!-- Inclusion des scripts et liens -->
    <?php include "link.php"?>
    <!-- Navigation -->

<head>
    <title>Statistiques des t-shirts</title>
    <link rel="stylesheet" href="../css/style_table.css" />
    <link rel="stylesheet" href="../css/style.css" />
    <style>
        .accordion {
            background-color: #eee;
            color: #444;
            cursor: pointer;
            padding: 18px;
            width: 100%;
            border: none;
            text-align: left;
            outline: none;
            font-size: 15px;
            transition: 0.4s;
        }

        .accordion.active, .accordion:hover {
            background-color: #ccc;
        }

        .panel {
            padding: 0 18px;
            background-color: white;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.2s ease-out;
        }
    </style>
</head>
<body>

<?php include_once "header.php" ?>

<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <h2>Statistiques des fournitures</h2>

            <!-- Ajout d'une entrée pour les t-shirts -->
            <button class="accordion">T-shirts</button>
            <div class="panel">
                <?php include "tableau_tshirt.php" ?>
            </div>            

            <!-- Ajout d'une entrée pour les serre-têtes -->
            <button class="accordion">Serre-têtes</button>
            <div class="panel">
                <?php include "serre_tete.php" ?>
            </div>
            
            <!-- Ajout d'une entrée pour les tour de cou -->
            <button class="accordion">Tour de cou</button>
            <div class="panel">
                <?php include "neck_warme.php" ?>
            </div> 
        </div>
    </div>
</div>

<script>
    var acc = document.getElementsByClassName("accordion");
    var i;

    for (i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var panel = this.nextElementSibling;
            if (panel.style.maxHeight) {
                panel.style.maxHeight = null;
            } else {
                panel.style.maxHeight = panel.scrollHeight + "px";
            }
        });
    }
</script>

<?php include "footer.php" ?>

</body>
</html>
