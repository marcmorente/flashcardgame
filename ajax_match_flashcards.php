<?php
require_once 'vendor/autoload.php';

use Model\FlashcardsRepository;

$flashcardRepository = new FlashcardsRepository();

if ($_POST) {
    $hash = $_POST['hash'];
    $match = $flashcardRepository->match($hash);
    echo (int)$match;
}
