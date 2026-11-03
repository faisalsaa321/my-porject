<?php
header("Content-Type: text/html; charset=utf-8");
require "db.php";

// لو فتح أحد الصفحة مباشرة (بدون فورم) نرجعه للتسجيل
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: register.html");
    exit;
}

$username = trim($_POST["username"] ?? "");
$email    = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";
$confirm  = $_POST["confirm_password"] ?? "";

// التحقق في الخادم (الأساسي، لأن تحقق JavaScript يمكن تجاوزه)
if (mb_strlen($username) < 3 || !filter_var($email, FILTER_VALIDATE_EMAIL)
    || strlen($password) < 8 || $password !== $confirm) {
    exit("بيانات غير صحيحة. <a href='register.html'>رجوع</a>");
}

// هل الاسم أو البريد مستخدم من قبل؟
$check = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
$check->execute([$username, $email]);
if ($check->fetch()) {
    exit("اسم المستخدم أو البريد مستخدم من قبل. <a href='register.html'>رجوع</a>");
}

// تشفير كلمة المرور ثم الحفظ
$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
$stmt->execute([$username, $email, $hash]);

header("Location: login.html");
exit;