<?php
header("Content-Type: text/html; charset=utf-8");
session_start();
require "db.php";

// لازم المستخدم يكون مسجّل دخول، والطلب من فورم
if (!isset($_SESSION["user_id"]) || $_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.html");
    exit;
}

$age     = (int)($_POST["age"] ?? 0);
$height  = (int)($_POST["height_cm"] ?? 0);
$weight  = (float)($_POST["weight_kg"] ?? 0);
$days    = (int)($_POST["workout_days"] ?? 0);
$goal    = $_POST["goal"] ?? "";
$level   = $_POST["level"] ?? "";
$place   = $_POST["place"] ?? "";
$notes   = mb_substr(trim($_POST["notes"] ?? ""), 0, 500);

// التحقق في الخادم (الأساسي) مع قوائم القيم المسموحة
$valid = $age >= 12 && $age <= 80
      && $height >= 100 && $height <= 230
      && $weight >= 30 && $weight <= 250
      && $days >= 2 && $days <= 6
      && in_array($goal, ["lose_weight", "build_muscle", "general_fitness"], true)
      && in_array($level, ["beginner", "intermediate", "advanced"], true)
      && in_array($place, ["home", "gym"], true);

if (!$valid) {
    header("Location: profile.php?error=1");
    exit;
}

// إذا عنده ملف قديم نحدّثه، وإلا نضيف جديد
$stmt = $pdo->prepare(
    "INSERT INTO profiles (user_id, age, height_cm, weight_kg, goal, level, workout_days, place, notes)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
     ON DUPLICATE KEY UPDATE
       age = VALUES(age), height_cm = VALUES(height_cm), weight_kg = VALUES(weight_kg),
       goal = VALUES(goal), level = VALUES(level), workout_days = VALUES(workout_days),
       place = VALUES(place), notes = VALUES(notes)"
);
$stmt->execute([$_SESSION["user_id"], $age, $height, $weight, $goal, $level, $days, $place, $notes]);

header("Location: profile.php?saved=1");
exit;