<?php 
session_start();

if(!isset($_SESSION['pseudo'])){
    header('Location: index.php');
}

// si l'id du questionnaire n'existe pas on redirige vers la page home.php
if(!isset($_SESSION['id'])){
    header('Location: home.php');
}
$idquestionnaire = $_SESSION['id'];



include('class/Reponse.php');
include('class/Questions.php');
include('connect.php');
$bdd = connectdb();
// une requete pour récupérer les questions et les réponses

// on instancie les objets
$question = new Question($bdd);
$reponse = new Reponse($bdd);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">

    <title>QCM</title>
</head>
<body>
    <section class="qcm">
    <h1>QCM</h1>
    <h2> </h2>
    <form action="reponse.php" method="POST">

    <?php
    // Récupération des questions du questionnaire
    $questions = $question->getByQuestionnaireId($idquestionnaire);
?>

<ul>
    <?php foreach($questions as $quest): ?>
        <!-- print dans la console $quest-->
        
        <h3 class="question"><li><?= $quest["QUESTION"] ?></li></h3>
        <?php
            // Récupération des réponses pour la question courante
            $reponses = $reponse->getByQuestionId($quest["ID"]);
        ?>
        <script>console.log(<?= json_encode($reponse) ?>)</script>

        <ul class="choix">
            <?php foreach($reponses as $reponse): ?>
                <li><input type="radio" name="<?= $quest["ID"] ?>" value="<?= $reponse["ID"] ?>"><?= $reponse["REP"] ?></li>
            <?php endforeach; ?>
        </ul>

    <?php
    $reponse = new Reponse($bdd); // sinon crash
 endforeach; ?>
</ul>

        <button type="submit" name="submit" class="style_btn">Prochaine question</button>

    </form>
    </section>
</body>
</html>
