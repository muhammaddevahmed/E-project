




document.querySelectorAll('.qty-btn').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const input = document.querySelector(`.qty-input[data-id='${id}']`);
        let quantity = parseInt(input.value);

        if (this.classList.contains('inc')) {
            quantity++;
        } else if (this.classList.contains('dec') && quantity > 1) {
            quantity--;
        }

        input.value = quantity;

        // Update Total Price
        const price = parseFloat(document.querySelector(`.price[data-id='${id}']`).getAttribute('data-price'));
        const totalPrice = (price * quantity).toFixed(2);
        document.querySelector(`.total-price[data-id='${id}']`).textContent = `$${totalPrice}`;

        // Send updated cart data to PHP
        updateCartInSession(id, quantity);
    });
});

// Function to send data to PHP using AJAX
function updateCartInSession(productId, quantity) {
    fetch('update_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${productId}&quantity=${quantity}`
    })
    .then(response => response.text())
    .then(data => {
        console.log(data); // Debugging
        updateTotals();
    });
}

function updateTotals() {
    let subtotal = 0;
    document.querySelectorAll('.total-price').forEach(item => {
        subtotal += parseFloat(item.textContent.replace('$', ''));
    });

    document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
    document.getElementById('total').textContent = `$${subtotal.toFixed(2)}`;
}


