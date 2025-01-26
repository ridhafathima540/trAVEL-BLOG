<?php
// Start the session
session_start();

// Include the database connection file
include 'db.php'; // Make sure this file contains the correct database connection

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate the input (ensure both fields are not empty)
    if (empty($username) || empty($password)) {
        echo "<p>Please fill in both fields.</p>";
    } else {
        // Prepare the SQL query to check if the username exists in the users table
        $query = "SELECT id, username, password FROM users WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username); // Use prepared statements to prevent SQL injection
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the username exists
        if ($result->num_rows > 0) {
            // Fetch the user data from the database
            $user = $result->fetch_assoc();

            // Verify the password using password_verify()
            if (password_verify($password, $user['password'])) {
                // Password is correct, create a session for the user
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                // Redirect to the blog submission page after successful login
                header("Location: submit_blog.php"); // Redirect to blog submission page
                exit();
            } else {
                // Incorrect password
                echo "<p>Invalid username or password.</p>";
            }
        } else {
            // Username doesn't exist
            echo "<p>Invalid username or password.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - My Blog</title>
    <style>
        /* Global styles and resets */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body */
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #d3d3d3, #f0f0f0); /* Light grey background */
            color: #333;
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            justify-content: space-between;
        }

        /* Header Styles */
        header {
            background: linear-gradient(to right, #333, #555);
            color: white;
            padding: 20px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        header h1 {
            font-size: 2.5rem;
            margin-left: 20px;
            text-align: left;
        }

        header nav {
            margin-right: 20px;
        }

        header nav a {
            color: white; /* Changed text color to white */
            font-size: 1.1rem;
            margin: 0 15px;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        header nav a:hover {
            color: #e8a211;
        }

        /* Main content */
        main {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex: 1;
        }

        main h2 {
            font-size: 2em;
            margin-bottom: 20px;
            text-align: center;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 5px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 1em;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1.1em;
            cursor: pointer;
        }

        button:hover {
            background-color: #555;
        }

        /* Footer Styles */
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 15px 0;
            width: 100%;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            main {
                padding: 20px;
                width: 90%;
            }

            header nav {
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            header nav a {
                margin-left: 10px;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>

<header>
    <h1>Login to My Blog</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    </nav>
</header>

<main>
    <h2>Login</h2>
    <form action="login.php" method="post">
        <div class="form-group">
            <input type="text" name="username" placeholder="Username" required>
        </div>
        <div class="form-group">
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <button type="submit">Login</button>
    </form>

    <p style="text-align: center;">Don't have an account? <a href="register.php">Register here</a></p>
</main>

<footer>
    <p>&copy; 2025 My Blog. All Rights Reserved.</p>
</footer>

</body>
</html>
