<?php
session_start();

// تحقق مما إذا كانت الجلسة نشطة
if (isset($_SESSION['user_id'])) {
    session_destroy();
    header("Location: login.html"); // إعادة التوجيه إلى صفحة تسجيل الدخول
    exit();
} else {
    // إذا كانت الجلسة غير موجودة بالفعل، يمكنك إعادة توجيه المستخدم إلى الصفحة الرئيسية أو صفحة أخرى
    header("Location: index.html");
    exit();
}
?>
