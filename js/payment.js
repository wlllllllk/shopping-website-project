paypal.Buttons({
    // Sets up the transaction when a payment button is clicked
    createOrder: (data, actions) => {
        // console.log("CHECKOUT!");
        // console.log(document.querySelector("#cart"));
        // console.log(data);
        // console.log(actions);

        let products = [];
        for (let i = 0; i < localStorage.length; i++) {
            if (!isNaN(localStorage.key(i))) {
                products.push({ "pid": localStorage.key(i), "quantity": localStorage.getItem(localStorage.key(i)) });
            }
        }

        console.log(products);

        let totalPrice = 0;
        // for (let i = 0; i < products.length; i++) {
        let request = new XMLHttpRequest();

        // what to do after sending the request
        request.onreadystatechange = function () {

            // if the request is done
            if (this.readyState == 4) {

                // if the request is success
                if (this.status == 200) {
                    // products[i].price = Number(JSON.parse(this.responseText).PRICE);
                    // console.log(products[i].quantity * products[i].price);
                    // totalPrice += (products[i].quantity * products[i].price);
                    console.log("HI");
                    console.log(JSON.parse(this.responseText));
                }
            }
        };

        // if (!isNaN(products[i].pid)) {
        // use GET method
        // request.open("GET", "payment.php?pid=" + encodeURIComponent(products[i].pid), true);
        request.open("GET", "payment.php?data=" + encodeURIComponent(JSON.stringify(products)), true);

        // send the request
        request.send();
        // }


        return actions.order.create({
            purchase_units: [{
                amount: {
                    // currency_code: 'HKD',
                    value: '14', // Can also reference a variable or function
                    // breakdown: {
                    //     item_total: '14'
                    // }
                },

                // invoice_id: '1',

                // items: [{
                //     item: {
                //         name: 'Item 1',
                //         // unit_amount: 1,
                //         quantity: '1'
                //     },
                //     item: {
                //         name: 'Item 2',
                //         // unit_amount: 2,
                //         quantity: '2'
                //     },
                //     item: {
                //         name: 'Item 3',
                //         // unit_amount: 3,
                //         quantity: '3'
                //     }
                // }
                // ]
            }]
        });
    },

    // Finalize the transaction after payer approval
    onApprove: (data, actions) => {
        return actions.order.capture().then(function (orderData) {
            // Successful capture! For dev/demo purposes:
            console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
            const transaction = orderData.purchase_units[0].payments.captures[0];
            alert(`Transaction ${transaction.status}: ${transaction.id}\n\nSee console for all available details`);
            // When ready to go live, remove the alert and show a success message within this page. For example:
            // const element = document.getElementById('paypal-button-container');
            // element.innerHTML = '<h3>Thank you for your payment!</h3>';
            // Or go to another URL:  actions.redirect('thank_you.html');
        });
    },

    style: {
        layout: 'horizontal',
        color: 'black',
        label: 'pay',
        tagline: 'false'
    }
}).render('#paypal-button-container');