<!--?php
require_once "../conn.php"; // Include the database connection file
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once("../phpmailers/src/PHPMailer.php");
require_once("../phpmailers/src/Exception.php");
require_once("../phpmailers/src/SMTP.php");

session_start();
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $course = trim($_POST['course']);
    $price = trim($_POST['price']);
    $startDate = trim($_POST['start_date']);
    $duration = trim($_POST['duration']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Validate required fields
    if (empty($fullName) || empty($email) || empty($course) || empty($price) || empty($startDate) || empty($duration) || empty($password) || empty($confirmPassword)) {
        $errors[] = "All fields are required.";
    }

    // Validate password length
    if (strlen($password) < 4) {
        $errors[] = "Password must be at least 4 characters long.";
    }

    // Check if passwords match
    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    // If there are no validation errors
    if (empty($errors)) {
        // Check if email exists in the students table
        $checkQuery = "SELECT * FROM students WHERE email = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows == 0) {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert into students table
            $insertQuery = "INSERT INTO students (full_name, email, course, price, start_date, duration, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->bind_param("sssssss", $fullName, $email, $course, $price, $startDate, $duration, $hashedPassword);

            if ($insertStmt->execute()) {
                echo "<script>alert('Success! You have been registered. You will be notified of payment details via email.');</script>";

$buttonUrl = "https://www.lse-course.co.uk/start?email=" . urlencode($email);

        // Send email notification
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ronaldlangat105@gmail.com';
            $mail->Password = 'rxjz tibe cjnk bqkw'; // Replace with your SMTP credentials
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->setFrom('ironaldlangat105@gmail.com', 'LSE course');
            $mail->addAddress($email);

            $mail->Subject = "Make payment to secure your place";
            $mail->isHTML(true);
            $mail->Body = "
                    <div style='padding:20px 20px; background-color: #f9f9f9;'>
                    <div class='email-container' style='background-color: #ffffff;
            max-width: 650px;
            margin: 20px auto; margin-bottom:0; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);'>
        <div class='logo' style=' text-align: left;'> 
            <img src='https://ci3.googleusercontent.com/meips/ADKq_Nb1349vuyTCQtjhcch7BQCPwBKLXUyx7OIpZLkE0skhCV0jHy2myJA8RV2xZ6JSfoGThKg4pvKXdnd0RX55Hm-O62fKt-HjRCVCz08rUXq5p3Ir9guGhLeUcA4TqKKNqJErw_8cMuwM9OdZ8oKDllfL797EW7UctuvGHG9yepg=s0-d-e1-ft#https://image.mail.getsmarter.com/lib/fe3211717064047f721578/m/1/c9379322-6825-49a9-a271-dfdf9161e54a.png' style='width: 600px;' alt='LSE Logo'>
        </div>

        <div class='hero-image' style='width:100%;'>
             <img src='https://ci3.googleusercontent.com/meips/ADKq_NZUaNKGaIe7tmu1L_357TNOF6ArAYIOOS23iGa5KhWw4aLt4oTRWrNzz3dBXbEdNHYLtMZh0SasNtSJuG2irhP94VBvqNxOPaVqomlULxugEt4JRdKGTpL-BoPcBJ9dNCn7vvXy_kM8tlWQOZRHq7mXbkon8uiPlnZCWuKfmQQ=s0-d-e1-ft#https://image.mail.getsmarter.com/lib/fe3211717064047f721578/m/1/3ae431c4-7ccb-4b40-a4ca-bf83b4813980.jpg' style='width: 100%;' alt='LSE Campus'>
             
        </div>

        <div class='email-body' style='color: #333333;
            line-height: 1.6;'>
            <div style='padding:10px;'>
            <p>Hi $fullName,</p>
            
            <p>
            Well done for completing the first step in your registration process. All that’s left now is to make payment and secure your place on the London School of Economics and Political Science (LSE) <a href='https://www.getsmarter.com/products/lse-real-estate-economics-and-finance-online-certificate-course?utm_source=email&utm_medium=SRHH&utm_campaign=LSE-REF-SR-HH1-Jan09' style='color: red; text-decoration: underline;' target='_blank'><strong> $course</strong></a> online certificate course.
            </p>
            <p style='padding:10px; text-align-left;'>
                You can elect to pay all at once or in multiple instalments, online or via EFT/bank transfer.*
            </p>
           
            <div class='cta-button' style='display: block; margin-top:20px; padding:20px; text-align: center;'>
                <a href='$buttonUrl' 
       style='text-decoration: none; background-color: red; color: #ffffff; padding: 15px 30px; font-weight: bold; font-size: 16px; border:none;' 
       target='_blank'>COMPLETE PAYMENT</a>
            </div>

            <p style='font-style:italic; margin-top:20px; padding:20px; text-align:center;'>
                *EFTs/bank transfers can take several days to clear and may delay course enrolment. We therefore strongly recommend making payment online via the GetSmarter website.
            </p>
            </div>
            <div  style='background:whitesmoke; margin-bottom:0; padding:20px;'>
            <p class='contact-info' style='font-size: 14px; color: #666666;
            text-align: left;'>
                <h1 style='font-weight:bold; color:black; text-align:center;'>Choose a date that works for you</h1>

This online certificate course has multiple start dates, with the next presentation starting on <strong>$startDate</strong>. If that doesn’t work for you, please reply to this mail or <a href='https://calendly.com/getsmarter-uk10/request-a-call?salesforce_uuid=7765d40c-1aef-45d5-99d9-514725c8d690&utm_source=email&utm_medium=SRHH&utm_campaign=LSE-REF-SR-HH1-Jan09&month=2025-01' style='color:red; text-decoration:underline;' target='_blank'>request a call</a> to speak to an Enrolment Advisor about future presentations that better suit your schedule.
            </p>
           </div>
             <p style='padding:10px; text-align-left;'>
                 Please don’t hesitate to reach out if you have any questions about the course content, funding or payment plan options, or the personalised online learning experience.
            </p>
            <p>Kind regards,</p>
            <p>The GetSmarter Team</p>
        
        </div>
    </div>

    <div class='footer' style='font-size: 14px;
            color: #666666; max-width:650px; margin:0 auto;
            text-align: center; background-color: #f9f9f9;'>
        <p style=' margin: 5px 0; font-size:12px;'>Contact: <a href='tel:+442038568850' style='color: #0056b3;' target='_blank'>+44 2038 568 850</a></p>
        <br>
            <p style=' margin: 5px 0; font-size:12px;'>Course questions: <a href='mailto:lseonline@getsmarter.com' style='color: #0056b3;' target='_blank'>lseonline@getsmarter.com</a></p>
        <p style=' margin: 5px 0; font-size:12px;'>Finance questions: <a href='mailto:registrations@getsmarter.com' style='color: #0056b3;' target='_blank'>registrations@getsmarter.com</a></p>
        <div class='footer-logo' style='margin: 20px 0;'>
        
            <img src='https://ci3.googleusercontent.com/meips/ADKq_NaikcwS6QXle34QI3l5sqRept7zsga6JURuUPvsm8qljA_y9ctTHB1tspuFannWidUgC_sioxiDGIrFFB_RsmFCEMhHB48Zr_1pnHfn1UEKImfGVb3HcaLk__EZSf8ZFQfldy6_EQp9A2t3biMaHQXfYUIsmvujQmdOeA4U5Yg=s0-d-e1-ft#https://image.mail.getsmarter.com/lib/fe37157075640778721074/m/2/74fbf53e-b817-46cb-b469-b4c2df028d05.png' style='width: 100px;' alt='GetSmarter Logo'>
        </div>
        <p style='font-size:10px;  margin: 5px 0; font-size:12px;'>
            15 Bishopsgate, London, England, EC2N 3AR<br>
            <a href='https://www.getsmarter.com/about-us?utm_source=email&utm_medium=SRHH&utm_campaign=LSE-REF-SR-HH1-Jan09' style='color: #0056b3;' target='_blank'>GetSmarter</a>™, part of edX, helps working professionals gain verifiable skills from leading global universities and institutions<br> to thrive in an ever-changing work environment.<br>
            <br>
            If you would like to unsubscribe, <a href='https://cloud.mail.getsmarter.com/unsubscribe?qs=51c2f53183dc772b7a463ee3da06b8892e55c00218de4e8fa4a5bc4400bc11523d1d0a0e8e41a2199ef8a9092bb09047160b2d235408c73b49384960cb3b2fb5&mid=515002718&utm_source=email&utm_medium=SRHH&utm_campaign=LSE-REF-SR-HH1-Jan09' style='color: #0056b3;' target='_blank'>Click here</a> 
        </p>
        
    </div>
    </div>
                ";
               $mail->send();
                } catch (Exception $e) {
                    echo "<script>alert('Error: Email could not be sent. Mailer Error: {$mail->ErrorInfo}');</script>";
                }
            } else {
                echo "<script>alert('Error: Could not complete the registration. Please try again.');</script>";
            }

            $insertStmt->close();
        } else {
            echo "<script>alert('Error: A user with this email is already registered.');</script>";
        }
    } else {
        foreach ($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
    }
}
?-->

<!--?php
require_once "../conn.php"; // Include the database connection file

// Start session before any output to avoid "headers already sent" error
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$errors = [];

// Fetch courses from the database
$coursesQuery = "SELECT id, course FROM courses";
$coursesResult = $conn->query($coursesQuery);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $course = trim($_POST['course']);
    $price = trim($_POST['price']);
    $startDate = isset($_POST['start_date']) ? trim($_POST['start_date']) : null; // Avoid undefined array key warning
    $duration = trim($_POST['duration']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Validate required fields
    if (empty($fullName) || empty($email) || empty($course) || empty($price) || empty($duration) || empty($password) || empty($confirmPassword)) {
        $errors[] = "All fields are required.";
    }

    // Validate password length
    if (strlen($password) < 4) {
        $errors[] = "Password must be at least 4 characters long.";
    }

    // Check if passwords match
    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        // Check if email exists in the students table
        $checkQuery = "SELECT * FROM students WHERE email = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows == 0) {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert into students table
            $insertQuery = "INSERT INTO students (full_name, email, course, price, start_date, duration, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->bind_param("sssssss", $fullName, $email, $course, $price, $startDate, $duration, $hashedPassword);

            if ($insertStmt->execute()) {
                echo "<script>alert('Success! You have been registered.');</script>";
            } else {
                echo "<script>alert('Error: Could not complete the registration. Please try again.');</script>";
            }

            $insertStmt->close();
        } else {
            echo "<script>alert('Error: A user with this email is already registered.');</script>";
        }
    } else {
        foreach ($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
    }
}

$conn->close();
?-->

<?php
require_once "../conn.php"; // Include the database connection file

// Start session before any output to avoid "headers already sent" error
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$errors = [];

// Fetch courses from the database
$coursesQuery = "SELECT id, course FROM courses";
$coursesResult = $conn->query($coursesQuery);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $course = trim($_POST['course']);
    $price = trim($_POST['price']);
    $startDate = isset($_POST['start_date']) ? trim($_POST['start_date']) : null; // Avoid undefined array key warning
    $duration = trim($_POST['duration']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Validate required fields
    if (empty($fullName) || empty($email) || empty($course) || empty($price) || empty($duration) || empty($password) || empty($confirmPassword)) {
        $errors[] = "All fields are required.";
    }

    // Validate password length
    if (strlen($password) < 4) {
        $errors[] = "Password must be at least 4 characters long.";
    }

    // Check if passwords match
    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        // Check if email exists in the students table
        $checkQuery = "SELECT * FROM students WHERE email = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            // Email exists - update the user's details
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $updateQuery = "UPDATE students SET full_name = ?, course = ?, price = ?, start_date = ?, duration = ?, password = ? WHERE email = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("sssssss", $fullName, $course, $price, $startDate, $duration, $hashedPassword, $email);

            if ($updateStmt->execute()) {
                echo "<script>alert('Your details have been updated successfully.');</script>";
            } else {
                echo "<script>alert('Error: Could not update your details. Please try again.');</script>";
            }

            $updateStmt->close();
        } else {
            // Email does not exist - insert new user
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $insertQuery = "INSERT INTO students (full_name, email, course, price, start_date, duration, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->bind_param("sssssss", $fullName, $email, $course, $price, $startDate, $duration, $hashedPassword);

            if ($insertStmt->execute()) {
                echo "<script>alert('Success! You have been registered.');</script>";
            } else {
                echo "<script>alert('Error: Could not complete the registration. Please try again.');</script>";
            }

            $insertStmt->close();
        }

        $checkStmt->close();
    } else {
        foreach ($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Form</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>    
        .contact-info {
            font-size: 14px;
            margin-top: 20px;
            color: #666666;
            text-align: center;
        }
        .container {
            max-width: 600px;
            margin: 100px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-section {
            display: none;
        }
        .form-section.active {
            display: block;
        }
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group label .required {
            color: red;
        }
        .form-group input,
        .form-group select {
            width: calc(100% - 40px);
            padding: 10px;
            padding-left: 40px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group i {
            position: absolute;
            top: 70%;
            left: 10px;
            transform: translateY(-50%);
            color: #ccc;
        }
        .form-group small {
            color: red;
            font-size: 12px;
        }
        .container button {
            width: 100%;
            padding: 10px;
            background: red;
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }
        .container button:hover {
            background: blue;
        }
        .button-group {
            display:flex;
            flex-wrap:wrap;
            justify-content:space-between;
        }
        .button-group button {
            width:150px;

        }
    </style>
</head>
<body>
<?php include '../header2.php'; ?>

<div class="container" style="margin-top:120px;">
     <?php if (!empty($errors)): ?>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li style="color: red;"><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
      <form action="registration" method="POST">
          <h1>Create User</h1>
        <!-- Form 1 Section -->
        <div id="form-1" class="form-section active">
            <div class="form-group">
                <label for="full-name">Full Name <span class="required">*</span></label>
                <i class="fas fa-user"></i>
                <input type="text" id="full-name" name="full_name" placeholder="Enter your full name" required><br>
                <small id="full-name-error" style="color:red;"></small>
            </div>
            
            <div class="form-group">
    <label for="course">Course <span class="required">*</span></label>
    <i class="fas fa-graduation-cap"></i>
    <select id="course" name="course" required>
        <option value="">Select a Course</option>
        <?php while ($course = $coursesResult->fetch_assoc()): ?>
            <option value="<?= htmlspecialchars($course['course']) ?>"><?= htmlspecialchars($course['course']) ?></option>
        <?php endwhile; ?>
    </select>
</div>

            <div class="form-group">
    <label for="price">Price (£)<span class="required">*</span></label>
    <i class="fas fa-tag"></i>
    <input type="text" id="price" name="price" value="10680" readonly>
</div>
<div class="form-group">
    <input type="hidden" id="start-date" name="start_date" readonly>
</div>
<div class="form-group">
    <label for="duration">Duration<span class="required">*</span></label>
    <i class="fas fa-graduation-cap"></i>
    <input type="text" id="duration" name="duration" value="5 months" readonly>
</div>

             <div class="form-group">
                <label for="email">Email Address <span class="required">*</span></label>
                <i class="fas fa-envelope"></i>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
                <small id="email-error" style="color:red;"></small>
            </div>
            <div class="form-group">
    <label for="password">Password <span class="required">*</span></label>
    <i class="fas fa-fingerprint"></i>
    <input type="password" id="password" name="password" placeholder="Enter your password" required><br>
    <small id="password-error" style="color:red;"></small>
</div>

<div class="form-group">
    <label for="confirm-password">Confirm Password <span class="required">*</span></label>
    <i class="fas fa-fingerprint"></i>
    <input type="password" id="confirm-password" name="confirm_password" placeholder="Confirm your password" required><br>
    <small id="confirm-password-error" style="color:red;"></small>
</div>
            <div class="checkbox">
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">By signing in, you agree to the Terms and Conditions <span class="required">*</span></label>
            </div>
            <button type="submit" id="submit-button">Submit</button>

            </div>
    </form>
</div>

<div class="container" style="margin-top:20px;">
     
      <form action="mail" method="POST">
          <h1>Send email to user</h1>
        <!-- Form 1 Section -->
        <div id="form-1" class="form-section active">
            
             <div class="form-group">
                <label for="email">Email Address <span class="required">*</span></label>
                <i class="fas fa-envelope"></i>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
                <small id="email-error" style="color:red;"></small>
            </div>
           
            <!--div class="checkbox">
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">By signing in, you agree to the Terms and Conditions <span class="required">*</span></label>
            </div-->
            <button type="submit" id="submit-button">Send email</button>

            </div>
    </form>
</div>

<script>
    const tab1 = document.getElementById('tab-1');
    const tab2 = document.getElementById('tab-2');
    const form1 = document.getElementById('form-1');
    const form2 = document.getElementById('form-2');
    const nextButton = document.getElementById('next-button');
    const backButton = document.getElementById('back-button');

    nextButton.addEventListener('click', () => {
        const fullName = document.getElementById('full-name').value.trim();
        const duration = document.getElementById('duration').value;

        let isValid = true;

        // Validate Full Name
        if (fullName.split(' ').length < 2) {
            document.getElementById('full-name-error').innerText = "Full Name must contain at least 2 words.";
            isValid = false;
        } else {
            document.getElementById('full-name-error').innerText = "";
        }

        // Validate Duration
        if (!duration) {
            document.getElementById('duration-error').innerText = "Please select a course duration.";
            isValid = false;
        } else {
            document.getElementById('duration-error').innerText = "";
        }

        if (isValid) {
            // Switch to Form 2
            form1.classList.remove('active');
            form2.classList.add('active');
            tab1.classList.remove('active');
            tab2.classList.add('active');
        }
    });

    backButton.addEventListener('click', () => {
        // Switch back to Form 1
        form2.classList.remove('active');
        form1.classList.add('active');
        tab2.classList.remove('active');
        tab1.classList.add('active');
    });

    document.getElementById('submit-button').addEventListener('click', (e) => {
        const password = document.getElementById('password').value.trim();
        const confirmPassword = document.getElementById('confirm-password').value.trim();

        let isValid = true;

        // Validate Password
        if (password.length < 4) {
            document.getElementById('password-error').innerText = "Password must be at least 4 characters.";
            isValid = false;
        } else {
            document.getElementById('password-error').innerText = "";
        }

        // Validate Confirm Password
        if (password !== confirmPassword) {
            document.getElementById('confirm-password-error').innerText = "Passwords must match.";
            isValid = false;
        } else {
            document.getElementById('confirm-password-error').innerText = "";
        }

        if (!isValid) {
            e.preventDefault();
        }
    });
</script>
<script>
     function fetchCourseDetails(courseName) {
    if (courseName) {
        const xhr = new XMLHttpRequest();
        // Prepare the GET request with the course name as a query parameter
        xhr.open("GET", `fetch_course_details.php?course_name=${encodeURIComponent(courseName)}`, true);

        // When the request is complete
        xhr.onload = function () {
            if (this.status === 200) {
                const data = JSON.parse(this.responseText); // Parse the JSON response
                if (data.error) {
                    alert(data.error); // Alert the user if there is an error (e.g., "Course not found")
                } else {
                    // Populate the form fields with the returned data
                    document.getElementById("course").value = courseName; // Show the course name
                    document.getElementById("price").value = data.price; // Show the course price
                    document.getElementById("start-date").value = data.start_date; // Show the course start date
                    document.getElementById("duration").value = data.duration; // Show the course duration
                }
            } else {
                alert("An error occurred while fetching course details. Please try again.");
            }
        };

        // Send the request
        xhr.send();
    } else {
        // Clear the fields if no course is selected
        document.getElementById("course").value = "";
        document.getElementById("price").value = "";
        document.getElementById("start-date").value = "";
        document.getElementById("duration").value = "";
    }
}

    </script>
    <?php include '../footer2.php'; ?>

</body>
</html>
