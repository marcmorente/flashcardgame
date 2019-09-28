<?php

namespace Entity;

class Flashcard {
    private $question;
    private $answer;
    private $hasQuestionAnswer;
    private $hasAnswerQuestion;
    

    /**
     * Get the value of question
     */ 
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set the value of question
     *
     * @return  self
     */ 
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get the value of answer
     */ 
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set the value of answer
     *
     * @return  self
     */ 
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get the value of hasQuestionAnswer
     */ 
    public function getHasQuestionAnswer()
    {
        return $this->hasQuestionAnswer;
    }

    /**
     * Set the value of hasQuestionAnswer
     *
     * @return  self
     */ 
    public function setHasQuestionAnswer($hasQuestionAnswer)
    {
        $this->hasQuestionAnswer = $hasQuestionAnswer;

        return $this;
    }

    /**
     * Get the value of hasAnswerQuestion
     */ 
    public function getHasAnswerQuestion()
    {
        return $this->hasAnswerQuestion;
    }

    /**
     * Set the value of hasAnswerQuestion
     *
     * @return  self
     */ 
    public function setHasAnswerQuestion($hasAnswerQuestion)
    {
        $this->hasAnswerQuestion = $hasAnswerQuestion;

        return $this;
    }
}