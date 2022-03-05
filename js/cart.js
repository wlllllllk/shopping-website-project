// // e.g to call, myLib.ajax({url:'process.php?q=hello',success:function(m){alert(m)}});
// myLib.ajax = function (opt) {
//     opt = opt || {};
//     var xhr = new XMLHttpRequest(),
//         async = opt.async || true,
//         success = opt.success || null, error = opt.error || function () {/*displayErr()*/ };
//     // pass three parameters, otherwise the default ones, to xhr.open()
//     xhr.open(opt.method || 'GET', opt.url || '', async);
//     if (opt.method == 'POST')
//         xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

//     // Asyhronous Call requires a callback function listening on readystatechange
//     if (async)
//         xhr.onreadystatechange = function () {
//             if (xhr.readyState == 4) {
//                 var status = xhr.status;
//                 if ((status >= 200 && status < 300) || status == 304 || status == 1223)
//                     success && success.call(xhr, xhr.responseText);
//                 else if (status < 200 || status >= 400)
//                     error.call(xhr);
//             }
//         };

//     xhr.onerror = function () { error.call(xhr) };

//     // POST parameters encoded as opt.data is passed here to xhr.send()
//     xhr.send(opt.data || null);

//     // Synchronous Call blocks UI and returns result immediately after xhr.send()
//     !async && success && success.call(xhr, xhr.responseText);
// };

// myLib.formData = function (form) {
//     // private variable for storing parameters
//     this.data = [];
//     for (var i = 0, j = 0, name, el, els = form.elements; el = els[i]; i++) {
//         // skip those useless elements
//         if (el.disabled || el.name == ''
//             || ((el.type == 'radio' || el.type == 'checkbox') && !el.checked))
//             continue;
//         // add those useful to the data array
//         this.append(el.name, el.value);
//     }
// };

// // public methods of myLib.formData
// myLib.formData.prototype = {
//     // output the required final POST parameters, e.g. a=1&b=2&c=3
//     toString: function () {
//         return this.data.join('&');
//     },

//     // encode the data with the built-in function encodeURIComponent
//     append: function (key, val) {
//         this.data.push(encodeURIComponent(key) + '=' + encodeURIComponent(val));
//     }
// };

// myLib.submitOverAJAX = function (form, opt) {
//     var formData = new myLib.formData(form);
//     formData.append('rnd', new Date().getTime());
//     opt = opt || {};
//     opt.url = opt.url || form.getAttribute('action');
//     opt.method = opt.method || 'POST';
//     opt.data = formData.toString();
//     opt.success = opt.success || function (msg) { alert(msg) };
//     myLib.ajax(opt);
// };

// function el(A) { return document.getElementById(A) };

// var loginForm = el('loginForm');
// loginForm.onsubmit = function () {
//     // submit the form over AJAX if it is properly validated
//     myLib.validate(this) && myLib.submitOverAJAX(this, {
//         success: function (msg) {
//             el('result').innerHTML = 'Echo from Server: $_POST = ' + msg.escapeHTML();
//         }
//     });

//     // always return false to cancel the default submission
//     return false;
// }

// // e.g to call, myLib.ajax({url:'process.php?q=hello',success:function(m){alert(m)}});
// $.ajax = function (opt) {
//     opt = opt || {};
//     var xhr = new XMLHttpRequest(),
//         async = opt.async || true,
//         success = opt.success || null, error = opt.error || function () {/*displayErr()*/ };

//     // pass three parameters, otherwise the default ones, to xhr.open()
//     xhr.open(opt.method || 'GET', opt.url || '', async);
//     if (opt.method == 'POST')
//         xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

//     // Asynchronous Call requires a callback function listening on readystatechange
//     if (async)
//         xhr.onreadystatechange = function () {
//             if (xhr.readyState == 4) {
//                 var status = xhr.status;
//                 if ((status >= 200 && status < 300) || status == 304 || status == 1223)
//                     success && success.call(xhr, xhr.responseText);
//                 else if (status < 200 || status >= 400)
//                     error.call(xhr);
//             }
//         };

//     xhr.onerror = function () { error.call(xhr) };

//     // POST parameters encoded as opt.data is passed here to xhr.send()
//     xhr.send(opt.data || null);

//     // Synchronous Call blocks UI and returns result immediately after xhr.send()
//     !async && success && success.call(xhr, xhr.responseText);
// };

// $.submitOverAJAX = function (form, opt) {
//     opt = opt || {};
//     opt.url = opt.url || form.getAttribute('action');
//     opt.method = opt.method || 'POST';
//     opt.data = formData.toString();
//     opt.success = opt.success || function (msg) { alert(msg) };
//     $.ajax(opt);
// };

function addToCart(e) {
    let exist = false;

    // get  the product ID
    let productID = e.children[1].value;
    // console.log(productID);

    // localStorage.setItem(productID, Number(0));

    // get its current quantity from local storage
    let currentQuantity = localStorage.getItem(productID) * 1;
    // console.log(currentQuantity);

    // check if the product has been added to the cart
    // and update the quantity correspondingly
    if (currentQuantity == null || currentQuantity == 0 || currentQuantity == "") {
        localStorage.setItem(productID, Number(1));
        exist = false;
    }
    else {
        localStorage.setItem(productID, Number(currentQuantity + 1));
        exist = true;
    }

    // localStorage.removeItem(productID);

    // update the shopping list in the HTML
    updateCart("update", productID);

    return false;
}

const template = document.querySelector("#cart-item-template");

// load the shopping list when page is first loaded
updateCart("load", -1);

function updateCart(mode, pid) {
    let currentCart = document.querySelector(".shopping-list ul");

    let priceDisplay = document.querySelector(".shopping-list .bottom .price");
    let quantityDisplay = document.querySelector(".shopping-list button");

    // page first loaded
    if (mode == "load") {
        let totalPrice = 0;
        let totalQuantity = 0;

        currentCart.innerHTML = "";

        for (let i = 0; i < localStorage.length; i++) {
            let pid = localStorage.key(i);
            let quantity = Number(localStorage.getItem(localStorage.key(i)));
            totalQuantity += quantity;

            let name = "Not Available";
            let price = 0;
            let image = "";

            let xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    // get the name, price, and thumbnail
                    name = JSON.parse(this.responseText).NAME;
                    price = JSON.parse(this.responseText).PRICE;
                    image = JSON.parse(this.responseText).THUMBNAIL;

                    // fill the content
                    const content = template.content.cloneNode(true);
                    content.querySelector("li").id = `P${pid}`;
                    content.querySelector("a").href = `product.php?pid=${pid}`;
                    content.querySelector("img").src = image;
                    content.querySelector(".name").innerHTML = name;
                    content.querySelector("input").value = quantity;
                    content.querySelector("input").title = pid;
                    content.querySelector("input").addEventListener("change", (e) => { validateQuantity(e); });
                    content.querySelector(".price").innerHTML = price;
                    content.querySelector(".delete").setAttribute("data-pid", pid);
                    content.querySelector(".delete").addEventListener("click", () => { removeProduct(pid) });

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
                    else
                        document.querySelector("#nothing").style.display = "block";
                }
            };
            xmlhttp.open("GET", "cart-process.php?pid=" + pid, true);
            xmlhttp.send();
        }
    }

    // update the cart only
    else if (mode == "update") {
        // get the current total quantity
        let totalQuantity = 0, totalPrice = 0;
        let allItem = document.querySelectorAll(".shopping-list ul li .text div");
        allItem.forEach(item => {
            let quantity = Number(item.children[0].value);

            totalQuantity += quantity;
        });

        let cartItem = document.querySelector(`#P${pid}`);

        // add new item
        if (cartItem == null) {
            let quantity = Number(localStorage.getItem(pid));

            let name = "Not Available";
            let price = 0;
            let image = "";

            let xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    // get the name, price, and thumbnail
                    name = JSON.parse(this.responseText).NAME;
                    price = JSON.parse(this.responseText).PRICE;
                    image = JSON.parse(this.responseText).THUMBNAIL;

                    // fill the content
                    const content = template.content.cloneNode(true);
                    content.querySelector("li").id = `P${pid}`;
                    content.querySelector("a").href = `product.php?pid=${pid}`;
                    content.querySelector("img").src = image;
                    content.querySelector(".name").innerHTML = name;
                    content.querySelector("input").value = quantity;
                    content.querySelector("input").title = pid;
                    content.querySelector("input").addEventListener("change", (e) => { validateQuantity(e); })
                    content.querySelector(".price").innerHTML = price;
                    content.querySelector(".delete").setAttribute("data-pid", pid);
                    content.querySelector(".delete").addEventListener("click", () => { removeProduct(pid) });

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
                    else
                        document.querySelector("#nothing").style.display = "block";
                }
            };
            xmlhttp.open("GET", "cart-process.php?pid=" + pid, true);
            xmlhttp.send();
        }

        // update old item
        else {
            // first update the quantity
            document.querySelector(`#P${pid} input`).value = Number(localStorage.getItem(pid));

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
            else
                document.querySelector("#nothing").style.display = "block";
        }
    }
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
        else
            document.querySelector("#nothing").style.display = "block";
    }
}

function removeProduct(pid) {
    if (confirm("Are you sure you want to remove this product from cart?")) {
        localStorage.removeItem(pid);
        updateCart("remove", pid);
    }
}

const quantity_pattern = /^[1-9]+[0-9]*$/;
function validateQuantity(e) {
    const input = e.target.value;
    const pid = e.target.title;

    if (input.match(quantity_pattern)) {
        e.target.classList.remove("invalid");
        localStorage.setItem(pid, input);
        updateCart("update", pid);
    }
    else {
        e.target.classList.add("invalid");
    }
}
