document.addEventListener("DOMContentLoaded", function () {
  const forms = document.querySelectorAll("form");

  forms.forEach((form) => {
    form.addEventListener("submit", function (event) {
      const username = form.querySelector('input[name="username"]').value;
      const password = form.querySelector('input[name="password"]').value;

      if (username === "" || password === "") {
        event.preventDefault();
        alert("Please fill in all fields.");
      }
    });
  });
});
