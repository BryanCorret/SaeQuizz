<?php
class Question
{
    private $bdd;
    public function __construct(PDO $bdds = null)
    {
        $this->bdd = $bdds;
    }

    public function getByQuestionnaireId($QUESTIONNAIRE_ID)
    {
        $req = $this->bdd->prepare("SELECT * FROM QUESTION WHERE QUESTIONNAIRE_ID=:QUESTIONNAIRE_ID");
        $req->bindParam(":QUESTIONNAIRE_ID", $QUESTIONNAIRE_ID);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNbQuestion($QUESTIONNAIRE_ID)
    {
        $req = $this->bdd->prepare("SELECT COUNT(*) FROM QUESTION WHERE QUESTIONNAIRE_ID=:QUESTIONNAIRE_ID");
        $req->bindParam(":QUESTIONNAIRE_ID", $QUESTIONNAIRE_ID);
        $req->execute();
        return $req->fetchColumn();
    }

    public function getById($id)
    {
        $req = $this->bdd->prepare("SELECT * FROM QUESTION WHERE ID=:id");
        $req = $this->bdd->prepare("SELECT * FROM QUESTION WHERE ID=:id");
        $req->bindParam(":id", $id);
        $req->execute();
        return $req->fetch(PDO::FETCH_ASSOC);
    }


    public function add($QUESTIONNAIRE_ID, $question, $type_question)
    {
        $req = $this->bdd->prepare("INSERT INTO QUESTION (QUESTIONNAIRE_ID, QUESTION,TYPE_QUESTION) VALUES (:QUESTIONNAIRE_ID, :question, :type_question)");
        $req->bindParam(":QUESTIONNAIRE_ID", $QUESTIONNAIRE_ID);
        $req->bindParam(":question", $question);
        $req->bindParam(":type_question", $type_question);
        $req->execute();
        return $this->bdd->lastInsertId();
    }

    public function update($id, $QUESTIONNAIRE_ID, $question, $type_question)
    {
        $req = $this->bdd->prepare("UPDATE QUESTION SET QUESTIONNAIRE_ID=:QUESTIONNAIRE_ID, QUESTION=:question, TYPE_QUESTION=:type_question WHERE ID=:id");
        $req->bindParam(":id", $id);
        $req->bindParam(":QUESTIONNAIRE_ID", $QUESTIONNAIRE_ID);
        $req->bindParam(":question", $question);
        $req->bindParam(":type_question", $type_question);
        $req->execute();
    }

    public function delete($id)
    {
        require_once("class/Reponse.php");
        //on supprime les reponses de la question
        $req = $this->bdd->prepare("DELETE FROM REPONSE WHERE QUESTION_ID=:id");
        $req->bindParam(":id", $id);
        $req->execute();
        $reponse = new Reponse($this->bdd);
        foreach ($reponse->getByQuestionId($id) as $reponse) {
            $reponse->delete($reponse['ID']);
        }
        

        //on supprime la question
        $req = $this->bdd->prepare("DELETE FROM QUESTION WHERE ID=:id");
        $req->bindParam(":id", $id);
        $req->execute();
    }
    public function getMaxId()
    {
        $req = $this->bdd->prepare("SELECT MAX(ID) FROM QUESTION");
        $req->execute();
        return $req->fetchColumn();
    }
    public function exportJSON($id)
    {
        // recuperer les donn??es de la question dans une variable
        $data = $this->getById($id);
        // transformer les donn??es en JSON
        return json_encode($data);
    }
    public function importJSON($json)
    {
        require_once('class/Reponse.php');

        // Transformer la cha??ne JSON en tableau associatif
        $data = json_decode($json, true);

        // V??rification des donn??es JSON
        if (!is_array($data)) {
            throw new Exception('Les donn??es JSON sont invalides.');
        }

        // Ajout de chaque question dans la base de donn??es



        foreach ([$data] as $question_data) {
        

            // si les donn??es de la question sont incompl??tes
            if (!isset($question_data['QUESTION']) || !isset($question_data['reponses'])) {
                throw new Exception('Les donn??es de la question sont incompl??tes.');
            } else {
                // Ajout de la question dans la base de donn??es
                // on cherche l'id du questionnaire
                $req = $this->bdd->prepare("SELECT MAX(ID) FROM QUESTIONNAIRE");
                $req->execute();
                $maxId = $req->fetch()[0];
                $this->add($maxId, $question_data['QUESTION'], $question_data['TYPE_QUESTION']);
                foreach ($question_data['reponses'] as $reponse_data) {
                    // V??rification des donn??es de la r??ponse
                    if (!isset($reponse_data['REP']) || !isset($reponse_data['CORRECT'])) {
                        throw new Exception('Les donn??es de la r??ponse sont incompl??tes.');
                    } else {
                        // Ajout de la r??ponse dans la base de donn??es
                        $reponse = new Reponse($this->bdd);
                        $reponse->importJSON($reponse_data);
                    }
                }
                


            }
            // regarde si l'on a fini le JSON
            if (next($question_data) === false) {
                break;
            }
        }
        return true;


    }
}
?>