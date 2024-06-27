<?php
    if($_COOKIE["userToken"]== '100200300400500600'){


    }
    else{
        header("Location: ./studentPage.php");
        exit();

    }
include "./leonardo.php"; 
global $connect; 
mysqli_set_charset($connect, 'utf8');

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض حضور الطالب</title>
</head>
<body>

<?php

$student_token = "240613045616126";

$sql = "SELECT students.name, COUNT(attendance.id) AS attendance_count
        FROM students
        LEFT JOIN attendance ON students.Token = attendance.student_Token
        WHERE students.Token = '$student_token' AND attendance.present = 1";

$result = mysqli_query($connect, $sql);

echo "<h2>حضور الطالب</h2>";
echo "<table border='1'>";
echo "<tr><th>اسم الطالب</th><th>عدد الحضور</th></tr>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr><td>".$row['name']."</td><td>".$row['attendance_count']."</td></tr>";
}

mysqli_close($connect);

echo "</table>";
?>

</body>
</html>
