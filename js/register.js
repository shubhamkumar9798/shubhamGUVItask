$(document).ready(function() {
    // Check if user already logged in
    var sessionToken = localStorage.getItem('sessionToken');
    if (sessionToken) {
        window.location.href = 'profile.html';
    }

    $('#registerForm').on('submit', function(e) {
        e.preventDefault();
        
        var username = $('#username').val();
        var email = $('#email').val();
        var password = $('#password').val();
        var confirmPassword = $('#confirm_password').val();
        
        // Basic validation
        if (password !== confirmPassword) {
            showMessage('Passwords do not match!', 'danger');
            return;
        }
        
        if (password.length < 6) {
            showMessage('Password must be at least 6 characters long!', 'danger');
            return;
        }
        
        // Send AJAX request
        $.ajax({
            url: 'php/register.php',
            type: 'POST',
            data: {
                username: username,
                email: email,
                password: password
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showMessage(response.message, 'success');
                    setTimeout(function() {
                        window.location.href = 'login.html';
                    }, 1500);
                } else {
                    showMessage(response.message, 'danger');
                }
            },
            error: function() {
                showMessage('An error occurred. Please try again.', 'danger');
            }
        });
    });
});

function showMessage(message, type) {
    var messageDiv = $('#message');
    messageDiv.removeClass('d-none alert-success alert-danger alert-info');
    messageDiv.addClass('alert-' + type);
    messageDiv.text(message);
    
    setTimeout(function() {
        messageDiv.addClass('d-none');
    }, 5000);
}
