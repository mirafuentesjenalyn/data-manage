<?php

include 'connect.php';

ob_start();

if (!isset($_GET['editid'])) {
    header("Location: dashboard.php");
    exit;
}

$id = $_GET['editid'];

$sql = "SELECT * FROM users WHERE id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $name = $row['name'];
        $email = $row['email'];
        $age = $row['age'];
    } else {
        echo '<script>
                alert("User not found. Redirecting to dashboard.");
                window.location.href = "dashboard.php";
              </script>';
        exit;
    }

    $stmt->close();
}

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $age = $_POST['age'];

    $checkEmail = "SELECT * FROM users WHERE email = ? AND id != ?";
    if ($stmt = $conn->prepare($checkEmail)) {
        $stmt->bind_param("si", $email, $id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire("Error", "Email already exist!", "error").then(() => {
                        window.location.href = "edit.php?editid='.$id.'";
                    });
                });
            </script>';
        } else {
            $sql = "UPDATE users SET name = ?, email = ?, age = ? WHERE id= ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("ssii", $name, $email, $age, $id);
                if ($stmt->execute()) {
                    echo '<script>
                        document.addEventListener("DOMContentLoaded", function() {
                            Swal.fire("Success", "User Updated Successfully!", "success").then(() => {
                                window.location.href = "edit.php?editid='.$id.'";
                            });
                        });
                    </script>';
                } else {
                    die ('Error: ' . $stmt->error);
                }
                $stmt->close();
            }
        }
    }
}
ob_end_flush();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Users</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>
    <h2>Edit Users</h2>
    
    <form action = "" method = "POST">
        <label for="id">User ID: <?php echo $id; ?></label> <br><br>
        <label for="name">Name: </label><br>
        <input type="text" placeholder="Enter a Name" name="name" value="<?php echo $name; ?>" required><br><br>
        <label for="email">Email: </label><br>
        <input type="text" placeholder="Enter Email" name="email" value="<?php echo $email; ?>" required><br><br>
        <label for="age">Age: </label><br>
        <input type="number" placeholder="Age" name="age" value="<?php echo $age; ?>" required><br><br>

        <button type="submit" name="submit">Update</button><br><br>

        <a href="dashboard.php">Return to Dashboard</a>
    </form>

</body>
</html>