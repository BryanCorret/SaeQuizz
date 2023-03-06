<?php
class Reponse {

    private $bdd;
    
    public function __construct(PDO $bdds = null) {
      $this->bdd= $bdds;

    }
     
    public function getReponse($id) {
        $req =  $this->bdd->prepare('SELECT * FROM reponse WHERE id = :id');
        $req->execute(array('id' => $id));
        $reponse = $req->fetch();
        return $reponse;
      
      }
  
    public function getByQuestionId($question_id) {
        $stmt =  $this->bdd->prepare("SELECT * FROM REPONSE WHERE QUESTION_ID=:question_id");
        $stmt->bindParam(":question_id", $question_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getByQuestionnaireId($questionnaire_id) {
        $stmt =  $this->bdd->prepare("SELECT * FROM REPONSE WHERE QUESTIONNAIRE_ID=:questionnaire_id");
        $stmt->bindParam(":questionnaire_id", $questionnaire_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function add($question_id, $questionnaire_id, $rep, $correct) {
        $stmt =  $this->bdd->prepare("INSERT INTO REPONSE (QUESTION_ID, QUESTIONNAIRE_ID, REP, CORRECT,) VALUES (:question_id, :questionnaire_id, :rep, :correct)");
        $stmt->bindParam(":question_id", $question_id);
        $stmt->bindParam(":questionnaire_id", $questionnaire_id);
        $stmt->bindParam(":rep", $rep);
        $stmt->bindParam(":correct", $correct);
        $stmt->execute();
        return $this->bdd->lastInsertId();
    }
    
    public function update($id, $question_id, $questionnaire_id, $rep, $correct) {
        $stmt =  $this->bdd->prepare("UPDATE REPONSE SET QUESTION_ID=:question_id, QUESTIONNAIRE_ID=:questionnaire_id, REP=:rep, CORRECT=:correct WHERE ID=:id");
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":question_id", $question_id);
        $stmt->bindParam(":questionnaire_id", $questionnaire_id);
        $stmt->bindParam(":rep", $rep);
        $stmt->bindParam(":correct", $correct);
        $stmt->execute();
    }
    
    public function delete($id) {
        $stmt =  $this->bdd->prepare("DELETE FROM REPONSE WHERE ID=:id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
    }
    public function exportJSON($id) {
        // recuperer les données de la question dans une variable
        $data = $this->getReponse($id);
        // transformer les données en JSON
        return json_encode($data);
    }

    public function importJSON($json) {
        $data = json_decode($json, true);

        // Vérification si les données sont valides
        if (!is_array($data)) {
            throw new Exception("Invalid JSON format.");
        }


        foreach ($data as $reponse) {
            // Vérification si toutes les clés sont présentes
            if (!isset($reponse['question_id'], $reponse['reponse'])) {
                throw new Exception("MAUVAIS FORMAT JSON.");
            }

            // Insertion dans la base de données
            $req = $this->bdd->prepare("INSERT INTO reponse (question_id, reponse) VALUES (:question_id, :reponse)");
            $req->bindParam(':question_id', $reponse['question_id']);
            $req->bindParam(':reponse', $reponse['reponse']);
            $req->execute();
        }
    }

}
?>
