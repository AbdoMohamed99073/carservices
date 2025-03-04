<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<p>يجب تسجيل الدخول أولاً. سيتم إعادة توجيهك إلى صفحة تسجيل الدخول...</p>";
    header("refresh:3; url=login.html");
    exit();
}

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

// جلب بيانات المواعيد الخاصة بالمستخدم مع اسم الخدمة
$user_id = $_SESSION['user_id'];
$sql = "SELECT service_id, appointment_date 
        FROM appointments a
        JOIN services s ON service_id = id 
        WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$appointments = [];
while ($row = $result->fetch_assoc()) {
    $appointments[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>حسابي</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
            background-color: #f8f9fa;
        }
        a, button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #16a085;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        a:hover, button:hover {
            background-color: #138d75;
        }
        .appointments {
            margin-top: 20px;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            display: inline-block;
            text-align: right;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    
    <h2>مرحبًا، <?php echo htmlspecialchars($_SESSION['name']); ?>!</h2>
    <p>تم تسجيل الدخول بنجاح إلى حسابك.</p>

    <div class="appointments">
        <h3>📅 مواعيدي المحجوزة</h3>
        <?php if (count($appointments) > 0): ?>
            <table>
                <tr>
                    <th>الخدمة</th>
                    <th>تاريخ الموعد</th>
                </tr>
                <?php foreach ($appointments as $appointment): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($appointment['service_id']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>لا يوجد لديك مواعيد محجوزة.</p>
        <?php endif; ?>
    </div>

    <br>
    <a href="appointment.html">🔧 لوحة التحكم</a>
    <a href="index.html">🏠 الصفحة الرئيسية</a>
    <a href="logout.php">🚪 تسجيل الخروج</a>

</body>
</html>
