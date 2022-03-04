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

function test(e) {
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
    if (!exist) {
        let currentCart = document.querySelector(".shopping-list ul");

        let li = document.createElement("li");
        li.id = `P${productID}`
        let div_details = document.createElement("div");
        div_details.classList.add("details");
        let a = document.createElement("a");
        a.href = "product.html";
        let div_photo = document.createElement("div");
        div_photo.classList.add("photo");
        let img = document.createElement("img");
        img.src = src = "../images/product.jpg";
        img.alt = "";
        div_photo.appendChild(img);
        a.appendChild(div_photo);

        let div_text = document.createElement("div");
        div_text.classList.add("text");
        let span_name = document.createElement("span");
        span_name.classList.add("name");
        span_name.innerHTML = "123456";
        let div_input = document.createElement("div");
        let input = document.createElement("input");
        input.type = "number";
        input.value = 1;
        let span_price = document.createElement("span");
        span_price.classList.add("price");
        span_price.innerHTML = "$8700";
        let div_delete = document.createElement("div");
        div_delete.classList.add("delete");
        div_delete.innerHTML = "&#10799";
        div_delete.setAttribute("data-pid", productID);
        div_delete.addEventListener("click", () => { removeProduct(productID) });

        div_input.appendChild(input);
        div_input.appendChild(span_price);
        div_text.appendChild(span_name);
        div_text.appendChild(div_input);

        div_details.appendChild(a);
        div_details.appendChild(div_text);
        div_details.appendChild(div_delete);

        li.appendChild(div_details);

        currentCart.appendChild(li);
    }
    else {
        updateCart();
    }
    return false;
}

updateCart();

// load the shopping list when page is first loaded
function updateCart() {
    let totalPrice = 0;
    let totalQuantity = 0;

    let currentCart = document.querySelector(".shopping-list ul");

    // for (let i = 0; i < currentCart.children.length; i++) {
    //     currentCart.removeChild(currentCart.children[i]);
    // }

    currentCart.innerHTML = '';

    for (let i = 0; i < localStorage.length; i++) {
        let pid = localStorage.key(i);
        let price = 123.4;
        let quantity = Number(localStorage.getItem(localStorage.key(i)));
        totalPrice += (price * quantity);
        totalQuantity += quantity;

        let li = document.createElement("li");
        li.id = `P${pid}`
        let div_details = document.createElement("div");
        div_details.classList.add("details");
        let a = document.createElement("a");
        a.href = "product.html";
        let div_photo = document.createElement("div");
        div_photo.classList.add("photo");
        let img = document.createElement("img");
        img.src = src = "../images/product.jpg";
        img.alt = "";
        div_photo.appendChild(img);
        a.appendChild(div_photo);

        let div_text = document.createElement("div");
        div_text.classList.add("text");
        let span_name = document.createElement("span");
        span_name.classList.add("name");
        span_name.innerHTML = "123456";
        let div_input = document.createElement("div");
        let input = document.createElement("input");
        input.type = "number";
        input.value = quantity;
        let span_price = document.createElement("span");
        span_price.classList.add("price");
        span_price.innerHTML = "$123.4";
        let div_delete = document.createElement("div");
        div_delete.classList.add("delete");
        div_delete.innerHTML = "&#10799";
        div_delete.setAttribute("data-pid", pid);
        div_delete.addEventListener("click", () => { removeProduct(pid) });

        div_input.appendChild(input);
        div_input.appendChild(span_price);
        div_text.appendChild(span_name);
        div_text.appendChild(div_input);

        div_details.appendChild(a);
        div_details.appendChild(div_text);
        div_details.appendChild(div_delete);

        li.appendChild(div_details);

        currentCart.appendChild(li);
    }

    // show the total price
    let priceDisplay = document.querySelector(".shopping-list .bottom .price");
    priceDisplay.innerHTML = `Total: $${totalPrice.toFixed(1)}`;

    // show the total quantity
    let quantityDisplay = document.querySelector(".shopping-list button");
    // quantityDisplay.setAttribute("data-quantity", totalQuantity);
    quantityDisplay.innerHTML = `Shopping List (${totalQuantity})`

    if (totalQuantity > 0)
        document.querySelector("#nothing").style.display = "none";
    else
        document.querySelector("#nothing").style.display = "block";

}

function removeProduct(pid) {
    if (confirm("Are you sure you want to remove this product from cart?")) {
        localStorage.removeItem(pid);
        updateCart();
    }
}



