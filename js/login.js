document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('login-form');
    const errorMessageU = document.getElementById('error-message-username');
    const errorMessageP = document.getElementById('error-message-password');

    loginForm.addEventListener('submit', function(event) {
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        let isValid = true; // Biến để kiểm tra tính hợp lệ của cả hai trường

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

        // Ngăn chặn form gửi dữ liệu nếu không hợp lệ
        if (!isValid) {
            event.preventDefault();
        }
    });
});