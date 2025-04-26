window.addEventListener('DOMContentLoaded', function () {
    // === CARD payment unavailable modal ===
    const cardBtn = document.getElementById("card-btn");
    const modal = document.getElementById("notification-modal");

    if (cardBtn && modal) {
        cardBtn.addEventListener("click", function () {
            modal.classList.add("show");
            setTimeout(() => {
                modal.classList.remove("show");
            }, 3000);
        });
    }

    // === REGISTER form password match ===
    const registerForm = document.getElementById('register-form');
    if (registerForm) {
        registerForm.addEventListener('submit', function (event) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm-password').value;

            if (password !== confirmPassword) {
                event.preventDefault();
                alert("Passwords do not match. Please try again.");
            }
        });
    }

    // === SWITCH between login and register ===
    const loginBox = document.getElementById('login-box');
    const registerBox = document.getElementById('register-box');
    const showRegister = document.getElementById('show-register');
    const showLogin = document.getElementById('show-login');

    if (showRegister) {
        showRegister.addEventListener('click', function () {
            loginBox.style.display = "none";
            registerBox.style.display = "flex";
        });
    }

    if (showLogin) {
        showLogin.addEventListener('click', function () {
            registerBox.style.display = "none";
            loginBox.style.display = "flex";
        });
    }

    // === AJAX CART UPDATE ===
    document.body.addEventListener('click', function (event) {
        if (event.target.classList.contains('add-btn')) {
            updateCart(event.target.dataset.id, 'add');
        }

        if (event.target.classList.contains('remove-btn')) {
            updateCart(event.target.dataset.id, 'remove');
        }
    });

    function updateCart(itemId, action) {
        fetch('php/update_cart_ajax.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `item_id=${itemId}&action=${action}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Refresh the page to show updated cart
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
});
