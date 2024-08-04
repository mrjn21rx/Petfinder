<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Petfinder- Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <style>
               * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        header {
            background-color: #fff;
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }
        .logo img {
            height: 40px;
            margin-right: 10px;
            border-radius: 50px;
        }
        nav a {
            margin: 0 10px;
            text-decoration: none;
            color: #000;
            background-color: #f8f8f8;
        }
        nav a:hover {
            text-decoration: underline;
            color: #d84d1f;
        }               
        .dashboard-text h2 {
            display: flex;
            flex-direction: column;
            font-size: 3em;
            color: #ee9d2b;
        }
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
        <img src="images/logo2.jpg" alt="Petfinder">
        <h1>Petfinder</h1>
        </div>
        <nav>
            <a href="#">Home</a>
            <a href="#">Tentang Kami</a>
            <a href="Login.php">Login</a>
            <a href="dogProfile.php">Profile</a> 
        </nav>
    </header>
    <main>
        <div class="dashboard-section">
            <img src="images/puppy.jpg" alt="Dog Image">
            <div class="dashboard-text">
                <h2>Di setiap kesesatan, ada cerita yang tak terungkap  Dalam setiap klik, kehidupan terungkap.❣</h2>
                <p>Selamat datang di dasbor inisiatif penyelamatan anjing liar kami.</p>
            </div>
        </div>
        <div class="quick-access-section">
            <h2>Quick Access</h2>
            <div class="quick-access-buttons">
                <div class="quick-access-item">
                    <a href="ReportStray.php"><img src="images/speaker.jpg" alt="Report a Stray"></a>
                    <h3>Report a Stray</h3>
                    <p>Bantulah seekor anjing yang membutuhkan</p>
                </div>
                <div class="quick-access-item">
                    <a href="Emergency.php"><img src="images/emergency.jpg" alt="Emergency Rescue"></a>
                    <h3>Emergency Rescue</h3>
                    <p>Bantuan Segera</p>
                </div>
                <div class="quick-access-item">
                <a href="lostfound.php"><img src="images/search.png" alt="Reunite Paws"></a>
                <h3>Reunite Paws</h3>
                    <p>Temukan anjing yang hilang</p>
                </div>
                <div class="quick-access-item">
                    <a href="Volunteer.php"><img src="images/volunteer.png" alt="Volunteer"></a>
                    <h3>Volunteer</h3>
                    <p>Bergabunglah dengan tujuan kami</p>
                </div>
                <div class="quick-access-item">
                    <a href="ShelterFoster.php"><img src="images/adoption.jpg" alt="Adoption"></a>
                    <h3>Shelters and Fosters</h3>
                    <p>Sediakan rumah</p>
                </div>
            </div>
        </div>
        <div class="map-section">
            <img src="images/banner.jpg" alt="Dog Icons">
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

</body>
</html>
