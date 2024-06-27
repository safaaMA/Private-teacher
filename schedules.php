<?php
if ($_COOKIE["userToken"] != '100200300400500600') {
    header("Location: ./studentPage.php");
    exit();
}

include "./leonardo.php"; 
global $connect; 
mysqli_set_charset($connect, 'utf8');

$Not = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['group_name'])) {
        $group_name = $_POST['group_name'];
        $day1 = $_POST['day1'];
        $time1 = $_POST['time1'];
        $day2 = $_POST['day2'];
        $time2 = $_POST['time2'];
        $day3 = $_POST['day3'];
        $time3 = $_POST['time3'];

        $sql = "INSERT INTO schedules (group_name, day1, time1, day2, time2, day3, time3)
                VALUES ('$group_name', '$day1', '$time1', '$day2', '$time2', '$day3', '$time3')";

        if ($connect->query($sql) === TRUE) {
            $Not = '<p class="note"> <i class="fa-solid fa-check"></i> تمت إضافة الجدول بنجاح</p>';
            echo '<meta http-equiv="refresh" content="1; url=schedules.php" />';
        } else {
            echo '<meta http-equiv="refresh" content="1; url=schedules.php" />';
        }
    } elseif (isset($_POST['delete_id'])) {
        $delete_id = $_POST['delete_id'];
        $sql = "DELETE FROM schedules WHERE id='$delete_id'";
        if ($connect->query($sql) === TRUE) {
            $Not = '<p class="note"> <i class="fa-solid fa-check"></i> تمت حذف السجل بنجاح</p>';
            echo '<meta http-equiv="refresh" content="1; url=schedules.php" />';
        } else {
            echo '<meta http-equiv="refresh" content="1; url=schedules.php" />';
        }
    }

    $connect->close();
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اوقات المحاضرات</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap');
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #232428;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            direction: rtl;
            flex-direction: column;
        }
        .container {
            width: 100%;
            max-width: 800px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .card, table {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            margin: 10px 0;
            padding: 20px;
            box-sizing: border-box;
        }
        .card input, .card select, .card button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-family: 'Cairo', sans-serif;
            text-align: center;
        }
        .card .row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .card .row select, .card .row input {
            flex: 1;
            min-width: calc(50% - 10px);
        }
        .card button {
            background-color: #332346;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
            font-family: 'Cairo', sans-serif;
        }
        .card button:hover {
            background-color: #45a049;
        }
        .HomePage {
            background: #343a40;
            padding: 3px;
            border-radius: 8px;
            text-align: center;
            width: 100%;
            margin: 4px auto 37px;
        }
        .HomePage a {
            color: #FFF;
            text-decoration: none;
        }
        p.note {
            text-align: center;
            background: #332346;
            color: #fff;
            width: 100%;
            margin: auto;
            border-radius: 13px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            overflow-x: auto;
            display: block;
        }
        table th, table td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #f4f4f4;
        }
        table tr:last-child td {
            border-bottom: none;
        }
        table button {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        table button:hover {
            background-color: #ff0000;
        }
        @media (max-width: 768px) {
            .card .row select, .card .row input {
                min-width: 100%;
                flex: none;
            }
            table {
                font-size: 14px;
            }
            table th, table td {
                padding: 8px;
            }
            .card button {
                padding: 8px;
            }
        }
        @media (max-width: 480px) {
            .card {
                padding: 10px;
            }
            .card input, .card select, .card button {
                padding: 8px;
                margin: 5px 0;
            }
            table th, table td {
                padding: 5px;
            }
            table {
                font-size: 12px;
            }
            table button {
                padding: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php echo $Not ?>
        <div class="HomePage"><a href="./Admin.php"> <i class="fa-solid fa-house"></i> الصفحة الرئيسية</a></div>

        <div class="card">
            <h3>اضف وقت المحاضرة الخاص بكل كروب</h3>
            <form method="post" action="">
                <input type="text" name="group_name" placeholder="اسم المجموعة" required>
                <div class="row">
                    <select name="day1" required>
                        <option value="" disabled selected>اليوم</option>
                        <option value="السبت">السبت</option>
                        <option value="الأحد">الأحد</option>
                        <option value="الإثنين">الإثنين</option>
                        <option value="الثلاثاء">الثلاثاء</option>
                        <option value="الأربعاء">الأربعاء</option>
                        <option value="الخميس">الخميس</option>
                        <option value="الجمعة">الجمعة</option>
                    </select>
                    <input type="text" name="time1" placeholder="الأوقات" required>
                </div>
                <div class="row">
                    <select name="day2" required>
                        <option value="" disabled selected>اليوم</option>
                        <option value="السبت">السبت</option>
                        <option value="الأحد">الأحد</option>
                        <option value="الإثنين">الإثنين</option>
                        <option value="الثلاثاء">الثلاثاء</option>
                        <option value="الأربعاء">الأربعاء</option>
                        <option value="الخميس">الخميس</option>
                        <option value="الجمعة">الجمعة</option>
                    </select>
                    <input type="text" name="time2" placeholder="الأوقات" required>
                </div>
                <div class="row">
                    <select name="day3" required>
                        <option value="" disabled selected>اليوم</option>
                        <option value="السبت">السبت</option>
                        <option value="الأحد">الأحد</option>
                        <option value="الإثنين">الإثنين</option>
                        <option value="الثلاثاء">الثلاثاء</option>
                        <option value="الأربعاء">الأربعاء</option>
                        <option value="الخميس">الخميس</option>
                        <option value="الجمعة">الجمعة</option>
                    </select>
                    <input type="text" name="time3" placeholder="الأوقات" required>
                </div>
                <button type="submit">إرسال</button>
            </form>
        </div>

        <table>
            <tr>
                <th>اسم المجموعة</th>
                <th>اليوم الأول</th>
                <th>الوقت الأول</th>
                <th>اليوم الثاني</th>
                <th>الوقت الثاني</th>
                <th>اليوم الثالث</th>
                <th>الوقت الثالث</th>
                <th>حذف</th>
            </tr>
            <?php
            $sql = "SELECT * FROM schedules";
            $result = $connect->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['group_name']}</td>
                        <td>{$row['day1']}</td>
                        <td>{$row['time1']}</td>
                        <td>{$row['day2']}</td>
                        <td>{$row['time2']}</td>
                        <td>{$row['day3']}</td>
                        <td>{$row['time3']}</td>
                        <td>
                            <form method='post' action=''>
                                <input type='hidden' name='delete_id' value='{$row['id']}'>
                                <button type='submit'>حذف</button>
                            </form>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>لا توجد بيانات</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
