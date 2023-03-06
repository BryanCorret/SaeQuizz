<?php 
    session_start();
    require_once 'connect.php'; // On inclu la connexion à la bdd
    $bdd=connectdb();

    // Si les variables existent et qu'elles ne sont pas vides
    if(!empty($_POST['pseudo']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password_retype'])){
        $pseudo = ($_POST['pseudo']);
        $email = ($_POST['email']);
        $password = ($_POST['password']);
        $password_retype = ($_POST['password_retype']);

        // on vérifie si le pseudo est conforme à la regex
        if(preg_match('/^[a-zA-Z0-9_]+$/', $pseudo)){
            // on le mail
            if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                // on vérifie si le mot de passe est conforme à la regex
                if(preg_match('/^(?=.*[^\w\s])(?=.{9,}$).+$/', $password) ){
                    // on vérifie si les deux mots de passe sont identiques
                    if($password == $password_retype ){
                        // On uptate dans la base de données
                            $update = $bdd->prepare("UPDATE USER SET PSEUDO=:PSEUDO, MAIL=:MAIL, MDP=:MDP, TYPEDECOMPTE=:TYPEDECOMPTE WHERE ID=:ID");
                            $update->execute(array(
                                'PSEUDO' => $pseudo,
                                'MAIL' => $email,
                                'MDP' => $password,
                                'TYPEDECOMPTE' => 'USER',
                                'ID' => $_SESSION['user']
                            ));
                            // on met à jour les variables de session
                            $_SESSION['pseudo'] = $pseudo;
                            $_SESSION['email'] = $email;
                            $_SESSION['password'] = $password;

            
                            // On redirige avec le message de succès
                            header('Location:infoCompte.php?reg_err=success');
                            
                        }else{ header('Location: infoCompte.php?reg_err=password'); }
                    }else{ header('Location: infoCompte.php?reg_err=password_correct'); }
                }else{ header('Location: infoCompte.php?reg_err=email'); }
            }else{ header('Location: infoCompte.php?reg_err=pseudo_length'); }
        }else{ header('Location: infoCompte.php?reg_err=already'); 
    }
?>