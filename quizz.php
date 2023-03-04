<!DOCTYPE html>
<?php
include("connect.php");
// recuperer le post
$idQuestionnaire = $_GET['id'];

// verifier si le questionnaire existe
$questionnaires = $bdd->query('SELECT ID FROM questionnaire')->fetchAll(PDO::FETCH_ASSOC);
$questionnaireExiste = false;

foreach ($questionnaires as $questionnaire) {
    if ($questionnaire['ID'] == $idQuestionnaire) { // si le questionnaire existe
        $questionnaireExiste = true;
    }
}



?>
<html lang="en">
<head>
    <title>Document</title>
</head>
<body>
    <!-- si le questionnaire existe -->
    <?php if ($questionnaireExiste) { ?>
        <h1>Questionnaire <?php echo $idQuestionnaire; ?></h1>
        <form action="quizz.php" method="post">
            <input type="hidden" name="questionnaire" value="<?php echo $idQuestionnaire; ?>">
            <input type="submit" value="Valider">
        </form>
    <?php } 
    
    else { ?>
        <h1>Questionnaire inconnu</h1>
        <p>Le questionnaire demandé n'existe pas merci de retourner à l'acceuil</p>
        <a href="home.php">Retour à l'acceuil</a>

        <?php } ?>

</body>
</html>