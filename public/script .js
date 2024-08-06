document.addEventListener("DOMContentLoaded", () => {
    // Handle registration form submission
    const registerForm = document.getElementById("registerForm");
    if (registerForm) {
      registerForm.addEventListener("submit", function(event) {
        event.preventDefault();
  
        // Collect form data
        const formData = new FormData(this);
  
        // Send form data to register.php using fetch API
        fetch('/register.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.text())
        .then(data => {
          // Display server response in registerMessage div
          document.getElementById("registerMessage").textContent = data;
        })
        .catch(error => console.error('Error:', error));
      });
    }
  
    // Handle login form submission
    const loginForm = document.getElementById("loginForm");
    if (loginForm) {
      loginForm.addEventListener("submit", function(event) {
        event.preventDefault();
  
        // Collect form data
        const formData = new FormData(this);
  
        // Send form data to login.php using fetch API
        fetch('/login.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.text())
        .then(data => {
          // Display server response in loginMessage div
          document.getElementById("loginMessage").textContent = data;
        })
        .catch(error => console.error('Error:', error));
      });
    }
  
    // Handle add book form submission
    const addBookForm = document.getElementById("addBookForm");
    if (addBookForm) {
      addBookForm.addEventListener("submit", function(event) {
        event.preventDefault();
  
        // Collect form data
        const formData = new FormData(this);
  
        // Send form data to add_book.php using fetch API
        fetch('/add_book.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.text())
        .then(data => {
          // Display server response in addBookMessage div
          document.getElementById("addBookMessage").textContent = data;
        })
        .catch(error => console.error('Error:', error));
      });
    }
  });