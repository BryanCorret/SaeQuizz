
<?php
    session_start();
    // regex sur le pseudo pour bloquer les caractere '-"/\[]{}()_'
    $regex = '/[-"\/\[\]{}\(\)_]/';

    if(isset($_POST['button'])){
       // verifier que le champ n'est pas vide et que le pseudo ne contient pas de caractere speciaux du regex
         if(!empty($_POST['pseudo']) && !(preg_match($regex, $_POST['pseudo']))){
            // on stock le pseudo dans une variable de session
            $_SESSION['pseudo'] = $_POST['pseudo'];
            // redirection vers la page quizz.php
            header('Location: home.php');         
    }
    else{
        // afficher un message d'erreur
        $error = "Veuillez entrez un pseudo correct !";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Quizz</title>
</head>
<body>
    <?php include('menu.php');?>
    
    
    
    <section class="pseudo">
    <form action="./index.php" method="POST">

        <p class="error">
            <?php
                if(isset($error)){
                    echo $error;
                }
            ?>
        <p> Entrez votre pseudo :</p>
        <input type="text" name="pseudo" placeholder="Votre pseudo">
        <button type="submit" name="button" class="style_btn">Valider</button>
    </form>
</section>
    
</body>
</html>