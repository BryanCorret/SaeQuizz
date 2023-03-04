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

// connexion à la base de données
include('connect.php');

// si le formulaire est envoyé
if(isset($_POST['submit'])){
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">

    <title>QCM</title>
</head>
<body>
    <section class="qcm">
    <h1>QCM</h1>
    <h2> </h2>
    <form action="reponse.php" method="POST">

        <?php
        $req = $bdd->prepare('SELECT * FROM QUESTION WHERE QUESTIONNAIRE_ID = ?');
        $req->execute(array($idquestionnaire));
        $questions = $req->fetchAll();
        $req->closeCursor();
        ?>
        

        <ul>
            <?php foreach($questions as $question): ?>
            <h3 class="question"><li><?= $question['QUESTION'] ?></li></h3>
            <?php
            $req = $bdd->prepare('SELECT * FROM REPONSE WHERE QUESTION_ID = ?');
            $req->execute(array($question['ID']));
            $reponses = $req->fetchAll();
            $req->closeCursor();
            ?>
            <ul class="choix">
                <?php foreach($reponses as $reponse): ?>
                <li><input type="radio" name="<?= $question['ID']?>" value="<?= $reponse['ID'] ?>"><?= $reponse['REP'] ?></li>
                <?php endforeach; ?>
            </ul>
            <?php endforeach; ?>
        </ul>
        <button type="submit" name="submit" class="style_btn">Prochaine question</button>

    </form>
    </section>
</body>
</html>