<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "car_service_db";

// إنشاء الاتصال بقاعدة البيانات
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// التحقق من أن الطلب قادم عبر POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    // التحقق من صحة البريد الإلكتروني
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("<script>alert('يرجى إدخال بريد إلكتروني صحيح'); window.history.back();</script>");
    }

    // التحقق من عدم ترك الحقول فارغة
    if (empty($name) || empty($email) || empty($message)) {
        die("<script>alert('يرجى ملء جميع الحقول'); window.history.back();</script>");
    }

    // استخدام الاستعلامات المحضرة
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);
    
    if ($stmt->execute()) {
        echo "<script>alert('تم إرسال الرسالة بنجاح!'); window.location.href='contact.html';</script>";
    } else {
        echo "<script>alert('حدث خطأ أثناء الإرسال!'); window.history.back();</script>";
    }

    $stmt->close();
}
$conn->close();
?>
