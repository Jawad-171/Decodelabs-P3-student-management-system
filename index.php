<?php
include 'db.php';

$search = "";

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

if ($search !== "") {

    $stmt = $conn->prepare(
        "SELECT * FROM students
         WHERE name LIKE ?
         OR email LIKE ?
         OR course LIKE ?
         OR phone LIKE ?
         ORDER BY id DESC"
    );

    $searchTerm = "%" . $search . "%";

    $stmt->bind_param(
        "ssss",
        $searchTerm,
        $searchTerm,
        $searchTerm,
        $searchTerm
    );

    $stmt->execute();

    $result = $stmt->get_result();

    $total_students = $result->num_rows;

} else {

    $sql = "SELECT * FROM students ORDER BY id DESC";
    $result = mysqli_query($conn, $sql);

    $total_students = mysqli_num_rows($result);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Management System</title>

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

                <h1>Students</h1>

                <p>Manage student records from one place.</p>

            </div>

            <a href="add_student.php" class="btn">
                + Add Student
            </a>

        </div>


        <!-- Statistics -->

        <div class="stats-card">

            <div>

                <span>
                    <?php echo $search !== "" ? "Search Results" : "Total Students"; ?>
                </span>

                <strong>
                    <?php echo $total_students; ?>
                </strong>

            </div>

        </div>


        <!-- Search -->

        <form method="GET" class="search-form">

            <input
                type="text"
                name="search"
                placeholder="Search by name, email, phone or course..."
                value="<?php echo htmlspecialchars($search); ?>"
            >

            <button type="submit" class="btn">
                Search
            </button>

            <?php if ($search !== "") { ?>

                <a href="index.php" class="clear-btn">
                    Clear
                </a>

            <?php } ?>

        </form>


        <!-- Students Table -->

        <div class="table-card">

            <table>

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Name</th>

                        <th>Email</th>

                        <th>Phone</th>

                        <th>Course</th>

                        <th>Actions</th>

                    </tr>

                </thead>


                <tbody>

                    <?php if ($total_students > 0) { ?>

                        <?php while ($student = $result->fetch_assoc()) { ?>

                            <tr>

                                <td>
                                    #<?php echo $student['id']; ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($student['name']); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($student['email']); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($student['phone']); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($student['course']); ?>
                                </td>

                                <td>

                                    <a
                                        href="edit_student.php?id=<?php echo $student['id']; ?>"
                                        class="edit-btn"
                                    >
                                        Edit
                                    </a>

                                    &nbsp; | &nbsp;

                                    <a
                                        href="delete_student.php?id=<?php echo $student['id']; ?>"
                                        class="delete-btn"
                                        onclick="return confirm('Are you sure you want to delete this student?');"
                                    >
                                        Delete
                                    </a>

                                </td>

                            </tr>

                        <?php } ?>

                    <?php } else { ?>

                        <tr>

                            <td colspan="6" style="text-align: center;">

                                <?php if ($search !== "") { ?>

                                    No students found for
                                    "<?php echo htmlspecialchars($search); ?>".

                                <?php } else { ?>

                                    No students found.

                                <?php } ?>

                            </td>

                        </tr>

                    <?php } ?>

                </tbody>

            </table>

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