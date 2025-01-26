<?php
// Start the session
session_start();

// Include database connection file
include 'db.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password (for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the username already exists in the database
    $check_query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If username exists, display an error
        echo "<p>Username already taken. Please choose another one.</p>";
    } else {
        // Insert new user data into the database
        $insert_query = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("ss", $username, $hashed_password);

        if ($stmt->execute()) {
            echo "<p>Registration successful! You can <a href='login.php'>login now</a>.</p>";
        } else {
            echo "<p>Error: " . $stmt->error . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - My Blog</title>
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
    <h1>Register for My Blog</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="dashboard.php">Dashboard</a>
        <?php endif; ?>
    </nav>
</header>

<main>
    <h2>Register</h2>
    <form action="register.php" method="post">
        <div class="form-group">
            <input type="text" name="username" placeholder="Username" required>
        </div>
        <div class="form-group">
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
</main>

<footer>
    <p>&copy; 2025 My Blog. All Rights Reserved.</p>
</footer>

</body>
</html>
