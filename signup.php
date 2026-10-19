<?php session_start(); 
// it will not be included again
    require_once 'config/database.php';
    $message = '';
    $message_type = '';

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $uname = $_POST['uname'];
        $pass = $_POST['pass'];

        $sql = "SELECT * FROM users WHERE username = '$uname'";
        $result = mysqli_query($conn, $sql);

// returns total number of rows in MYSQL result set
        if(mysqli_num_rows($result) > 0){
            $message = "Username already exists or error occurred: " . mysqli_error($conn);
            $message_type = "danger";
        }
        else{
            $hash = password_hash($pass,PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username,password) VALUES ('$uname','$hash')";
            $result = mysqli_query($conn,$sql);
            if($result){
                $message = "Account created successfully! You can now log in.";
                $message_type = "success";
                $_SESSION["uname"] = $uname;
                $_SESSION["pass"] = $pass;
            }
            else{
                $message = "Please fill in all fields.";
                $message_type = "warning";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TechNexus - Sign Up</title>
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
    </style>
</head>
<body>
    <div class="card">
        <h3 class="text-center mb-4">TechNexus Sign Up</h3>
<!--added this because simple fatal error is coming-->
        <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo $message_type; ?>" style="padding: 10px; margin-bottom: 15px; border-radius: 4px; background-color: 
            <?php echo $message_type === 'success' ? '#00d25b' : '#fc424a'; ?>; color: white;">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
     
        <form action="signup.php" method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="uname" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="pass" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary p-2">Register Account</button>
        </form>
    </div>
</body>
</html>
