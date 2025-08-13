<?php include('admin-header.php'); ?>

<div class="container contact-container">
    <h2 style="text-align: center;">Enter the details</h2>
    <br>
    <form action="appoint.php" method="post">
        <div class="row">
            <div class="col-md-8" style="padding-left: 400px;">
                <input type="text" name="username" class="form-control student-text" placeholder="User Name" required>
                <input type="text" name="f_name" class="form-control student-text" placeholder="First Name" required>
                <input type="text" name="l_name" class="form-control student-text" placeholder="Last Name" required>
                <input type="password" name="password" class="form-control student-text" placeholder="Password" required>
                
                <select class="custom-select" name="hostel_name" required>
                    <option selected disabled>Hostel Name</option>
                    <option value="A">A Hostel</option>
                    <option value="B">B Hostel</option>
                    <option value="C">C Hostel</option>
                    <option value="D">D Hostel</option>
                    <option value="E">E Hostel</option>
                    <option value="F">F Hostel</option>
                </select>

                <input type="text" 
                       name="mobile" 
                       class="form-control student-text" 
                       placeholder="Mobile" 
                       maxlength="10" 
                       pattern="^\d{10}$" 
                       title="Please enter a 10-digit mobile number" 
                       oninput="this.value = this.value.replace(/\D/g, '').slice(0, 10);" 
                       required>
            </div>

            <div class="col-md-10" style="padding-left: 540px;">
                <button type="submit" name="appoint-hm" class="btn-lg btn-primary" style="margin-top: 10px;">Submit</button>
            </div>
        </div>
    </form>
</div>

<script>
    document.querySelector('form').addEventListener('submit', function (e) {
        const mobileInput = document.querySelector('input[name="mobile"]');
        const mobilePattern = /^\d{10}$/;
        const passwordInput = document.querySelector('input[name="password"]');
        const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

        if (!mobilePattern.test(mobileInput.value)) {
            alert("Please enter a valid 10-digit mobile number.");
            e.preventDefault();
        }

        if (!passwordPattern.test(passwordInput.value)) {
            alert("Password must be at least 8 characters long and include uppercase, lowercase, a number, and a special character.");
            e.preventDefault();
        }
    });
</script>

<?php include('admin-footer.php'); ?>

<?php
if (isset($_POST['appoint-hm'])) {
    // Assuming $conn and session are already initialized in the included header or elsewhere
    $username = $_POST['username'];
    $password = $_POST['password'];
    $f_name = $_POST['f_name'];
    $l_name = $_POST['l_name'];
    $hostel_name = $_POST['hostel_name'];
    $mobile = $_POST['mobile'];
    $ha_id = $_SESSION['ha_id'];

    // Check for empty fields
    if (empty($username) || empty($password) || empty($f_name) || empty($l_name) || empty($hostel_name) || empty($mobile)) {
        echo "<script type='text/javascript'>alert('Empty Field!')</script>";
        exit();
    }

    // Mobile number validation (10 digits)
    if (!preg_match('/^\d{10}$/', $mobile)) {
        echo "<script type='text/javascript'>alert('Please enter a valid 10-digit mobile number!')</script>";
        exit();
    }

    // Password validation
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        echo "<script type='text/javascript'>alert('Password must be at least 8 characters long and include uppercase, lowercase, a number, and a special character.')</script>";
        exit();
    }

    // Hash the password before storing it
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Fetch hostel ID based on hostel name
    $sql = "SELECT * FROM hostel WHERE hostel_name='$hostel_name';";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        $hostel_id = $row['hostel_id'];

        // Insert data into hostel_manager table
        $sql = "INSERT INTO hostel_manager (f_name, l_name, username, mobile, password, admin, hostel_id) 
                VALUES ('$f_name', '$l_name', '$username', '$mobile', '$hashed_password', '$ha_id', '$hostel_id');";

        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo "<script type='text/javascript'>alert('Hostel Manager insertion Success!')</script>";
            exit();
        } else {
            echo "<script type='text/javascript'>alert('Hostel Manager insertion Failed!')</script>";
            exit();
        }
    } else {
        echo "<script type='text/javascript'>alert('Invalid Hostel Name!')</script>";
        exit();
    }
}
?>
