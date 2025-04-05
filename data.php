<html>
<header>
<style>
body{
    font-family: san-sarif;
    background-color:gray;
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
    margin:0;
}  

.login-container
{
    background-color:white;
    padding:30px;
    border-radius:5px;
    width:300px;
}

h2
{
    text-align:center;
    margin-bottom:5px;
}
label{
    display:block;
    margin-bottom:5px;
}
.inputText
{
    width:100%;
    padding:10px;
    border: 1px solid gray;
    box-sizing:border-box;
}

.btnSubmit
{
    background-color:blue;
    color:white;
    padding: 10px 15px;
    border:none;
    border-radius: 3px;
    cursor:pointer;
}

.btnSubmit:hover
{
    background-color:green;
}

</style>




</header>

<body>
<div class="login-container">
<form method="POST" action="data.php">
<h2>Please login:</h2>
<?php
session_start();

// Get form inputs
$username = filter_input(INPUT_POST, 'username');
$password = filter_input(INPUT_POST, 'password');

// Database connection
$host = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "activityphp";

$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare SQL to prevent SQL injection
$sql = "SELECT * FROM Users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Check if user exists
if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Check password
    if (($password == $user['password_hash'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on role
        if ($user['role'] == 'teacher') {
            header("Location: teacherdashboard.php");
        } elseif ($user['role'] == 'student') {
            header("Location: studentdashboard.php");
        } else {
            echo "Unknown role.";
        }
    } else {
        echo "Invalid password.".$password.$user['password_hash'];
    }
} else {
    echo "No user found with that username.";
}

$conn->close();
?>
    <label for="username" id="Lblusername" name="Lblusername">Username: </label>
    <input type="text" id="username" name="username" class="inputText"/>

    <label for="password" id="lblpassword" name="lblpassword">Password: </label>
    <input type="password" id="password" name="password" class="inputText"/>

    <input type="submit" id="btnSubmit" name="btnSubmit" value="Submit" class="btnSubmit"/>
</form>

</div>

</body>
</html>