let lastID;

function fetchOrder() {
    return new Promise(resolve => {
        let products = [];
        for (let i = 0; i < localStorage.length; i++) {
            if (!isNaN(localStorage.key(i))) {
                products.push({ "pid": localStorage.key(i), "quantity": localStorage.getItem(localStorage.key(i)) });
            }
        }

        if (products.length == 0) {
            alert("There is no products in the shopping list!");
            return false;
        }

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

paypal.Buttons({
    // Sets up the transaction when a payment button is clicked
    createOrder: async (data, actions) => {
        let order_details = await fetchOrder();
        lastID = order_details[1]['id'];
        // console.log(order_details);
        // console.log(lastID);

        return actions.order.create(order_details[0]);
    },

    // Finalize the transaction after payer approval
    onApprove: async (data, actions) => {
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
                actions.redirect('https://secure.s67.ierg4210.ie.cuhk.edu.hk/result.php?status=1');
            else
                actions.redirect('https://secure.s67.ierg4210.ie.cuhk.edu.hk/result.php?status=2');
        });
    },

    style: {
        layout: 'horizontal',
        color: 'black',
        label: 'pay',
        tagline: 'false'
    }
}).render('#paypal-button-container');
