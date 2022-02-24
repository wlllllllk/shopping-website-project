const image_upload_field = document.querySelector(".image-upload-field");

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

image_upload_field.addEventListener('drop', () => {
    // console.log("DROP");
    // image_upload_field.classList.remove("drag-enter");
    image_upload_field.classList.add("drop");
    image_upload_field.classList.remove("drag-over");
});