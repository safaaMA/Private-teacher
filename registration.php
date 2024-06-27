<?php
if ($_COOKIE["userToken"] == '100200300400500600') {
    // User is authenticated
} else {
    header("Location: ./studentPage.php");
    exit();
}

include "./leonardo.php"; 
global $connect; 
mysqli_set_charset($connect, 'utf8');

session_start();

if (isset($_POST['submit'])) {
    $attendance_data = $_POST['attendance'];
    $attendance_date = date('Y-m-d'); 

    foreach ($attendance_data as $student_Token => $present) {
        $student_Token = mysqli_real_escape_string($connect, $student_Token);
        $present = mysqli_real_escape_string($connect, $present);

        $insert_query = "INSERT INTO attendance (student_Token, attendance_date, present) VALUES ('$student_Token', '$attendance_date', '$present')";
        if (mysqli_query($connect, $insert_query)) {
            $_SESSION['message'] = 'تمت إضافة الحضور بنجاح';
        } else {
            $_SESSION['message'] = 'حدث خطأ أثناء إضافة الحضور: ' . mysqli_error($connect);
        }
    }

    mysqli_close($connect);

    header("Location: registration.php");
    exit();
}

if (isset($_POST['decrease'])) {
    $student_Token = mysqli_real_escape_string($connect, $_POST['student_Token']);

    $delete_query = "DELETE FROM attendance WHERE student_Token = '$student_Token' AND present = 1 ORDER BY attendance_date DESC LIMIT 1";
    if (mysqli_query($connect, $delete_query)) {
        $_SESSION['message'] = 'تم إنقاص عدد الحضور بنجاح';
    } else {
        $_SESSION['message'] = 'حدث خطأ أثناء إنقاص عدد الحضور: ' . mysqli_error($connect);
    }

    mysqli_close($connect);

    header("Location: registration.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل غياب الطلاب</title>
    <link rel="stylesheet" href="Regster.css">
    <link rel="icon" href="./Logo.png" type="image/x-icon">
    <script src="https://kit.fontawesome.com/6c84e23e68.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="HomePage"><a href="./Admin.php">الصفحة الرئيسية <i class="fa-solid fa-house"></i></a></div>
<header>
    <h1>حضور الطلاب</h1>
</header>
<main>
    <?php
    if (isset($_SESSION['message'])) {
        echo '<p class="massage">' . $_SESSION['message'] . '</p>';
        unset($_SESSION['message']); 
    }
    ?>
    <section>
        <h2>جدول التسجيل</h2>
        <form method="POST" action="">
            <table>
                <thead>
                    <tr>
                        <th>اسم الطالب</th>
                        <th>حالة الغياب</th>
                        <th>عدد الغياب</th>
                        <th>انقاص غياب الطالب</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $students_query = "SELECT Token, name FROM students WHERE token != '100200300400500600'";
                    $students_result = mysqli_query($connect, $students_query);
                    
                    while ($student = mysqli_fetch_array($students_result)) {
                        $student_token = $student["Token"];
                        $attendance_count_query = "SELECT COUNT(*) as attendance_count FROM attendance WHERE student_Token = '$student_token' AND present = 1";
                        $attendance_count_result = mysqli_query($connect, $attendance_count_query);
                        $attendance_count = mysqli_fetch_assoc($attendance_count_result)["attendance_count"];

                        echo '
                        <tr>
                            <td>' . htmlspecialchars($student["name"]) . '</td>
                            <td><input type="checkbox" name="attendance[' . htmlspecialchars($student_token) . ']" value="1"></td>
                            <td>' . htmlspecialchars($attendance_count) . '</td>
                            <td>
                                <form method="POST" action="" style="display:inline;">
                                    <input type="hidden" name="student_Token" value="' . htmlspecialchars($student_token) . '">
                                    <button type="submit" name="decrease">-</button>
                                </form>
                            </td>
                        </tr>
                        ';
                    }
                    ?>
                </tbody>
            </table>
            <button type="submit" name="submit">حفظ</button>
        </form>
    </section>
</main>
</body>
</html>
