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

if (!isset($_GET['token']) || !ctype_digit($_GET['token'])) {
    header('Location:./studentsLest.php');
    exit;
}

$token = intval($_GET['token']);

// تحضير وتنفيذ استعلام SELECT
$sql = $connect->prepare("SELECT * FROM students WHERE token = ?");
$sql->bind_param("i", $token);
$sql->execute();
$result = $sql->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    die("الطالب غير موجود");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // التحقق من صحة وتنقية المدخلات من POST
    $name = trim($_POST['name']);
    $code = trim($_POST['code']);
    
    if (!empty($name) && !empty($code)) {
        // تحضير وتنفيذ استعلام UPDATE
        $sql = $connect->prepare("UPDATE students SET name = ?, code = ? WHERE token = ?");
        $sql->bind_param("ssi", $name, $code, $token);
        
        if ($sql->execute() === TRUE) {
            header('Location: ./studentsLest.php');
            exit;
        } else {
            echo "خطأ: " . $sql->error;
        }
    } else {
        echo "جميع الحقول مطلوبة.";
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل الطالب</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap');

        body {
            font-family: "Cairo", sans-serif;
            background-color: #f8f9fa;
            text-align: center;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #343a40;
            margin-bottom: 20px;
        }

        label {
            float: right;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }
        .form-control{
            text-align: end;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>تعديل الطالب</h1>
        <form method="post">
            <div class="form-group">
                <label for="name">الاسم</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($student['name']); ?>">
            </div>
            <div class="form-group">
                <label for="code">الكود</label>
                <input type="text" class="form-control" id="code" name="code" value="<?php echo htmlspecialchars($student['code']); ?>">
            </div>
            <button type="submit" class="btn btn-primary">تعديل</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
