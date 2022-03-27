// This is used to populate the products in main page
String.prototype.escapeHTML = function () {
    return this.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}

String.prototype.escapeQuotes = function () {
    return this.replace(/"/g, '&quot;').replace(/'/g, '&#39;');
}

// the template
const productTemplate = document.querySelector("#product-template");

// fetch page 1 when the webpage is first loaded
fetchPage(1);

function fetchPage(page) {
    let currentProductList = document.querySelector(".product-list");
    let numPerPage = 12;
    let name = "Not Available";
    let price = 0;
    let image = "";

    let request = new XMLHttpRequest();
    request.onreadystatechange = function () {

        // the request is done
        if (this.readyState == 4) {

            // the request is success
            if (this.status == 200) {
                currentProductList.innerHTML = "";

                // the number of products to be displayed
                const length = JSON.parse(this.responseText).length;

                for (let i = 0; i < length; i++) {
                    // get the name, price, and thumbnail
                    name = JSON.parse(this.responseText)[i].NAME;
                    price = JSON.parse(this.responseText)[i].PRICE;
                    image = JSON.parse(this.responseText)[i].THUMBNAIL;
                    pid = JSON.parse(this.responseText)[i].PID;

                    // fill the content
                    const product = productTemplate.content.cloneNode(true);
                    product.querySelector("a").href = `product.php?pid=${encodeURIComponent(pid)}`;
                    product.querySelector("img").src = image.escapeQuotes();
                    product.querySelector(".text a").href = `product.php?pid=${encodeURIComponent(pid)}`;
                    product.querySelector(".name").innerHTML = name.escapeHTML();
                    product.querySelector(".price").innerHTML = price.escapeHTML();
                    product.querySelector("input").value = pid.escapeQuotes();

                    // append to current HTML
                    currentProductList.appendChild(product);
                }
            }

            // the request is failed for some reason
            else {
                currentProductList.innerHTML = "Error fetching products, try refreshing the page :(";
            }
        }
    };
    request.open("GET", "product-fetch.php?page=" + page + "&num=" + numPerPage, true);
    request.send();

    // indicate the page that is currently visiting
    let allPage = document.querySelectorAll(".pages span");
    allPage.forEach(span => {
        if (span.id == page) {
            span.classList.add("visiting");
        } else {
            span.classList.remove("visiting");
        }
    });
}

