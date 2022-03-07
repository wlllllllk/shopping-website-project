// This is use to hide the webpage content before it is fully loaded
document.onreadystatechange = () => {
    if (document.readyState !== "complete") {
        document.querySelector("body").style.visibility = "hidden";
        document.querySelector("#loading").style.visibility = "visible";
    } else {
        document.querySelector("body").style.visibility = "visible";
        document.querySelector("#loading").style.visibility = "hidden";
    }
}
