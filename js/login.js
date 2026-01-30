$(document).ready(function() {
    // Check if user already logged in
    var sessionToken = localStorage.getItem('sessionToken');
    if (sessionToken) {
        window.location.href = 'profile.html';
    }

    $('#loginForm').on('submit', function(e) {
        e.preventDefault();
        
        var email = $('#email').val();
        var password = $('#password').val();
        
        // Send AJAX request
        $.ajax({
            url: 'php/login.php',
            type: 'POST',
            data: {
                email: email,
                password: password
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Store session token in localStorage
                    localStorage.setItem('sessionToken', response.sessionToken);
                    localStorage.setItem('userId', response.userId);
                    
                    showMessage('Login successful! Redirecting...', 'success');
                    setTimeout(function() {
                        window.location.href = 'profile.html';
                    }, 1000);
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
