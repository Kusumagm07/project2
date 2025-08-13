<?php include('student-header.php'); ?>

<?php
// Enable error reporting for debugging purposes
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Initialize $hostel_name as a default value
$hostel_name = "not alloted";

// Check if hostel_id is set and valid in the session
if (isset($_SESSION['hostel_id']) && !empty($_SESSION['hostel_id'])) {
    $hostel_id = (int)$_SESSION['hostel_id']; // Cast to integer for safety

    // Query to retrieve hostel name
    $sql = "SELECT hostel_name FROM hostel WHERE hostel_id = $hostel_id";
    $result = mysqli_query($conn, $sql);

    if ($result && $result->num_rows != 0) {
        if ($row = mysqli_fetch_assoc($result)) {
            $hostel_name = $row['hostel_name'];
        }
    }
}
?>

<div class="container contact-container">
    <h2>Contact Us</h2>
    <br>
    <form action="contact.php" method="post">
        <div class="row">
            <div class="col-md-6">
                <input type="text" class="form-control student-text" placeholder="Hostel Name" value="<?php echo htmlspecialchars($hostel_name); ?>" readonly>
                <input type="text" class="form-control student-text" placeholder="Roll No" value="<?php echo htmlspecialchars($_SESSION['roll']); ?>" readonly>
                <input type="text" class="form-control student-text" placeholder="Name" value="<?php echo htmlspecialchars($_SESSION['fname'] . ' ' . $_SESSION['lname']); ?>" readonly>
                <input type="text" class="form-control student-text" name="subject" placeholder="Subject" required>
            </div>

            <div class="col-md-6">
                <textarea class="form-control student-text-area" rows="7" placeholder="Message..." name="message" required></textarea>
            </div>

            <div class="col-md-5" style="padding-left: 540px;">
                <button type="submit" name="submit" class="btn-lg btn-primary" style="margin-top:10px;">Submit</button>
            </div>
        </div>
    </form>
</div>

<?php include('student-footer.php'); ?>

<?php
if (isset($_POST['submit'])) {
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    // Check for empty fields
    if (empty($subject) || empty($message)) {
        echo "<script type='text/javascript'>alert('Subject or message cannot be empty!');</script>";
        exit();
    }

    // Check if hostel is assigned
    if ($hostel_name == "not alloted") {
        echo "<script type='text/javascript'>alert('Hostel not alloted!');</script>";
        exit();
    }

    // Retrieve hostel manager ID
    $query6 = "SELECT * FROM hostel_manager WHERE hostel_id = '$hostel_id'";
    $result6 = mysqli_query($conn, $query6);

    if ($result6 && $row6 = mysqli_fetch_assoc($result6)) {
        $hm_id = $row6['hm_id'];
    } else {
        echo "<script type='text/javascript'>alert('Hostel manager not found!');</script>";
        exit();
    }

    $roll = $_SESSION['roll'];
    date_default_timezone_set('Asia/Calcutta');
    $time_stamp = date('Y-m-d H:i:s');

    // Insert message into the database
    $query = "INSERT INTO messages (hm_id, student_id, subject, message, time_stamp, sender)
              VALUES ('$hm_id', '$roll', '$subject', '$message', '$time_stamp', '1')";

    if (mysqli_query($conn, $query)) {
        echo ("<script type='text/javascript'>
            alert('Message sent to hostel manager successfully!');
            window.location.href = 'home.php';
        </script>");
        exit();
    } else {
        echo "<script type='text/javascript'>alert('Error in sending message! Please try again.');</script>";
        exit();
    }
}
?>