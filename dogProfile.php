<?php
session_start();
require_once 'utils/connect.php'; // Ensure this path is correct

$response = [
    'success' => false,
    'message' => 'Failed to save details'
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are set
    if (isset($_FILES['photo'], $_POST['age'], $_POST['dogName'], $_POST['description'], $_POST['gender'])) {
        $photo = $_FILES['photo']['name'];
        $age = $_POST['age'];
        $dogName = $_POST['dogName'];
        $description = $_POST['description'];
        $video = isset($_FILES['video']['name']) ? $_FILES['video']['name'] : '';
        $gender = $_POST['gender'];

        // File upload handling
        $target_dir = "uploads/";
        $target_file_photo = $target_dir . basename($_FILES["photo"]["name"]);
        $target_file_video = isset($_FILES["video"]["name"]) ? $target_dir . basename($_FILES["video"]["name"]) : '';

        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file_photo) && 
            (empty($video) || move_uploaded_file($_FILES["video"]["tmp_name"], $target_file_video))) {
            // Insert data into database
            $stmt = $conn->prepare("INSERT INTO dogprofile (photo, age, dog_name, description, video, gender) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sissss", $photo, $age, $dogName, $description, $video, $gender);

            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Dog profile successfully saved!';
            } else {
                $response['message'] = 'Failed to save details: ' . $stmt->error;
            }

            $stmt->close();
        } else {
            $response['message'] = 'Failed to upload photo or video';
        }
    } else {
        $response['message'] = 'Required fields are missing';
    }

    echo json_encode($response);
    exit;
}

// Fetch dog profiles for display
$profiles = [];
$result = $conn->query("SELECT id, photo, age, dog_name, description, video, gender FROM dogprofile");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $profiles[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dog Profile Form</title>
    <link rel="stylesheet" href="css/dogProfile.css">
    <style>
  
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="images/logo2.jpg" alt="Logo">
            <h1>Dog Profile</h1>
        </div>
        <nav>
            <a href="Dashboard.php">Home</a>
            <a href="#">Tentang Kami</a>
            <a href="#">Kontak Kami</a>
            <a href="Adoption.php">Adopt</a>
            <input type="search" placeholder="Search...">
        </nav>
    </header>
    <div class="hero">
        <div class="hero-content">
            <img src="images/icon.jpg" alt="Dog profile" class="hero-image">
            <div class="hero-text">
                <h2>Meet Our Dogs</h2>
                <p>With Petfinder, every stray finds its way home.</p>
                <a class="adopt-button" href="Adoption.php">Adopt Now</a>
                <a class="register-button" href="ShelterFoster.php">Register as Shelter/Foster</a>
            </div>
        </div>
    </div>

    <div class="container">
        <h2>Create Dog Profile</h2>

        <div id="notification" class="notification"></div>

        <div class="form-section">
            <form id="dogProfileForm" enctype="multipart/form-data" method="POST">
                <label for="photo">Photo</label>
                <input type="file" id="photo" name="photo" accept="image/*" required>

                <label for="dogName">Dog Name</label>
                <input type="text" id="dogName" name="dogName" required>

                <label for="age">Age</label>
                <input type="number" id="age" name="age" required>

                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4" required></textarea>

                <label for="video">Video (optional)</label>
                <input type="file" id="video" name="video" accept="video/*">

                <div class="gender-options">
                <label for="gender">Gender</label>
                    <label><input type="radio" name="gender" value="male" required> Male</label>
                    <label><input type="radio" name="gender" value="female" required> Female</label>
                </div>

                <div class="button-group">
                    <button type="reset">Reset</button>
                    <button type="submit">Submit</button>
                </div>
            </form>
        </div>

        <div class="profiles-section">
            <h2>Dog Profiles</h2>
            <div class="profiles" id="profiles">
                <?php foreach ($profiles as $profile): ?>
                    <div class="profile" onclick="toggleProfileDetails(<?php echo $profile['id']; ?>)">
                        <img src="uploads/<?php echo $profile['photo']; ?>" alt="<?php echo htmlspecialchars($profile['dog_name']); ?>">
                        <h3><?php echo htmlspecialchars($profile['dog_name']); ?></h3>
                        <p>Age: <?php echo htmlspecialchars($profile['age']); ?> years</p>
                        <div id="profile-details-<?php echo $profile['id']; ?>" class="profile-details" style="display: none;">
                            <p>Description: <?php echo htmlspecialchars($profile['description']); ?></p>
                            <p>Gender: <?php echo htmlspecialchars($profile['gender']); ?></p>
                            <?php if ($profile['video']): ?>
                                <p>Video: <a href="uploads/<?php echo $profile['video']; ?>" target="_blank">Watch Video</a></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
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
        document.getElementById('dogProfileForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            const form = event.target;
            const formData = new FormData(form);
            const notification = document.getElementById('notification');

            fetch('dogProfile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayMessage(data.message, 'success');
                    form.reset(); // Clear the form after successful submission
                    loadProfiles(); // Reload profiles after submission
                } else {
                    displayMessage(data.message || 'Failed to save details. Please try again.', 'error');
                }
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

            // Remove the message after 5 seconds
            setTimeout(() => {
                notification.style.display = 'none';
            }, 5000);
        }

        function loadProfiles() {
            fetch('getProfiles.php')
                .then(response => response.json())
                .then(data => {
                    const profilesContainer = document.getElementById('profiles');
                    profilesContainer.innerHTML = '';
                    data.forEach(profile => {
                        const profileDiv = document.createElement('div');
                        profileDiv.className = 'profile';
                        profileDiv.setAttribute('onclick', `toggleProfileDetails(${profile.id})`);

                        profileDiv.innerHTML = `
                            <img src="uploads/${profile.photo}" alt="${profile.dog_name}">
                            <h3>${profile.dog_name}</h3>
                            <p>Age: ${profile.age} years</p>
                            <div id="profile-details-${profile.id}" class="profile-details" style="display: none;">
                                <p>Description: ${profile.description}</p>
                                <p>Gender: ${profile.gender}</p>
                                ${profile.video ? `<p>Video: <a href="uploads/${profile.video}" target="_blank">Watch Video</a></p>` : ''}
                            </div>
                        `;
                        profilesContainer.appendChild(profileDiv);
                    });
                });
        }

        function toggleProfileDetails(id) {
            const details = document.getElementById(`profile-details-${id}`);
            if (details.style.display === 'none') {
                details.style.display = 'block';
            } else {
                details.style.display = 'none';
            }
        }

        // Load profiles when the page loads
        window.onload = loadProfiles;
    </script>
</body>
</html>
