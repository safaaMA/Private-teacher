<?php
    if($_COOKIE["userToken"] == '100200300400500600') {
        // Allow access
    } else {
        header("Location: ./studentPage.php");
        exit();
    }

    include "./leonardo.php"; 
    global $connect;

    if ($connect->connect_error) {
        die("Connection failed: " . $connect->connect_error);
    }

    mysqli_set_charset($connect, 'utf8');

    $Note = null;
    $Time = date("y-m-d h:i:s");
    $Date = date("ymdhis");
    $RNumber = rand(100, 200);
    $Token = $Date . $RNumber;

    $Getstudents = "SELECT * FROM students WHERE token != '100200300400500600'";
    $startStudents = mysqli_query($connect, $Getstudents);

    if (!$startStudents) {
        die("Query failed: " . mysqli_error($connect));
    }

    if (isset($_POST['submit'])) {
        $lech = $_POST['lech'];
        $names = $_POST['names'];
        $grades = $_POST['grades'];

        $all_successful = true;

        for ($i = 0; $i < count($names); $i++) {
            $name = $names[$i];
            $grade = $grades[$i];

            if (trim($grade) === '') {
                $grade = 'لا توجد درجة';
            }

            $stmt = $connect->prepare("INSERT INTO grading (StudentName, grading, LcName, token) VALUES (?, ?, ?, ?)");
            if ($stmt === false) {
                $all_successful = false;
                $Note = '<p class="note"><i class="fa-solid fa-times"></i> تحضير الاستعلام فشل: ' . htmlspecialchars($connect->error, ENT_QUOTES, 'UTF-8') . '</p>';
                break;
            }

            $stmt->bind_param("ssss", $name, $grade, $lech, $Token);

            if (!$stmt->execute()) {
                $all_successful = false;
                $Note = '<p class="note"><i class="fa-solid fa-times"></i> تنفيذ الاستعلام فشل: ' . htmlspecialchars($stmt->error, ENT_QUOTES, 'UTF-8') . '</p>';
                break;
            }

            $stmt->close();
        }

        if ($all_successful) {
            $Note = '<p class="note"><i class="fa-solid fa-check"></i> تم إضافة الدرجة بنجاح</p>';
                            echo'<meta http-equiv="refresh" content="1; url=Grading.php" />';

        }
    }

    $connect->close();
?>

<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>درجات الطلاب</title>
    <link rel="icon" href="./Logo.png" type="image/x-icon">
    <script src="https://kit.fontawesome.com/6c84e23e68.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="Regster.css">
</head>
<style>
    p.note {
    text-align: center;
    color: red;
    
    
}
.HomePage {
    background: #343a40;
    padding: 3px;
    border-radius: 8px 8px 8px 8px;
    text-align: center;
    width: 98%;
    margin: 4px auto;
}

</style>
<body>
<div class="HomePage"><a href="./Admin.php"><i class="fa-solid fa-house"></i> الصفحة الرئيسية</a></div>

    <header>
        <h1>درجات الطلاب</h1>
    </header>
    <main>
        <section>
            <h2 class="title">نوع الامتحان</h2>
            <?php echo $Note ?>
            <form id="attendanceForm" method="POST">
                <div class="Name-of-lesson">
                    <h1>
                        <input class="input-name" name="lech" type="text" placeholder="يرجى ادخال نوع الامتحان" required>
                    </h1>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>اسم الطالب</th>
                            <th>درجة الامتحان</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($Data = mysqli_fetch_array($startStudents)) {
                            echo '
                            <tr>
                                <td><input name="names[]" type="text" value="' . htmlspecialchars($Data["name"], ENT_QUOTES, 'UTF-8') . '" readonly></td>
                                <td><input name="grades[]" type="text" placeholder="ادخل الدرجة"></td>
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
