<?php
class Questionnaire {
    private $bdd;
    
    public function __construct($bdds) {
        $this->bdd = $bdds;
    }
    public function getAll() {
        $stmt = $this->bdd->prepare("SELECT * FROM QUESTIONNAIRE");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $stmt = $this->bdd->prepare("SELECT * FROM QUESTIONNAIRE WHERE ID=:id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function add($NOM_QUESTIONAIRE) {
        $stmt = $this->bdd->prepare("INSERT INTO QUESTIONNAIRE (NOM_QUESTIONAIRE) VALUES (:NOM_QUESTIONAIRE)");
        $stmt->bindParam(":NOM_QUESTIONAIRE", $NOM_QUESTIONAIRE);
        $stmt->execute();
        return $this->bdd->lastInsertId();
    }
    
    public function update($id, $NOM_QUESTIONAIRE) {
        $stmt = $this->bdd->prepare("UPDATE QUESTIONNAIRE SET NOM_QUESTIONAIRE=:NOM_QUESTIONAIRE WHERE ID=:id");
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":NOM_QUESTIONAIRE", $NOM_QUESTIONAIRE);
        $stmt->execute();
    }
    
    public function delete($id) {
        // on supprime les questions associées au questionnaire	
        require_once("class/Questions.php");
        $question = new Question($this->bdd);
        $questions = $question->getByQuestionnaireId($id);
        foreach ($questions as $q) {
            $question->delete($q['ID']);
        }

        $stmt = $this->bdd->prepare("DELETE FROM QUESTIONNAIRE WHERE ID=:id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
    }
    public function exportJSON($id) {
        // récupération des informations sur le questionnaire
        $query = "SELECT * FROM QUESTIONNAIRE WHERE ID=:id";
        $stmt = $this->bdd->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $questionnaire = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // récupération des questions associées au questionnaire
        $query = "SELECT * FROM QUESTION WHERE QUESTIONNAIRE_ID=:questionnaire_id";
        $stmt = $this->bdd->prepare($query);
        $stmt->bindParam(":questionnaire_id", $id);
        $stmt->execute();
        $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // pour chaque question, récupération des réponses associées
        foreach ($questions as &$question) {
            $query = "SELECT * FROM REPONSE WHERE QUESTION_ID=:question_id";
            $stmt = $this->bdd->prepare($query);
            $stmt->bindParam(":question_id", $question['ID']);
            $stmt->execute();
            $reponses = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $question['reponses'] = $reponses;
        }
    
        // création d'un tableau associatif avec les données du questionnaire et des questions
        $data = array(
            'id' => $questionnaire['ID'],
            'titre' => $questionnaire['NOM_QUESTIONAIRE'],
            'questions' => $questions
        );
    
        // transformation du tableau en JSON
        return json_encode($data);
    }
 

    public function importJson($json) {
        include('class/Questions.php');
        $data = json_decode($json, true);
        if (!$data) {
            throw new Exception("Erreur lors du décodage JSON.");
        }
    
        if (!isset($data['questions']) || !is_array($data['questions'])) {
            throw new Exception("Le tableau de questions est manquant ou invalide.");
        }else{
            $req = $this->add($data['titre']);
            foreach ($data['questions'] as $questionData) {
                
            $question = new Question($this->bdd);
            $question->importJson(json_encode($questionData));            
        }
    }
      
    }
    
}
?>
