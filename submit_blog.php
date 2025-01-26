<?php
// Start the session
session_start();

// Include the database connection file
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Logout functionality
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

// Handle new blog submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_blog'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $user_id = $_SESSION['user_id'];

    if (empty($title) || empty($content)) {
        $message = "<p style='color: red;'>Please fill in both the title and content.</p>";
    } else {
        $query = "INSERT INTO blogs (title, content, user_id, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $title, $content, $user_id);
        if ($stmt->execute()) {
            $message = "<p style='color: green;'>Blog post submitted successfully!</p>";
        } else {
            $message = "<p style='color: red;'>Failed to submit blog post. Please try again.</p>";
        }
    }
}

// Handle blog search
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search) {
    $query = "SELECT * FROM blogs WHERE title LIKE ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($query);
    $searchTerm = "%" . $search . "%";
    $stmt->bind_param("s", $searchTerm);
    $is_search = true;
} else {
    $query = "SELECT * FROM blogs WHERE user_id = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $is_search = false;
}

$stmt->execute();
$blogs = $stmt->get_result();

// Handle deletion of a blog
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];

    $checkQuery = "SELECT * FROM blogs WHERE id = ? AND user_id = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("ii", $delete_id, $_SESSION['user_id']);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        $deleteQuery = "DELETE FROM blogs WHERE id = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param("i", $delete_id);
        if ($deleteStmt->execute()) {
            header("Location: submit_blog.php");
            exit();
        } else {
            $message = "<p style='color: red;'>Failed to delete blog post. Please try again.</p>";
        }
    } else {
        $message = "<p style='color: red;'>You are not authorized to delete this blog post.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - My Blog</title>
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

        .search-bar {
            width: 200px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-bar-button {
            background-color: #333;
            border: none;
            color: white;
            padding: 8px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-bar-button:hover {
            background-color: #555;
        }

        #content {
            flex: 1;
            padding: 20px;
        }

        form {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        button {
            padding: 10px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #555;
        }

        .blog-list {
            margin-top: 20px;
        }

        .blog-list li {
            background-color: white;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .delete-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: #d9534f;
            color: white;
            padding: 5px 10px;
            border-radius: 3px;
            text-decoration: none;
        }

        .delete-btn:hover {
            background-color: #c9302c;
        }

        .logout-btn a {
            padding: 10px 20px;
            background-color: #d9534f;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .logout-btn a:hover {
            background-color: #c9302c;
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
    <h1>Blog Dashboard</h1>
    <form method="get" action="">
        <input type="text" name="search" class="search-bar" placeholder="Search Blogs..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="search-bar-button">Search</button>
    </form>
    <div class="logout-btn">
        <a href="?logout=true">Logout</a>
    </div>
</header>

<main id="content">
    <?php if (isset($message)) echo $message; ?>
    <section id="submit-form">
        <h2>Create New Blog Post</h2>
        <form action="submit_blog.php" method="post">
            <div class="form-group">
                <input type="text" name="title" placeholder="Blog Title" required>
            </div>
            <div class="form-group">
                <textarea name="content" placeholder="Write your blog content here..." required></textarea>
            </div>
            <button type="submit" name="submit_blog">Submit Blog</button>
        </form>
    </section>

    <hr>

    <section id="blogs-list" class="blog-list">
        <h2>Your Blogs</h2>
        <?php if ($blogs->num_rows > 0): ?>
            <ul>
                <?php while ($blog = $blogs->fetch_assoc()): ?>
                    <li>
                        <h3><?php echo htmlspecialchars($blog['title']); ?></h3>
                        <p><?php echo nl2br(htmlspecialchars(substr($blog['content'], 0, 200))); ?>...</p>
                        <a href="view_blog.php?id=<?php echo $blog['id']; ?>">Read More</a>
                        <?php if (!$is_search): ?>
                        <a href="?delete_id=<?php echo $blog['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this blog?')">Delete</a>
                        <?php endif; ?>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No blogs found. Create your first blog post!</p>
        <?php endif; ?>
    </section>
</main>

<footer>
    <p>&copy; 2025 My Blog. All Rights Reserved.</p>
</footer>
</body>
</html>
