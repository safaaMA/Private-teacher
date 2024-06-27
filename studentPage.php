<?php
    if(@$_COOKIE["userRank"]== '10'){
        header("Location: ./Admin.php");
        exit();
        
    }elseif(@$_COOKIE["userRank"]== '0'){


    }
    else{
        header("Location: ./login.php");
        exit();
    
    }
    include "leonardo.php"; 
    global $connect; 
    mysqli_set_charset($connect, 'utf8');    

    
    $userToken = @$_COOKIE["userToken"];
  
    $Note=null;
    $DataStudent = "SELECT * FROM students WHERE token='$userToken'";
    $RunTask = mysqli_query($connect, $DataStudent);
    $Data = mysqli_fetch_array($RunTask);

    $report = "SELECT * FROM `report` ORDER BY `report`.`id` DESC";
    $Runreport = mysqli_query($connect, $report);
    $report = mysqli_fetch_array($Runreport);


    $sql = "SELECT students.name, COUNT(attendance.id) AS attendance_count
        FROM students
        LEFT JOIN attendance ON students.Token = attendance.student_Token
        WHERE students.Token = '$userToken' AND attendance.present = 1";

    $result = mysqli_query($connect, $sql);
    $row = mysqli_fetch_assoc($result);

    $sqlHW = "SELECT * FROM homework ORDER BY id DESC LIMIT 1";
    $resultHW = mysqli_query($connect, $sqlHW);

    $sqlTime = "SELECT day, time, note FROM week_schedule ORDER BY day ";
    $resultTime = mysqli_query($connect, $sqlTime);

    if (isset($_POST['submit'])) {
        $comment = $_POST["comment"];
                $sql = "INSERT INTO comment (token_Student, comment, Date) VALUES ('$userToken', '$comment', NOW())";
        
        if (mysqli_query($connect, $sql)) {
            $Note = '<p class="note"> <i class="fa-solid fa-check"></i> تم ارسال التعليق بنجاح</p>';
                    echo '<meta http-equiv="refresh" content="1; url=studentPage.php" />';
        } else {
            $Note = '<p class="note"> لم يتم ارسال التعليق بنجاح</p>';
        }
    }
    
    

    if (isset($_POST['submitfile'])) {
        if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
            $file_name = basename($_FILES['file']['name']);
            $target_path = "ExamFile/" . $file_name;
            
            if (move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
                $sqlFile = "INSERT INTO schoolwork (file, studenttoken) VALUES (?, ?)";
                $stmtFile = $connect->prepare($sqlFile);
                $stmtFile->bind_param("ss", $target_path, $userToken);
    
                if ($stmtFile->execute()) {
                    $Note = "<p class='note'> <i class='fa-solid fa-check'></i> تم تسليم الواجب بنجاح   </p>";
                                        echo '<meta http-equiv="refresh" content="1; url=studentPage.php" />';

                    
                } else {
                    $Note = "<p class='note'> خطأ في الإضافة: " . $stmtFile->error . "</p>";
                }
                $stmtFile->close();
            } else {
                $Note = "<p> حدث خطأ أثناء نقل الملف. تأكد من أن المسار الهدف صحيح.</p>";
            }
        } else {
            $Note = "<p> حدث خطأ أثناء تحميل الملف.</p>";
        }
    }
    $sqlgrading = "SELECT g.StudentName, g.LcName, g.grading 
               FROM grading g
               JOIN students s ON g.StudentName = s.name
               WHERE s.token = ?
               ORDER BY g.id DESC LIMIT 1";

$stmtgrading = $connect->prepare($sqlgrading);
$stmtgrading->bind_param("s", $userToken);
$stmtgrading->execute();
$resultgrading = $stmtgrading->get_result();
$rowgrading = $resultgrading->fetch_assoc();



$grading = htmlspecialchars($rowgrading['grading']);
$isNoGrade = $grading == 'لا توجد درجة';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="StudentStyle.css">
    <script src="https://kit.fontawesome.com/6c84e23e68.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" <link
        rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css" crossorigin="">
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <link rel="icon" href="./Logo.png" type="image/x-icon">

    <title>صفحة الطلاب</title>
</head>
<style>
.ex-de.has-grade {
    font-size: 129px;
}

.ex-de.no-grade {
    font-size: 33px;
}
</style>
<body>
    <div class="container">
        <div class="top">
            <div class="interface">
                <div class="logOut">
                    <a href="./logout.php">تسجيل الخروج</a>
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </div>
                <div class="big-Box">
                    <div class="Pro-Info">
                        <div class="logo"><img src="./Logo.png" alt=""></div>
                        <div class="title">
                            <h1>الاستاذ محمود علي النجم </h1>
                        </div>
                    </div>
                    <div class="info-student">
                    <h1><samp>اسم الطالب :</samp><?php echo $Data['name']; ?></h1>
                    <h1><?php echo $Note; ?></h1>
                    </div>
                </div>
                <br>
            </div>
            <div class="report-inter">
                <div class="report">
                    <h5>تبليغات الاستاذ</h5>
                    <p><?php echo $report['public_report']; ?></p>
                </div>
            </div>
            <div class="interface-down">
                <div class="Regester">
                    <h5>عدد الغيابات  </h5>
                    <h1>+<?php echo $row['attendance_count'] ?></h1>
                </div>
                <div class="Regester Time">
                    <h5>كتابة تعليق للاستاذ</h5>
                    <form action="" method="post">
                        <div class="boxForm">
                            <textarea name="comment" id=""></textarea>
                        </div>
                        <input type="submit" name="submit" value="ارسال">
                    </form>
                </div>
            </div>
            <div class="show-ex">
                <div class="title-ex">
                    <h5>الواجبات</h5>
                </div>
                <div class="box-ex">
                <?php
while ($rowHW = mysqli_fetch_assoc($resultHW)) {
?>
    <div class="caption">
        <p>
            <?php echo $rowHW['Detils']; ?>
        </p>
        <div class="pdf-link-container">
            <button id="showPdfBtn">عرض ملف PDF</button>
        </div>
    </div>
    <div id="pdfViewer" class="pdf-viewer-container" style="display: none;">
        <embed src="./file/<?php echo $rowHW['file']; ?>" type="application/pdf" width="100%" height="600px" />
    </div>
<?php
}
?>

            </div>
<div class="time-of-lec">
    <h5>جدول أوقات المحاضرات</h5>
    <div class="schedule-table-container">
        <?php 
        
        $sql = "SELECT group_name, day1, time1, day2, time2, day3, time3 FROM schedules";
$result = $connect->query($sql);

if ($result->num_rows > 0) {
while($row = $result->fetch_assoc()) {
echo "<div class='card'>";
echo "<p class='group-name-sc'> ". $row["group_name"]. "</p>";
echo "<p class='day'>" . $row["day1"]. "<spam class='time'> " . $row["time1"]. "</spam></p>";
echo "<p class='day'>" . $row["day2"]. "<spam class='time'> " . $row["time2"]. "</spam></p>";
echo "<p class='day'>" . $row["day3"]. "<spam class='time'> " . $row["time3"]. "</spam></p>";
echo "</div>";
}
} else {
echo "0 نتائج";
}
$connect->close();
        
        ?>
</div>
    </tbody>
</table>

</div>
<div class="interface-down for-exam">
                <div class="Regester-dgree">
    <h5>درجة امتحان <span><?php echo htmlspecialchars($rowgrading['LcName']) ?></span></h5>
    <h1 class="ex-de <?php echo $isNoGrade ? 'no-grade' : 'has-grade'; ?>">
        <?php echo $isNoGrade ? $grading : '+' . $grading; ?>
    </h1>
</div>
                <div class="Regester-Examp ">
                    <h5 style=" margin: 20px 0px;   width: 100%;"> <samp><i class="fa-solid fa-hand-pointer"></i></samp>   اضغط الارسال الواجب</h5>
                    <form class="form-examp" action="" method="post" enctype="multipart/form-data">
                        <div class="boxForm-Examp">
                            <label style="cursor: pointer;" for="examp"><img src="./exam.png" alt=""></label>
                            <input style="display:none;" type="file" name="file" id="examp" require>      
                        </div>
                        <input type="submit" name="submitfile" value="ارسال">
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    document.getElementById('showPdfBtn').addEventListener('click', function () {
        var pdfViewer = document.getElementById('pdfViewer');
        pdfViewer.style.display = 'block';
    });
</script>

</html>