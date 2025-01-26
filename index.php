<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - My Blog</title>
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
            align-items: center;
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
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Blog Posts Section */
        .blog-posts {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 30px;
        }

        .blog-posts article {
            background-color: #f7f7f7; /* Light grey for the blog blocks */
            padding: 20px;
            width: 60%; /* Enlarge the blog boxes */
            max-width: 400px;
            border-radius: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            text-align: center;
        }

        .blog-posts article:hover {
            transform: translateY(-10px);
        }

        .blog-posts article h3 {
            font-size: 1.8rem;
            margin-bottom: 15px;
            color: #007BFF;
        }

        .blog-posts article p {
            font-size: 1rem;
            margin-bottom: 15px;
        }

        .blog-posts article a {
            font-weight: bold;
            color: #333;
            transition: color 0.3s ease;
        }

        .blog-posts article a:hover {
            color: #e8a211;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .blog-posts article {
                width: 80%;
            }
        }
    </style>
</head>
<body>

<header>
    <h1>Blog</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    </nav>
</header>

<main>
    <div class="blog-posts">
        <?php
        // Include the database connection file
        include 'db.php';

        // Query to fetch the top 3 recent blog posts
        $query = "SELECT * FROM blogs ORDER BY created_at DESC LIMIT 3";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            // Loop through the blog posts and display them
            while ($row = $result->fetch_assoc()) {
                echo '
                <article>
                    <h3>' . htmlspecialchars($row['title']) . '</h3>
                    <p>' . htmlspecialchars(substr($row['content'], 0, 100)) . '...</p>
                    <a href="view_blog.php?id=' . $row['id'] . '">Read More</a>
                </article>';
            }
        } else {
            echo '<p>No recent blog posts found.</p>';
        }
        ?>
    </div>
</main>

</body>
</html>
