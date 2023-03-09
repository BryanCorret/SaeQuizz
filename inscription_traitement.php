<?php 
    require_once 'connect.php'; // On inclu la connexion à la bdd
    include('class/User.php'); // On inclu la classe User
    $bdd=connectdb();

    // Si les variables existent et qu'elles ne sont pas vides
    if(!empty($_POST['pseudo']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password_retype'])){
        $pseudo = ($_POST['pseudo']);
        $email = ($_POST['email']);
        $password = ($_POST['password']);
        $password_retype = ($_POST['password_retype']);
        // on créee un objet de la classe User
        $user = new User($bdd);

        // On vérifie si l'utilisateur existe
        $row = $user->check($email);

        $email = strtolower($email); // on transforme toute les lettres majuscule en minuscule 
        
        // Si la requete renvoie un 0 alors l'utilisateur n'existe pas 
        if($row){ 
            if(strlen($pseudo) <= 100){ // On verifie que la longueur du pseudo <= 100
                // on vérifie si le mot de passe est conforme à la regex
                if(preg_match('/^(?=.*[^\w\s])(?=.{9,}$).+$/', $password) ){
                    if(filter_var($email, FILTER_VALIDATE_EMAIL)){ // Si l'email est de la bonne forme
                        if($password === $password_retype){ // si les deux mdp saisis sont bon

                                        
                            
                            // On insère dans la base de données
                            $user->insert($pseudo, $email, $password, 'USER');
                          
                            // On redirige avec le message de succès
                            header('Location:inscription.php?reg_err=success');
                            
                        }else{ header('Location: inscription.php?reg_err=password'); }
                    }else{ header('Location: inscription.php?reg_err=email'); }
                }else{ header('Location: inscription.php?reg_err=password_correct'); }
            }else{ header('Location: inscription.php?reg_err=pseudo_length'); }
        }else{ header('Location: inscription.php?reg_err=already'); }
    }