const form = document.getElementById("profileForm");
const error = document.getElementById("error");

// تحقق بسيط قبل الإرسال
form.addEventListener("submit", (e) => {
  const age = form.elements["age"];
  const height = form.elements["height_cm"];
  const weight = form.elements["weight_kg"];
  const days = form.elements["workout_days"];
  const goal = form.elements["goal"];
  const level = form.elements["level"];
  const place = form.elements["place"];

  // دالة تتأكد أن الرقم داخل المدى المسموح
  const outOfRange = (field, min, max) => {
    const n = Number(field.value);
    return field.value.trim() === "" || isNaN(n) || n < min || n > max;
  };

  // كل فحص: [الحقل، هل فيه خطأ؟، رسالة الخطأ]
  const checks = [
    [age, outOfRange(age, 12, 80), "اكتب عمر صحيح (من 12 إلى 80)."],
    [height, outOfRange(height, 100, 230), "اكتب طول صحيح بالسنتيمتر (من 100 إلى 230)."],
    [weight, outOfRange(weight, 30, 250), "اكتب وزن صحيح بالكيلو (من 30 إلى 250)."],
    [days, days.value === "", "اختر عدد أيام التمرين."],
    [goal, goal.value === "", "اختر هدفك."],
    [level, level.value === "", "اختر مستواك."],
    [place, place.value === "", "اختر مكان التمرين."],
  ];

  // نوقف عند أول خطأ فقط
  const failed = checks.find((check) => check[1]);

  if (failed) {
    e.preventDefault();
    error.textContent = failed[2];
    error.hidden = false;
    failed[0].focus();
  } else {
    error.hidden = true;
  }
});