<?php
// الاتصال بقاعدة البيانات (غيّر الأسماء إذا صاحبك سمّاها بشكل مختلف)
$pdo = new PDO(
    "mysql:host=localhost;dbname=darbni;charset=utf8mb4",
    "root",   // اسم مستخدم XAMPP الافتراضي
    "",       // كلمة المرور الافتراضية فاضية
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);