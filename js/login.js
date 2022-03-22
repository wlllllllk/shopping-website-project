// client-side validation


// this function checks the input of the submitted form
const email_pattern = /^[\w._%+-]+[a-zA-Z\d]+\@{1}[\w.-]+\.[a-z]{2,8}$/;
function check_input(passed) {
    let valid = true;

    // get all input of the submitted form
    let all_input = [];
    for (let i = 0; i < passed.children.length; i++) {
        if (passed.children[i].localName == "input") {
            all_input.push(passed.children[i]);
        }
    }

    // check the value of each input
    all_input.forEach((input) => {

        // special requirements for email
        if (input.type == "email") {
            if (input.value.match(email_pattern)) {
                input.classList.remove("invalid");
            }
            else {
                input.classList.add("invalid");
                valid = false;

                // start checking user input on keypress if user entered unacceptable input
                input.addEventListener('keyup', (e) => {
                    if (e.target.value.match(email_pattern)) {
                        e.target.classList.remove("invalid");
                    }
                    else {
                        e.target.classList.add("invalid");
                    }
                });
            }
        }

        // no special requirements for password as long as it is not null
        else if (input.type == "password") {
            if (input.value == "" || input.value == null) {
                input.classList.add("invalid");
                valid = false;

                // start checking user input on keypress if user entered unacceptable input
                input.addEventListener('keyup', (e) => {
                    if (e.target.value == "" || e.target.value == null) {
                        e.target.classList.add("invalid");
                    }
                    else {
                        e.target.classList.remove("invalid");
                    }
                });
            }
            else {
                input.classList.remove("invalid");
            }
        }
    });

    return valid;
}


// hide the register form by default
const all_form = document.querySelectorAll("fieldset");
try {
    const register_form = document.querySelector("#register-form");
    const login_link = document.querySelector("#login-link");
    register_form.style.display = "none";
    login_link.style.display = "none";
}
catch {
    // error will be caught if user have logged in
    // console.log("Form does not exist"); 
}


// show the selected form and hide the others
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

