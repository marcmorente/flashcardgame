<?php
require_once 'vendor/autoload.php';

use Model\FlashcardsRepository;

//Get flashcards randomly from database 
$flashcardRepository = new FlashcardsRepository();
$flashcards = $flashcardRepository->getFlashcardsRandomly();

//Iterate array of objects, and create an array with questions and answers
$questonsAndRespones = [];
foreach ($flashcards as $flashcard) {
    $question = $flashcard->getQuestion();
    $answer = $flashcard->getAnswer();
    $questonsAndRespones[] = $question;
    $questonsAndRespones[] = '{{reverse}}'.$answer;// Add tag for know who is the reverse of card
}

//Randomize questons and responses
shuffle($questonsAndRespones);

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/css/sweetalert2.min.css" rel="stylesheet">
        <link href="assets/css/style.css" rel="stylesheet">
    </head>
    <body>
        <div class="container centered" id="flashcard-container" >
            <div class="row">
                <?php foreach ($questonsAndRespones as $flashcard) { ?>
                    <div class="col-lg-4">
                        <div class="card m-2 flashcard center-card <?php if (strpos($flashcard, '{{reverse}}') !== false) { echo 'reverse-flashcard'; }?>" data-value="<?= md5(str_replace("{{reverse}}", "", $flashcard)); ?>">
                            <div class="card-body text-center">
                                <div>
                                    <?= str_replace("{{reverse}}", "", $flashcard); ?>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

        <script src="assets/js/jquery-3.3.1.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/sweetalert2.min.js"></script>
        <script src="assets/js/flowtype.js"></script>

        <script type="text/javascript">
            $(document).ready(function () {
                let questionAndResponse = "";
                let click = 0;
                let correct = 0;
                let delayInMilliseconds = 1000;

                // Responsive text
                $('body').flowtype({
                    minimum   : 500,
                    maximum   : 700,
                    minFont   : 12,
                    maxFont   : 40,
                    fontRatio : 30
                });

                //Resize text when is too large
                $( ".card-body" ).each(function( index ) {
                    let children = $(this).children();
                    children.css('font-size', '1em');

                    while( children.height() > $(this).height() ) {
                        children.css('font-size', (parseInt(children.css('font-size')) - 1) + "px" );
                    }
                    
                });

                //Matching flashcards
                $(".flashcard").click(function () {
                    click++;
                    
                    if (click <= 2) { //Only max of two clicks for matching
                        $(this).addClass("yellow-flashcard");
                        
                        //Concatenate question and response for each click
                        questionAndResponse += $(this).attr("data-value");

                        //Find if the matching is correct via AJAX
                        $.ajax({
                            type: 'post',
                            url: 'ajax_match_flashcards.php',
                            data: {
                                hash: questionAndResponse
                            },
                            success: function(response){
                                if (click == 2 && response.trim() == 1) {
                                    let el = $('.yellow-flashcard');
                                    el.addClass('green-flashcard');
                                    el.removeClass('yellow-flashcard');

                                    setTimeout(function() {
                                        $('.green-flashcard').remove();
                                        click = 0;
                                        questionAndResponse = "";
                                    }, delayInMilliseconds);

                                    correct++;

                                    if (correct == 6) {
                                        setTimeout(function() {
                                            Swal.fire({
                                                title: "Well done!",
                                                text: "Do you want to play again?",
                                                type: 'success',
                                                showCancelButton: true,
                                                confirmButtonColor: '#3085d6',
                                                cancelButtonColor: '#d33',
                                                confirmButtonText: 'Yes!'
                                            }).then((result) => {
                                                if (result.value) {
                                                    location.reload();
                                                }
                                            });
                                        }, delayInMilliseconds);
                                    }
                                    
                                } else if (click == 2) { 
                                    //Restore default values if the matching is incorrect
                                    let el = $('.yellow-flashcard');
                                    el.addClass('red-flashcard');
                                    el.removeClass('yellow-flashcard');
                                    setTimeout(function() {
                                        $('.flashcard').removeClass('red-flashcard');
                                        click = 0;
                                        questionAndResponse = "";
                                    }, delayInMilliseconds);
                                }
                            }
                        });
                    }
                });
            });
        </script>
    </body>
</html>