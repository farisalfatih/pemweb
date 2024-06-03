<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info Gresik | Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="container text-center">
        <h2>Sign Up</h2>
        
        <!-- Sign Up Form -->
        <form id="signup-form" action="proses_signup.php" method="post" onsubmit="return validatePassword()">
            <div class="form-group">
                <label for="signup-nama">Nama</label>
                <input type="text" class="form-control" id="signup-nama" name="nama" required>
            </div>
            <div class="form-group">
                <label for="signup-email">Email</label>
                <input type="email" class="form-control" id="signup-email" name="email" required>
            </div>
            <div class="form-group">
                <label for="signup-password">Password</label>
                <input type="password" class="form-control" id="signup-password" name="password" required>
            </div>
            <div class="form-group">
                <label for="signup-confirm-password">Confirm Password</label>
                <input type="password" class="form-control" id="signup-confirm-password" name="confirm_password" required>
            </div>
            <div class="form-group">
                <label for="signup-role">Role</label>
                <select class="form-control" id="signup-role" name="role" required>
                    <option value="admin">Admin</option>
                    <option value="reader">Reader</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
        </form>

        <!-- Link to Login Page -->
        <div class="text-center mt-3">
            <span>Sudah punya akun? </span>
            <a href="login.php">Log In</a>
        </div>
    </div>

    <!-- External Libraries -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Custom Script for Password Validation -->
    <script>
        function validatePassword() {
            var password = document.getElementById("signup-password").value;
            var confirmPassword = document.getElementById("signup-confirm-password").value;
            if (password !== confirmPassword) {
                alert("Password tidak sama");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>