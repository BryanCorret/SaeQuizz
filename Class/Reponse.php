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
        $stmt =  $this->bdd->prepare("INSERT INTO REPONSE (QUESTION_ID, QUESTIONNAIRE_ID, REP, CORRECT) VALUES (:question_id, :questionnaire_id, :rep, :correct)");
        $stmt->bindParam(":question_id", $question_id);
        $stmt->bindParam(":questionnaire_id", $questionnaire_id);
        $stmt->bindParam(":rep", $rep);
        $stmt->bindParam(":correct", $correct, PDO::PARAM_BOOL);
        $stmt->execute();
        
        return $this->bdd->lastInsertId();
    }
    
    public function update($question_id, $questionnaire_id, $rep, $correct) {
        $stmt =  $this->bdd->prepare("UPDATE REPONSE SET QUESTION_ID=:question_id, QUESTIONNAIRE_ID=:questionnaire_id, REP=:rep, CORRECT=:correct WHERE ID=:id");
        $stmt->bindParam(":question_id", $question_id);
        $stmt->bindParam(":questionnaire_id", $questionnaire_id);
        $stmt->bindParam(":rep", $rep);
        $stmt->bindParam(":correct", $correct,PDO::PARAM_BOOL);
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

        foreach ([$json] as $reponse) {
            // Vérification si toutes les clés sont présentes
            if (!isset($reponse['REP']) || !isset($reponse['QUESTIONNAIRE_ID']) || !isset($reponse['CORRECT'])) {
                throw new Exception("MAUVAIS FORMAT JSON.");
            }

            // Insertion dans la base de données
            // on get l'id max de la question
            $req = $this->bdd->prepare("SELECT MAX(ID) FROM QUESTION");
            $req->execute();
            $maxIdQuestion = $req->fetch()[0];

            // on get l'id max du questionnaire
            $req = $this->bdd->prepare("SELECT MAX(ID) FROM QUESTIONNAIRE");
            $req->execute();
            $maxId = $req->fetch()[0];
            

            $this->add($maxIdQuestion,$maxId,$reponse['REP'], $reponse['CORRECT']);
        }
    }

}
?>
