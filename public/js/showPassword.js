function pushHideButton() {
  const txtPass = document.getElementById("password");
  const btnEye = document.getElementById("buttonEye");
  if (txtPass.type === "text") {
    txtPass.type = "password";
    btnEye.className = "fa fa-eye";
  } else {
    txtPass.type = "text";
    btnEye.className = "fa fa-eye-slash";
  }
}

function pushHideButtonConfirm() {
  const txtPass = document.getElementById("password-confirm");
  const btnEye = document.getElementById("buttonEye");
  if (txtPass.type === "text") {
    txtPass.type = "password";
    btnEye.className = "fa fa-eye";
  } else {
    txtPass.type = "text";
    btnEye.className = "fa fa-eye-slash";
  }
}