-- requete de selection

SELECT * FROM QUESTIONNAIRE;

SELECT * FROM QUESTION;

SELECT * FROM REPONSE;

--tous selectionner

SELECT * FROM QUESTIONNAIRE natural join  QUESTION natural join REPONSE;

-- choisire le questionnaire 1 et afficher les questions

SELECT * FROM QUESTION WHERE QUESTIONNAIRE_ID = 1;

-- selon une question, afficher les reponses
SELECT * FROM REPONSE WHERE QUESTION_ID = 1;
