$(document).ready(function() {
    // Check if user is logged in
    var sessionToken = localStorage.getItem('sessionToken');
    var userId = localStorage.getItem('userId');
    
    if (!sessionToken || !userId) {
        window.location.href = 'login.html';
        return;
    }
    
    // Load user profile
    loadProfile();
    
    $('#editBtn').on('click', function() {
        $('#viewMode').addClass('d-none');
        $('#editMode').removeClass('d-none');
    });
    
    $('#cancelBtn').on('click', function() {
        $('#editMode').addClass('d-none');
        $('#viewMode').removeClass('d-none');
    });
    
    $('#profileForm').on('submit', function(e) {
        e.preventDefault();
        updateProfile();
    });
    
    $('#logoutBtn').on('click', function() {
        logout();
    });
});

function loadProfile() {
    var sessionToken = localStorage.getItem('sessionToken');
    var userId = localStorage.getItem('userId');
    
    $.ajax({
        url: 'php/profile.php',
        type: 'GET',
        data: {
            sessionToken: sessionToken,
            userId: userId
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                var user = response.user;
                
                // Display profile data
                $('#display_username').text(user.username);
                $('#display_email').text(user.email);
                $('#display_age').text(user.age || 'Not set');
                $('#display_dob').text(user.dob || 'Not set');
                $('#display_contact').text(user.contact || 'Not set');
                
                // Fill form fields
                $('#username').val(user.username);
                $('#age').val(user.age);
                $('#dob').val(user.dob);
                $('#contact').val(user.contact);
            } else {
                showMessage(response.message, 'danger');
                if (response.message === 'Invalid session') {
                    setTimeout(function() {
                        logout();
                    }, 2000);
                }
            }
        },
        error: function() {
            showMessage('Failed to load profile', 'danger');
        }
    });
}

function updateProfile() {
    var sessionToken = localStorage.getItem('sessionToken');
    var userId = localStorage.getItem('userId');
    
    var username = $('#username').val();
    var age = $('#age').val();
    var dob = $('#dob').val();
    var contact = $('#contact').val();
    
    $.ajax({
        url: 'php/update_profile.php',
        type: 'POST',
        data: {
            sessionToken: sessionToken,
            userId: userId,
            username: username,
            age: age,
            dob: dob,
            contact: contact
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showMessage(response.message, 'success');
                $('#editMode').addClass('d-none');
                $('#viewMode').removeClass('d-none');
                loadProfile();
            } else {
                showMessage(response.message, 'danger');
            }
        },
        error: function() {
            showMessage('Failed to update profile', 'danger');
        }
    });
}

function logout() {
    var sessionToken = localStorage.getItem('sessionToken');
    
    $.ajax({
        url: 'php/logout.php',
        type: 'POST',
        data: {
            sessionToken: sessionToken
        },
        dataType: 'json',
        success: function() {
            localStorage.removeItem('sessionToken');
            localStorage.removeItem('userId');
            window.location.href = 'login.html';
        },
        error: function() {
            localStorage.removeItem('sessionToken');
            localStorage.removeItem('userId');
            window.location.href = 'login.html';
        }
    });
}

function showMessage(message, type) {
    var messageDiv = $('#message');
    messageDiv.removeClass('d-none alert-success alert-danger alert-info');
    messageDiv.addClass('alert-' + type);
    messageDiv.text(message);
    
    setTimeout(function() {
        messageDiv.addClass('d-none');
    }, 5000);
}
