<?php 
    session_start();
    require_once 'config/database.php';
    $error = '';

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $uname = $_POST['uname'];
        $pass = $_POST['pass'];

        $sql = "SELECT * FROM users WHERE username = '$uname'";
        $result = mysqli_query($conn, $sql);
        
// checks if database found any matching user record and if found it fethches their databse record        
        if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

            if (password_verify($pass, $row["password"])) {
                // Store user information in session
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = $row['role'];

                header("Location: index.php");
                exit();
            } 
            else {
                $error = "Invalid password";
            }
        } 
        else {
            $error = "Username not found";
        }
    }   
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TechNexus - Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background-color: #000;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .card {
            background-color: #191c24;
            border-radius: 8px;
            padding: 30px;
            width: 100%;
            max-width: 400px;
        }
        .form-control {
            background-color: #2A3038;
            border: 1px solid #2A3038;
            color: #fff;
            margin-bottom: 15px;
        }
        .btn-primary {
            background-color: #00d25b;
            border: none;
            width: 100%;
        }
        .alert-danger {
            background-color: #fc424a;
            color: #fff;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="card">
        <h3 class="text-center mb-4">TechNexus Login</h3>

        <?php if (!empty($error)): ?>
            <div class="alert-danger text-center">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="uname" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="pass" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary p-2">Log In</button>
        </form>

        <p class="text-center mt-3" style="color: #6c757d;">
            Don't have an account? <a href="signup.php" style="color: #00d25b;">Sign up</a>
        </p>
    </div>
</body>
</html>
