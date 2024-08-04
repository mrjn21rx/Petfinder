<?php
session_start();
require_once 'utils/connect.php';  // Ensure this path is correct

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$response = [
    'success' => false,
    'message' => 'Failed to save details'
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['description'], $_POST['location'])) {
        $description = trim($_POST['description']);
        $location = trim($_POST['location']);

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
        $stmt = $conn->prepare("INSERT INTO emergencyreport (description, location, photos) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $description, $location, $photos_serialized);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Details successfully saved!';
        } else {
            $response['message'] = 'Failed to save details: ' . $stmt->error;
        }

        $stmt->close();
    } elseif (isset($_POST['report_id'], $_POST['status'])) {
        $report_id = $_POST['report_id'];
        $status = $_POST['status'];

        // Update status in the database
        $stmt = $conn->prepare("UPDATE emergencyreport SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $report_id);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Status successfully updated!';
        } else {
            $response['message'] = 'Failed to update status: ' . $stmt->error;
        }

        $stmt->close();
    } else {
        $response['message'] = 'Required fields missing';
    }

    echo json_encode($response);
    exit;
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency Rescue Form</title>
    <link rel="stylesheet" href="css/emergency.css">
    <style>

    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="images/logo2.jpg" alt="Logo">
            <h1>Emergency Rescue</h1>
        </div>
        <nav>
            <a href="Dashboard.php">Home</a>
            <a href="lostfound.php">Lost dogs</a>
            <a href="#">Tentang Kami</a>
            <input type="search" placeholder="Search in site">
        </nav>
    </header>

    <div class="container">
        <h2>Emergency Rescue Form</h2>

        <div id="notification" class="notification"></div>

        <div class="form-section">
            <div class="image-section">
                <img src="images/EmergencyDog.jpg" alt="Dogs">
            </div>
            <form id="rescueForm" enctype="multipart/form-data">
                <label for="description">Dog's Description</label>
                <input type="text" id="description" name="description" placeholder="Describe the injured dog's condition" required>

                <label for="location">Location</label>
                <input type="text" id="location" name="location" placeholder="Enter the exact location of the injured dog" required>

                <label for="photos">Upload Photos</label>
                <input type="file" id="photos" name="photos[]" multiple accept="image/*">

                <button type="submit">Submit Report</button>
            </form>
        </div>
    </div>
    <div class="emergency-reports">
    <h2>Emergency Reports</h2>
    <div id="reportList">
        <?php
        // Fetch reports from the database
        require_once 'utils/connect.php'; // Ensure the path is correct

        $sql = "SELECT id, description, location, photos, status FROM emergencyreport";
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
                echo '<div class="status-update">';
                echo '<select class="status-select" data-report-id="' . htmlspecialchars($row['id']) . '">';
                echo '<option value="status" ' . ($row['status'] == 'status' ? 'selected' : '') . '>Status</option>';
                echo '<option value="rescued" ' . ($row['status'] == 'rescued' ? 'selected' : '') . '>Rescued</option>';
                echo '<option value="rescue in progress" ' . ($row['status'] == 'rescue in progress' ? 'selected' : '') . '>Rescue in Progress</option>';
                echo '</select>';
                echo '<button class="save-status-button">Save</button>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<p>No reports found.</p>';
        }

        $conn->close();
        ?>
    </div>
</div>


<div class="vet-clinics">
    <h2>Featured Vet Clinics</h2>
    <button>View Clinics</button>
    <div class="clinics">
        <div class="clinic">
            <img src="images/vetclinic.jpg" alt="Vet Clinic 1">
        </div>
        <div class="clinic">
            <img src="images/vetclinic2.jpg" alt="Vet Clinic 2">
        </div>
        <div class="clinic">
            <img src="images/vetclinic2.jpg" alt="Vet Clinic 3">
        </div>
        <div class="clinic">
            <img src="images/vetclinic2.jpg" alt="Vet Clinic 4">
        </div>
        <div class="clinic">
            <img src="images/vetclinic2.jpg" alt="Vet Clinic 5">
        </div>
    </div>
</div>


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
 document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('#rescueForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);

        fetch('Emergency.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            displayMessage(data.message, data.success ? 'success' : 'error');
        })
        .catch(error => {
            console.error('Error:', error);
            displayMessage('Failed to save details. Please try again.', 'error');
        });
    });

    function displayMessage(message, type) {
        const notification = document.getElementById('notification');
        notification.textContent = message;
        notification.className = `notification ${type}`;
        notification.style.display = 'block';

        setTimeout(() => {
            notification.style.display = 'none';
        }, 5000);
    }
    statusSelects.forEach(select => {
                select.addEventListener('change', function () {
                    const reportId = this.dataset.reportId;
                    const status = this.value;
                    if (status !== 'status') {
                        updateStatus(reportId, status);
                    }
                });
            });

            saveButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const reportId = this.dataset.reportId;
                    const status = this.previousElementSibling.querySelector('select').value;
                    if (status !== 'status') {
                        updateStatus(reportId, status);
                    }
                });
            });

            function updateStatus(reportId, status) {
                fetch('ReportStray.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `report_id=${reportId}&status=${status}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        notification.className = 'notification success';
                    } else {
                        notification.className = 'notification error';
                    }
                    notification.textContent = data.message;
                    notification.style.display = 'block';
                });
            }
        });


    </script>
</body>
</html>
