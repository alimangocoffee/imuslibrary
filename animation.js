// Smooth scrolling for navigation links
document.querySelectorAll('nav ul li a').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        if (this.getAttribute('href').startsWith('#')) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            target.scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
});

// Button hover animation
const buttons = document.querySelectorAll('.btn');
buttons.forEach(button => {
    button.addEventListener('mouseover', () => {
        button.style.boxShadow = '0 8px 15px rgba(0, 0, 0, 0.2)';
    });
    button.addEventListener('mouseout', () => {
        button.style.boxShadow = 'none';
    });
});


document.addEventListener("DOMContentLoaded", () => {
    const inputs = document.querySelectorAll("input");

    inputs.forEach(input => {
        input.addEventListener("focus", () => {
            input.style.boxShadow = "0 0 8px rgba(74, 78, 105, 0.5)";
        });

        input.addEventListener("blur", () => {
            input.style.boxShadow = "none";
        });
    });
});



