document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('register-form');
    const errorMessageU = document.getElementById('error-message-username');
    const errorMessageP = document.getElementById('error-message-password');
    const errorMessageCP = document.getElementById('error-message-confirm-password');
    const errorMessageE = document.getElementById('error-message-email');

    registerForm.addEventListener('submit', function(event) {
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm-password').value;
        const email = document.getElementById('email').value;
        let isValid = true; // Biến để kiểm tra tính hợp lệ của tất cả các trường

        // Kiểm tra tên người dùng
        if (username.trim() === '') {
            errorMessageU.textContent = 'Tên tài khoản không được để trống!';
            errorMessageU.style.display = 'inline';
            isValid = false; // Đánh dấu không hợp lệ nếu tên tài khoản trống
        } else {
            errorMessageU.textContent = ''; // Xóa thông báo lỗi nếu đã nhập đủ thông tin
            errorMessageU.style.display = 'none';
        }

        // Kiểm tra mật khẩu 
        if (password.trim() === '') {
            errorMessageP.textContent = 'Mật khẩu không được để trống!';
            errorMessageP.style.display = 'inline';
            isValid = false; // Đánh dấu không hợp lệ nếu mật khẩu trống
        } else {
            errorMessageP.textContent = ''; // Xóa thông báo lỗi nếu đã nhập đủ thông tin
            errorMessageP.style.display = 'none';
        }

        // Kiểm tra mật khẩu xác nhận 
        if (confirmPassword.trim() === '') {
            errorMessageCP.textContent = 'Xác nhận mật khẩu không được để trống!';
            errorMessageCP.style.display = 'inline';
            isValid = false; // Đánh dấu không hợp lệ nếu mật khẩu xác nhận trống
        } else {
            errorMessageCP.textContent = ''; // Xóa thông báo lỗi nếu đã nhập đủ thông tin
            errorMessageCP.style.display = 'none';
        }

        // Kiểm tra tính hợp lệ của email
        if (!validateEmail(email)) {
            errorMessageE.textContent = 'Email không hợp lệ!';
            errorMessageE.style.display = 'inline';
            isValid = false; // Đánh dấu không hợp lệ nếu email không hợp lệ
        } else {
            errorMessageE.textContent = ''; // Xóa thông báo lỗi nếu đã nhập đủ thông tin
            errorMessageE.style.display = 'none';
        }

        // Ngăn chặn form gửi dữ liệu nếu không hợp lệ
        if (!isValid) {
            event.preventDefault();
        }
    });

    function validateEmail(email) {
        const re = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        return re.test(String(email).toLocaleLowerCase());
    }
});