<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/admin.css" />
    <link
      rel="shortcut icon"
      href="images/places/Favicom1.jpg"
      type="image/x-icon"
    />
</head>
<body>
    <h1>Admin Dashboard</h1>
    <div class="container">
        <!-- Add Flight Form -->
        <div class="form-section">
            <h2>Add Flight</h2>
            <form
              id="addFlightForm"
              method="POST"
              enctype="multipart/form-data"
              action="add_flight.php"
            >
                <input type="hidden" name="action" value="add" />
                <input type="hidden" name="id" />
                <input
                  type="text"
                  id="flight_name"
                  name="flight_name"
                  placeholder="Flight Name"
                  required
                />
                <input
                  type="text"
                  id="origin"
                  name="origin"
                  placeholder="Origin"
                  required
                />
                <input
                  type="text"
                  id="destination"
                  name="destination"
                  placeholder="Destination"
                  required
                />
                <input
                  type="number"
                  id="price"
                  name="price"
                  placeholder="Price"
                  required
                />
                <input
                  type="date"
                  id="travel_date"
                  name="travel_date"
                  placeholder="Travel Date"
                  required
                />
                <select
                  title="Select flight class"
                  id="flight_class"
                  name="class"
                  required
                >
                    <option value="business">Business</option>
                    <option value="economy">Economy</option>
                </select>
                <input
                  title="choose flight image"
                  type="file"
                  id="flight_image"
                  name="image"
                  required
                />
                <button type="submit">Add Flight</button>
            </form>
        </div>

        <!-- Add Hotel Form -->
        <div class="form-section">
            <h2>Add Hotel</h2>
            <form
              id="addHotelForm"
              method="POST"
              enctype="multipart/form-data"
              action="add_hotel.php"
            >
                <input type="hidden" name="action" value="add" />
                <input type="hidden" name="id" />
                <input
                  type="text"
                  id="hotel_name"
                  name="name"
                  placeholder="Hotel Name"
                  required
                />
                <input
                  type="text"
                  id="hotel_location"
                  name="location"
                  placeholder="Location"
                  required
                />
                <textarea
                  id="hotel_services"
                  name="services"
                  placeholder="Services"
                  required
                ></textarea>
                <textarea
                  id="hotel_description"
                  name="description"
                  placeholder="Description"
                  required
                ></textarea>
                <input
                  title="choose hotel image"
                  type="file"
                  id="hotel_image"
                  name="image"
                  required
                />
                <button type="submit">Add Hotel</button>
            </form>
        </div>

        <!-- Add Tourist Site Form -->
        <div class="form-section">
            <h2>Add Tourist Site</h2>
            <form
              id="addTouristSiteForm"
              method="POST"
              enctype="multipart/form-data"
              action="add_tourist_site.php"
            >
                <input type="hidden" name="action" value="add" />
                <input type="hidden" name="id" />
                <input
                  type="text"
                  id="site_name"
                  name="name"
                  placeholder="Site Name"
                  required
                />
                <input
                  type="text"
                  id="site_location"
                  name="location"
                  placeholder="Location"
                  required
                />
                <textarea
                  id="site_description"
                  name="description"
                  placeholder="Description"
                  required
                ></textarea>
                <input
                  title="choose site image"
                  type="file"
                  id="site_image"
                  name="image"
                  required
                />
                <button type="submit">Add Tourist Site</button>
            </form>
        </div>
    </div>

    <div class="data-section">
        <!-- Manage Flights -->
        <h2>Manage Flights</h2>
        <table id="flightsTable">
            <thead>
                <tr>
                    <th>Flight Name</th>
                    <th>Origin</th>
                    <th>Destination</th>
                    <th>Price</th>
                    <th>Travel Date</th>
                    <th>Class</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Database connection
                $host = 'localhost';
                $db = 'project';
                $user = 'root';
                $pass = '';
                $conn = new mysqli($host, $user, $pass, $db);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Fetch flights data
                $sql = "SELECT * FROM flights";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['flight_name']}</td>
                            <td>{$row['origin']}</td>
                            <td>{$row['destination']}</td>
                            <td>{$row['price']}</td>
                            <td>{$row['travel_date']}</td>
                            <td>{$row['class']}</td>
                            <td><img src='uploads/{$row['image']}' alt='{$row['flight_name']}' width='100'></td>
                            <td>
                                <a href='edit_flight.php?id={$row['id']}'>Edit</a> |
                                <a href='delete_flight.php?id={$row['id']}' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No flights found</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>

        <!-- Manage Hotels -->
        <h2>Manage Hotels</h2>
        <table id="hotelsTable">
            <thead>
                <tr>
                    <th>Hotel Name</th>
                    <th>Location</th>
                    <th>Services</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Database connection
                $conn = new mysqli($host, $user, $pass, $db);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Fetch hotels data
                $sql = "SELECT * FROM hotels";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['name']}</td>
                            <td>{$row['location']}</td>
                            <td>{$row['services']}</td>
                            <td>{$row['description']}</td>
                            <td><img src='uploads/{$row['image']}' alt='{$row['name']}' width='100'></td>
                            <td>
                                <a href='edit_hotel.php?id={$row['id']}'>Edit</a> |
                                <a href='delete_flight.php?hotel_id={$row['id']}' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No hotels found</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>

        <!-- Manage Tourist Sites -->
        <h2>Manage Tourist Sites</h2>
        <table id="touristSitesTable">
            <thead>
                <tr>
                    <th>Site Name</th>
                    <th>Location</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Database connection
                $conn = new mysqli($host, $user, $pass, $db);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Fetch tourist sites data
                $sql = "SELECT * FROM tourist_sites";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['name']}</td>
                            <td>{$row['location']}</td>
                            <td>{$row['description']}</td>
                            <td><img src='uploads/{$row['image']}' alt='{$row['name']}' width='100'></td>
                            <td>
                                <a href='edit_tourist_site.php?id={$row['id']}'>Edit</a> |
                                <a href='delete_flight.php?tourist_site_id={$row['id']}' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No tourist sites found</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
