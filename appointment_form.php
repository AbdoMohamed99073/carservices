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


$sql = "SELECT id , name FROM services " ;
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$services_name = [];
while ($row = $result->fetch_assoc()) {
    $services_name[] = $row;
}


?>



<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>حجز موعد</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    
</head>
<body class="container">

<header>
        <div class="container">
        <h1>🚗 مركز صيانة السيارات</h1>

            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
                </button>
              
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                  <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                      <a class="nav-link" href="index.HTML">الصفحة الرئيسة <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="contact.html">تواصل معنا</a>
                    </li>
                  </ul>
                  <form class="form-inline my-2 my-lg-0">
                  </form>
                </div>
              </nav>
        </div>
    </header>


    <section class="container">
        <h2>📅 احجز موعد الصيانة</h2>
        
        <form id="appointment-form" method="post" action="appointment.php">
            <label for="service">🔧 نوع الخدمة:</label>
            <select id="service" name="service">
                <?php foreach ($services_name as $service_name): ?>
                    <option value="<?php echo htmlspecialchars($service_name['id']); ?>"><?php echo htmlspecialchars($service_name['name']); ?></option>
                <?php endforeach ?>
            </select>
            <label for="date">📆 تاريخ الموعد:</label>
            <input type="date" name="appointment_date" name="date" required>

            <button type="submit">✅ تأكيد الحجز</button>
        </form>
    </section>

    <footer>
        <p>حقوق الطبع والنشر © 2025 مركز صيانة السيارات - جميع الحقوق محفوظة</p>
    </footer>

    <script src="js/scripts.js"></script>

</body>
</html>
