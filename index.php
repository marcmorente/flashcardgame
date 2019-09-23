<?php

$arrayFlashcards = [];
$flashcards = [];
$count = 0;

$fn = fopen("flashcards.txt", "r");
while (!feof($fn)) {
    //Parse csv flashcard
    $data = fgetcsv($fn, ",");
    
    $question = $data[0];
    $answer = $data[1];
    $arrayFlashcards[] = [$question => '{{reverse}}'.$answer];// Add tag for know who is the reverse of card

    /*
    * Create array concatenating it questions and answers for matching later.
    * Hash the questons and answers to don't have problems with html codification
    */
    $flashcards[] = md5($question) . md5($answer);
    $flashcards[] = md5($answer) . md5($question);
}

//Get random quantity of questons and responses
$rand = rand(0, count($arrayFlashcards));
$getRandomFlashcards = array_slice($arrayFlashcards, $rand, 6);
$questonsAndRespones = [];

//Iterate the array of arrays to get the questons and responses
foreach ($getRandomFlashcards as $fCard) {
    foreach ($fCard as $key => $value) {
        $questonsAndRespones[] = $key;
        $questonsAndRespones[] = $value;
    }
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
                <?php foreach ($questonsAndRespones as $key => $flashcard) { ?>
                    <div class="col-lg-4" id="<?= $key; ?>">
                        <div class="card m-2 flashcard center-card <?php if (strpos($flashcard, '{{reverse}}') !== false) { echo 'reverse-flashcard'; }?>" data-id="<?= $key; ?>" data-value="<?= md5(str_replace("{{reverse}}", "", $flashcard)); ?>">
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
                let flashcards = <?php echo json_encode($flashcards); ?>;
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
                    $(this).addClass("yellow-flashcard");

                    questionAndResponse += $(this).attr("data-value");
                    
                    //Find if the matching is correct
                    let result = flashcards.indexOf(questionAndResponse);

                    if (click == 2 && result !== -1) {
                        let el = $('.yellow-flashcard');
                        el.addClass('green-flashcard');
                        el.removeClass('yellow-flashcard');

                        setTimeout(function() {
                            let hide = $('.green-flashcard');
                            hide.addClass('hide');
                            hide.removeClass('green-flashcard');
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
                        }, delayInMilliseconds);

                        click = 0;
                        questionAndResponse = "";
                    }
                });
            });
        </script>
    </body>
</html>