<!DOCTYPE html>
<?php
include('function1.php');
$con = mysqli_connect("localhost", "root", "", "hms");
$doctor = $_SESSION['dname'];

// Function to check if the appointment is prescribed
function isPrescribed($id)
{
    global $con;
    $query = "SELECT * FROM prescriptiontable WHERE AppID = '$id'";
    $result = mysqli_query($con, $query);
    return mysqli_num_rows($result) > 0;
}
function isAccepted($id)
{
    global $con;
    $query = "SELECT * FROM appointment WHERE AppID = '$id' AND doctorStatus=0";
    $result = mysqli_query($con, $query);
    return mysqli_num_rows($result) > 0;
}

// Function to check if the appointment is cancelled
function isCancelled($id)
{
    global $con;
    $query = "SELECT * FROM appointment WHERE AppID = '$id' AND userStatus = 0";
    $result = mysqli_query($con, $query);
    return mysqli_num_rows($result) > 0;
}
if (isset($_GET['accept'])) {
    $AppID = $_GET['AppID'];
    $sql = mysqli_query($con, "UPDATE appointment SET doctorStatus= 0 WHERE AppID = '$AppID'");
    if ($sql) {
        echo "<script>alert('Your appointment was successfully accepted.');</script>";
    }
}

if (isset($_GET['cancel'])) {
    $AppID = $_GET['AppID'];
    $query = mysqli_query($con, "UPDATE appointment SET userStatus = 0 WHERE AppID = '$AppID'");
    if ($query) {
        echo "<script>alert('Your appointment was successfully cancelled.');</script>";
    }
}
// if (isset($_GET['accept'])) {
//     $AppID = $_GET['AppID'];
//     $query = mysqli_query($con, "UPDATE appointment SET userStatus= 1 WHERE AppID = '$AppID'");
//     if ($query) {
//         echo "<script>alert('Your appointment was successfully Accepted.');</script>";
//     }
// }

if (isset($_GET['prescribe'])) {
    $AppID = $_GET['AppID'];
    $appdate = $_GET['appdate'];
    $apptime = $_GET['apptime'];
    $disease = $_GET['disease'];
    $allergy = $_GET['allergy'];
    $prescription = $_GET['prescription'];
    $query = mysqli_query($con, "INSERT INTO prescriptiontable(doctor, AppID, appdate, apptime, disease, allergy, prescription) VALUES ('$doctor', '$AppID', '$appdate', '$apptime', '$disease', '$allergy', '$prescription');");
    if ($query) {
        echo "<script>alert('Prescribed successfully!');</script>";
    } else {
        echo "<script>alert('Unable to process your request. Try again!');</script>";
    }
}

?>

<html lang="en">

<head>
    <script src="https://kit.fontawesome.com/2323653b3c.js" crossorigin="anonymous"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style4.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <script>
        function validateForm() {
            var contactInput = document.forms["searchForm"]["contact"].value;
            var numbersOnly = /^\d+$/;
            if (contactInput === "" || !contactInput.match(numbersOnly) || contactInput.length !== 10) {
                alert("Please enter a valid 10-digit contact number.");
                return false;
            }
        }
    </script>
</head>

<body>
    <!-- dashboard -->
    <div class="sidebar">
        <div class="logo-details">
            <i class='bx bx-plus-medical'></i>
            <span class="logo_name">
                <a href="#">
                    MediBook: Appointment Schedule</a>
            </span>
        </div>
        <ul class="nav-links">
            <li>
                <a class="active" href="#list-dash">
                    <i class='bx bx-grid-alt'></i>
                    <span class="links_name">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="#list-app" id="list-pat-list" role="tab" data-toggle="list" aria-controls="home">
                    <i class='bx bx-list-ul'></i>
                    <span class="links_name">Appointments</span>
                </a>
            </li>
            <li>
                <a href="#list-pres" id="list-pres-list" role="tab" data-toggle="list" aria-controls="home">
                    <i class='bx bx-detail'></i>
                    <span class="links_name">Prescriptions</span>
                </a>
            </li>
            <li class="log_out">
                <a href="logout.php" onclick="logout()">
                    <i class='bx bx-log-out'></i>
                    <span class="links_name">Log out</span>
                </a>
            </li>
        </ul>
    </div>
    <!-- sections  -->
    <div class="section-container" id="sections">
        <!-- Navbar content -->
        <nav class="doc-nav">
            <div class="welcome">
                <i class='bx bx-menu sidebarBtn'></i>
                <span class="admin"><?php echo $_SESSION['dname'] ?></span>
            </div>
            <div>
                <form class="form-group" name="searchForm" onsubmit="return validateForm()" method="post" action="search.php">
                    <div class="psearch">
                        <div class="email-field">
                            <input class="form-control" type="text" placeholder="Enter contact number" aria-label="Search" name="contact">
                        </div>
                        <div class="submit-btn">
                            <input type="submit" class="btn btn-primary" id="inputbtn" name="search_submit" value="Search">
                        </div>
                    </div>
                </form>
            </div>
        </nav>

        <!-- Default contents and dashboard contents -->
        <div class="home-content" id="list-dash">
            <div class="overview-boxes">
                <div class="box">
                    <div class="right-side">
                        <span class="fa-stack fa-2x">
                            <i class="fa fa-square fa-stack-2x text-primary"></i>
                            <i class="fa fa-paperclip fa-stack-1x fa-inverse"></i>
                        </span>
                        <h4>My Appointments</h4>
                        <p class="cl-effect-1">
                            <a href="#app-list" onclick="clickDiv('#list-pat-list')">
                                View Appointments
                            </a>
                        </p>
                    </div>
                </div>
                <div class="box">
                    <div class="right-side">
                        <span class="fa-stack fa-2x">
                            <i class="fa fa-square fa-stack-2x text-primary"></i>
                            <i class="fa fa-list-ul fa-stack-1x fa-inverse"></i>
                        </span>
                        <h4>Prescriptions</h4>

                        <p>
                            <a href="#list-pres" onclick="clickDiv('#list-pres-list')">
                                View Prescriptions List
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Appointments view section -->
        <div class="home-content" id="list-app">
            <table class="app-table">
                <thead>
                    <tr>
                        <th scope="col">Patient ID</th>
                        <th scope="col">Appointment ID</th>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Gender</th>
                        <th scope="col">Email</th>
                        <th scope="col">Contact</th>
                        <th scope="col">Appointment Date</th>
                        <th scope="col">Appointment Time</th>
                        <th scope="col">Current Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $con = mysqli_connect("localhost", "root", "", "hms");
                    global $con;
                    $dname = $_SESSION['dname'];
                    $query = "SELECT pid, AppID, fname, lname, gender, email, contact, appdate, apptime, userStatus, doctorStatus FROM appointment WHERE doctor = '$dname';";
                    $result = mysqli_query($con, $query);
                    while ($row = mysqli_fetch_array($result)) {
                        $id = $row['AppID'];
                        $accepted = isAccepted($id);
                        $cancelled = isCancelled($id);
                        $prescribed = isPrescribed($id);
                    
                        // Adjustments for button display logic
                        $showCancelAcceptButtons = !$cancelled && !$accepted && !$prescribed;
                        $showPrescribeButton = $accepted && !$prescribed && !$cancelled; 
                    ?>
                        <tr>
                            <td><?php echo $row['pid']; ?></td>
                            <td><?php echo $row['AppID']; ?></td>
                            <td><?php echo $row['fname']; ?></td>
                            <td><?php echo $row['lname']; ?></td>
                            <td><?php echo $row['gender']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['contact']; ?></td>
                            <td><?php echo $row['appdate']; ?></td>
                            <td><?php echo $row['apptime']; ?></td>
                            <td>
                                <?php
                                if ($cancelled) {
                                    echo "Cancelled";
                                } elseif ($accepted) {
                                    echo "Accepted";
                                // } elseif ($prescribed) {
                                //     echo "Accepted And Prescribed";
                                } else {
                                    echo "Active";
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if ($showCancelAcceptButtons) {
                                    echo '<a href="doctor-panel.php?AppID=' . $row['AppID'] . '&cancel=update" onClick="return confirm(\'Are you sure you want to cancel this appointment?\')" title="Cancel Appointment"><button class="btn btn-primary">Cancel</button></a>';
                                    echo '<a href="doctor-panel.php?AppID=' . $row['AppID'] . '&accept=update" onClick="return confirm(\'Are you sure you want to accept this appointment?\')" title="Accept Appointment"><button class="btn btn-primary">Accept</button></a>';
                                } elseif ($showPrescribeButton) {
                                    echo '<a href="prescribe.php?pid=' . $row['pid'] . '&AppID=' . $row['AppID'] . '&fname=' . $row['fname'] . '&lname=' . $row['lname'] . '&appdate=' . $row['appdate'] . '&apptime=' . $row['apptime'] . '&disease=&allergy=&prescription=" title="Prescribe"><button class="btn btn-primary">Prescribe</button></a>';
                                }
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <br>
        </div>

        <!-- Prescription section -->
        <div class="home-content" id="list-pres">
            <table class="pres-table">
                <thead>
                    <tr>
                        <th scope="col">Patient ID</th>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Appointment ID</th>
                        <th scope="col">Appointment Date</th>
                        <th scope="col">Appointment Time</th>
                        <th scope="col">Disease</th>
                        <th scope="col">Allergy</th>
                        <th scope="col">Prescription</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $con = mysqli_connect("localhost", "root", "", "hms");
                    global $con;

                    $query = "SELECT pid, fname, lname, AppID, appdate, apptime, disease, allergy, prescription FROM prescriptiontable WHERE doctor = '$doctor';";

                    $result = mysqli_query($con, $query);
                    if (!$result) {
                        echo mysqli_error($con);
                    }

                    while ($row = mysqli_fetch_array($result)) {
                    ?>
                        <tr>
                            <td><?php echo $row['pid']; ?></td>
                            <td><?php echo $row['fname']; ?></td>
                            <td><?php echo $row['lname']; ?></td>
                            <td><?php echo $row['AppID']; ?></td>
                            <td><?php echo $row['appdate']; ?></td>
                            <td><?php echo $row['apptime']; ?></td>
                            <td><?php echo $row['disease']; ?></td>
                            <td><?php echo $row['allergy']; ?></td>
                            <td><?php echo $row['prescription']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const sidebarBtn = document.querySelector(".sidebarBtn");
            const sidebar = document.querySelector(".sidebar");
            const sections = document.querySelector("#sections");
            const links = document.querySelectorAll(".nav-links li a");

            // Show the dashboard section by default
            document.getElementById("list-dash").style.display = "block";
            document.querySelector(".nav-links li a.active").classList.remove("active");
            document.querySelector(".nav-links li a[href='#list-dash']").classList.add("active");

            // Hide other sections when the page loads
            document.querySelectorAll(".home-content").forEach(function(section) {
                if (section.id !== "list-dash") {
                    section.style.display = "none";
                }
            });

            // Toggle sidebar
            sidebarBtn.onclick = function() {
                sidebar.classList.toggle("active");
                if (sidebar.classList.contains("active")) {
                    sidebarBtn.classList.replace("bx-menu", "bx-menu-alt-right");
                } else {
                    sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
                }
            };

            // Handle click events for navigation links
            links.forEach(function(link) {
                link.addEventListener("click", function(event) {
                    event.preventDefault();
                    const targetSection = document.querySelector(this.getAttribute("href"));
                    sections.querySelectorAll(".home-content").forEach(function(section) {
                        section.style.display = "none";
                    });
                    targetSection.style.display = "block";
                    document.querySelector(".nav-links li a.active").classList.remove("active");
                    this.classList.add("active");
                });
            });
        });

        // logout button code
        function logout() {
            event.preventDefault();
            window.location.href = "logout.php"; // Redirect to logout.php
        }

        // default page contents js
        function clickDiv(id) {
            document.querySelector(id).click();
        }
    </script>
</body>

</html>