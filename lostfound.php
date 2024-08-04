<?php
session_start();
require_once 'utils/connect.php'; // Adjust this to your actual connection file

// Check if the form was submitted with POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // File upload handling
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES["dogPhoto"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["dogPhoto"]["tmp_name"]);
    if ($check === false) {
        $response = array("success" => false, "message" => "File is not an image.");
        echo json_encode($response);
        exit();
    }

    // Check file size
    if ($_FILES["dogPhoto"]["size"] > 2 * 1024 * 1024) {
        $response = array("success" => false, "message" => "Sorry, your file is too large.");
        echo json_encode($response);
        exit();
    }

    // Allow only certain file formats
    $allowedTypes = array('jpg', 'jpeg', 'png');
    if (!in_array($imageFileType, $allowedTypes)) {
        $response = array("success" => false, "message" => "Only JPG, JPEG, PNG files are allowed.");
        echo json_encode($response);
        exit();
    }

    
    // Move uploaded file
    if (move_uploaded_file($_FILES["dogPhoto"]["tmp_name"], $targetFile)) {
        // File uploaded successfully, now insert details into database
        $dogName = $_POST['name'];
        $dogAge = $_POST['age'];
        $dogDescription = $_POST['description'];
        $lastSeenLocation = $_POST['location'];
        $dateTime = $_POST['datetime'];
        $photoPath = $targetFile;

        // Ensure datetime format matches 'YYYY-MM-DD HH:mm:ss' expected by MySQL
            $dateTime = date('Y-m-d H:i:s', strtotime($_POST['datetime'])); // Convert to MySQL datetime format

            // Insert into database
            $stmt = $conn->prepare("INSERT INTO lostandfound (dog_name, dog_age, dog_description, last_seen_location, datetime, photo_path) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $dogName, $dogAge, $dogDescription, $lastSeenLocation, $dateTime, $photoPath);

            if ($stmt->execute()) {
                $response = array("success" => true);
                echo json_encode($response);
            } else {
                $response = array("success" => false, "message" => "Error saving dog details to database: " . $stmt->error);
                echo json_encode($response);
            }

            $stmt->close();

    } else {
        $response = array("success" => false, "message" => "Sorry, there was an error uploading your file.");
        echo json_encode($response);
    }

    $conn->close();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reunite Paws - Report a Lost Dog</title>
    <link rel="stylesheet" href="css/lostfound.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
    <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
    <style>
         #footer {
            background-color: #333;
            color: #fcf8fb;
            padding: 40px 20px;
            display: flex;
            flex-wrap: wrap;
        }

        .footer-section {
            flex: 1 1 300px;
            margin-bottom: 30px;
        }

        .section-content {
            padding: 0 20px;
        }

        .section-content h2 {
            font-size: 24px;
            margin-bottom: 15px;
            color: #f393ec;
        }

        .section-content p,
        .section-content ul {
            color: #f393ec;
            line-height: 1.6;
        }

        .section-content ul {
            padding: 0;
            list-style: none;
        }

        .section-content ul li {
            margin-bottom: 10px;
        }

        .section-content ul li a {
            color: #f393ec;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .section-content ul li a:hover {
            color: #f393ec;
        }

        .social-icons {
            display: flex;
            align-items: center; /* Center align items vertically */
        }

        .social-icons a {
            margin-right: 15px;
            transition: transform 0.3s ease; /* Smooth transition for hover effect */
        }

        .social-icons a:last-child {
            margin-right: 0; /* Remove margin-right from the last child to prevent extra space */
        }

        .social-icons a img {
            width: 30px; /* Set a specific width for the icons */
            height: 30px; /* Set a specific height for the icons */
            border-radius: 50%; /* Make the icons circular */
            transition: opacity 0.3s ease; /* Smooth transition for opacity */
        }

        .social-icons a:hover {
            transform: translateY(-3px); /* Move the icon up slightly on hover */
        }

        .social-icons a:hover img {
            opacity: 0.7; /* Reduce opacity of the icon on hover */
        }


        .footer-bottom {
            text-align: center;
            padding: 15px 0;
            background-color: #a93ca9;
            color: #e9a2db;
            font-size: 14px;
            width: 100%;
        }     
</style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="images/logo2.jpg" alt="Reunite Paws">
            <h1>Reunite Paws</h1>
        </div>
        <nav>
            <a href="Dashboard.php">Home</a>
            <a href="#">Tentang Kami</a>
            <a href="lostfound.php">Lost Dogs</a>
            <a href="#">Kontak Kami</a>
            <input type="text" placeholder="Search in site">
        </nav>
    </header>
    <main>
        <div class="report-section">
            <h2>Report a Lost Dog</h2>
            <p>Fill out the form below to report a lost dog.</p>
        </div>
        <div class="form-section">
            <img src="images/lostdog.jpg" alt="Lost Dog Image">
            <form method="POST" action="lostfound.php" id="lostDogForm" enctype="multipart/form-data">
                <!-- Success message container -->
                <div id="successMessageContainer"></div>
                <div class="form-group">
                    <label for="dogPhoto">Upload Photo</label>
                    <input type="file" id="dogPhoto" name="dogPhoto" accept="image/png, image/jpeg" required>
                    <small>Accepted formats: PNG & JPEG. Max file size: 2MB.</small>
                </div>
                <div class="form-group">
                    <label for="name">Name of the Dog</label>
                    <input type="text" id="name" name="name" placeholder="Enter name" required>
                    <small>Please provide the dog's name.</small>
                </div>
                <div class="form-group">
                    <label for="age">Age</label>
                    <input type="text" id="age" name="age" placeholder="Enter age" required>
                    <small>Estimated age in years.</small>
                </div>
                <div class="form-group">
                    <label for="description">Physical Description</label>
                    <input type="text" id="description" name="description" placeholder="Enter description" required>
                    <small>e.g. breed, color, size.</small>
                </div>
                <div class="form-group">
                    <label for="location">Last Seen Location</label>
                    <input type="text" id="location" name="location" placeholder="Enter location" required>
                    <small>Where the dog was last seen.</small>
                    <!-- Add map integration here if needed -->
                </div>
                <div class="form-group">
                    <label for="datetime">Date and Time</label>
                    <input type="text" id="datetime" name="datetime" placeholder="Select date and time" required>
                    <small>When the dog was last seen.</small>
                </div>
                <div class="form-buttons">
                    <button type="reset" class="cancel-button">Cancel</button>
                    <button type="submit" class="submit-button">Submit</button>
                </div>
            </form>
        </div>
        <div class="reports-section">
            <h2>Lost Dog Reports</h2>
            <?php
        require_once 'utils/connect.php'; // Adjust this to your actual connection file

        // Fetch data from database
        $sql = "SELECT dog_name, dog_age, dog_description, last_seen_location, datetime, photo_path FROM lostandfound";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $dogName = htmlspecialchars($row['dog_name']);
                $dogAge = htmlspecialchars($row['dog_age']);
                $dogDescription = htmlspecialchars($row['dog_description']);
                $lastSeenLocation = htmlspecialchars($row['last_seen_location']);
                $dateTime = htmlspecialchars($row['datetime']);
                $photoPath = htmlspecialchars($row['photo_path']);

                // Output each report as HTML
                echo '<div class="report">';
                echo '<img src="' . $photoPath . '" alt="' . $dogName . '">';
                echo '<div class="report-details">';
                echo '<h3>Name: ' . $dogName . '</h3>';
                echo '<p>Age: ' . $dogAge . ' years</p>';
                echo '<p>Description: ' . $dogDescription . '</p>';
                echo '<p>Last Seen: ' . $lastSeenLocation . '</p>';
                echo '<p>Date: ' . $dateTime . '</p>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<p>No lost dog reports found.</p>';
        }

        // Close the database connection
        $conn->close();
        ?>
        </div>
    </main>
    <div id="footer">
        <div class="footer-section">
            <div class="section-content about">
                <h2>Tentang Kami</h2>
                <p>Petfinder berdedikasi untuk membantu anjing liar menemukan rumah yang penuh kasih sayang dan memberikan dukungan kepada pemilik hewan peliharaan yang membutuhkan. Bergabunglah bersama kami dalam membuat perbedaan.</p>
            </div>
        </div>
        <div class="footer-section">
            <div class="section-content contact">
                <h2>Kontak Kami</h2>
                <ul>
                <li><a href="mailto:kurnia.10121238@mahasiswa.unikom.ac.id">kurnia.10121238@mahasiswa.unikom.ac.id</a></li>
                <li>+6285155446496</li>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3961.038110959514!2d107.61001206247053!3d-6.886038599999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e73d90f73c03%3A0x13403a208d0e05f!2sUNIKOM%20DAGO!5e0!3m2!1sid!2sid!4v1722777519745!5m2!1sid!2sid" width="250" height="250" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </ul>
            </div>
        </div>
        <div class="footer-section">
            <div class="section-content social">
                <h2>Follow Kami</h2>
                <div class="social-icons">
                    <a href="#"><img src="images/facebook.jpg" alt="Facebook"></a>
                    <a href="#"><img src="images/twitter.jpg" alt="Twitter"></a>
                    <a href="#"><img src="images/instagram.jpg" alt="Instagram"></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; 2024 Petfinder. All Rights Reserved.
        </div>
   </div>

    <script>
        // Initialize Pikaday datepicker
        new Pikaday({
            field: document.getElementById('datetime'),
            format: 'YYYY-MM-DD HH:mm:ss',
            onSelect: function() {
                document.getElementById('datetime').value = this.getMoment().format('YYYY-MM-DD HH:mm:ss');
            }
        });

        // Handle form submission
        document.getElementById('lostDogForm').addEventListener('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(this);

            fetch('lostfound.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    var successMessageContainer = document.getElementById('successMessageContainer');
                    var successMessage = document.createElement('div');
                    successMessage.className = 'success-message';
                    successMessage.textContent = 'Dog details saved successfully!';
                    successMessageContainer.innerHTML = ''; // Clear previous messages
                    successMessageContainer.appendChild(successMessage);
                    document.getElementById('lostDogForm').reset(); // Clear the form
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>
