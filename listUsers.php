<?php
include(__DIR__ . '/../../controller/UserController.php');

$userController = new UserController();
$list = $userController->listUsers();
$totalUsers = $userController->countUsers();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Back-Office - User Management</title>
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
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        .stats {
            background: rgba(255,255,255,0.2);
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
        }
        .content {
            padding: 30px;
        }
        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }
        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .btn-danger {
            background: #e74c3c;
            color: white;
        }
        .btn-danger:hover {
            background: #c0392b;
        }
        .btn-warning {
            background: #f39c12;
            color: white;
        }
        .btn-warning:hover {
            background: #e67e22;
        }
        .search-box {
            padding: 10px 15px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            width: 300px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }
        td {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
        }
        tr:hover {
            background: #f8f9fa;
        }
        .badge {
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-admin {
            background: #e74c3c;
            color: white;
        }
        .badge-user {
            background: #3498db;
            color: white;
        }
        .actions {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>👥 Back-Office - User Management</h1>
            <div class="stats">
                <strong>Total Users:</strong> <?php echo $totalUsers; ?>
            </div>
        </div>
        
        <div class="content">
            <div class="action-bar">
                <a href="addUser.php" class="btn btn-primary">➕ Add New User</a>
                <form method="GET" style="display: flex; gap: 10px;">
                    <input type="text" name="search" class="search-box" placeholder="Search users..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit" class="btn btn-primary">🔍 Search</button>
                    <?php if(isset($_GET['search'])): ?>
                        <a href="listUsers.php" class="btn btn-warning">Clear</a>
                    <?php endif; ?>
                </form>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Full Name</th>
                        <th>Role</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if(isset($_GET['search']) && !empty($_GET['search'])) {
                        $list = $userController->searchUsers($_GET['search']);
                    }
                    
                    foreach ($list as $user) {
                    ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($user['username']); ?></strong></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                        <td>
                            <span class="badge badge-<?php echo $user['role']; ?>">
                                <?php echo strtoupper($user['role']); ?>
                            </span>
                        </td>
                        <td><?php echo date('d/m/Y H:i', strtotime($user['created_at'])); ?></td>
                        <td class="actions">
                            <a href="updateUser.php?id=<?php echo $user['id']; ?>" class="btn btn-warning">✏️ Edit</a>
                            <a href="deleteUser.php?id=<?php echo $user['id']; ?>" 
                               class="btn btn-danger"
                               onclick="return confirm('Are you sure you want to delete this user?')">
                               🗑️ Delete
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>