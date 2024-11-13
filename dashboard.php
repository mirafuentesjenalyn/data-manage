<?php
include 'connect.php';

$sql = "SELECT * FROM users";
$result = mysqli_query($conn, $sql);

if (isset($_GET['deleteid']) && !isset($_GET['confirm_delete'])) {
    $id = $_GET['deleteid'];

    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>"; 
    echo "<script>
        window.onload =function() {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'dashboard.php?confirm_delete=true&deleteid=$id';
                } else {
                    window.location.href = 'dashboard.php';
                }
            });
        }
    </script>";
}

if (isset($_GET['confirm_delete']) && $_GET['confirm_delete'] == 'true' && isset($_GET['deleteid'])) {
    $id = $_GET['deleteid'];

    $sql = "DELETE FROM users WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            header("Location: dashboard.php?action=deleted");
            exit();
        } else {
            header("Location: dashboard.php?action=error");
            exit();
        }
    } else {
        header("Location: dashboard.php?action=error");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">

</head>
<body>
    <h1>Dashboard</h1>
    <form action = "add.php" method="GET">
        <button type="submit">Add New User</button>
        <a href="login.php">Logout</a>
    </form>

    <div class="container">
        <div class="text-center">
            <div class="table-responsive">
                <table class = "table table-info table-striped table-bordered my-4">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col"><div class="p-3">ID</div></th>
                            <th scope="col"><div class="p-3">Name</div></th>
                            <th scope="col"><div class="p-3">Email</div></th>
                            <th scope="col"><div class="p-3">Age</div></th>
                            <th scope="col"><div class="p-3">Created At</div></th>
                            <th scope="col"><div class="p-3">Operation</div></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if ($result) {
                                while($row = mysqli_fetch_assoc($result)) {
                                    $id = $row['id'];
                                    $name = $row['name'];
                                    $email = $row['email'];
                                    $age = $row['age'];
                                    $created_at = $row['created_at'];

                                    echo'<tr>
                                    <th scope = "row">'.$id.'</th>
                                    <td>' .$name.'</td>
                                    <td>' .$email.'</td>
                                    <td>' .$age.'</td>
                                    <td>' .$created_at.'</td>
                                    <td><center>

                                        <div class = "btn-group">
                                            <a href="edit.php?editid='.$id.'" class="btn btn-info text-dark">Edit</a>
                                            <a href="dashboard.php?deleteid='.$id.'" class="btn btn-danger text-light">Delete</a>
                                        </div></td>
                                        </center>
                                    </tr>';
                                }
                            }
                        ?>
                    </tbody>    
                </table>
            </div>
        </div>
    </div>

    <?php

        if (isset($_GET['action'])) {
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
            if ($_GET['action'] == 'deleted') {
                echo '<script>
                    Swal.fire("Deleted!", "User has been deleted.", "success").then(() => {
                        window.location.href = "dashboard.php"; 
                    });
                </script>';
            } else {
                echo '<script>
                    Swal.fire("Error", "There was a problem deleting the user.", "error").then(() => {
                        window.location.href = "dashboard.php"; 
                    });
                </script>';
            }
        }
    ?>


</body>
</html>

