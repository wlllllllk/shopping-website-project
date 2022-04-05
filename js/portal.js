const button = document.querySelector('#password-change-button');
const form = document.querySelector('#password-change-form');

button.addEventListener('click', () => {
    if (form.style.display == "none") {
        form.style.display = "block";
    } else {
        form.style.display = "none";
    }
});