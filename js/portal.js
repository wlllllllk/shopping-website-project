function searchOrder(passed) {
    let orderID = passed.children[1].value;

    window.location.href = `https://secure.s67.ierg4210.ie.cuhk.edu.hk/portal.php?ref=${orderID}`;
    return false;
}