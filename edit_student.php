<?php
include 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Student not found.";
    exit();
}

$student = $result->fetch_assoc();
$stmt->close();

if (isset($_POST['update'])) {

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
            "UPDATE students
             SET name = ?, email = ?, phone = ?, course = ?
             WHERE id = ?"
        );

        $stmt->bind_param(
            "ssssi",
            $name,
            $email,
            $phone,
            $course,
            $id
        );

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: index.php");
            exit();
        } else {
            $error = "Error updating student.";
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

    <title>Edit Student - Student Management</title>

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

                <h1>Edit Student</h1>

                <p>Update the student's information.</p>

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
                        value="<?php echo htmlspecialchars($student['name']); ?>"
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
                        value="<?php echo htmlspecialchars($student['email']); ?>"
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
                        value="<?php echo htmlspecialchars($student['phone']); ?>"
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
                        value="<?php echo htmlspecialchars($student['course']); ?>"
                        required
                    >

                </div>


                <button
                    type="submit"
                    name="update"
                    class="btn"
                >
                    Update Student
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