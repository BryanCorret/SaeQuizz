<?php
session_start();

if (isset($_GET['login_err'])) {
    $error = "Erreur email ou mot de passe incorrect";
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Quizz</title>
</head>

<body>
    <?php include('menu.php'); ?>
    <section class="pseudo">

        <form action="connexion.php" method="post">
            <h2>Connexion</h2>
            <p class="error">
                <?php if (isset($error)) {
                    echo $error;
                }
                ?> </p>
            <p> Entrez votre mail :</p>
            <input type="email" name="mail" placeholder="Email" required="required">
            <p> Entrez votre mot de passe :</p>

            <input type="password" name="mdp" placeholder="Mot de passe" required="required">
            <button type="submit" class="style_btn">Connexion</button>
        </form>
        <p class="lien"> <a href="inscription.php" class="lien">Inscription</a></p>
        </form>
    </section>

</body>

</html>