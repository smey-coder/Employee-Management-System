// Select the forms
const loginForm = document.getElementById('login-form');
const registerForm = document.getElementById('register-form');

// Function to show the selected form
function showForm(formId) {
    if (formId === 'login-form') {
        loginForm.classList.add('active');
        registerForm.classList.remove('active');
    } else if (formId === 'register-form') {
        registerForm.classList.add('active');
        loginForm.classList.remove('active');
    }
}

// Optional: remember which form was last opened (for smoother UX)
document.addEventListener("DOMContentLoaded", () => {
    const savedForm = localStorage.getItem('activeForm');
    if (savedForm) {
        showForm(savedForm);
    }
});

// Store which form is active when switching
function showForm(formId) {
    loginForm.classList.toggle('active', formId === 'login-form');
    registerForm.classList.toggle('active', formId === 'register-form');
    localStorage.setItem('activeForm', formId);
}

