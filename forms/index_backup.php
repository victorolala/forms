
<?php
require_once "../conn.php"; // Include the database connection file

if (isset($_GET['course_id'])) {
    $course_id = intval($_GET['course_id']);
    $stmt = $conn->prepare("SELECT price, duration FROM courses WHERE id = ?");
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $course = $result->fetch_assoc();

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($course);
    exit;
}


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
        button #submit-button{
            background-color: #d31a68 !important;
            color: #fff;
            border: none;
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
     
    <form action="submit-application.php" method="POST">
        <!-- CSRF Token -->
        <input type="hidden" name="_token" value="your-csrf-token">

        <h1>Create User</h1>

        <!-- Full Name -->
        <div class="form-group">
            <label for="full-name">Full Name <span class="required">*</span></label>
            <input type="text" id="full-name" name="full_name" placeholder="Enter your full name" required>
            <small id="full-name-error" class="error-message"></small>
        </div>

        <!-- Course -->
        <div class="form-group">
            <label for="course">Course <span class="required">*</span></label>
            <select id="course" name="course" required>
                <option selected disable>Select a Course</option>

                <option value="1">Finance and Economics</option>
                <?php
                // Fetch courses from the database
                // $query = "SELECT id, course_name FROM courses ORDER BY course_name ASC";
                // $result = $conn->query($query);

                // while ($course = $result->fetch_assoc()): ?>
                    <!-- <option value="<?= htmlspecialchars($course['id']) ?>"><?= htmlspecialchars($course['course_name']) ?></option> -->
                <?php 
                // endwhile;
                 ?>
            </select>
        </div>

        <!-- Price -->
        <div class="form-group">
            <label for="price">Price (€ GBP) <span class="required">*</span></label>
            <input type="text" id="price" name="price" readonly required>
        </div>

        <!-- Duration -->
        <div class="form-group">
            <label for="duration">Duration (Months) <span class="required">*</span></label>
            <input type="text" id="duration" name="duration" readonly required>
        </div>

        <!-- Email -->
        <div class="form-group">
            <label for="email">Email Address <span class="required">*</span></label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
            <small id="email-error" class="error-message"></small>
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password">Password <span class="required">*</span></label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <small id="password-error" class="error-message"></small>
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label for="confirm-password">Confirm Password <span class="required">*</span></label>
            <input type="password" id="confirm-password" name="confirm_password" placeholder="Confirm your password" required>
            <small id="confirm-password-error" class="error-message"></small>
        </div>

        <!-- Terms & Conditions -->
        <div class="checkbox">
            <input type="checkbox" id="terms" name="terms" required>
            <label for="terms">By signing in, you agree to the Terms and Conditions <span class="required">*</span></label>
        </div>

        <!-- Submit Button -->
        <button type="submit" id="submit-button" style="background-color: #d31a68 !important;">Submit</button>
    </form>

</div>

<div class="container" style="margin-top:20px;">
     
      <form action="test.php" method="POST">
          <h1>Send email to user</h1>
        <!-- Form 1 Section -->
        <div id="form-1" class="form-section active">
            
             <div class="form-group">
                <label for="email">Email Address <span class="required">*</span></label>
                <i class="fas fa-envelope"></i>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
                <small id="email-error" style="color:red;"></small>
            </div>           
            <button type="submit" id="submit-button" style="background-color: #d31a68 !important;">Send email</button>

            </div>
    </form>
</div>


<script>
    document.getElementById('course').addEventListener('change', function () {
        const courseId = this.value;

        if (courseId) {
            // Fetch course details via AJAX
            fetch(`get-course-details.php?course_id=${courseId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.price && data.duration) {
                        // Populate price and duration fields
                        document.getElementById('price').value = data.price;
                        document.getElementById('duration').value = data.duration;
                    } else {
                        alert('Course details not found.');
                        // Reset fields
                        document.getElementById('price').value = '';
                        document.getElementById('duration').value = '';
                    }
                })
                .catch(error => {
                    console.error('Error fetching course details:', error);
                    alert('Failed to load course details. Please try again.');
                });
        } else {
            // Reset fields if no course is selected
            document.getElementById('price').value = '';
            document.getElementById('duration').value = '';
        }
    });
</script>

    <?php include '../footer2.php'; ?>

</body>
</html>
