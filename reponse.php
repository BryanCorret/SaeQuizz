<?php
session_start();
if (!isset($_SESSION['pseudo'])) {
    header('Location: index.php');
}

// Connexion à la base de données
include('connect.php');
include('menu.php');
include("class/Questions.php");
$bdd = connectdb();

$idquestionnaire = $_SESSION['id'];

$nbReponseVrai = 0;
$question = new Question($bdd);
$nbQuestion = $question->getNbQuestion($idquestionnaire);

$questions = $question->getByQuestionnaireId($idquestionnaire);

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
        <h1> Résultat du QCM de <span class="change_color">
                <?php echo $_SESSION['pseudo'] ?>
            </span></h1>
        <p class="color">Voici la correction :</p>

        <?php
        // on parcourt le tableau des questions
        
        foreach ($questions as $question) {
            // on récupère l'id de la question
            $idQuestion = $question['ID'];
            // on récupère l'id de la réponse
            $idReponse = $_POST[$idQuestion];
            // on vérifie le type de question
            if ($question['TYPE_QUESTION'] != 'text' && $question['TYPE_QUESTION'] != 'number' && $question['TYPE_QUESTION'] != 'textarea') {
                if ($question['TYPE_QUESTION'] == 'select') {
                    $req = $bdd->prepare('SELECT * FROM REPONSE WHERE QUESTION_ID = ? AND CORRECT = 1');
                    $req->execute(array($idQuestion));
                    $reponseInfo = $req->fetchAll();
                    $reponseCorrect = $req->fetch();

                    // on construit une variable avec l'id de la reponse envoyée par l'utilisateur
                    $reponseDonne = $idReponse;
                    // on construit une variable avec l'id de la reponse correcte
                    $reponseCorrect = $reponseInfo[0]['ID'];
                    if ($reponseCorrect == $reponseDonne) {
                        echo '<div class="correction">';
                        echo '<p class="question_vrai">' . $question['QUESTION'] . '</p>';
                        echo '<p class="color"> Vous avez répondu correctement </p>';
                        echo '<p class="reponse_vrai">' . $reponseInfo[0]['REP'] . '</p>';
                        echo '</div>';

                        $nbReponseVrai++;
                    } else {
                        echo '<div class="correction">';
                        echo '<p class="question_error">' . $question['QUESTION'] . '</p>';
                        echo '<p class="color"> Vous aviez répondu </p>';
                        echo '<p class="reponse_faux">' . $reponseInfo[0]['REP'] . '</p>';

                        echo '<p class="color"> Alors que la bonne réponse est </p>';
                        echo '<p class="reponse_vrai">' . ($reponseInfo[0]['REP']) . '</p>';
                        echo '</div>';
                    }
                } elseif ($question['TYPE_QUESTION'] == 'checkbox') {
                    $req = $bdd->prepare('SELECT * FROM REPONSE WHERE QUESTION_ID = ? AND CORRECT = 1');
                    $req->execute(array($idQuestion));
                    $reponseInfo = $req->fetchAll();
                    $reponseCorrect = $req->fetch();

                    // on contruit un tableau avec les id des reponses correctes
                    $reponseCorrect = array();
                    foreach ($reponseInfo as $reponse) {
                        $reponseCorrect[] = $reponse['ID'];
                    }
                    // on contruit un tableau avec les id des reponses données par l'utilisateur
                    $reponseDonne = array();
                    foreach ($idReponse as $reponse) {
                        $reponseDonne[] = $reponse;
                    }
                    // on compare les deux tableaux
                    foreach ($reponseDonne as $reponse) {
                        if (!in_array($reponse, $reponseCorrect)) {
                            $reponseCorrect = false;
                            break;
                        }
                    }
                    if ($reponseCorrect) {
                        echo '<div class="correction">';
                        echo '<p class="question_vrai">' . $question['QUESTION'] . '</p>';
                        echo '<p class="color"> Vous avez répondu correctement </p>';
                        foreach ($reponseInfo as $reponse) {
                            echo '<p class="reponse_vrai">' . $reponse['REP'] . '</p>';
                        }
                        echo '</div>';

                        $nbReponseVrai++;
                    } else {
                        echo '<div class="correction">';
                        echo '<p class="question_error">' . $question['QUESTION'] . '</p>';
                        echo '<p class="color"> Vous aviez répondu </p>';
                        foreach ($reponseInfo as $reponse) {
                            echo '<p class="reponse_faux">' . $reponse['REP'] . '</p>';
                        }

                        echo '<p class="color"> Alors que la bonne réponse est </p>';
                        foreach ($reponseInfo as $reponse) {
                            echo '<p class="reponse_vrai">' . $reponse['REP'] . '</p>';
                        }
                        echo '</div>';
                    }
                } else {
                    $reqReponse = $bdd->prepare('SELECT * FROM REPONSE WHERE id = ?');
                    $reqReponse->execute(array($idReponse));
                    $reponse = $reqReponse->fetch();
                    if (isset($reponse['CORRECT']) && isset($question['QUESTION']) && $reponse['CORRECT']) {
                        echo '<div class="correction">';
                        echo '<p class="question_vrai">' . $question['QUESTION'] . '</p>';
                        echo '<p class="color"> Vous avez répondu correctement </p>';
                        echo '<p class="reponse_vrai">' . $reponse['REP'] . '</p>';
                        echo '</div>';
                        $nbReponseVrai++;

                    } else {
                        if (isset($question['QUESTION']) && isset($reponse['CORRECT'])) {
                            echo '<div class="correction">';
                            echo '<p class="question_error">' . $question['QUESTION'] . '</p>';
                            echo '<p class="color"> Vous aviez répondu </p>';
                            echo '<p class="reponse_faux">' . $reponse['REP'] . '</p>';
                            echo '<p class="color"> Alors que la bonne réponse est </p>';
                            $req = $bdd->prepare('SELECT * FROM REPONSE WHERE QUESTION_ID = ? AND CORRECT = 1');
                            $req->execute(array($idQuestion));
                            $reponse = $req->fetch();
                            echo '<p class="reponse_vrai">' . $reponse['REP'] . '</p>';
                            echo '</div>';
                        }
                    }

                }

            }
        }


        echo '<p class="note">Votre note est de : ' . $nbReponseVrai . '/' . $nbQuestion . '</p>';
        // on enregistre la note dans la base de données
        $req = $bdd->prepare('INSERT INTO NOTE_USER (NOTE, QUESTIONNAIRE_ID, USER_ID) VALUES (?, ?, ?)');
        $req->execute(array($nbReponseVrai, $idquestionnaire, $_SESSION['user']));



        ?>

    </section>
</body>

</html>