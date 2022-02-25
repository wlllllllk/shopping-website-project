const all_image_upload_field = document.querySelectorAll(".image-upload-field");
const all_real_image_upload_field = document.querySelectorAll(".image-upload-field input");

all_image_upload_field.forEach((image_upload_field) => {
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

    real_image_upload_field.addEventListener("change", () => {
        image_upload_field.classList.add("drop");
        image_upload_field.classList.remove("drag-over");

        image_preview.style.display = "flex";

        let files = real_image_upload_field.files;
        console.log(files[0]);

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

// let image_preview = document.querySelector(".image-preview");
// let thumbnail = document.querySelector(".image-preview img");
// let thumbnail_name = document.querySelector(".image-preview div");

// let i = 0;
// all_real_image_upload_field.forEach((real_image_upload_field) => {
//     console.log(real_image_upload_field);
//     i++;

//     real_image_upload_field.addEventListener("change", () => {
//         image_upload_field.classList.add("drop");
//         image_upload_field.classList.remove("drag-over");

//         image_preview.style.display = "flex";

//         let files = real_image_upload_field.files;
//         console.log(files[0]);

//         if (files[0].type != "image/jpeg" && files[0].type != "image/png" && files[0].type != "image/gif") {
//             thumbnail.style.display = "none";
//             thumbnail_name.innerHTML = "Invalid File Format";
//             real_image_upload_field.value = null;
//             return 0;
//         }

//         if (files[0].size > 10000000) {
//             thumbnail.style.display = "none";
//             thumbnail_name.innerHTML = "File Size Too Large";
//             real_image_upload_field.value = null;
//             return 0;
//         }

//         let reader = new FileReader();
//         reader.readAsDataURL(files[0]);
//         reader.onload = (e) => {
//             thumbnail.src = e.target.result;
//             thumbnail.style.display = "block";
//             thumbnail_name.innerHTML = files[0].name;
//         };
//     });
// });


// image_upload_field.addEventListener('dragenter', () => {
//     // console.log("ENTER");
//     // image_upload_field.classList.add("drag-enter");
// });

// image_upload_field.addEventListener('dragleave', () => {
//     // console.log("LEAVE");
//     // image_upload_field.classList.remove("drag-enter");
//     image_upload_field.classList.remove("drag-over");
// });

// image_upload_field.addEventListener('dragover', () => {
//     // console.log("OVER");
//     image_upload_field.classList.add("drag-over");
// });


// image_upload_field.addEventListener('drop', (e) => {
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
// });
