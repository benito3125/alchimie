<!DOCTYPE html>
<html>
<head>
    <!-- Inclusion des scripts et liens -->
    <?php include "link.php"?>
    <title>Page Bénévole</title>
    <style>

        .affiche_bene {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            margin-bottom: 50px;
        }

        button {
            background-color: #F18932;
            color: #861773;
            padding: 15px 30px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px;
        }
    </style>
</head>

<body>
    <?php include "header.php"?>
    
    <div class="affiche_bene">
        <button onclick="window.location.href='find_users.php'">Rechercher</button>
        <button onclick="window.location.href='onboarding.php'">Onboarding</button>
        <button onclick="window.location.href='outboarding.php'">Outboarding</button>
    </div>

    <!-- Footer -->
    <footer>
        <?php include "footer.php" ?>
    </footer>
</body>
</html>
