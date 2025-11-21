<?php
require_once __DIR__ . '/../../controller/UserController.php';

$userController = new UserController();
$list = $userController->listUsers();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Directory - Front-Office</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            color: white;
            margin-bottom: 40px;
        }
        .header h1 {
            font-size: 3em;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        .header p {
            font-size: 1.2em;
            opacity: 0.9;
        }
        .search-container {
            max-width: 600px;
            margin: 0 auto 40px;
        }
        .search-box {
            width: 100%;
            padding: 15px 20px;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }
        .user-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
        }
        .user-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .user-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        }
        .user-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .user-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2em;
            font-weight: bold;
            margin: 0 auto 20px;
        }
        .user-name {
            font-size: 1.5em;
            color: #333;
            margin-bottom: 10px;
            text-align: center;
        }
        .user-username {
            color: #667eea;
            text-align: center;
            margin-bottom: 5px;
            font-weight: 600;
        }
        .user-email {
            color: #7f8c8d;
            text-align: center;
            margin-bottom: 15px;
            font-size: 0.9em;
        }
        .user-info {
            display: flex;
            justify-content: space-around;
            padding-top: 15px;
            border-top: 2px solid #f0f0f0;
            margin-top: 15px;
        }
        .info-item {
            text-align: center;
        }
        .info-label {
            font-size: 0.8em;
            color: #95a5a6;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .info-value {
            font-weight: bold;
            color: #333;
        }
        .badge {
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }
        .badge-admin {
            background: #e74c3c;
            color: white;
        }
        .badge-user {
            background: #3498db;
            color: white;
        }
        .no-results {
            text-align: center;
            color: white;
            font-size: 1.5em;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>👥 User Directory</h1>
            <p>Meet our community members</p>
        </div>

        <div class="search-container">
            <form method="GET">
                <input type="text" name="search" class="search-box" 
                       placeholder="🔍 Search users by name, username or email..." 
                       value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            </form>
        </div>

        <div class="user-grid">
            <?php
            if(isset($_GET['search']) && !empty($_GET['search'])) {
                $list = $userController->searchUsers($_GET['search']);
            }
            
            $hasResults = false;
            foreach ($list as $user) {
                $hasResults = true;
                $initials = strtoupper(substr($user['full_name'], 0, 1));
            ?>
            <div class="user-card">
                <div class="user-avatar"><?php echo $initials; ?></div>
                <div class="user-name"><?php echo htmlspecialchars($user['full_name']); ?></div>
                <div class="user-username">@<?php echo htmlspecialchars($user['username']); ?></div>
                <div class="user-email">📧 <?php echo htmlspecialchars($user['email']); ?></div>
                
                <div class="user-info">
                    <div class="info-item">
                        <div class="info-label">Role</div>
                        <div class="info-value">
                            <span class="badge badge-<?php echo $user['role']; ?>">
                                <?php echo strtoupper($user['role']); ?>
                            </span>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Member Since</div>
                        <div class="info-value"><?php echo date('M Y', strtotime($user['created_at'])); ?></div>
                    </div>
                </div>
            </div>
            <?php } 
            
            if (!$hasResults) {
                echo '<div class="no-results">No users found matching your search.</div>';
            }
            ?>
        </div>
    </div>
</body>
</html>