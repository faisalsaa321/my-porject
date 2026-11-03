<?php
header("Content-Type: text/html; charset=utf-8");
session_start();
require "db.php";

// لو المستخدم ما سجّل دخول، نرجعه لصفحة الدخول
if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit;
}

// نجيب بياناته المحفوظة (إن وُجدت) عشان نعبّي الحقول
$p = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM profiles WHERE user_id = ?");
    $stmt->execute([$_SESSION["user_id"]]);
    $p = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
} catch (PDOException $e) {
    $p = []; // جدول profiles ما انسوّى بعد، نكمل بدون تعبئة
}

// دوال صغيرة: قيمة آمنة للعرض، وتحديد الخيار المختار
function v($p, $key) { return htmlspecialchars((string)($p[$key] ?? "")); }
function sel($p, $key, $val) { return ((string)($p[$key] ?? "") === (string)$val) ? "selected" : ""; }

$saved = isset($_GET["saved"]);
$hasError = isset($_GET["error"]);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>دربني | ملفك الشخصي</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Lalezar&family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="login.css">
</head>
<body>

  <!-- جهة الفورم -->
  <main class="panel">
    <section class="card">
      <h1>ملفك الشخصي</h1>

      <?php if ($saved): ?>
        <p class="notice" role="status">تم حفظ بياناتك.</p>
      <?php endif; ?>

      <form id="profileForm" action="save_profile.php" method="POST" novalidate>
        <div class="row">
          <div class="field">
            <label for="age">العمر</label>
            <input id="age" name="age" type="number" inputmode="numeric" value="<?= v($p, 'age') ?>" required>
          </div>
          <div class="field">
            <label for="height_cm">الطول (سم)</label>
            <input id="height_cm" name="height_cm" type="number" inputmode="numeric" value="<?= v($p, 'height_cm') ?>" required>
          </div>
        </div>

        <div class="row">
          <div class="field">
            <label for="weight_kg">الوزن (كجم)</label>
            <input id="weight_kg" name="weight_kg" type="number" step="0.1" inputmode="decimal" value="<?= v($p, 'weight_kg') ?>" required>
          </div>
          <div class="field">
            <label for="workout_days">أيام التمرين بالأسبوع</label>
            <select id="workout_days" name="workout_days" required>
              <option value="">اختر</option>
              <option value="2" <?= sel($p, 'workout_days', 2) ?>>2</option>
              <option value="3" <?= sel($p, 'workout_days', 3) ?>>3</option>
              <option value="4" <?= sel($p, 'workout_days', 4) ?>>4</option>
              <option value="5" <?= sel($p, 'workout_days', 5) ?>>5</option>
              <option value="6" <?= sel($p, 'workout_days', 6) ?>>6</option>
            </select>
          </div>
        </div>

        <div class="field">
          <label for="goal">هدفك</label>
          <select id="goal" name="goal" required>
            <option value="">اختر</option>
            <option value="lose_weight" <?= sel($p, 'goal', 'lose_weight') ?>>خسارة وزن</option>
            <option value="build_muscle" <?= sel($p, 'goal', 'build_muscle') ?>>بناء عضلات</option>
            <option value="general_fitness" <?= sel($p, 'goal', 'general_fitness') ?>>لياقة عامة</option>
          </select>
        </div>

        <div class="row">
          <div class="field">
            <label for="level">مستواك</label>
            <select id="level" name="level" required>
              <option value="">اختر</option>
              <option value="beginner" <?= sel($p, 'level', 'beginner') ?>>مبتدئ</option>
              <option value="intermediate" <?= sel($p, 'level', 'intermediate') ?>>متوسط</option>
              <option value="advanced" <?= sel($p, 'level', 'advanced') ?>>متقدم</option>
            </select>
          </div>
          <div class="field">
            <label for="place">مكان التمرين</label>
            <select id="place" name="place" required>
              <option value="">اختر</option>
              <option value="home" <?= sel($p, 'place', 'home') ?>>البيت</option>
              <option value="gym" <?= sel($p, 'place', 'gym') ?>>النادي</option>
            </select>
          </div>
        </div>

        <div class="field">
          <label for="notes">ملاحظات أو إصابات (اختياري)</label>
          <textarea id="notes" name="notes" maxlength="500"><?= v($p, 'notes') ?></textarea>
        </div>

        <p class="error" id="error" role="alert" <?= $hasError ? '' : 'hidden' ?>>بيانات غير صحيحة، راجع الحقول.</p>

        <button type="submit" class="submit">حفظ</button>
      </form>

      <p class="alt"><a href="logout.php">تسجيل الخروج</a></p>
    </section>
  </main>

  <!-- جهة التصميم -->
  <aside class="art">
    <svg class="lanes" viewBox="0 0 600 800" preserveAspectRatio="xMidYMid slice" aria-hidden="true" focusable="false">
      <g fill="none" stroke-width="30" stroke-linecap="round">
        <circle class="lane c1" cx="600" cy="800" r="90" pathLength="1" transform="rotate(90 600 800)" style="--i:0;--len:.54"/>
        <circle class="lane c2" cx="600" cy="800" r="138" pathLength="1" transform="rotate(90 600 800)" style="--i:1;--len:.54"/>
        <circle class="lane c3" cx="600" cy="800" r="186" pathLength="1" transform="rotate(90 600 800)" style="--i:2;--len:.54"/>
        <circle class="lane c1" cx="600" cy="800" r="234" pathLength="1" transform="rotate(90 600 800)" style="--i:3;--len:.54"/>
        <circle class="lane c2" cx="600" cy="800" r="282" pathLength="1" transform="rotate(90 600 800)" style="--i:4;--len:.54"/>
        <circle class="lane c3" cx="600" cy="800" r="330" pathLength="1" transform="rotate(90 600 800)" style="--i:5;--len:.54"/>
        <circle class="lane c1" cx="600" cy="800" r="378" pathLength="1" transform="rotate(90 600 800)" style="--i:6;--len:.54"/>
        <circle class="lane c2" cx="600" cy="800" r="426" pathLength="1" transform="rotate(90 600 800)" style="--i:7;--len:.54"/>
        <circle class="lane c3" cx="600" cy="800" r="474" pathLength="1" transform="rotate(90 600 800)" style="--i:8;--len:.54"/>
      </g>
    </svg>

    <div class="brand">
      <p class="name">دربني</p>
      <p class="tagline">مدرّبك الذكي متى ما تبي</p>
    </div>
  </aside>

  <script src="profile.js"></script>
</body>
</html>