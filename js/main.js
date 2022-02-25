const image_upload_field = document.querySelector(".image-upload-field");
const real_image_upload_field = document.querySelector(".image-upload-field input");

real_image_upload_field.addEventListener('onclick', (e) => {
    console.log("HI");
    // e.preventDefault();
});

image_upload_field.addEventListener('dragenter', () => {
    // console.log("ENTER");
    // image_upload_field.classList.add("drag-enter");
});

image_upload_field.addEventListener('dragleave', () => {
    // console.log("LEAVE");
    // image_upload_field.classList.remove("drag-enter");
    image_upload_field.classList.remove("drag-over");
});

image_upload_field.addEventListener('dragover', () => {
    // console.log("OVER");
    image_upload_field.classList.add("drag-over");
});

let image_preview = document.querySelector(".image-preview");
let thumbnail = document.querySelector(".image-preview img");
let thumbnail_name = document.querySelector(".image-preview div");

image_upload_field.addEventListener('drop', (e) => {
    // console.log("DROP");
    // e.stopPropagation();
    // e.preventDefault();
    // image_upload_field.classList.add("drop");
    // image_upload_field.classList.remove("drag-over");

    // // let testf = e.dataTransfer.files;
    // // console.log("F1.1: ", testf);
    // // console.log("F2.1: ", testf[0]);

    // let files = e.dataTransfer.files;
    // console.log("F1: ", files);
    // console.log("F2: ", files[0]);

    // let reader = new FileReader();
    // reader.readAsDataURL(files[0]);
    // reader.onload = (e) => {
    //     thumbnail.src = e.target.result;
    //     thumbnail_name.innerHTML = files[0].name;
    // };

    // image_preview.style.display = "flex";
});

real_image_upload_field.addEventListener("change", () => {
    image_upload_field.classList.add("drop");
    image_upload_field.classList.remove("drag-over");

    let files = real_image_upload_field.files;

    let reader = new FileReader();
    reader.readAsDataURL(files[0]);
    reader.onload = (e) => {
        thumbnail.src = e.target.result;
        thumbnail_name.innerHTML = files[0].name;
    };

    image_preview.style.display = "flex";
});