<?php
session_start();
include('connect.php');
$bdd=connectdb();
include('class/Questionnaire.php'); 

// si la variable de session pseudo n'existe pas on redirige vers la page index.php
if (!isset($_SESSION['pseudo'])) {
    header('Location: index.php');
}   


// si le bouton de qcm est cliqué on redirige vers la page qcm.php
if (isset($_POST['qcm'])) {
    // recuperer la valeur du bouton text caché
    $_SESSION['id'] = $_POST['id'];
    // redirection vers la page qcm.php
    header('Location: qcm.php');
}

if (isset($_POST['export'])) {
    // Récupérer l'id du questionnaire à exporter
    $id = $_POST['id'];
    
    // Créer un objet Questionnaire avec la connexion à la base de données
    $questionnaire = new Questionnaire($bdd);

    // Récupérer les données du questionnaire au format JSON
    $json = $questionnaire->exportJSON($id);

    // Envoyer les données JSON au navigateur
    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename="questionnaire_' . $id . '.json"');
    echo $json;
    exit;
}

if (isset($_POST['import'])) {
    // Récupérer le fichier JSON
    $file = file_get_contents($_FILES['file']['tmp_name']);

    // Créer un objet Questionnaire avec la connexion à la base de données
    $questionnaire = new Questionnaire($bdd);

    // print file en javascript
    //echo '<script>console.log(' . json_encode($file) . ')</script>';
    // Importer le questionnaire
    
    $questionnaire->importJSON($file);

    // Rediriger vers la page d'accueil
    //header('Location: home.php');
    exit;
}

if (isset($_POST['delete'])) {
    // Récupérer l'id du questionnaire à supprimer
    $id = $_POST['id'];

    // Créer un objet Questionnaire avec la connexion à la base de données
    $questionnaire = new Questionnaire($bdd);

    // on regarde si l'utilisateur à repondu à ce questionnaire
    $req = $bdd->query('SELECT * FROM NOTE_USER WHERE QUESTIONNAIRE_ID=' . $id . ' AND USER_ID=' . $_SESSION['user']);
    $result = $req->fetch();
    // si il a repondu on supprime sa note
    if ($result) {
        $bdd->query('DELETE FROM NOTE_USER WHERE QUESTIONNAIRE_ID=' . $id . ' AND USER_ID=' . $_SESSION['user']);
    }

    
    // Supprimer le questionnaire
    $questionnaire->delete($id);

    // renvoyer vers la page d'accueil sans formulaire en post
    header('Location: home.php');
    exit;
}

if (isset($_POST['recommencer'])){
    // Récupérer l'id du questionnaire à recommencer
    $id = $_POST['id'];

    // supprimer la note de l'utilisateur pour ce questionnaire
    $bdd->query('DELETE FROM NOTE_USER WHERE QUESTIONNAIRE_ID=' . $id . ' AND USER_ID=' . $_SESSION['user']);

    // redirection vers la page qcm.php
    header('Location:  qcm.php');

}

$Listquestionnaires = $bdd->query('SELECT * FROM QUESTIONNAIRE')->fetchAll(PDO::FETCH_ASSOC); // Recup les qestionnaires sous forme de dico (Array ( [0] => Array ( [ID] => 1 [NOM_QUESTIONAIRE] => Questionnaire 1 ) )
$QuestionnaireFait = $bdd->query('SELECT QUESTIONNAIRE_ID, NOTE FROM NOTE_USER WHERE USER_ID=' . $_SESSION['user'])->fetchAll(PDO::FETCH_ASSOC); // Recup les questionnaires fait par l'utilisateur sous forme de dico (Array ( [0] => Array ( [QUESTIONNAIRE_ID] => 1 [NOTE] => 0 ) ) )
?>

<!DOCTYPE html>
<html>

<head>
    <title>Liste des questionnaires</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body class="home">
    <?php include('menu.php'); ?>
    <div class="title"> 
        <h1>Liste des questionnaires</h1>
        <h3>Bonjour <span class="change_color"> <?php echo $_SESSION['pseudo']; ?> </span> choisissez un Questionnaire</h3>
    </div>
    <?php foreach ($Listquestionnaires as $questionnaire) { ?>
        <div>
            <section class="questionnaire">
                <form action="" method="POST">
                    <ul>
                        <p><?php echo $questionnaire['NOM_QUESTIONAIRE']; ?></p>
                        <input type="hidden" name="id" value="<?php echo $questionnaire['ID']; ?>">
                        <!-- si il n'a pas deja fait le questionnaire -->
                        <?php if (!in_array($questionnaire['ID'], array_column($QuestionnaireFait, 'QUESTIONNAIRE_ID'))) { ?>
                            <button type="submit" name="qcm" class="style_btn">Commencer</button>

                        <?php } else { ?>
                            <button type="submit" name="recommencer" class="style_btn">Recommencer</button>
                            <p> Note : <?php echo $QuestionnaireFait[array_search($questionnaire['ID'], array_column($QuestionnaireFait, 'QUESTIONNAIRE_ID'))]['NOTE']; ?> </p>
                        <?php } ?>
                    
                           

                        <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {?>
                            <button  type="submit"name="export" class="style_btn">Exporter en JSON</button>
                            <button type="submit" name="delete" class="style_btn_red">Supprimer</button>
                        <?php } ?>
                    </ul>
                </form>
            </section>
        </div>
    <?php } ?>
    <?php 
            // si l'utilisateur est un admin on affiche le bouton pour ajouter un questionnaire
            // on verifie si la variable de session admin existe et si elle est égale à 1
            if (isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {?>
                <section class="questionnaire">

                    <form method="post" enctype="multipart/form-data" action="home.php">
                        <label for="file">Importer un questionnaire</label>
                        <input type="file" name="file" class="">
                        <button type="submit" name="import" class="style_btn">Importer</button>
                    </form>
                </section>
            <?php }
        ?>
</body>

</html>