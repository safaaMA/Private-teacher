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

$sql = "SELECT * FROM students WHERE token != '100200300400500600'";
$result = $connect->query($sql);

if(isset($_POST['delete'])){
    $token = $_POST['token'];
    $sqltoken = "DELETE FROM students WHERE token=$token" ;
    
    if ($connect->query($sqltoken) === TRUE) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $connect->error]);
    }
    exit;
}

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/6c84e23e68.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" <link

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap');

        body {
            font-family: "Cairo", sans-serif;
            background-color: #f8f9fa;
            text-align: center;
        }

        h1 {
            color: #343a40;
            margin-top: 20px;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            direction: rtl;
        }

        th, td {
            padding: 10px;
            border: 1px solid #dee2e6;
        }

        th {
            background-color: #343a40;
            color: #fff;
        }

   td a {
    color: #ffffff;
    text-decoration: none;
    margin: 0 5px;
    background: #343a40;
    padding: 0 6px;
    border-radius: 3px;
    text-directur:none;
}

        a:hover {
            text-decoration: underline;
        }

.delete-btn {
    background-color: red;
    color: #fff;
    border: none;
    padding: 6px 14px;
    cursor: pointer;
    border-radius: 4px;
}
        .delete-btn:hover {
            background-color: #c82333;
        }
.HomePage {
    /* position: fixed; */
    right: 0;
    background: #343a40;
    padding: 3px;
    border-radius: 8px 8px 8px 8px;
    text-direction: none;
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
        <h1>قائمة الطلاب</h1>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>الكود</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr id="row-<?php echo $row['token']; ?>">
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['code']); ?></td>
                    <td>
                        <a href="EditStudent.php?token=<?php echo $row['token']; ?>" class="btn btn-primary btn-sm">تعديل</a>
                        <form class="delete-form d-inline" data-token="<?php echo $row['token']; ?>">
                            <button type="button" class="delete-btn btn btn-danger btn-sm my-2">حذف</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script>
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('.delete-form');
                const token = form.getAttribute('data-token');
                
                if (confirm('هل أنت متأكد أنك تريد حذف هذا الطالب؟')) {
                    fetch('', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            'delete': true,
                            'token': token
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            document.getElementById('row-' + token).remove();
                        } else {
                            alert('خطأ: ' + data.message);
                        }
                    })
                    .catch(error => {
                        alert('حدث خطأ أثناء محاولة حذف الطالب');
                    });
                }
            });
        });
    </script>
</body>
</html>
