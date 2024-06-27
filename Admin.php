<?php
    if($_COOKIE["userToken"]== '100200300400500600'){


        }
        else{
            header("Location: ./login.php");
            exit();

        }
   include "./leonardo.php"; 
   global $connect; 
   mysqli_set_charset($connect, 'utf8');

   $Note=null;
   $Not=null;

   $Time = date("y-m-d h:i:s");
   $Date = date("ymdhis");
   $RNumber = rand(100, 200);
   $Token = $Date . $RNumber;

   $S_Name = @$_POST['Name'];
   $S_Code = @$_POST['code'];
   $S_Password = @$_POST['password'];
   $S_Gender = @$_POST['gender'];
   $S_rank = '0';

   $Detils = @$_POST['Detils'];
   $Report = @$_POST['report'];

   if (isset($_POST['submit'])) {
    $insertData = "INSERT INTO `students` (token, name, code, password, gender,Rank) 
    VALUES ('$Token', '$S_Name', '$S_Code', '$S_Password', '$S_Gender','$S_rank')";
    if(mysqli_query($connect, $insertData)){
                $Note ='<p class="note"> <i class="fa-solid fa-check"></i> تمت إضافة الطالب  بنجاح</p>';
                echo'<meta http-equiv="refresh" content="1; url=Admin.php" />';
                exit();
            }else{
                $Note ='<p class="note"> <i class="fa-solid fa-check"></i> لم يتم إضافة الطالب  بنجاح</p>';
                echo'<meta http-equiv="refresh" content="1; url=Admin.php" />';
                exit();
            }
    }

   if (isset($_POST['submit_report'])) {
    $insertReport = "INSERT INTO `report` (public_report) VALUES ('$Report')";
    if(mysqli_query($connect, $insertReport)){
                $Not ='<p class="note"> <i class="fa-solid fa-check"></i> تمت إضافة التبليغ  بنجاح</p>';
                echo'<meta http-equiv="refresh" content="1; url=Admin.php" />';
                exit();
            }else{
                $Not ='<p class="note"> <i class="fa-solid fa-check"></i> لم يتم إضافة التبليغ  بنجاح</p>';
                echo'<meta http-equiv="refresh" content="1; url=Admin.php" />';
                exit();
            }
    }

    $query = "SELECT * FROM week_schedule";
    $result = mysqli_query($connect, $query);

if (isset($_POST['submitFile'])) {
  if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
    $file_name = basename($_FILES['file']['name']);
    $target_path ="file/". $file_name;

    if (move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
        
        $sql = "INSERT INTO `homework` (Detils,file) VALUES (?,?)";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("ss",$Detils, $file_name);

        if ($stmt->execute()) {
                echo'<meta http-equiv="refresh" content="1; url=Admin.php" />';

        } else {
                echo'<meta http-equiv="refresh" content="1; url=Admin.php" />';

        }
        $stmt->close();
        
    } else {
        $Not ="<p> حدث خطأ أثناء نقل الملف. تأكد من أن المسار الهدف صحيح.</p>";
    }
} else {
    $sql = "INSERT INTO `homework` (Detils) VALUES (?)";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("s", $Detils);

    if ($stmt->execute()) {
        $Not ="<p  class='note'> <i class='fa-solid fa-check'></i>     تمت إضافة البيانات بنجاح بدون فايل</p>";
    } else {
        $Not ="<p class='note'>  خطأ في الإضافة: </p>";
    }
    $stmt->close();
}

$connect->close();
}



    $sqlC = "SELECT students.name AS student_name, comment.comment AS comment, comment.Date AS Date, comment.id AS id
        FROM students
        INNER JOIN comment ON students.token = comment.token_Student
        ORDER BY comment.id DESC
        LIMIT 3";
    $resultC = mysqli_query($connect, $sqlC);



    $sqlNum = "SELECT COUNT(*) as student_count FROM students WHERE token != '100200300400500600'";
$resultNum = $connect->query($sqlNum);

if ($resultNum->num_rows > 0) {
    $rowNUM = $resultNum->fetch_assoc();
    $student_count = $rowNUM['student_count'];
} 

$exceeded_query = "SELECT s.name 
                   FROM students s
                   JOIN attendance a ON s.Token = a.student_Token
                   WHERE a.present = '1'
                   GROUP BY s.name
                   HAVING COUNT(a.student_Token) >= 3";

$exceeded_result = mysqli_query($connect, $exceeded_query);
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/6c84e23e68.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" <link
        rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css" crossorigin="">
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <link rel="icon" href="./Logo.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <title>النجم</title>
</head>

<body>
    <div class="container">
        <div class="RightSide">
            <nav>
                <div class="logOut">
                    <a href="./logout.php">تسجيل الخروج</a>
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
        
                </div>
                <div class="title">

                    <h1>النــجم</h1>
                </div>
                <div class="seting" onclick="Activite()"> <i class="fa-solid showd fa-bars-staggered"></i>
                    <span>القائمة</span>
                </div>
            </nav>

            <?php echo $Note ?>
            <?php echo $Not ?>
            <div class="content">

                <div class="comment">
                    <div class="title-comment">
                        <div class="com-st">
                            <h1> <i class="fa-regular fa-comments"></i> تعليقات الطلبة </h1>
                        </div>
                        <div class="showAll">
                            <a href="./AllComment.php"> <i class="fa-solid fa-arrow-down-wide-short"></i> عرض الكل </a>
                        </div>
                    </div>
                    <div class="box-comm">
    <?php
    while ($rowC = mysqli_fetch_assoc($resultC)) {
        echo '
        <div class="comment-info">
            <div class="name-photo-Student">
                <div class="name">
                    <h1>' . htmlspecialchars($rowC['student_name'], ENT_QUOTES, 'UTF-8') . '</h1>
                </div>
                <div class="photo"><img src="student.png" alt=""></div>
            </div>
            <div class="caption">
                <p>' . htmlspecialchars($rowC['comment'], ENT_QUOTES, 'UTF-8') . '</p>
                <p class="date">'. htmlspecialchars($rowC['Date'], ENT_QUOTES, 'UTF-8') .'</p>
            </div>
        </div>';
    }
    ?>
</div>

                      
                </div>
                <div class="report-detels">

                    <div class="report">
                        <div class="titleRep">
                        <div class="re-title">
                               <h1> <i class="fa-brands fa-think-peaks"></i> اضافة تبليغ عام الى الطلبة </h1>
                           </div>
                            <div class="all-re">
                                <a href="./AllReport.php"> <i class="fa-solid fa-arrow-down-wide-short"></i> عرض الكل </a>
                            </div>
                        </div>
                        <form method="post">
                            <textarea name="report" id=""></textarea>
                            <input type="submit" name="submit_report" value="نـشر">
                        </form>
                    </div>

                    <div class="Numbers">
                        <div class="NumberStodent">
                            <div class="TetleNum">
                                <h1> <i class="fa-solid fa-arrow-up-9-1"></i> عدد الطلبة في النظام </h1>
                            </div>
                            <div class="IntNum" id="studentCount">0</div>
                            </div>
                    </div>
                        <div class="number-of-reg">
                               <p class="offside">الطلبة الذين تجاوزو عدد الغيابات المقررة </p>
                    <main>
        <?php if (mysqli_num_rows($exceeded_result) > 0) : ?>
            <div class="massage">
                <ul>
                    <?php while ($student = mysqli_fetch_assoc($exceeded_result)) : ?>
                        <li>الطالب: <?php echo htmlspecialchars($student['name']); ?></li>
                    <?php endwhile; ?>
                </ul>
            </div>
        <?php else : ?>
            <div class="massage">
                <p>لا يوجد طلاب تجاوزوا الحد المسموح بالغيابات.</p>
            </div>
        <?php endif; ?>
    </main>
                    </div>
                </div>

            </div>
        </div>
        <div class="LeftSide" id="menu">
            <div class="info-tech">
                <div class="imgTec"><img src="./Logo.png" alt=""></div>
                <div class="Nametech">
                    <h1>الاستاذ محمود علي النجم </h1>
                </div>
            </div>
            <div class="list">
                <ul class="Active">
                    <li><a href="">الصفحة الرئيسية </a><i class="fa-brands fa-squarespace"></i></li>
                </ul>
                <ul>
                    <li onclick="AddStudent()"><a>اضافة طالب </a><i class="fa-solid fa-user-plus"></i></li>
                </ul>
                <ul>
                                        <li onclick="HWForm()"><a>الواجبــات</a><i class="fa-solid fa-plus"></i></li>

                </ul>
                <ul>
                    <li><a href="./registration.php">تسجيل الحضور </a><i
                            class="fa-solid fa-person-circle-question"></i></li>
                </ul>
                <ul>
                         <li><a href="schedules.php">اوقات المحاضرات </a><i class="fa-regular fa-hourglass"></i></li>

                </ul>
                <ul>
                    <li><a href="./Grading.php"> درجات الطلاب </a><i class="fa-solid fa-layer-group"></i></li>
                </ul>
                <ul>
                    <li><a href="./HomeWork.php">  واجبات الطلاب </a><i class="fa-solid fa-grip"></i>
                </ul>
                <ul>
                    <li><a href="./studentsLest.php">  ادارة الطلاب </a><i class="fa-solid fa-screwdriver-wrench"></i></li>
                </ul>
            </div>
        </div>
        <div class="AddStudent" id="studentForm">
            <div class="title-Add-Studen">
                <h1>اضافة طالب</h1>
            </div>

            <form class="Form-Add-Student" action="" method="post">
                <div class="close"><i class="fa-regular fa-circle-xmark"></i></div>
                <div class="mainbox">
                    <div class="boxinput">
                        <label for="name"> اسم الطالب الثلاثي</label>
                        <input type="text" name="Name" id="name">
                    </div>
                    <div class="boxinput">
                        <label for="code"> كود الطالب</label>
                        <div class="code-box">
                            <input type="text" name="code" id="code">
                            <button onclick="generateCode()">توليد كود</button>

                        </div>
                    </div>
                </div>
                <div class="mainbox">
                    <div class="boxinput">
                        <label for="password">رمز الدخول</label>
                        <input type="password" name="password" id="password">
                    </div>
                    <div class="boxinput se">
                        <label for="famel-Male">الجنس</label>
                        <div class="boxSe">
                            <label for="female">أنثى</label>
                            <input type="radio" id="female" name="gender" value="أنثى"><br>
                        </div>
                        <div class="boxSe">
                            <label for="male">ذكر</label>
                            <input type="radio" id="male" name="gender" value="ذكر"><br>
                        </div>
                    </div>
                </div>
                <input class="submit-Student" name="submit" type="submit" value="اضافة">

            </form>
        </div>
        <div class="AddStudent" id="HWForm">
            <div class="title-Add-Studen">
                <h1>اضافة واجب</h1>
            </div>

            <form class="Form-Add-Student WH" action="" method="post" enctype="multipart/form-data">
                <div class="closeHw"><i class="fa-regular fa-circle-xmark"></i></div>
                <div class="mainbox">
                    <div class="boxinput WHBOX">
                        <label for="Detils"> التفاصــيل</label>
                        <textarea name="Detils" id="Detils"></textarea>
                    </div>
                </div>
                <div class="mainbox">
                    <div class="boxinput WHFILE">
                        <label class="HWlabel" for="file">اضغط لاضافة ملف الواجب</label>
                        <input type="file" style="display: none;" id="file" name="file" accept=".pdf" required>

                    </div>
                    
                </div>
                          <div class="mainbox">
                <div class="boxinput WHFILE">
                        <a href="AllHomWork.php" class="allreport-btn">عرض كل الواجبات</a>
                    </div>
                </div>

                <input class="submit-Student" type="submit" name="submitFile" value="اضافة">

            </form>
        </div>
        <div class="AddStudent" id="TimeForm">
            <div class="title-Add-Studen">
                <h1>اضافة اوقات المحاضرة</h1>
            </div>

            <form class="Form-Add-Student WH" action="" method="post">
                <div class="closeTime"><i class="fa-regular fa-circle-xmark"></i></div>
                <div class="mainbox">
                    <table>
                        <thead>
                            <tr>
                                <th>اليوم</th>
                                <th>الوقت</th>
                                <th>ملاحظة</th>
                            </tr>
                        </thead>
                        <tbody>
                          
                            <?php
            while ($row = mysqli_fetch_array($result)) {
                echo '
                <tr>
                    <td><input  class="notTime" name="days[]" type="text" value="' . htmlspecialchars($row["day"]) . '" readonly></td>
        <td><input name="times[]" type="time" value="' . htmlspecialchars($row["time"]) . '" required></td>
                    <td><input class="notTime" name="notes[]" type="text" value="' . htmlspecialchars($row["note"]) . '"></td>
                    <input type="hidden" name="ids[]" value="' . htmlspecialchars($row["id"]) . '">
                </tr>
                ';
            }
            ?>
                        </tbody>
                    </table>
                </div>

                <input class="submit-Student" type="submit" name="submitTable" value="اضافة">

            </form>
        </div>
    </div>
</body>
<script src="./js.js"></script>
<script>
        document.addEventListener("DOMContentLoaded", function() {
            const studentCountElement = document.getElementById("studentCount");
            const targetCount = <?php echo $student_count; ?>;
            let currentCount = 0;
            const increment = Math.ceil(targetCount / 100); // Adjust the speed of the counter
            const duration = 1000; // Total time for the counter to reach the target
            const stepTime = Math.abs(Math.floor(duration / targetCount));

            function updateCount() {
                currentCount += increment;
                if (currentCount > targetCount) {
                    currentCount = targetCount;
                }
                studentCountElement.innerText = currentCount;
                if (currentCount < targetCount) {
                    setTimeout(updateCount, stepTime);
                }
            }

            updateCount();
        });
    </script>
</html>