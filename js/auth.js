document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');

    if (registerForm) {
        registerForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            formData.append('action', 'register');

            const response = await fetch('php/auth.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.status === 'success') {
                alert(result.message);
                window.location.href = 'login.html';
            } else {
                alert(result.message);
            }
        });
    }

    if (loginForm) {
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            formData.append('action', 'login');

            const response = await fetch('php/auth.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            console.log(result); // Debugging log

            if (result.status === 'success') {
                alert(result.message);
                if (result.role === 'admin') {
                    window.location.href = 'admin_page.html';
                } else {
                    window.location.href = 'user_page.html';
                }
            } else {
                alert(result.message);
            }
        });
    }
});
