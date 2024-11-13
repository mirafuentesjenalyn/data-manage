<?php

include 'connect.php';
ob_start();

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $age = $_POST['age'];

    $checkEmail = "SELECT * FROM users WHERE email = ?";
    if ($stmt = $conn->prepare($checkEmail)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire("Error", "Email already exist!", "error").then(() => {
                        window.location.href = "add.php";
                    });
                });
            </script>';
        } else {
            $sql = "INSERT INTO users (name, email, age) VALUES (?,?,?)";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("ssi", $name, $email, $age);
        
                if ($stmt->execute()) {
                    echo '<script>
                        document.addEventListener("DOMContentLoaded", function() {
                            Swal.fire("Success", "New User Added!", "success").then(()=> {
                                window.location.href = "add.php";
                            });
                        });
                    </script>';
                } else {
                    echo '<script>
                        document.addEventListener("DOMContentLoaded", function() {
                            Swal.fire("Error", "'.$stmt->error.'", "error").then(()=> {
                                window.location.href = "add.php";
                            });
                        });
                    </script>';
                }
            }
        }
            $stmt->close();
    } else {
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire("Error", "'.$conn->$error.'", "error").then(()=> {
                    window.location.href = "add.php";
                });
            });
        </script>';
    }
    }
    
ob_end_flush();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>
    <h2>Add New User</h2><br>
    <form action="" method="POST">
        <label for="email">Email: </label><br>
        <input type="email" id="email" name="email" placeholder="Enter Your Email" required><br><br>

        <label for="name">Name: </label><br>
        <input type="name" id="name" name="name" placeholder="Enter your Name" required><br><br>

        <label for="age">Age: </label><br>
        <input type="number" id="age" name="age" min="1" max="100" placeholder="Age" required><br><br>

        <button type="submit" name="submit">Add New User</button><br><br>
        
        <a href="dashboard.php">
            <label>Return to Dashboard</label>
        </a>

    </form>

</body>
</html>