// client-side validation for textarea (pattern attribute not available in HTML)
const all_textarea = document.querySelectorAll("textarea");
const textarea_pattern = /^[\w\r\n\-\.\,\'\"\(\)\?\&\%\!\:\/\*\+\; ]+$/;

all_textarea.forEach((textarea) => {
    textarea.addEventListener("keyup", (e) => {
        const content = textarea.value;
        if (content.match(textarea_pattern))
            e.target.classList.remove("invalid");
        else
            e.target.classList.add("invalid");
    });
});


// for the drag-and-drop area
const forms = document.querySelectorAll(".form-with-image");
forms.forEach((form) => {
    let image_upload_field;
    for (let i = 0; i < form.children.length; i++) {
        if (form.children[i].classList.contains("image-upload-field")) {
            image_upload_field = form.children[i];
            break;
        }
    }

    image_upload_field.addEventListener('dragover', () => {
        image_upload_field.classList.add("drag-over");
    });

    image_upload_field.addEventListener('dragleave', () => {
        image_upload_field.classList.remove("drag-over");
    });

    const image_preview = image_upload_field.children[0];
    const thumbnail = image_preview.children[0];
    const thumbnail_name = image_preview.children[1];

    const real_image_upload_field = image_upload_field.children[1];

    form.addEventListener("reset", () => {
        image_upload_field.classList.remove("drop");
        image_preview.style.display = "none";
    });

    real_image_upload_field.addEventListener("change", () => {
        image_upload_field.classList.add("drop");
        image_upload_field.classList.remove("drag-over");

        image_preview.style.display = "flex";

        let files = real_image_upload_field.files;

        if (files[0].type != "image/jpeg" && files[0].type != "image/png" && files[0].type != "image/gif") {
            thumbnail.style.display = "none";
            thumbnail_name.innerHTML = "Invalid File Format";
            real_image_upload_field.value = null;
            return 0;
        }

        if (files[0].size > 10000000) {
            thumbnail.style.display = "none";
            thumbnail_name.innerHTML = "File Size Too Large";
            real_image_upload_field.value = null;
            return 0;
        }

        let reader = new FileReader();
        reader.readAsDataURL(files[0]);
        reader.onload = (e) => {
            thumbnail.src = e.target.result;
            thumbnail.style.display = "block";
            thumbnail_name.innerHTML = files[0].name;
        };
    });
});
