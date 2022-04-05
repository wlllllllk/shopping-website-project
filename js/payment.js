let lastId;

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


function updateOrder(orderId) {
    return new Promise(resolve => {
        let request = new XMLHttpRequest();

        // what to do after sending the request
        request.onreadystatechange = function () {

            // if the request is done
            if (this.readyState == 4) {

                // if the request is success
                if (this.status == 200) {
                    let response = this.responseText;
                    return resolve(response);
                }
            }
        };

        // use GET method
        let content = `update=${lastId}&new=${orderId}`;
        request.open("GET", "payment.php?" + encodeURIComponent(content), true);

        // send the request
        request.send();
    });
}

paypal.Buttons({

    // Sets up the transaction when a payment button is clicked
    createOrder: async (data, actions) => {
        let order_details = await fetchOrder();
        lastId = order_details[1]['id'];
        console.log(order_details);
        console.log(lastId);

        return actions.order.create(order_details[0]);
    },

    // Finalize the transaction after payer approval
    // TODO !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    onApprove: async (data, actions) => {
        return actions.order.capture().then(function (orderData) {
            // Successful capture! For dev/demo purposes:
            console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
            const transaction = orderData.purchase_units[0].payments.captures[0];
            // alert(`Transaction ${transaction.status}: ${transaction.id}\n\nSee console for all available details`);

            console.log(orderData.id);

            let update_result = await updateOrder(orderData.id);
            console.log(update_result);

            if (update_result == "success")
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

/*
!  item_name10=Item 11
! &item_name11=Item 14
! &item_name12=Item 6
&first_name=John
&discount=0.00
&mc_currency=USD
&payer_status=verified
&shipping_discount=0.00
&payment_fee=3.21
&address_status=confirmed
&payment_gross=78.00
&address_zip=95131
&address_country_code=US
&num_cart_items=12
&txn_type=cart
&verify_sign=A4ffosV9eZnI9PfOxrUT6ColxyFXA7swbGDZQRAikVBkGgeDqzhOlA2H
&payer_id=TQB2FA27XPDZU
&charset=UTF-8
&receiver_id=67JRFFNKJ7JZ4
! &item_name1=Item 5
! &item_name2=Item 4
! &item_name3=Item 7
! &item_name4=Item 3
&payment_type=instant
! &item_name5=Item 15
! &item_name6=Item 12
! &item_name7=Item 13
! &item_name8=Item 8
&address_street=1 Main St
! &item_name9=Item 10
TODO &txn_id=8UF331476R048764F
    &mc_gross_1=3.00
&quantity1=3
    &mc_gross_2=2.00
&quantity2=2
&item_number1=
&protection_eligibility=Eligible
    &mc_gross_3=5.00
&quantity3=5
&item_number2=
    &mc_gross_4=1.00
&quantity4=1
&item_number3=
&custom=
    &mc_gross_5=12.00
&quantity5=12
&item_number4=
    &mc_gross_6=9.00
&quantity6=9
&item_number5=
    &mc_gross_7=10.00
&quantity7=10
&item_number6=
&quantity8=6
&business=sb-ujfkm15543764@business.example.com
&item_number7=
    &mc_gross_8=6.00
&quantity9=7
&item_number8=
    &mc_gross_9=7.00
&residence_country=US
&last_name=Doe
&item_number9=
&address_state=CA
&quantity10=8
&quantity11=11
&quantity12=4
&payer_email=sb-lafdz15406658@personal.example.com
&address_city=San Jose
TODO &payment_status=Completed
&payment_date=09:30:56 Apr 04, 2022 PDT
&transaction_subject=
TODO &receiver_email=sb-ujfkm15543764@business.example.com
&mc_fee=3.21
&notify_version=3.9
&item_number10=
&shipping_method=Default
&item_number11=
&item_number12=
&address_country=United States
    &mc_gross=78.00
&test_ipn=1
&insurance_amount=0.00
&address_name=John Doe
    &mc_gross_10=8.00
    &mc_gross_11=11.00
    &mc_gross_12=4.00
&ipn_track_id=8758a5128b895


{"purchase_units": [{
                    "amount": {
                        "currency_code": "USD",
                        "value": "346877.6",
                        "breakdown": {
                            "item_total": {
                                "currency_code": "USD",
                                "value": "346877.6"
                            }
                        }
                    },
                    "items": [
                        {
                            "name": "Bamboo",
                            "unit_amount": {
                                "currency_code": "USD",
                                "value": "1700.0"
                            },
                            "quantity": "3"
                        },
                        {
                            "name": "Lime",
                            "unit_amount": {
                                "currency_code": "USD",
                                "value": "12.0"
                            },
                            "quantity": "2"
                        },
                        {
                            "name": "Doge",
                            "unit_amount": {
                                "currency_code": "USD",
                                "value": "8700.0"
                            },
                            "quantity": "5"
                        },
                        {
                            "name": "Mango",
                            "unit_amount": {
                                "currency_code": "USD",
                                "value": "20.0"
                            },
                            "quantity": "1"
                        },
                        {
                            "name": "Google Pixel 4",
                            "unit_amount": {
                                "currency_code": "USD",
                                "value": "5999.0"
                            },
                            "quantity": "12"
                        },
                        {
                            "name": "iPhone 6",
                            "unit_amount": {
                                "currency_code": "USD",
                                "value": "2999.0"
                            },
                            "quantity": "9"
                        },
                        {
                            "name": "iPhone 13",
                            "unit_amount": {
                                "currency_code": "USD",
                                "value": "7998.0"
                            },
                            "quantity": "10"
                        },
                        {
                            "name": "Stonks",
                            "unit_amount": {
                                "currency_code": "USD",
                                "value": "12999.0"
                            },
                            "quantity": "6"
                        },
                        {
                            "name": "Nope",
                            "unit_amount": {
                                "currency_code": "USD",
                                "value": "169.8"
                            },
                            "quantity": "7"
                        },
                        {
                            "name": "No Image",
                            "unit_amount": {
                                "currency_code": "USD",
                                "value": "1.0"
                            },
                            "quantity": "8"
                        },
                        {
                            "name": "Orange",
                            "unit_amount": {
                                "currency_code": "USD",
                                "value": "8.0"
                            },
                            "quantity": "11"
                        },
                        {
                            "name": "Another Tree",
                            "unit_amount": {
                                "currency_code": "USD",
                                "value": "9999.0"
                            },
                            "quantity": "4"
                        }]
                    }]}
*/