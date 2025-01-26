<?php
// Start the session
session_start();

// Include the database connection file
include 'db.php';

// Check if the blog ID is provided in the URL
if (isset($_GET['id'])) {
    $blog_id = $_GET['id'];

    // Prepare the SQL query to fetch the full blog post
    $query = "SELECT * FROM blogs WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $blog_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the blog post
    if ($result->num_rows > 0) {
        $blog = $result->fetch_assoc();
    } else {
        echo "<p>Blog not found.</p>";
        exit();
    }
} else {
    echo "<p>No blog ID provided.</p>";
    exit();
}

// Get the referring page URL
$referring_page = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Blog - My Blog</title>
    <style>
        /* General Styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            background-color: #444;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            font-size: 24px;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 15px;
        }

        nav a:hover {
            text-decoration: underline;
        }

        #content {
            flex: 1;
            padding: 20px;
        }

        .back-btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #333;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            margin-bottom: 20px;
        }

        .back-btn:hover {
            background-color: #555;
        }

        .blog-content {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        footer {
            text-align: center;
            background-color: #444;
            color: white;
            padding: 10px 0;
            margin-top: auto;
        }
    </style>
</head>
<body>

<header>
    <h1>View Blog Post</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    </nav>
</header>

<main>
    <div id="content">
        <!-- Back Button -->
        <a href="<?php echo htmlspecialchars($referring_page); ?>" class="back-btn">Back to Blog List</a>

        <div class="blog-content">
            <h2><?php echo htmlspecialchars($blog['title']); ?></h2>
            <p><?php echo nl2br(htmlspecialchars($blog['content'])); ?></p>
        </div>
    </div>
</main>

<footer>
    <p>&copy; 2025 My Blog. All Rights Reserved.</p>
</footer>

</body>
</html>
