function addToCart(id, quantity){
    if(localStorage.getItem("cart") == null){
        localStorage.setItem("cart", JSON.stringify({}));
    }

    let cartLs = localStorage.getItem("cart");
    let cart = JSON.parse(cartLs);
    cart[id] = quantity;
    localStorage.setItem("cart", JSON.stringify(cart));
}


// addToCart(50, 12)
// addToCart(52, 20)
// addToCart(50, 13)