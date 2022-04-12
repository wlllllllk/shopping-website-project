let lastID, ref, error;
const paypalButton = document.querySelector("#paypal-button-container");
paypalButton.addEventListener("click", () => {
    console.log("HI");
    return false;
});

function fetchOrder(products) {
    return new Promise(resolve => {
        let request = new XMLHttpRequest();

        // what to do after sending the request
        request.onreadystatechange = function () {

            // if the request is done
            if (this.readyState == 4) {

                // if the request is success
                if (this.status == 200) {
                    // console.log(this.responseText);
                    let response = JSON.parse(this.responseText);
                    return resolve(response);
                }
            }
        };

        // use GET method
        request.open("GET", "payment.php?data=" + encodeURIComponent(JSON.stringify(products)), true);

        // send the request
        request.send();
    });
}

function updateOrder(status, order) {
    return new Promise(resolve => {
        let request = new XMLHttpRequest();

        // what to do after sending the request
        request.onreadystatechange = function () {

            // if the request is done
            if (this.readyState == 4) {

                // if the request is success
                if (this.status == 200) {
                    // console.log(this.responseText);
                    let response = JSON.parse(this.responseText);
                    return resolve(response);
                }
            }
        };

        // use GET method
        request.open("GET", "payment.php?status=" + encodeURIComponent(status) + "&order=" + encodeURIComponent(order), true);

        // send the request
        request.send();
    });
}

paypal.Buttons({
    // Sets up the transaction when a payment button is clicked
    createOrder: async (data, actions) => {
        let products = [];
        for (let i = 0; i < localStorage.length; i++) {
            if (!isNaN(localStorage.key(i))) {
                products.push({ "pid": localStorage.key(i), "quantity": localStorage.getItem(localStorage.key(i)) });
            }
        }

        if (products.length == 0) {
            var daddy = window.self;
            daddy.opener = window.self;
            daddy.close();
            // alert("There is no products in the shopping list!");
            error = 1;
            return false;
        }
        else {
            let order_details = await fetchOrder(products);
            lastID = order_details[1]['id'];
            ref = order_details[1]['ref'];

            return actions.order.create(order_details[0]);
        }
    },

    // Finalize the transaction after payer approval
    onApprove: (data, actions) => {
        return actions.order.capture().then(function (orderData) {
            // clear local storage
            localStorage.clear();

            // reset the cart
            document.querySelector(".shopping-list ul").innerHTML = "";
            document.querySelector(".shopping-list .bottom .price").innerHTML = `Total: $0`;
            document.querySelector(".shopping-list button").innerHTML = `Shopping List (0)`;

            // display the cart empty message
            document.querySelector("#nothing").style.display = "block";

            if (orderData.status == "COMPLETED")
                actions.redirect('https://secure.s67.ierg4210.ie.cuhk.edu.hk/result.php?status=1&ref=' + encodeURIComponent(ref));
            else
                actions.redirect('https://secure.s67.ierg4210.ie.cuhk.edu.hk/result.php?status=2&ref=' + encodeURIComponent(ref));
        });
    },

    onCancel: async function (data) {
        let response = await updateOrder("CANCELLED", lastID);
        // console.log(response);

        window.location.href = "https://secure.s67.ierg4210.ie.cuhk.edu.hk/result.php?status=3&ref=" + encodeURIComponent(ref);
    },

    onError: async function (err) {
        if (error == 1) {
            window.location.href = "https://secure.s67.ierg4210.ie.cuhk.edu.hk/result.php?status=4";
        }
        else {
            let response = await updateOrder("ERROR", lastID);
            // console.log(response);

            window.location.href = "https://secure.s67.ierg4210.ie.cuhk.edu.hk/result.php?status=2&ref=" + encodeURIComponent(ref);
        }

    },

    style: {
        layout: 'horizontal',
        color: 'black',
        label: 'pay',
        tagline: 'false'
    }
}).render('#paypal-button-container');
