<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "car_service_db";

// الاتصال بقاعدة البيانات
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// التحقق من تسجيل الدخول
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim(strtolower($_POST['email'])); // تحويل البريد إلى حروف صغيرة
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT id , password , name FROM users WHERE LOWER(email) = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id ,$hashed_password , $name);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id']= $id;
            $_SESSION['name']= $name;
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script>alert('كلمة المرور غير صحيحة'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('البريد الإلكتروني غير مسجل'); window.history.back();</script>";
    }

    $stmt->close();
}
$conn->close();
?>
