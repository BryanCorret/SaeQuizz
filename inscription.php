<!DOCTYPE html>
    <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <link rel="stylesheet" href="./style.css">
            <title>Inscription</title>
        </head>
        <body>
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
                                <strong>Succès</strong> inscription réussie !
                            </div>
                            
                        <?php
                        header('Location:index.php');
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
                        case 'already':
                        ?>
                            <div class=" error">
                                <strong>Erreur</strong> compte deja existant
                            </div>
                        <?php 

                    }
                }
                ?>
            
            <form action="inscription_traitement.php" method="post">
                <h2 class="pseudo">Inscription</h2>       
                <input type="text" name="pseudo" placeholder="Pseudo" required="required">
                <input type="email" name="email" placeholder="Email" required="required">
                <input type="password" name="password" placeholder="Mot de passe" required="required">
                <input type="password" name="password_retype" placeholder="Re-tapez le mot de passe" required="required">
                <button type="submit" class="style_btn">Inscription</button>
            </form>
        </section>
   
        </body>
</html>