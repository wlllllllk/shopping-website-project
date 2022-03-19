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

    if (email.value.match(email_pattern))
        email.classList.remove("invalid");
    else
        email.classList.add("invalid");

    return false;
}


