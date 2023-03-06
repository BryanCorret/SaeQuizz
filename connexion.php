<?php 
    session_start(); 
    require_once 'connect.php'; // On inclut la connexion à la base de données
    $bdd=connectdb();

    if(isset($_POST['mail']) && isset($_POST['mdp'])){ // Si il existe les champs email, mdp et qu'il sont pas vident       
        $email = ($_POST['mail']); 
        $mdp = ($_POST['mdp']);
       
        $check = $bdd->prepare('SELECT ID,PSEUDO, MAIL, MDP, TYPEDECOMPTE FROM USER WHERE MAIL = ?');
        $check->execute(array($email));
        $data = $check->fetch();
        $row = $check->rowCount();
        // afficher dans la console l'email et le mot de passe
        echo $email;
        echo $mdp;



        // Si > à 0 alors l'utilisateur existe
        if($row > 0){
            // Si le mail est bon niveau format
            if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                // Si le mot de passe est le bon
                if($mdp == $data['MDP']){
                    // On créer la session et on redirige sur quizz.php
                    $_SESSION['user'] = $data['ID'];
                    $_SESSION['pseudo'] = $data['PSEUDO'];
                    $_SESSION['mail'] = $data['MAIL'];
                    $_SESSION['MDP'] = $data['MDP'];


                    if($data['TYPEDECOMPTE'] == 'USER'){
                        $_SESSION['admin'] = 1;
                    }
                    header('Location: home.php');
                }else{ header('Location: index.php?login_err=error');   }
            }else{ header('Location: index.php?login_err=error');   }
        }else{ header('Location: index.php?login_err=error');
        }
    }else{ header('Location: index.php?login_err=error');  } // si le formulaire est envoyé sans aucune données
?>
