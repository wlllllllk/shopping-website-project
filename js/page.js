// get the total number of products and populate the page link
const pageContainer = document.querySelector(".pages");
const pageTemplate = document.querySelector("#page-template");

let totalProduct = 0;

let xmlhttp = new XMLHttpRequest();
xmlhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
        length = Object.values(JSON.parse(this.responseText))[0];

        for (let i = 0; i < Math.ceil(length / 10); i++) {
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
xmlhttp.open("GET", "page.php", true);
xmlhttp.send();



