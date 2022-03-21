// client-side validation for email input
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
    let valid = true;
    console.log(passed);

    let all_input = [];

    for (let i = 0; i < passed.children.length; i++) {
        if (passed.children[i].localName == "input") {
            all_input.push(passed.children[i]);
        }
    }

    console.log(all_input);

    all_input.forEach((input) => {
        if (input.value == "" || input.value == null) {
            input.classList.add("invalid");
            valid = false;
        }

        if (input.type == "email") {
            if (input.value.match(email_pattern)) {
                input.classList.remove("invalid");
            }
            else {
                input.classList.add("invalid");
                valid = false;
            }
        }
    });

    // const email = passed.children[1];
    // const password = passed.children[3];

    // if (email.value.match(email_pattern)) {
    //     email.classList.remove("invalid");
    //     return true;
    // }
    // else {
    //     email.classList.add("invalid");
    //     return false;
    // }

    return valid;
}

// show login form by default
const all_form = document.querySelectorAll("fieldset");
try {
    const register_form = document.querySelector("#register-form");
    const login_link = document.querySelector("#login-link");
    register_form.style.display = "none";
    login_link.style.display = "none";
}
catch {
    //console.log("Form does not exist"); 
}

// show selected form and hide others
function show_form(target) {
    const all_link = document.querySelectorAll(".main a");
    const form_to_show = document.querySelector(`#${target}-form`);
    form_to_show.style.display = "block";

    all_form.forEach((form) => {
        if (form.id != `${target}-form`) {
            form.style.display = "none";
        }
    });

    all_link.forEach((link) => {
        link.style.display = "block";
    });

    const link_to_hide = document.querySelector(`#${target}-link`);
    link_to_hide.style.display = "none";
}

