
document.addEventListener("DOMContentLoaded", function() {
    const loginForm = document.querySelector('form');
    loginForm.addEventListener('submit', function(e) {
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;

        if (username === '' || password === '') {
            e.preventDefault();
            alert("Please fill in all fields.");
        }
    });
});