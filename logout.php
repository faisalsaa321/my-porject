<?php
// تسجيل الخروج: نمسح بيانات الجلسة ونرجع لصفحة الدخول
session_start();
session_unset();
session_destroy();
header("Location: login.html");
exit;