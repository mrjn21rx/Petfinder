<?php
session_start();
require_once 'utils/connect.php';

// Initialize response variables
$response = [
    'success' => false,
    'message' => 'Failed to save details'
];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['description'], $_POST['location'], $_POST['behaviour'])) {
        $description = $_POST['description'];
        $location = $_POST['location'];
        $behaviour = $_POST['behaviour'];

        // File upload handling
        $photos = [];
        if (!empty($_FILES['photos']['name'][0])) {
            $target_dir = "uploads/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            foreach ($_FILES['photos']['name'] as $key => $name) {
                $target_file = $target_dir . basename($name);
                if (move_uploaded_file($_FILES['photos']['tmp_name'][$key], $target_file)) {
                    $photos[] = $target_file;
                } else {
                    $response['message'] = 'Failed to upload some files';
                    echo json_encode($response);
                    exit;
                }
            }
        }

        $photos_serialized = serialize($photos);

        // Insert data into database
        $stmt = $conn->prepare("INSERT INTO reportstray (description, location, photos, behaviour) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $description, $location, $photos_serialized, $behaviour);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Details successfully saved!';
        } else {
            $response['message'] = 'Failed to save details';
        }

        $stmt->close();
    } elseif (isset($_POST['report_id'], $_POST['status'])) {
        $report_id = $_POST['report_id'];
        $status = $_POST['status'];

        // Update status in the database
        $stmt = $conn->prepare("UPDATE reportstray SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $report_id);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Status successfully updated!';
        } else {
            $response['message'] = 'Failed to update status';
        }

        $stmt->close();
    } else {
        $response['message'] = 'Required fields missing';
    }

    // Set session variables to handle the notification in the HTML part
    $_SESSION['response'] = $response;

    // Redirect back to the form page to display the message
    header("Location: ReportStray.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report a Stray Dog</title>
    <link rel="stylesheet" href="css/reportStray.css">
    <style>
       .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }

        .form-container, .reports-section {
            width: 80%;
            max-width: 800px;
            margin-bottom: 40px;
        }

        .notification {
            display: none;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .notification.success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .notification.error {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        @media (max-width: 768px) {
            .form-container, .reports-section {
                width: 100%;
            }

            .report {
                flex-direction: column;
                align-items: flex-start;
            }

            .report img {
                width: 100%;
                height: auto;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="images/logo2.jpg" alt="Reunite Paws">
            <h1>Report a stray</h1>
        </div>
        <nav>
            <a href="Dashboard.php">Home</a>
            <a href="#">Tentang Kami</a>
            <a href="lostfound.php">Lost Dogs</a>
            <a href="#">Kontak Kami</a>
        </nav>
    </header>
    <main>
        <div class="report-section">
            <h2>Report a Stray Dog</h2>
            <p>Fill out the form below to report a stray dog.</p>
        </div>
        <div class="container">
            <div class="form-container">
                <div class="image-section">
                    <img src="images/straydog.jpeg" alt="Stray Dog">
                </div>
                <div class="form-section">
                    <!-- Display success or error message -->
                    <div id="notification" class="notification"></div>

                    <!-- Form for reporting a stray dog -->
                    <form id="reportForm" action="ReportStray.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" id="responseMessage" value="<?php echo isset($_SESSION['response']) ? $_SESSION['response']['message'] : ''; ?>">
                        <input type="hidden" id="responseSuccess" value="<?php echo isset($_SESSION['response']) ? $_SESSION['response']['success'] : ''; ?>">
                        <h2>Report Form</h2>
                        <p>Please provide detailed information about the stray dog.</p>
                        <label for="description">Dog's Description</label>
                        <input type="text" id="description" name="description" required>

                        <label for="location">Location</label>
                        <input type="text" id="location" name="location" required>

                        <label for="photos">Photos</label>
                        <input type="file" id="photos" name="photos[]" multiple accept="image/*">

                        <label>Behaviour</label>
                        <div class="radio-group">
                            <label><input type="radio" name="behaviour" value="Aggressive" required> Aggressive</label>
                            <label><input type="radio" name="behaviour" value="Friendly" required> Friendly</label>
                        </div>

                        <button type="submit">Submit Report</button>
                    </form>
                </div>
            </div>
            <div class="reports-section">
                <h2>Stray Dog Reports</h2>
                <?php
                // Fetch reports from the database
                require_once 'utils/connect.php';

                $sql = "SELECT id, description, location, photos, behaviour, status FROM reportstray";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $photos = unserialize($row['photos']);
                        echo '<div class="report">';
                        if (!empty($photos)) {
                            echo '<img src="' . htmlspecialchars($photos[0]) . '" alt="Dog Photo">';
                        }
                        echo '<div class="report-details">';
                        echo '<h3>Description: ' . htmlspecialchars($row['description']) . '</h3>';
                        echo '<p>Location: ' . htmlspecialchars($row['location']) . '</p>';
                        echo '<p>Behaviour: ' . htmlspecialchars($row['behaviour']) . '</p>';
                        echo '</div>';
                        echo '<div class="status-container">';
                        echo '<div class="status-dropdown">';
                        echo '<select class="status-select" data-report-id="' . htmlspecialchars($row['id']) . '">';
                        echo '<option value="status" ' . ($row['status'] == 'status' ? 'selected' : '') . '>Status</option>';
                        echo '<option value="rescued" ' . ($row['status'] == 'rescued' ? 'selected' : '') . '>Rescued</option>';
                        echo '<option value="rescue in progress" ' . ($row['status'] == 'rescue in progress' ? 'selected' : '') . '>Rescue in Progress</option>';
                        echo '</select>';
                        echo '</div>';
                        echo '<button class="save-button" data-report-id="' . htmlspecialchars($row['id']) . '">Save</button>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No reports available.</p>';
                }

                $conn->close();
                ?>
            </div>
           <div class="shelters">
                <h2>Nearest Shelters and Foster Homes</h2>
                <button class="search-button" onclick="searchShelters()">Search for Shelters</button>
                <div class="shelter">
                    <img src="images/map.jpg" alt="Dummy Map">
                      <p>Click the button above to search for nearest shelters and foster homes.</p>                      
                </div>
            </div>
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
        // Function to display the success or error message
        function displayMessage() {
            const notification = document.getElementById('notification');
            const responseMessage = document.getElementById('responseMessage').value;
            const responseSuccess = document.getElementById('responseSuccess').value;

            if (responseMessage) {
                notification.innerHTML = responseMessage;
                notification.classList.add(responseSuccess == '1' ? 'success' : 'error');
                notification.style.display = 'block';
            }
        }

        // Display the message on page load
        window.onload = displayMessage;

        // Handle status update
        document.querySelectorAll('.save-button').forEach(button => {
            button.addEventListener('click', function () {
                const reportId = this.dataset.reportId;
                const status = this.previousElementSibling.querySelector('.status-select').value;

                const formData = new FormData();
                formData.append('report_id', reportId);
                formData.append('status', status);

                fetch('ReportStray.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        if (data.success) {
                            location.reload();
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    </script>
</body>
</html>
