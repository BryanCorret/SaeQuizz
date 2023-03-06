<?php
class Question {
    private $bdd;
    public function __construct(PDO $bdds = null) {
        $this->bdd = $bdds;
    }

    public function getByQuestionnaireId($QUESTIONNAIRE_ID) {
        $req = $this->bdd->prepare("SELECT * FROM QUESTION WHERE QUESTIONNAIRE_ID=:QUESTIONNAIRE_ID");
        $req->bindParam(":QUESTIONNAIRE_ID", $QUESTIONNAIRE_ID);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
   
    public function getById($id) {
        $req =  $this->bdd->prepare("SELECT * FROM QUESTION WHERE ID=:id");
        $req->bindParam(":id", $id);
        $req->execute();
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    
    public function add($QUESTIONNAIRE_ID, $question) {
        $req =  $this->bdd->prepare("INSERT INTO QUESTION (QUESTIONNAIRE_ID, QUESTION) VALUES (:QUESTIONNAIRE_ID, :question)");
        $req->bindParam(":QUESTIONNAIRE_ID", $QUESTIONNAIRE_ID);
        $req->bindParam(":question", $question);
        $req->execute();
        return $this->bdd->lastInsertId();
    }
    
    public function update($id, $QUESTIONNAIRE_ID, $question) {
        $req =  $this->bdd->prepare("UPDATE QUESTION SET QUESTIONNAIRE_ID=:QUESTIONNAIRE_ID, QUESTION=:question WHERE ID=:id");
        $req->bindParam(":id", $id);
        $req->bindParam(":QUESTIONNAIRE_ID", $QUESTIONNAIRE_ID);
        $req->bindParam(":question", $question);
        $req->execute();
    }
    
    public function delete($id) {
        $req =  $this->bdd->prepare("DELETE FROM QUESTION WHERE ID=:id");
        $req->bindParam(":id", $id);
        $req->execute();
    }
    public function exportJSON($id) {
        // recuperer les données de la question dans une variable
        $data = $this->getById($id);
        // transformer les données en JSON
        return json_encode($data);
    }
    public function importJSON($json) {
        // Transformer la chaîne JSON en tableau associatif
        $data = json_decode($json, true);

        // Vérification des données JSON
        if (!is_array($data)) {
            throw new Exception('Les données JSON sont invalides.');
        }

        // Ajout de chaque question dans la base de données
        foreach ($data as $question_data) {
            // Vérification des données de la question
            if (!isset($question_data['question']) || !isset($question_data['QUESTIONNAIRE_ID'])) {
                throw new Exception('Les données de la question sont incomplètes.');
            }
            else{
                
            }


       
        // Ajouter la question dans la base de données
        $req = $this->bdd->prepare("INSERT INTO question (QUESTIONNAIRE_ID, question) VALUES (:QUESTIONNAIRE_ID, :question)");
        $req->bindParam(":QUESTIONNAIRE_ID", $data['QUESTIONNAIRE_ID']);
        $req->bindParam(":question", $data['question']);
        $req->execute();
    
        return true;
    }

    
}
}
?>
