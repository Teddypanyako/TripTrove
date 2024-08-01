<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];
    $type = $_POST['type'];
    $id = $_POST['id'] ?? null;

    // Common function to handle file uploads
    function uploadImage($file)
    {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($file['name']);
        move_uploaded_file($file['tmp_name'], $target_file);
        return $target_file;
    }

    // Common function to execute SQL and check for errors
    function executeSQL($conn, $sql)
    {
        if ($conn->query($sql) === TRUE) {
            echo "Operation completed successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Handle Flights
    if ($type == "flight") {
        $flight_name = $_POST['flight_name'];
        $origin = $_POST['origin'];
        $destination = $_POST['destination'];
        $price = $_POST['price'];
        $travel_date = $_POST['travel_date'];
        $class = $_POST['class'];
        $image_path = uploadImage($_FILES['image']);

        if ($action == "add") {
            $sql = "INSERT INTO flights (flight_name, origin, destination, price, travel_date, class, image_path)
                    VALUES ('$flight_name', '$origin', '$destination', '$price', '$travel_date', '$class', '$image_path')";
        } elseif ($action == "edit") {
            $sql = "UPDATE flights SET flight_name='$flight_name', origin='$origin', destination='$destination', price='$price', travel_date='$travel_date', class='$class', image_path='$image_path' WHERE id='$id'";
        } elseif ($action == "delete") {
            $sql = "DELETE FROM flights WHERE id='$id'";
        }
    }

    // Handle Hotels
    if ($type == "hotel") {
        $name = $_POST['name'];
        $location = $_POST['location'];
        $services = $_POST['services'];
        $description = $_POST['description'];
        $image_path = uploadImage($_FILES['image']);

        if ($action == "add") {
            $sql = "INSERT INTO hotels (name, location, services, description, image_path)
                    VALUES ('$name', '$location', '$services', '$description', '$image_path')";
        } elseif ($action == "edit") {
            $sql = "UPDATE hotels SET name='$name', location='$location', services='$services', description='$description', image_path='$image_path' WHERE id='$id'";
        } elseif ($action == "delete") {
            $sql = "DELETE FROM hotels WHERE id='$id'";
        }
    }

    // Handle Tourist Sites
    if ($type == "tourist_site") {
        $name = $_POST['name'];
        $location = $_POST['location'];
        $description = $_POST['description'];
        $image_path = uploadImage($_FILES['image']);

        if ($action == "add") {
            $sql = "INSERT INTO tourist_sites (name, location, description, image_path)
                    VALUES ('$name', '$location', '$description', '$image_path')";
        } elseif ($action == "edit") {
            $sql = "UPDATE tourist_sites SET name='$name', location='$location', description='$description', image_path='$image_path' WHERE id='$id'";
        } elseif ($action == "delete") {
            $sql = "DELETE FROM tourist_sites WHERE id='$id'";
        }
    }

    // Execute the SQL query
    executeSQL($conn, $sql);
}

$conn->close();
