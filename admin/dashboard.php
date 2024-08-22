<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

include('connection.php');


// Fetch the count of contacts
$sql_count_contacts = "SELECT COUNT(*) as count FROM contacts";
$result_count_contacts = $conn->query($sql_count_contacts);
$count_contacts = $result_count_contacts->fetch_assoc()['count'];

// Fetch the count of appointments
$sql_count_appointments = "SELECT COUNT(*) as count FROM appointments";
$result_count_appointments = $conn->query($sql_count_appointments);
$count_appointments = $result_count_appointments->fetch_assoc()['count'];

// Fetch the data for the tables
$sql_contacts = "SELECT * FROM contacts";
$result_contacts = $conn->query($sql_contacts);

$sql_appointments = "SELECT * FROM appointments";
$result_appointments = $conn->query($sql_appointments);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Floraisondental | Dashboard</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">

    <!-- =======================================================
  * Template Name: NiceAdmin
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Updated: Apr 20 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-between">
            <a href="dashboard.php" class="logo d-flex align-items-center">
                <img src="assets/img/logo.png" alt="">
                <span class="d-none d-lg-block">Admin</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div><!-- End Logo -->

        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">

                <li class="nav-item d-block d-lg-none">
                    <a class="nav-link nav-icon search-bar-toggle " href="#">
                        <i class="bi bi-search"></i>
                    </a>
                </li><!-- End Search Icon-->

                <li class="nav-item dropdown pe-3">

                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
                        <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $_SESSION['username']; ?></span>
                    </a><!-- End Profile Iamge Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6><?php echo $_SESSION['username']; ?></h6>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="logout.php">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Sign Out</span>
                            </a>
                        </li>

                    </ul><!-- End Profile Dropdown Items -->
                </li><!-- End Profile Nav -->

            </ul>
        </nav><!-- End Icons Navigation -->

    </header><!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">

            <li class="nav-item">
                <a class="nav-link " href="dashboard.php">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-menu-button-wide"></i><span>Components</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="components-alerts.html">
                            <i class="bi bi-circle"></i><span>Alerts</span>
                        </a>
                    </li>
                </ul>
            </li> -->

        </ul>

    </aside><!-- End Sidebar-->

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Dashboard</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section dashboard">
            <div class="row">

                <!-- Left side columns -->
                <div class="col-lg-12">
                    <div class="row">

                        <!-- Appointments Card -->
                        <div class="col-xxl-4 col-md-6">
                            <div class="card info-card sales-card">
                                <div class="card-body">
                                    <h5 class="card-title">Appointments</h5>
                                    <div class="d-flex align-items-center">
                                        <!-- <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-cart"></i>
                                        </div> -->
                                        <div class="ps-3">
                                            <h6><?php echo $count_appointments; ?></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- End Appointments Card -->

                        <!-- Contacts Card -->
                        <div class="col-xxl-4 col-md-6">
                            <div class="card info-card revenue-card">
                                <div class="card-body">
                                    <h5 class="card-title">Contacts</h5>
                                    <div class="d-flex align-items-center">
                                        <!-- <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-currency-dollar"></i>
                                        </div> -->
                                        <div class="ps-3">
                                            <h6><?php echo $count_contacts; ?></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- End Contacts Card -->

                    </div>
                    <div class="row">
                        <div class="col-lg-12">

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Appoinments</h5>

                                    <!-- Table with stripped rows -->
                                    <table class="table datatable">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Phone</th>
                                                <th>Email</th>
                                                <th>Time</th>
                                                <th>Service</th>
                                                <th>Message</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($result_appointments->num_rows > 0) {
                                                $index = 1; // Initialize the index counter
                                                while ($row = $result_appointments->fetch_assoc()) {
                                                    echo "<tr><td>" . $index . "</td><td>" . $row["name"] . "</td><td>" . $row["phone"] . "</td><td>" . $row["email"] . "</td><td>" . $row["time"] . "</td><td>" . $row["service"] . "</td><td>" . $row["message"] . "</td></tr>";
                                                    $index++; // Increment the index counter
                                                }
                                            } else {
                                                echo "<tr><td colspan='7'>No appointments found</td></tr>";
                                            }
                                            ?>
                                        </tbody>

                                    </table>
                                    <!-- End Table with stripped rows -->

                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Contacts</h5>

                                    <!-- Table with stripped rows -->
                                    <table class="table datatable">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Message</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($result_contacts->num_rows > 0) {
                                                $index = 1; // Initialize the index counter
                                                while ($row = $result_contacts->fetch_assoc()) {
                                                    echo "<tr><td>" . $index . "</td><td>" . $row["name"] . "</td><td>" . $row["email"] . "</td><td>" . $row["message"] . "</td></tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='4'>No contacts found</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <!-- End Table with stripped rows -->

                                </div>
                            </div>

                        </div>
                    </div>
                </div><!-- End Left side columns -->

            </div>

        </section>

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>Floraisondental</span></strong>. All Rights Reserved
        </div>
        <div class="credits">
            <!-- All the links in the footer should remain intact. -->
            <!-- You can delete the links only if you purchased the pro version. -->
            <!-- Licensing information: https://bootstrapmade.com/license/ -->
            <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
        </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/chart.js/chart.umd.js"></script>
    <script src="assets/vendor/echarts/echarts.min.js"></script>
    <script src="assets/vendor/quill/quill.js"></script>
    <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>

</body>

</html>
<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>

</body>

</html>