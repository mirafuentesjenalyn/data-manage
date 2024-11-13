<?php

include 'connect.php';

ob_start();

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT id, username, password FROM admins WHERE username = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
             $stmt->bind_result($id, $stored_username, $stored_password);
             $stmt->fetch();

             if (password_verify($password, $stored_password)) {
                $_SESSION['id'] = $id;
                $_SESSION['username'] = $stored_username;

                header('Location: dashboard.php');
                exit();
             } else {
                echo '<script>
                    document.addEventListener("DOMContentLoaded", function() {
                        Swal.fire("Error", "Invalid Password!", "error").then(()=> {
                            window.location.href = "login.php";
                        });
                    });
                </script>';
             }
        } else {
            echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire("Error", "No Username Found!", "error").then(() => {
                        window.location.href = "login.php";
                    });
                });
            </script>';
        }
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }
}
ob_end_flush();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>
    <h2>Login</h2>
    <form action="" method="POST">
        <label for="username">Username </label><br>
        <input type="username" id="username" name="username" placeholder="Enter Username" required><br><br>

        <label for="password">Password: </label><br>
        <input type="password" id="password" name="password"placeholder="Enter Password" required> <br><br>

        <button type="submit">Login</button><br><br>
        
    </form>



</body>
</html>