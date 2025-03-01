<?php
session_start();

// بيانات الاتصال بقاعدة البيانات
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "car_service_db";

// إنشاء الاتصال
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// التحقق من أن الطلب قادم بطريقة POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // التأكد من تسجيل دخول المستخدم
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('يجب تسجيل الدخول أولاً!'); window.location.href='login.html';</script>";
        exit();
    }

    // استقبال البيانات من النموذج
    $user_id = $_SESSION['user_id'];
    $service_id = intval($_POST['service_id']); // تحويل إلى عدد صحيح لحماية من SQL Injection
    $appointment_date = htmlspecialchars(trim($_POST['appointment_date'])); // تنظيف المدخلات

    // التحقق من صحة البيانات
    if (empty($service_id) || empty($appointment_date)) {
        echo "<script>alert('يرجى ملء جميع الحقول!'); window.history.back();</script>";
        exit();
    }

    // التحقق من أن تاريخ الموعد ليس في الماضي
    if (strtotime($appointment_date) < strtotime(date("Y-m-d"))) {
        echo "<script>alert('لا يمكنك حجز موعد في تاريخ سابق!'); window.history.back();</script>";
        exit();
    }

    // التحقق من عدم وجود موعد مكرر لنفس المستخدم في نفس اليوم
    $check_stmt = $conn->prepare("SELECT id FROM appointments WHERE user_id = ? AND appointment_date = ?");
    $check_stmt->bind_param("is", $user_id, $appointment_date);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('لديك بالفعل موعد محجوز في هذا اليوم!'); window.history.back();</script>";
        exit();
    }
    $check_stmt->close();

    // إدخال البيانات إلى قاعدة البيانات
    $stmt = $conn->prepare("INSERT INTO appointments (user_id, service_id, appointment_date) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $service_id, $appointment_date);

    if ($stmt->execute()) {
        echo "<script>alert('تم حجز الموعد بنجاح!'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('حدث خطأ أثناء الحجز، يرجى المحاولة مرة أخرى لاحقًا.'); window.history.back();</script>";
    }

    // إغلاق الاستعلام والاتصال
    $stmt->close();
}

$conn->close();
?>
