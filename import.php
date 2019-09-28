<?php 
require_once 'vendor/autoload.php';

use Model\FlashcardsRepository;
use Entity\Flashcard;

$flashcardRepository = new FlashcardsRepository();
$fn = fopen("flashcards.txt", "r");
$error = 0;
$result = false;

while (!feof($fn)) {
    //Parse csv flashcard
    $data = fgetcsv($fn, ",");
    $question = $data[0];
    $answer = $data[1];

    $flashcard = new Flashcard();
    $flashcard->setQuestion($question);
    $flashcard->setAnswer($answer);
    $flashcard->setHasQuestionAnswer(md5($question) . md5($answer));
    $flashcard->setHasAnswerQuestion(md5($answer) . md5($question));

    // Uncomment this for don't have duplicate entries on the database
    //if ($flashcardRepository->checkIfExist($flashcard)) {
        $result = $flashcardRepository->persist($flashcard);
    //}
    
    if (!$result) {
        $error++;
    }

}

if ($error != 0) {
    echo "Import error, some flashcards could not be imported"; 
} else {
    echo "Import successfully completed";
}