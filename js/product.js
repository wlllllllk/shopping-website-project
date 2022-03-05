// fill in the products
const productTemplate = document.querySelector("#product-template");

fetchPage(1);

function fetchPage(page) {
    let currentProductList = document.querySelector(".product-list");

    let name = "Not Available";
    let price = 0;
    let image = "";

    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            currentProductList.innerHTML = "";

            const length = JSON.parse(this.responseText).length;

            for (let i = 0; i < length; i++) {
                // get the name, price, and thumbnail
                name = JSON.parse(this.responseText)[i].NAME;
                price = JSON.parse(this.responseText)[i].PRICE;
                image = JSON.parse(this.responseText)[i].THUMBNAIL;
                pid = JSON.parse(this.responseText)[i].PID;

                // fill the content
                const product = productTemplate.content.cloneNode(true);
                product.querySelector("a").href = `product-fill.php?pid=${pid}`;
                product.querySelector("img").src = image;
                product.querySelector(".text a").href = `product-fill.php?pid=${pid}`;
                product.querySelector(".name").innerHTML = name;
                product.querySelector(".price").innerHTML = price;
                product.querySelector("input").value = pid;

                // append to current HTML
                currentProductList.appendChild(product);
            }
        }
    };
    xmlhttp.open("GET", "product-fill.php?page=" + page, true);
    xmlhttp.send();

    let allPage = document.querySelectorAll(".pages span");
    allPage.forEach(span => {
        if (span.id == page) {
            span.classList.add("visiting");
        } else {
            span.classList.remove("visiting");
        }
    });
}