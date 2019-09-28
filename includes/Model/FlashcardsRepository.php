<?php

namespace Model;

use Entity\Flashcard;
use Database\DatabaseConnection;

class FlashcardsRepository extends DatabaseConnection
{

    public function persist(Flashcard $flashcard)
    {
        //Insert
        $query = '
            INSERT INTO flashcards
            (
                question, 
                answer,
                hash_question_answer,
                hash_answer_question
            ) 
            VALUES 
            (
                :question, 
                :answer,
                :hash_question_answer,
                :hash_answer_question
            )
        ';

        $stmt = $this->database_handle->prepare($query);

        $stmt->bindValue(':question', $flashcard->getQuestion(), \PDO::PARAM_STR);
        $stmt->bindValue(':answer', $flashcard->getAnswer(), \PDO::PARAM_STR);
        $stmt->bindValue(':hash_question_answer', $flashcard->getHasQuestionAnswer(), \PDO::PARAM_STR);
        $stmt->bindValue(':hash_answer_question', $flashcard->getHasAnswerQuestion(), \PDO::PARAM_STR);

        if (!$stmt->execute()) {
            return false;
        }

        return true;
    }

    public function checkIfExist(Flashcard $flashcard)
    {
        
        $query = '
            SELECT 
                question, answer 
            FROM 
                flashcards fc 
            WHERE 
                fc.question = :question 
            AND 
                fc.answer = :answer
        ';

        $stmt = $this->database_handle->prepare($query);
        

        $stmt->bindValue(':question', $flashcard->getQuestion(), \PDO::PARAM_STR);
        $stmt->bindValue(':answer', $flashcard->getAnswer(), \PDO::PARAM_STR);

        $stmt->execute();
        $stmt->fetchAll();

        if ($stmt->rowCount() == 0) {
            return true;
        }

        return null;
    }

    public function match($hash)
    {
        
        $query = '
            SELECT 
                hash_question_answer, hash_answer_question 
            FROM 
                flashcards fc 
            WHERE 
                fc.hash_question_answer = :hash_question_answer
            OR 
                fc.hash_answer_question = :hash_answer_question
        ';

        $stmt = $this->database_handle->prepare($query);
        
        $stmt->bindValue(':hash_question_answer', $hash, \PDO::PARAM_STR);
        $stmt->bindValue(':hash_answer_question', $hash, \PDO::PARAM_STR);

        $stmt->execute();
        $stmt->fetchAll();

        if ($stmt->rowCount() > 0) {
            return true;
        }

        return false;
    }

    public function getFlashcardsRandomly()
    {
        
        $query = 'SELECT question, answer FROM flashcards ORDER BY RAND() LIMIT 6';

        $stmt = $this->database_handle->prepare($query);
        $stmt->execute();
        $row  = $stmt->fetchAll();

        if ($stmt->rowCount() > 0) {
            foreach ($row as $value) {
                $flashcard = new Flashcard();
                $flashcard->setQuestion($value['question']);
                $flashcard->setAnswer($value['answer']);
                $flashcards[] = $flashcard;
            }
            return $flashcards;
        }

        return null;
    }
}
