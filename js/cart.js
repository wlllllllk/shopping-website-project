String.prototype.escapeHTML = function () {
    return this.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}

String.prototype.escapeQuotes = function () {
    return this.replace(/"/g, '&quot;').replace(/'/g, '&#39;');
}

// set up the "Clear ALL" button
document.querySelector("#clear").addEventListener("click", () => {
    // if (window.confirm("Are you sure you want to clear ALL cart items? This action cannot be undone.")) {
    // clear the local storage
    localStorage.clear();

    // reset the cart
    document.querySelector(".shopping-list ul").innerHTML = "";
    document.querySelector(".shopping-list .bottom .price").innerHTML = `Total: $0`;
    document.querySelector(".shopping-list button").innerHTML = `Shopping List (0)`;

    // display the cart empty message
    document.querySelector("#nothing").style.display = "block";
    // }
});

// event handler for when "Add to Cart" button is pressed
function addToCart(e) {

    // temporarily disable the button to prevent multiple records in cart
    e.children[0].disabled = true;
    e.children[0].classList.add("adding");

    // get  the product ID
    let productID = e.children[1].value;

    // get its current quantity from local storage
    let currentQuantity = Number(localStorage.getItem(productID));

    // check if the product has been added to the cart
    // and update the quantity correspondingly
    if (currentQuantity == null || currentQuantity == 0 || currentQuantity == "")
        localStorage.setItem(productID, Number(1));
    else
        localStorage.setItem(productID, Number(currentQuantity + 1));

    // update the shopping list in the HTML
    updateCart("update", productID, e.children[0]);

    // prevent page reload
    return false;
}



// template of cart item
const template = document.querySelector("#cart-item-template");

// update items in cart
function updateCart(mode, pid, source) {

    // list that stores all item
    let currentCart = document.querySelector(".shopping-list ul");

    // display for total price and quantity
    let priceDisplay = document.querySelector(".shopping-list .bottom .price");
    let quantityDisplay = document.querySelector(".shopping-list button");

    // when page first loaded
    if (mode == "load") {
        let totalPrice = 0;
        let totalQuantity = 0;

        // first empty the HTML content
        currentCart.innerHTML = "";

        // then fetch the item one-by-one based on the records in local storage
        for (let i = 0; i < localStorage.length; i++) {
            let pid = localStorage.key(i);

            let name = "Not Available";
            let price = 0;
            let image = "";

            let request = new XMLHttpRequest();

            // what to do after sending the request
            request.onreadystatechange = function () {

                // if the request is done
                if (this.readyState == 4) {

                    // if the request is success
                    if (this.status == 200) {

                        // get the name, price, and thumbnail
                        name = JSON.parse(this.responseText).NAME;
                        price = JSON.parse(this.responseText).PRICE;
                        image = JSON.parse(this.responseText).THUMBNAIL;

                        // if product is removed from database
                        if (name == undefined) {
                            localStorage.removeItem(pid);
                        }
                        else {
                            let quantity = Number(localStorage.getItem(localStorage.key(i)));
                            totalQuantity += quantity;

                            // fill the content
                            const content = template.content.cloneNode(true);
                            content.querySelector("li").id = `P${pid.escapeQuotes()}`;
                            content.querySelector("a").href = `product.php?pid=${encodeURIComponent(pid)}`;
                            content.querySelector("img").src = image.escapeQuotes();
                            content.querySelector(".name").innerHTML = name.escapeHTML();
                            content.querySelector("input").value = quantity;
                            content.querySelector("input").setAttribute("data-pid", pid.escapeQuotes())
                            content.querySelector("input").addEventListener("change", (e) => { validateQuantity(e); });
                            content.querySelector(".price").innerHTML = price.escapeHTML();
                            content.querySelector(".delete").setAttribute("data-pid", pid.escapeQuotes());
                            content.querySelector(".delete").addEventListener("click", () => { removeProduct(pid); });

                            // append to current HTML
                            currentCart.appendChild(content);


                            // update the total price
                            totalPrice += (price * quantity);

                            // show the updated total price
                            priceDisplay.innerHTML = `Total: $${totalPrice.toFixed(1)}`;

                            // show the updated total quantity
                            quantityDisplay.innerHTML = `Shopping List (${totalQuantity})`;

                            // display cart empty message depending on total quantity
                            if (totalQuantity > 0)
                                document.querySelector("#nothing").style.display = "none";
                            else {
                                document.querySelector("#nothing").innerHTML = "There is nothing in the cart :(";
                                document.querySelector("#nothing").style.display = "block";
                            }
                        }

                    }

                    // the request is failed for some reason
                    else {
                        document.querySelector("#nothing").innerHTML = "Error getting cart item, try refreshing the page :(";
                        document.querySelector("#nothing").style.display = "block";
                    }
                }
            };

            // use GET method
            request.open("GET", "cart-process.php?pid=" + encodeURIComponent(pid), true);

            // send the request
            request.send();
        }
    }

    // update the cart only
    else if (mode == "update") {

        // get the current total quantity
        let totalQuantity = 0, totalPrice = 0;

        // get the item that should be updated
        let cartItem = document.querySelector(`#P${pid}`);

        // add new item
        if (cartItem == null) {
            let quantity = Number(localStorage.getItem(pid));

            let name = "Not Available";
            let price = 0;
            let image = "";

            let request = new XMLHttpRequest();

            // what to do after sending the request
            request.onreadystatechange = function () {

                // if the request is done
                if (this.readyState == 4) {

                    // and the request is success
                    if (this.status == 200) {

                        let allItem = document.querySelectorAll(".shopping-list ul li .text div");
                        allItem.forEach(item => {
                            let current_quantity = Number(item.children[0].value);
                            let current_price = Number(item.children[1].innerHTML);

                            totalQuantity += current_quantity;
                            totalPrice += (current_quantity * current_price)
                        });

                        // get the name, price, and thumbnail
                        name = JSON.parse(this.responseText).NAME;
                        price = JSON.parse(this.responseText).PRICE;
                        image = JSON.parse(this.responseText).THUMBNAIL;

                        // fill the content
                        const content = template.content.cloneNode(true);
                        content.querySelector("li").id = `P${pid.escapeQuotes()}`;
                        content.querySelector("a").href = `product.php?pid=${encodeURIComponent(pid)}`;
                        content.querySelector("img").src = image.escapeQuotes();
                        content.querySelector(".name").innerHTML = name.escapeHTML();
                        content.querySelector("input").value = quantity;
                        content.querySelector("input").setAttribute("data-pid", pid.escapeQuotes())
                        content.querySelector("input").addEventListener("change", (e) => { validateQuantity(e); });
                        content.querySelector(".price").innerHTML = price.escapeHTML();
                        content.querySelector(".delete").setAttribute("data-pid", pid.escapeQuotes());
                        content.querySelector(".delete").addEventListener("click", () => { removeProduct(pid); });

                        // append to current HTML
                        currentCart.appendChild(content);

                        // update the total price
                        totalPrice += (price * quantity);

                        // update the total quantity
                        totalQuantity += quantity;

                        // show the updated total price
                        priceDisplay.innerHTML = `Total: $${totalPrice.toFixed(1)}`;

                        // show the updated total quantity
                        quantityDisplay.innerHTML = `Shopping List (${totalQuantity})`;

                        // display cart empty message depending on total quantity
                        if (totalQuantity > 0)
                            document.querySelector("#nothing").style.display = "none";
                        else {
                            document.querySelector("#nothing").innerHTML = "There is nothing in the cart :(";
                            document.querySelector("#nothing").style.display = "block";
                        }
                    }

                    // the request is failed for some reason
                    else {
                        alert("Error adding item to cart, please try again later.");
                    }

                    // re-enable the button
                    source.disabled = false;
                    source.classList.remove("adding");
                }
            };

            // use GET method
            request.open("GET", "cart-process.php?pid=" + pid, true);

            // send the request
            request.send();
        }

        // update old item
        else {
            // first update the quantity
            document.querySelector(`#P${pid} input`).value = Number(localStorage.getItem(pid));

            // then calculate the new total quantity and price
            let allItem = document.querySelectorAll(".shopping-list ul li .text div");
            allItem.forEach(item => {
                let quantity = Number(item.children[0].value);
                let price = Number(item.children[1].innerHTML);

                totalQuantity += quantity;
                totalPrice += (quantity * price)
            });

            // show the updated total price
            priceDisplay.innerHTML = `Total: $${totalPrice.toFixed(1)}`;

            // show the updated total quantity
            quantityDisplay.innerHTML = `Shopping List (${totalQuantity})`;

            // display cart empty message depending on total quantity
            if (totalQuantity > 0)
                document.querySelector("#nothing").style.display = "none";
            else
                document.querySelector("#nothing").style.display = "block";

            // re-enable the button
            source.disabled = false;
            source.classList.remove("adding");
        }
    }

    // remove a item from cart
    else if (mode == "remove") {

        // first remove the item from HTML
        let itemRemove = document.querySelector(`#P${pid}`);
        currentCart.removeChild(itemRemove);

        // then calculate the new total quantity and price
        let totalQuantity = 0, totalPrice = 0;
        let allItem = document.querySelectorAll(".shopping-list ul li .text div");
        allItem.forEach(item => {
            let quantity = Number(item.children[0].value);
            let price = Number(item.children[1].innerHTML);

            totalQuantity += quantity;
            totalPrice += (quantity * price)
        });

        // show the updated total price
        priceDisplay.innerHTML = `Total: $${totalPrice.toFixed(1)}`;

        // show the updated total quantity
        quantityDisplay.innerHTML = `Shopping List (${totalQuantity})`;

        // display cart empty message depending on total quantity
        if (totalQuantity > 0)
            document.querySelector("#nothing").style.display = "none";
        else {
            document.querySelector("#nothing").innerHTML = "There is nothing in the cart :(";
            document.querySelector("#nothing").style.display = "block";
        }
    }
}


// NOTE
// Product deletion is executed DIRECTLY without prompt because 
// user can easily disable browser built-it prompt and think 
// there is something wrong with the site

//// prompt user before removing an item
function removeProduct(pid) {
    // if (window.confirm("Are you sure you want to remove this item from cart? This action cannot be undone.")) {
    localStorage.removeItem(pid);
    updateCart("remove", pid);
    // }
}

// load the shopping list when page is first loaded
updateCart("load", -1, null);

// validate the quantity input
const quantity_pattern = /^[1-9]+[0-9]*$/;
function validateQuantity(e) {
    const input = e.target.value;
    const pid = e.target.dataset.pid;

    //// check if user want to remove the product
    // applicable to number input only
    if (input != "" && input <= 0) {

        // if yes then remove the item
        // if (window.confirm("Are you sure you want to remove this item from cart? This action cannot be undone.")) {
        localStorage.removeItem(pid);
        updateCart("remove", pid);
        // }

        // else reset the value to 1
        // else {
        //     e.target.value = 1;
        //     localStorage.setItem(pid, 1);
        //     updateCart("update", pid, e.target);
        // }

        // need not run the codes below
        return 0;
    }

    // check with regex
    // if ok then update the value in local storage
    if (input.match(quantity_pattern)) {
        e.target.classList.remove("invalid");

        localStorage.setItem(pid, input);
        updateCart("update", pid, e.target);
    }

    // else warn the user
    else {
        e.target.classList.add("invalid");
    }
}
