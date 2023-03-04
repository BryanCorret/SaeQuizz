<?php
session_start();
if(!isset($_SESSION['pseudo'])){
    header('Location: index.php');
}

// Connexion à la base de données
include('connect.php');
include('menu.php');


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
        <h1> Résultat du QCM de <span class = "blue"><?php echo $_SESSION['pseudo']  ?></span></h1>
        <?php 
            foreach($_POST as $idQuestion =>$idReponse){

                $req = $bdd->prepare('SELECT * FROM QUESTION WHERE id = ?');
                $req->execute(array($idQuestion));
                $question = $req->fetch();
            
                $req = $bdd->prepare('SELECT * FROM REPONSE WHERE id = ?');
                $req->execute(array($idReponse));
                $reponse = $req->fetch();

                // on verifie si la reponse est correcte


                if(isset($reponse['CORRECT']) && $reponse['CORRECT']){
                    echo '<div class="correction">';
                        echo '<p class="question_vrai">'.$question['QUESTION'].'</p>';
                        echo '<p class="color"> La bonne reponse etait </p>';
                        echo '<p class="reponse_vrai">'.$reponse['REP'].'</p>';    
                    echo '</div>';                   

                }else{
                    if(isset($question['QUESTION'])) {
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