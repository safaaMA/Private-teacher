<?php
if ($_COOKIE["userToken"] != '100200300400500600') {
    header("Location: ./studentPage.php");
    exit();
}

include "./leonardo.php"; 
global $connect;
mysqli_set_charset($connect, 'utf8');    

if ($_COOKIE["userLogin"] != 1) {
    echo '<meta http-equiv="refresh" content="0; url=./login.php" />';
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM schoolwork WHERE id = ?";
    $stmt = $connect->prepare($sql);
    if (!$stmt) {
        die("فشل إعداد البيان: " . $connect->error);
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $connect->close();

    header("Location: ./HomeWork.php");
        exit();
} else {
    header("Location: ./HomeWork.php");
    exit();
}
?>
