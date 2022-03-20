// client-side validation for email input
//const email = document.querySelector("#login-email");
const email_pattern = /^[\w._%+-]+\@{1}[\w.-]+\.[a-z]{2,4}$/;

// email.addEventListener("keyup", (e) => {
//     const content = email.value;
//     console.log(content);
//     if (content != null) {
//         if (content.match(email_pattern))
//             e.target.classList.remove("invalid");
//         else
//             e.target.classList.add("invalid");
//     }
// });

function check_input(passed) {
    const email = passed.children[1];
    const password = passed.children[3];

    if (email.value.match(email_pattern)) {
        email.classList.remove("invalid");
        return true;
    }
    else {
        email.classList.add("invalid");
        return false;
    }
}

// show login form by default
const all_form = document.querySelectorAll("fieldset");
// const all_link = document.querySelectorAll(".main a");

// all_form.forEach((form) => {
//     form.style.display = "none";
// });
// document.querySelector("#login-form").style.display = "block";
// document.querySelector("#login-link").style.display = "none";

// // show selected form and hide others
// function show_form(target) {
//     const form_to_show = document.querySelector(`#${target}-form`);
//     form_to_show.style.display = "block";

//     all_form.forEach((form) => {
//         if (form.id != `${target}-form`) {
//             form.style.display = "none";
//         }
//     });

//     all_link.forEach((link) => {
//         link.style.display = "block";
//     });

//     const link_to_hide = document.querySelector(`#${target}-link`);
//     link_to_hide.style.display = "none";
// }

