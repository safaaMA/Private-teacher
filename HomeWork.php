<?php
    if($_COOKIE["userToken"]== '100200300400500600'){
        // السماح بالوصول
    } else {
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


    $sql = "SELECT s.name, sw.file, sw.id
    FROM students s
    JOIN schoolwork sw ON s.token = sw.studenttoken
    ORDER BY sw.id DESC";


    $stmt = $connect->prepare($sql);
    if (!$stmt) {
        die("فشل إعداد البيان: " . $connect->error);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $studentWork = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $connect->close();


    
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض الواجبات</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap');

        body {
            font-family: "Cairo", sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
            direction: rtl;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        a {
            color: #3498db;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .note {
            text-align: center;
            color: green;
        }
        .delete-button {
            color: red;
            cursor: pointer;
        }
.HomePage {
    position: fixed;
    right: 0;
    left: 0;
    top: 4px;
    background: #343a40;
    padding: 3px;
    border-radius: 8px 8px 8px 8px;
    width: 98%;
    text-align: center;
    margin: 0px auto;
}
.HomePage a{
    color: #FFF;
    text-decoration: none;
}
    </style>
</head>
<body>
<div class="HomePage"><a href="./Admin.php"> <i class="fa-solid fa-house"></i> الصفحة الرئيسية</a></div>

    <div class="container">
        <h1>واجبات الطالب</h1>
        <table>
            <tr>
                <th>اسم الطالب</th>
                <th>ملف الواجب</th>
                <th>إجراءات</th>
            </tr>
            <?php if (!empty($studentWork)): ?>
                <?php foreach ($studentWork as $work): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($work['name']); ?></td>
                        <td><a href="<?php echo htmlspecialchars($work['file']); ?>" target="_blank">عرض الملف</a></td>
                        <td><a class="delete-button" href="delete.php?id=<?php echo $work['id']; ?>">حذف</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">لا توجد واجبات متاحة.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>
