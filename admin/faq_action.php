<?php

require_once('../connection.php');
global $connect;
// =========================
// DELETE
// =========================
if (isset($_GET['delete'])) {

    $id = (int)$_GET['delete'];

    $stmt = mysqli_prepare($connect, "DELETE FROM faq WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: faq.php?deleted=1");
    } else {
        header("Location: faq.php?error=delete");
    }

    exit;
}

// =========================
// UPDATE
// =========================
if (isset($_POST['update'])) {

    $id = (int)$_POST['id'];
    $question = trim($_POST['question']);
    $answer = trim($_POST['answer']);

    $stmt = mysqli_prepare($connect, "UPDATE faq SET question=?, answer=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "ssi", $question, $answer, $id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: faq.php?updated=1");
    } else {
        header("Location: faq.php?error=update");
    }

    exit;
}

// =========================
// INSERT
// =========================
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $question = trim($_POST['question']);
    $answer = trim($_POST['answer']);

    $stmt = mysqli_prepare($connect, "INSERT INTO faq(question,answer) VALUES(?,?)");
    mysqli_stmt_bind_param($stmt, "ss", $question, $answer);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: faq.php?success=1");
    } else {
        header("Location: faq.php?error=1");
    }

    exit;
}

header("Location: faq.php");
exit;