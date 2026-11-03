const form = document.getElementById("loginForm");
const username = document.getElementById("username");
const password = document.getElementById("password");
const toggle = document.getElementById("toggle");
const error = document.getElementById("error");

// إظهار / إخفاء كلمة المرور
toggle.addEventListener("click", () => {
  const show = password.type === "password";
  password.type = show ? "text" : "password";
  toggle.textContent = show ? "إخفاء" : "إظهار";
  toggle.setAttribute("aria-pressed", String(show));
});

// تحقق بسيط قبل الإرسال
form.addEventListener("submit", (e) => {
  let message = "";

  if (username.value.trim() === "") {
    message = "اكتب اسم المستخدم.";
    username.focus();
  } else if (password.value === "") {
    message = "اكتب كلمة المرور.";
    password.focus();
  }

  if (message) {
    e.preventDefault();
    error.textContent = message;
    error.hidden = false;
  } else {
    error.hidden = true;
  }
});
