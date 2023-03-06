<?php
session_start();
if(!isset($_SESSION['pseudo'])){
    header('Location: index.php');
}

// Connexion à la base de données
include('connect.php');
include('menu.php');
$bdd=connectdb();

$idquestionnaire = $_SESSION['id'];

$nbReponseVrai = 0;
$nbQuestion = $bdd->prepare('SELECT COUNT(*) FROM QUESTION WHERE QUESTIONNAIRE_ID = ?');
$nbQuestion->execute(array($idquestionnaire));
$nbQuestion = $nbQuestion->fetch();
$nbQuestion = $nbQuestion[0];

$reqQuestion = $bdd->prepare('SELECT * FROM QUESTION WHERE QUESTIONNAIRE_ID = ?');
$reqQuestion->execute(array($idquestionnaire));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    
    <title>Resultat</title>
</head>
<body>
    <section class="resultat">
        <h1> Résultat du QCM de <span class = "change_color"><?php echo $_SESSION['pseudo']  ?></span></h1>
        <p class="color">Voici la correction :</p>
        
        <?php 
            foreach($_POST as $idQuestion =>$idReponse){

                $question = $reqQuestion->fetch();
              
                $reqReponse = $bdd->prepare('SELECT * FROM REPONSE WHERE id = ?');
                $reqReponse->execute(array($idReponse));
                $reponse = $reqReponse->fetch();

                // on verifie si la reponse est correcte
                


                if(isset($reponse['CORRECT']) && isset($question['QUESTION']) && $reponse['CORRECT']){
                    echo '<div class="correction">';
                        echo '<p class="question_vrai">'.$question['QUESTION'].'</p>';
                        echo '<p class="color"> Vous avez répondu correctement </p>';
                        echo '<p class="reponse_vrai">'.$reponse['REP'].'</p>';    
                    echo '</div>';                   
                    $nbReponseVrai++;

                }else{
                    if(isset($question['QUESTION']) && isset($reponse['CORRECT'])) {
                        echo '<div class="correction">';
                            echo '<p class="question_error">'.$question['QUESTION'].'</p>';
                            echo '<p class="color"> Vous aviez répondu </p>';
                            echo '<p class="reponse_faux">'.$reponse['REP'].'</p>';
                            echo '<p class="color"> Alors que la bonne réponse est </p>';
                            $req = $bdd->prepare('SELECT * FROM REPONSE WHERE QUESTION_ID = ? AND CORRECT = 1');
                            $req->execute(array($idQuestion));
                            $reponse = $req->fetch();
                            echo '<p class="reponse_vrai">'.$reponse['REP'].'</p>';
                        echo '</div>';
                    }
                }

            }
            echo '<p class="note">Votre note est de : '.$nbReponseVrai.'/'.$nbQuestion.'</p>';
            // on enregistre la note dans la base de données
            $req = $bdd->prepare('INSERT INTO NOTE_USER (NOTE, QUESTIONNAIRE_ID, USER_ID) VALUES (?, ?, ?)');
            $req->execute(array($nbReponseVrai, $idquestionnaire, $_SESSION['user']));
            

        ?>
    </section>

    <!-- <section class="resultat">
        <h1> Résultat du QCM de <span class = "blue"</span></h1>
        <p class="color">Voici la correction :</p>
        <p class="question_error">La question</p>
        <p class="color"> La bonne reponse etait </p>
        <p class="reponse_vrai">la reponse</p>

        <p class="note">Votre note est de : 14/20</p>
    </section> -->
</body>
</html>