<?php
if ($_COOKIE["userToken"] == '100200300400500600') {
    // User is authenticated
} else {
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
        .btn {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .delete-btn {
            background-color: #ff4d4d;
            color: white;
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
            .btn {
                padding: 5px 8px;
            }
        }
        .HomePage {
            background: #343a40;
            padding: 3px;
            border-radius: 8px;
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
    if (isset($_POST['delete_homework'])) {
        $id = $_POST['delete_homework'];
        $delete_query = "DELETE FROM homework WHERE id = '$id'";
        $delete_result = mysqli_query($connect, $delete_query);
        if ($delete_result) {
            echo "<script>alert('تم حذف الواجب بنجاح');</script>";
        } else {
            echo "<script>alert('حدث خطأ أثناء حذف الواجب');</script>";
        }
    }
}

$sql = "SELECT id, Detils, file FROM homework ORDER BY id DESC";
$result = mysqli_query($connect, $sql);

echo "<h2>جميع الواجبات</h2>";
echo "<table>";
echo "<tr><th>التفاصيل</th><th>الملف</th><th>الحذف</th></tr>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>".$row['Detils']."</td>";
    echo "<td><a href='./file/".$row['file']."' target='_blank'>".$row['file']."</a></td>";
    echo "<td>
            <form method='post'>
                <button class='btn delete-btn' type='submit' name='delete_homework' value='".$row['id']."'>حذف</button>
            </form>
          </td>";
    echo "</tr>";
}

mysqli_close($connect);

echo "</table>";
?>
</body>
</html>
