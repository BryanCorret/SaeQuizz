<?php

class User {
    private $bdd;
    
    public function __construct($bdds = null) {
        $this->bdd = $bdds;
    }
    
    public function insert($pseudo, $mail, $mdp, $typedecompte) {
        $req = $this->bdd->prepare('INSERT INTO USER (PSEUDO, MAIL, MDP, TYPEDECOMPTE) VALUES (?, ?, ?, ?)');
        $req->execute([$pseudo, $mail, $mdp, $typedecompte]);
        return $this->bdd->lastInsertId();
    }
    
    public function update($id, $pseudo, $mail, $mdp, $typedecompte) {
        $req = $this->bdd->prepare('UPDATE USER SET PSEUDO = ?, MAIL = ?, MDP = ?, TYPEDECOMPTE = ? WHERE ID = ?');
        $req->execute([$pseudo, $mail, $mdp, $typedecompte, $id]);

    }
    
    public function selectAll() {
        $req = $this->bdd->prepare('SELECT * FROM USER');
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function selectById($id) {
        $req = $this->bdd->prepare('SELECT * FROM USER WHERE ID = ?');
        $req->execute([$id]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    public function selectByMail($email) {
        $req = $this->bdd->prepare('SELECT * FROM USER WHERE Mail = ?');
        $req->execute([$email]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }
    
    public function check($email) {
        // On vérifie si l'utilisateu n'existe
        $req = $this->bdd->prepare('SELECT PSEUDO, MAIL, MDP FROM USER WHERE MAIL = ?');
        $req->execute([$email]);
        $row = $req->rowCount();

        if ($row == 0) {
            return true;
        } else {
            return false;
        }

    }
    public function delete($id) {
        $req = $this->bdd->prepare('DELETE FROM USER WHERE ID = ?');
        $req->execute([$id]);
    }
}


?>
