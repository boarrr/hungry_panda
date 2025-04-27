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

    // === Helper: Validate Phone Number ===
    function isValidPhoneNumber(phone) {
        const phoneRegex = /^[0-9]{8,15}$/;
        return phoneRegex.test(phone);
    }

    // === REGISTER form validation ===
    const registerForm = document.getElementById('register-form');
    if (registerForm) {
        registerForm.addEventListener('submit', function (event) {
            const phone = document.querySelector('#register-form input[name="phone_number"]').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm-password').value;

            if (!isValidPhoneNumber(phone)) {
                event.preventDefault();
                showModalMessage("Phone number must be 8-15 digits with no letters.");
                return;
            }

            if (password !== confirmPassword) {
                event.preventDefault();
                showModalMessage("Passwords do not match. Please try again.");
            }
        });
    }

    // === LOGIN form validation ===
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', function (event) {
            const loginPhone = document.querySelector('#login-form input[name="phone_number"]').value.trim();

            if (!isValidPhoneNumber(loginPhone)) {
                event.preventDefault();
                showModalMessage("Invalid phone number format.");
                return;
            }
        });
    }

    // === ACCOUNT form phone number update validation ===
    const updatePhoneInput = document.querySelector('form[action="php/account_action.php"] input[name="new_phone"]');
    if (updatePhoneInput) {
        updatePhoneInput.form.addEventListener('submit', function (event) {
            const phoneInput = updatePhoneInput.value.trim();
            if (!isValidPhoneNumber(phoneInput)) {
                event.preventDefault();
                showModalMessage("Phone number must be 8-15 digits, numbers only.");
                return;
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

    // === FADE OUT Success/Error Modals ===
    const successModal = document.querySelector('.custom-modal.show');
    if (successModal) {
        setTimeout(() => {
            successModal.style.opacity = '0';
            successModal.style.transition = 'opacity 1s ease';
            setTimeout(() => {
                successModal.style.display = 'none';
            }, 1000);
        }, 3000);
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
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
});

// === Show Error Modal ===
function showModalMessage(message, isSuccess = false) {
    const modal = document.createElement('div');
    modal.className = 'custom-modal show';
    modal.style.backgroundColor = isSuccess ? 'green' : 'red';
    modal.style.color = 'white';
    modal.style.textAlign = 'center';
    modal.style.marginBottom = '20px';
    modal.style.padding = '1.5rem 2.5rem';
    modal.style.fontSize = '1.5rem';
    modal.style.zIndex = '9999';
    modal.textContent = message;

    document.body.appendChild(modal);

    setTimeout(() => {
        modal.classList.remove('show');
        modal.style.opacity = '0';
        setTimeout(() => {
            document.body.removeChild(modal);
        }, 300);
    }, 3000);
}
