<?php
if (!isset($_SESSION)) {
    session_start();
}
$wordsList = array("computer", "summer", "spring", "autumn", "rain", "sun", "cloud", "snow", "wind", "storm");

//Sauvegarde du mot à trouver dans la session
if (!isset($_SESSION['word'])) {
    $word = $wordsList[array_rand($wordsList)];
    $wordLength = strlen($word);
    $Letters = str_split($word);
    $wordHidden = str_repeat("-", $wordLength);
    $_SESSION['word'] = $word;
    $_SESSION['wordHidden'] = $wordHidden;
    $_SESSION['Letters'] = $Letters;
    $_SESSION['wordLength'] = $wordLength;
    $_SESSION['attempts'] = 0;
    $_SESSION['maxAttempts'] = 6;
    $_SESSION['usedLetters'] = array();
}
//var_dump($_SESSION);

if(isset($_POST['singleChar']) or $_POST['singleChar'] != "") {
    for ($i = 0; $i < $_SESSION['wordLength']; $i++) {
        if ($_POST['singleChar'] == $_SESSION['Letters'][$i]) {
            $_SESSION['wordHidden'][$i] = $_POST['singleChar'];
            $letterFound = true;
            $_SESSION['usedLetters'][] = $_POST['singleChar'];
        }
    }
    if (!isset($letterFound) and $_POST['singleChar'] != "" and isset($_POST['singleChar'])) {
        echo "Letter not found <br>";
        $_SESSION['attempts']++;
        $_SESSION['usedLetters'][] = $_POST['singleChar'];
    }
    echo "Attempts: " . $_SESSION['attempts'] . "<br>";
    echo "Used letters: " . implode(", ", $_SESSION['usedLetters']) . "<br>";
    if ($_SESSION['attempts'] == $_SESSION['maxAttempts']) {
        echo "You lose ! The word was " . $_SESSION['word'] . "<br>" . "Press submit to play again.";
        session_destroy();
        $_POST = array();
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hangman</title>

</head>
<body>
<div id="word"><?php echo $_SESSION['wordHidden'] ?></div>
    <form action="<?= $_SERVER['PHP_SELF']?>" method="post">
        <label for="singleChar">letter</label>
        <input type="text" id="singleChar" name="singleChar" maxlength="1">
        <button id="submit">Submit</button>
    </form>
</body>
</html>

<?php

if ($_SESSION['wordHidden'] == $_SESSION['word']) {
    echo "You win. The word was " . $_SESSION['word'] . "<br>" . "Press submit to play again.";
       session_destroy();
       $_POST = array();
}
?>