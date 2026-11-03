const form = document.getElementById("registerForm");
const username = document.getElementById("username");
const email = document.getElementById("email");
const password = document.getElementById("password");
const confirmPassword = document.getElementById("confirm_password");
const error = document.getElementById("error");

// أزرار إظهار / إخفاء كلمة المرور (واحد لكل حقل)
document.querySelectorAll(".toggle").forEach((btn) => {
  btn.addEventListener("click", () => {
    const input = document.getElementById(btn.dataset.target);
    const show = input.type === "password";
    input.type = show ? "text" : "password";
    btn.textContent = show ? "إخفاء" : "إظهار";
    btn.setAttribute("aria-pressed", String(show));
  });
});

// تحقق بسيط قبل الإرسال
form.addEventListener("submit", (e) => {
  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  // كل فحص: [الحقل، هل فيه خطأ؟، رسالة الخطأ]
  const checks = [
    [username, username.value.trim().length < 3, "اسم المستخدم لازم يكون 3 أحرف على الأقل."],
    [email, !emailPattern.test(email.value.trim()), "اكتب بريد إلكتروني صحيح."],
    [password, password.value.length < 8, "كلمة المرور لازم تكون 8 أحرف على الأقل."],
    [confirmPassword, confirmPassword.value !== password.value, "كلمتا المرور غير متطابقتين."],
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