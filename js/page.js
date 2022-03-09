// This is used to get the total number of products and populate the page link

// the link container
const pageContainer = document.querySelector(".pages");
// the template
const pageTemplate = document.querySelector("#page-template");

let totalProduct = 0;
let productPerPage = 12;

let request = new XMLHttpRequest();
request.onreadystatechange = function () {

    // the request is done
    if (this.readyState == 4) {

        // the request is success
        if (this.status == 200) {

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

        // the request is failed for some reason
        else {
            pageContainer.innerHTML = "Error fetching page links, try refreshing the page :(";
        }
    }
};
request.open("GET", "page.php", true);
request.send();



