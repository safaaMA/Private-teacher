<?php
    if($_COOKIE["userToken"]== '100200300400500600'){


    }
    else{
        header("Location: ./studentPage.php");
        exit();

    }
    ?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/6c84e23e68.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" >
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap');
        body {
            font-family: "Cairo", sans-serif;
            background-color: #232428;
            margin: 0;
            padding: 0;
            direction: rtl;
            text-align: center;
            
        }
        h2 {
            color: #fff;
            margin-top: 20px;
        }
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
        th, td {
    padding: 12px;
    border: 1px solid #23242833;
}
        th {
    background-color: #332346eb;
    color: white;
}

        .delete-btn {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .delete-btn:hover {
            background-color: #ff1a1a;
        }
        @media (max-width: 768px) {
            table {
                width: 100%;
            }
            th, td {
                font-size: 14px;
            }
        }
        @media (max-width: 480px) {
            th, td {
                font-size: 12px;
                padding: 10px 5px;
            }
            .delete-btn {
                padding: 5px 8px;
            }

        }
        
        .HomePage {
    background: #343a40;
    padding: 3px;
    border-radius: 8px 8px 8px 8px;
    text-align: center;
    width: 98%;
    margin: 4px auto;
}

.HomePage a {
    color: #FFF;
    text-decoration: none;
}
    </style>
</head>
<body>
<div class="HomePage"><a href="./Admin.php"> <i class="fa-solid fa-house"></i> الصفحة الرئيسية</a></div>


<?php
include "./leonardo.php"; 
global $connect; 
mysqli_set_charset($connect, 'utf8');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete_comment'])) {
        $id = $_POST['delete_comment'];

        $delete_query = "DELETE FROM comment WHERE id = '$id'";
        $delete_result = mysqli_query($connect, $delete_query);
        if ($delete_result) {
            echo "<script>alert('تم حذف التعليق بنجاح');</script>";
        } else {
            echo "<script>alert('حدث خطأ أثناء حذف التعليق');</script>";
        }
    }
}

$sql = "SELECT students.name AS student_name, comment.comment AS comment, comment.id AS id
        FROM students
        INNER JOIN comment ON students.token = comment.token_Student";

$result = mysqli_query($connect, $sql);

echo "<h2>جميع التعليقات</h2>";
echo "<table>";
echo "<tr><th>اسم الطالب</th><th>التعليق</th><th>الحذف</th></tr>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>".$row['student_name']."</td>";
    echo "<td>".$row['comment']."</td>";
    echo "<td><form method='post'><button class='delete-btn' type='submit' name='delete_comment' value='".$row['id']."'>حذف</button></form></td>";
    echo "</tr>";
}

mysqli_close($connect);

echo "</table>";
?>

</body>
</html>
