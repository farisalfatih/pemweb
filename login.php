<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="container text-center">
        <h2>Login</h2>
        
        <!-- Login Form -->
        <form action="proses_login.php" method="post">
            <div class="form-group">
                <label for="login-email">Email</label>
                <input type="email" class="form-control" id="login-email" name="email" required>
            </div>
            <div class="form-group">
                <label for="login-password">Password</label>
                <input type="password" class="form-control" id="login-password" name="password" required>
            </div>
            <div class="form-group">
                <label for="login-role">Role</label>
                <select class="form-control" id="login-role" name="role" required>
                    <option value="admin">Admin</option>
                    <option value="reader">Reader</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>

        <!-- Link to Sign Up Page -->
        <div class="text-center mt-3">
            <span>Belum punya akun? </span>
            <a href="signup.php">Sign Up</a>
        </div>
    </div>

    <!-- External Libraries -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>