<?php
    session_start();
    if(!isset($_SESSION['pseudo']))
    {
        header('Location: index.php');
    }?>
<!DOCTYPE html>
    <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <link rel="stylesheet" href="./style.css">
            <title>Info du compte</title>
        </head>
        <body>
            <?php include('menu.php'); ?>
            <section class="inscritpion">
            <?php 
                if(isset($_GET['reg_err']))
                {
                    $err = ($_GET['reg_err']);

                    switch($err)
                    {
                        case 'success':
                        ?>
                            <div class="sucess">
                                <strong>Succès</strong> Changement effectué !
                            </div>
                            
                        <?php
                        header('Location:infoCompte.php');
                        break;

                        case 'password':
                        ?>
                            <div class=" error">
                                <strong>Erreur</strong> mot de passe différent
                            </div>
                        <?php
                        break;

                        case 'email':
                        ?>
                            <div class=" error">
                                <strong>Erreur</strong> email non valide
                            </div>
                        <?php
                        break;

                        case 'password_correct':
                        ?>
                            <div class=" error">
                                <strong>Erreur</strong> le mot de passe ne contient pas de majuscule, minuscule, chiffre et caractère spécial
                            </div>
                        <?php 
                        break;

                        case 'pseudo_length':
                        ?>
                            <div class=" error">
                                <strong>Erreur</strong> pseudo trop long
                            </div>
                        <?php 
                       

                    }
                }
                ?>
            
            <form action="modification_compte.php" method="post">
            <h3>Bonjour <span class="change_color"> <?php echo $_SESSION['pseudo']; ?> </span> bienvenue dans votre espace privée</h3>
                 <!--afficher dans la console en php le $_SESSION  -->
                <?php print_r($_SESSION); ?>
                <input type="text" name="pseudo" placeholder="Pseudo" required="required" value="<?php echo $_SESSION['pseudo']; ?>">
                <input type="email" name="email" placeholder="Email" required="required" value="<?php echo $_SESSION['mail']; ?>">
                <input type="password" name="password" placeholder="Mot de passe" required="required" value ="<?php echo $_SESSION['MDP']; ?>">
                <input type="password" name="password_retype" placeholder="Re-tapez le mot de passe" required="required" value= "<?php  echo $_SESSION['MDP']; ?>">
                <button type="submit" class="style_btn">Enregistrer mes nouvelles valeurs</button>
            </form>
        </section>