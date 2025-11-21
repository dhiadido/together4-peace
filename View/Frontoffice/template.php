<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title'] ?? 'Quiz'; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        /* ---------------------- NEW HEADER GRADIENT ---------------------- */
        header {
            background: linear-gradient(90deg, #3B82F6 0%, #10B981 100%);
            color: white;
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        /* ------------------------------------------------------------------ */

        nav {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.3rem;
            font-weight: bold;
            display: flex;
            align-items: center;
        }

        .logo img {
            height: 40px; 
            margin-right: 10px;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 2rem;
        }

        nav a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.3s;
        }

        nav a:hover {
            opacity: 0.8;
        }

        main {
            min-height: calc(100vh - 200px);
        }

        /* ---------------------- UPDATED FOOTER COLOR ---------------------- */
        footer {
            background: linear-gradient(90deg, #3B82F6 0%, #10B981 100%);
            color: white;
            text-align: center;
            padding: 2rem;
            margin-top: 4rem;
        }
        /* ------------------------------------------------------------------ */

        @media (max-width: 768px) {
            nav ul {
                gap: 1rem;
            }
        }
    </style>

    <link rel="stylesheet" href="View/Frontoffice/styles.css">
</head>

<body>
    <!-- Header Navigation -->
    <header>
        <nav>
            <div class="logo">
                <img src="..\..\assets\logo.png" alt="Logo"> Together4Peace
            </div>
            <ul>
                <li><a href="index.php">Quiz</a></li>
                <li><a href="#">À propos</a></li>
                <li><a href="..\..\View\Backoffice/index.php" class="btn">Back Office</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Content (Your Quiz Page) -->
    <main align="center">
        <?php 
        $viewFile = __DIR__ . '/' . $view . '.php';
        if(file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            echo "Quiz file not found: " . $viewFile;
        }
        ?>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Together4Peace. Une plateforme collaborative pour la paix mondiale, la tolérance et la solidarité</p>
    </footer>

</body>
</html>
