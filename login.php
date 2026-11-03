<?php
header("Content-Type: text/html; charset=utf-8");
session_start();
require "db.php";

// لو فتح أحد الصفحة مباشرة (بدون فورم) نرجعه للدخول
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.html");
    exit;
}

$username = trim($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";

$stmt = $pdo->prepare("SELECT id, username, password_hash FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user["password_hash"])) {
    session_regenerate_id(true);
    $_SESSION["user_id"] = $user["id"];
    $_SESSION["username"] = $user["username"];
    header("Location: profile.php");
    exit;
}

exit("اسم المستخدم أو كلمة المرور غير صحيحة. <a href='login.html'>رجوع</a>");