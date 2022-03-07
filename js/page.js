// This is used to get the total number of products and populate the page link

// the link container
const pageContainer = document.querySelector(".pages");
// the template
const pageTemplate = document.querySelector("#page-template");

let totalProduct = 0;
let productPerPage = 12;

let request = new XMLHttpRequest();
request.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {

        // get the returned value (number of total products in the database)
        length = Object.values(JSON.parse(this.responseText))[0];

        // map each page to corresponding button
        for (let i = 0; i < Math.ceil(length / productPerPage); i++) {
            const page = pageTemplate.content.cloneNode(true);
            page.querySelector("span").id = i + 1;
            page.querySelector("span").innerHTML = i + 1;
            page.querySelector("span").addEventListener("click", (e) => { fetchPage(e.target.id); })
            if (i == 0)
                page.querySelector("span").classList.add("visiting");

            pageContainer.appendChild(page);
        }
    }
};
request.open("GET", "page.php", true);
request.send();



