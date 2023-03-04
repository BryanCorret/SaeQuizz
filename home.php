<?php
session_start();
// si la variable de session pseudo n'existe pas on redirige vers la page index.php
if(!isset($_SESSION['pseudo'])){
    header('Location: index.php');
}
// si le bouton de deconnexion est cliqué on detruit la session et on redirige vers la page index.php
if(isset($_POST['deconnexion'])){
    session_destroy();
    header('Location: index.php');
}

// si le bouton de qcm est cliqué on redirige vers la page qcm.php
if(isset($_POST['qcm'])){
    // recuperer la valeur du bouton text caché
    $_SESSION['id'] = $_POST['id'];
    // redirection vers la page qcm.php
    header('Location: qcm.php');

}

include('connect.php');

$Listquestionnaires = $bdd->query('SELECT * FROM questionnaire')->fetchAll(PDO::FETCH_ASSOC); // Recup les qestionnaires sous forme de dico (Array ( [0] => Array ( [ID] => 1 [NOM_QUESTIONAIRE] => Questionnaire 1 ) )

?>



<!DOCTYPE html>
<html>

<head>
    <title>Liste des questionnaires</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <h1>Liste des questionnaires</h1>
    <h3>Bonjour <span class="change_color"> <?php echo $_SESSION['pseudo']; ?> </span> choissiser un Questionnaire</h3>
        <?php foreach ($Listquestionnaires as $questionnaire) { ?>
            <section class="questionnaire">
            <form action="" method="POST">
                <ul>
                    <p><?php echo $questionnaire['NOM_QUESTIONAIRE']; ?></p>
                    <input type="hidden" name="id" value="<?php echo $questionnaire['ID']; ?>">
                    <button type="submit" name="qcm" class="style_btn" >Jouer</button>
                </ul>
            </form>
        </section>  
    
    <?php } ?>

</body>

</html>
