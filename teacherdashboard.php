<?php
session_start();
if ($_SESSION['role'] !== 'teacher') {
    header("Location: teacherdashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Teacher Dashboard</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        h2 { color: #333; }
        form, table { margin-bottom: 40px; }
        table, th, td { border: 1px solid #ccc; border-collapse: collapse; padding: 8px; }
        input, select, textarea { padding: 5px; width: 100%; }
    </style>
</head>
<body>

<h1>Welcome, Teacher <?php echo $_SESSION['username']; ?>!</h1>

    <!-- Add a Book -->
    <h2>Add a New Book</h2>
    <form action="add_book.php" method="post" enctype="multipart/form-data">
        Title: <input type="text" name="title" required><br><br>
        Description: <textarea name="description" required></textarea><br><br>
        Cover Image: <input type="file" name="cover_image" accept="image/*"><br><br>
        <input type="submit" value="Add Book">
    </form>

    <!-- Assign a Book -->
    <h2>Assign a Book to a Student</h2>
    <form action="assign_book.php" method="post">
        Select Book:
        <select name="book_id" required>
            <?php
            // Fetch all books from database
            $conn = new mysqli("localhost", "root", "", "youtube");
            $books = $conn->query("SELECT * FROM Books");
            while ($book = $books->fetch_assoc()) {
                echo "<option value='{$book['book_id']}'>{$book['title']}</option>";
            }
            ?>
        </select><br><br>

        Select Student:
        <select name="user_id" required>
            <?php
            // Fetch all students
            $students = $conn->query("SELECT * FROM Users WHERE role='student'");
            while ($student = $students->fetch_assoc()) {
                echo "<option value='{$student['user_id']}'>{$student['username']}</option>";
            }
            ?>
        </select><br><br>

        <input type="submit" value="Assign Book">
    </form>

    <!-- See All Books -->
    <h2>All Books</h2>
    <table>
        <tr><th>ID</th><th>Title</th><th>Description</th><th>Cover</th></tr>
        <?php
        $books = $conn->query("SELECT * FROM Books");
        while ($book = $books->fetch_assoc()) {
            echo "<tr>
                    <td>{$book['book_id']}</td>
                    <td>{$book['title']}</td>
                    <td>{$book['description']}</td>
                    <td><img src='{$book['cover_image']}' width='50'></td>
                  </tr>";
        }
        ?>

</table>

<!-- See Assigned Books -->
<h2>Assigned Books</h2>
<table>
    <tr><th>Assignment ID</th><th>Book Title</th><th>Student</th></tr>
    <?php
    $sql = "SELECT a.assignment_id, b.title AS book_title, u.username AS student 
            FROM Assignments a
            JOIN Books b ON a.book_id = b.book_id
            JOIN Users u ON a.user_id = u.user_id";
            $assignments = $conn->query($sql);
            while ($row = $assignments->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['assignment_id']}</td>
                        <td>{$row['book_title']}</td>
                        <td>{$row['student']}</td>
                      </tr>";
            }
    
            $conn->close();
            ?>
        </table>
    
    </body>
    </html>