<?php
include 'db.php';

if (isset($_POST['submit'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $course = trim($_POST['course']);

    if ($name === '' || $email === '' || $course === '') {
        $error = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {

        $stmt = $conn->prepare(
            "INSERT INTO students (name, email, phone, course) VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param("ssss", $name, $email, $phone, $course);

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: index.php");
            exit();
        } else {
            $error = "Error adding student.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Student - Student Management</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

    <!-- Navbar -->

    <nav class="navbar">

        <h2>Student Management</h2>

        <a href="index.php">Dashboard</a>

    </nav>


    <!-- Main Content -->

    <main class="container">

        <div class="page-header">

            <div>

                <h1>Add Student</h1>

                <p>Add a new student to the database.</p>

            </div>

        </div>


        <!-- Form -->

        <div class="form-card">

            <?php if (isset($error)) { ?>

                <div class="error">
                    <?php echo htmlspecialchars($error); ?>
                </div>

            <?php } ?>


            <form method="POST">

                <div class="form-group">

                    <label for="name">
                        Full Name
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        placeholder="Enter student's full name"
                        value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="email">
                        Email Address
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Enter email address"
                        value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="phone">
                        Phone Number
                    </label>

                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        placeholder="Enter phone number"
                        value="<?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?>"
                    >

                </div>


                <div class="form-group">

                    <label for="course">
                        Course
                    </label>

                    <input
                        type="text"
                        id="course"
                        name="course"
                        placeholder="e.g. Computer Science"
                        value="<?php echo isset($course) ? htmlspecialchars($course) : ''; ?>"
                        required
                    >

                </div>


                <button
                    type="submit"
                    name="submit"
                    class="btn"
                >
                    Add Student
                </button>

                <br>

                <a href="index.php" class="back-link">
                    ← Back to Students
                </a>

            </form>

        </div>

  </main>

<footer class="footer">

    <p>
        © 2026 Student Management System
    </p>

    <p>
        Built with PHP & MySQL
    </p>

</footer>

</body>
</html>