const form = document.querySelector(".form");
const userName = document.querySelector("#name");
const email = document.querySelector("#email");
const password = document.querySelector("#password");
const password2 = document.querySelector("#password_confirmation");

let errors = [];

console.log("hello world");

//check error
const showError = (element, text) => {
  const formInput = element.parentElement;
  formInput.className = "form-control error";
  const label = formInput.querySelector("small");
  label.textContent = text;
};

//check success
const showSuccess = (element) => {
  const formInput = element.parentElement;
  formInput.className = "form-control success";
};

//check email
const checkEmail = (email) => {
  const regex = /^((?!\.)[\w\-_.]*[^.])(@\w+)(\.\w+(\.\w+)?[^.\W])$/;
  if (regex.test(email.value)) {
    showSuccess(email);
    fetch(`validate-email.php?email=${encodeURIComponent(email.value)}`)
      .then((res) => {
        return res.json();
      })
      .then((data) => {
        let usedEmail = data.available;
        if (usedEmail) {
          showError(email, "email already taken");
          errors.push("error");
          return false;
        }
      });
  } else {
    showError(email, `Invalid email please check again`);
    return;
  }
};

//validate fields
const checkFields = (inputElements) => {
  inputElements.forEach((element) => {
    if (element.value === "") {
      showError(element, `${element.id} cannot be empty`);

      errors.push("error");

      return false;
    } else if (element.id === "email") {
      checkEmail(element);
    } else {
      showSuccess(element);
    }
  });
};

//check length
const checkLength = (element, min, max) => {
  if (element.value.length < min) {
    showError(element, `${element.id} cannot be less than ${min} characters`);
    errors.push("error");
    return false;
  } else if (element.value.length > max) {
    showError(element, `${element.id} cannot be longer than ${max} characters`);
    errors.push("error");
    return false;
  }
};

//check match
const checkMatch = (elem1, elem2) => {
  if (elem1.value !== elem2.value) {
    showError(elem2, `passwords do not match try again!`);
    errors.push("error");
    return false;
  }
};

form.addEventListener("submit", (e) => {
  checkFields([userName, email, password, password2]);
  checkLength(password, 8, 12);
  checkMatch(password, password2);

  if (errors.length > 0) {
    e.preventDefault();
    errors = [];
  }
});
