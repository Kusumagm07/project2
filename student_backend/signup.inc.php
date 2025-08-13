<?php

if (isset($_POST['signup-student'])) {

  require 'config.inc.php';

  $student_id = $_POST['username'];
  $password = $_POST['password'];
  $confpassword = $_POST['confpassword'];
  $f_name = $_POST['f_name'];
  $l_name = $_POST['l_name'];
  $mobile = $_POST['mobile'];
  $gender = $_POST['gender'];
  $year = $_POST['year'];
  $dept = $_POST['dept'];

  if (empty($student_id) || empty($password) || empty($f_name) || empty($l_name) || empty($confpassword) || empty($mobile) || empty($gender) || empty($year) || empty($dept)) {
        echo ("<script LANGUAGE='JavaScript'>
        window.alert('Empty Fields!!');
        window.location.href='../index_signup.php';
        </script>");
        exit();
  } else {
    if ($confpassword != $password) {
      echo ("<script LANGUAGE='JavaScript'>
      window.alert('Passwords not matching');
      window.location.href='../index_signup.php';
      </script>");
      exit();
    }

    // Password validation: must contain uppercase, lowercase, number, and special character
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
      echo ("<script LANGUAGE='JavaScript'>
      window.alert('Password must be at least 8 characters long and include uppercase, lowercase, a number, and a special character.');
      window.location.href='../index_signup.php';
      </script>");
      exit();
    }

    if (!preg_match('/^\d{2}[A-Z]{2}[A-Z]{2}\d{3}$/', $student_id)) {
      echo ("<script LANGUAGE='JavaScript'>
      window.alert('Student ID must be in the format 24BECS402');
      window.location.href='../index_signup.php';
      </script>");
      exit();
    }
    

    $sql = "SELECT * FROM student WHERE student_id = '$student_id';";
    $result = mysqli_query($conn, $sql);
    if ($result) {
      $row = mysqli_fetch_assoc($result);
      if (!empty($row)) {
        echo ("<script LANGUAGE='JavaScript'>
        window.alert('User Already exists');
        window.location.href='../index_signup.php';
        </script>");
        exit();
      }
    }

    $sql = "INSERT INTO student VALUES ('$student_id','$f_name', '$l_name', '$year','$dept', '$password', NULL, NULL, $gender, '$mobile');";
    $result = mysqli_query($conn, $sql);
    if ($result) {
      echo ("<script LANGUAGE='JavaScript'>
      window.alert('Your account created Successfully');
      window.location.href='../index.php';
      </script>");
      exit();
    } else {
      echo ("<script LANGUAGE='JavaScript'>
      window.alert('Sign up error. Please try again later.');
      window.location.href='../index_signup.php';
      </script>");
      exit();
    }
  }
}

?>
