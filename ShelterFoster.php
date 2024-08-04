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
    if (isset($_POST['name'], $_POST['type'], $_POST['contactPerson'], $_POST['phoneNumber'], $_POST['email'], $_POST['address'], $_POST['geolocation'], $_POST['capacity'], $_POST['availability'])) {
        $name = $_POST['name'];
        $type = $_POST['type'];
        $contactPerson = $_POST['contactPerson'];
        $phoneNumber = $_POST['phoneNumber'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $geolocation = $_POST['geolocation'];
        $capacity = $_POST['capacity'];
        $availability = $_POST['availability'];

        // Insert data into database
        $stmt = $conn->prepare("INSERT INTO shelterfoster (name, type, contact_person, phone_number, email, address, geolocation, capacity, availability) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssis", $name, $type, $contactPerson, $phoneNumber, $email, $address, $geolocation, $capacity, $availability);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Details successfully saved!';
        } else {
            $response['message'] = 'Error: ' . $stmt->error;
        }

        $stmt->close();
    } else {
        $response['message'] = 'Please fill all required fields';
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
    <title>Register Your Shelter or Foster Home</title>
    <link rel="stylesheet" href="css/shelterfoster.css">
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

        .notification {
            display: none;
            padding: 10px;
            margin-bottom: 10px;
            color: white;
            font-weight: bold;
            text-align: center;
            border-radius: 5px;
        }

        .notification.success {
            background-color: green;
        }

        .notification.error {
            background-color: red;
        }
       
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="images/logo2.jpg" alt="Petfinder Logo">
            <h1>Bergabunglah dengan Kami untuk Menyelamatkan Anjing Liar</h1>
        </div>
        <nav>
            <a href="Dashboard.php">Home</a>
            <a href="Adoption.php">Adopt Dog</a>
            <a href="#">Tentang Kami</a>
            <a href="#">Kontak Kami</a>
        </nav>
    </header>
    <div class="report-section">
        <div class="report-text">
            <h2>Register Your Shelter or <br> Foster Home</h2>
            <p>Complete the form below with all required information.</p>
        </div>
        <img src="images/sheltericon.jpg" alt="Shelter Image">
    </div>
    <div class="container">
        <div class="form-wrapper">
            <div class="form-image">
                <img src="images/foster.jpg" alt="Shelter Image">
            </div>
            <div class="form-container">
                <!-- Display success or error message -->
                <div id="notification" class="notification"></div>

                <form id="shelterForm" action="ShelterFoster.php" method="POST">
                    <h3>Basic Information</h3>
                    <div class="form-group">
                        <label for="name">Name of the Shelter/Foster Home</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Type of Entity</label>
                        <div class="button-group">
                            <button type="button" class="type-button" data-value="Animal Shelter">Animal Shelter</button>
                            <button type="button" class="type-button" data-value="Foster Home">Foster Home</button>
                        </div>
                        <input type="hidden" id="type" name="type" required>
                    </div>
                    <div class="form-group">
                        <label for="contactPerson">Contact Person</label>
                        <input type="text" id="contactPerson" name="contactPerson" required>
                    </div>
                    <div class="form-group">
                        <label for="phoneNumber">Phone Number</label>
                        <input type="text" id="phoneNumber" name="phoneNumber" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Physical Address</label>
                        <input type="text" id="address" name="address" required>
                    </div>
                    <div class="form-group">
                        <label for="geolocation">Geolocation (latitude and longitude)</label>
                        <input type="text" id="geolocation" name="geolocation">
                    </div>
                    <div class="form-group">
                        <label for="capacity">Capacity</label>
                        <input type="number" id="capacity" name="capacity" required>
                    </div>
                    <div class="form-group">
                        <label for="availability">Availability</label>
                        <input type="text" id="availability" name="availability">
                    </div>
                    <button type="submit">Submit</button>
                    <button type="button" id="cancelButton" class="cancel-button">Cancel</button>
                </form>
            </div>
        </div>
        <div class="banner">
            <img src="images/fosterbanner.png" alt="Dog Icons">
            <p>Thank you for your interest in supporting stray animals. Your registration helps us provide better care and find loving homes for these animals.</p>
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
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('shelterForm');
            const notification = document.getElementById('notification');
            const typeButtons = document.querySelectorAll('.type-button');
            const typeInput = document.getElementById('type');
            const cancelButton = document.getElementById('cancelButton');

            typeButtons.forEach(button => {
                button.addEventListener('click', () => {
                    typeButtons.forEach(btn => btn.classList.remove('selected'));
                    button.classList.add('selected');
                    typeInput.value = button.getAttribute('data-value');
                });
            });

            form.addEventListener('submit', async (event) => {
                event.preventDefault();

                const formData = new FormData(form);

                if (!typeInput.value) {
                    displayMessage('Please select the type of entity.', 'error');
                    return;
                }

                try {
                    const response = await fetch('ShelterFoster.php', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await response.json();

                    if (data.success) {
                        displayMessage(data.message, 'success');
                        form.reset(); // Clear the form after successful submission
                    } else {
                        displayMessage(data.message || 'Failed to save details. Please try again.', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    displayMessage('Failed to save details. Please try again.', 'error');
                }
            });

            function displayMessage(message, type) {
                notification.textContent = message;
                notification.className = `notification ${type}`;
                notification.style.display = 'block';

                // Remove the message after 5 seconds
                setTimeout(() => {
                    notification.style.display = 'none';
                }, 5000);
            }

            cancelButton.addEventListener('click', () => {
                form.reset(); // Clear the form when cancel is clicked
                notification.style.display = 'none'; // Hide notification if cancel is clicked
            });
        });
    </script>
</body>
</html>
