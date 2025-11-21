<?php
include(__DIR__ . '/../../controller/UserController.php');

$userController = new UserController();
$error = null;
$success = null;

if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    $userData = $userController->showUser($userId);
    
    if (!$userData) {
        header('Location: listUsers.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $full_name = $_POST['full_name'] ?? '';
    $role = $_POST['role'] ?? 'user';

    if (empty($username) || empty($email) || empty($full_name)) {
        $error = "Username, email, and full name are required!";
    } else {
        $user = new User(null, $username, $email, $password, $full_name, $role);
        
        try {
            $userController->updateUser($user, $id);
            $success = "User updated successfully!";
            header("refresh:2;url=listUsers.php");
        } catch (Exception $e) {
            $error = "Error updating user: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User - Back-Office</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .form-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 600px;
        }
        h1 {
            color: #667eea;
            margin-bottom: 30px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
        }
        input, select {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #667eea;
        }
        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background: #f39c12;
            color: white;
        }
        .btn-primary:hover {
            background: #e67e22;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(243, 156, 18, 0.4);
        }
        .btn-secondary {
            background: #95a5a6;
            color: white;
            text-decoration: none;
            display: inline-block;
        }
        .btn-secondary:hover {
            background: #7f8c8d;
        }
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }
        .info-text {
            font-size: 14px;
            color: #7f8c8d;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>✏️ Update User</h1>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?> Redirecting...</div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="id" value="<?php echo $userData['id']; ?>">

            <div class="form-group">
                <label for="username">Username *</label>
                <input type="text" id="username" name="username" 
                       value="<?php echo htmlspecialchars($userData['username']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" 
                       value="<?php echo htmlspecialchars($userData['email']); ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Leave empty to keep current password">
                <p class="info-text">Only fill this field if you want to change the password</p>
            </div>

            <div class="form-group">
                <label for="full_name">Full Name *</label>
                <input type="text" id="full_name" name="full_name" 
                       value="<?php echo htmlspecialchars($userData['full_name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="role">Role *</label>
                <select id="role" name="role" required>
                    <option value="user" <?php echo $userData['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                    <option value="admin" <?php echo $userData['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-primary">💾 Update User</button>
                <a href="listUsers.php" class="btn btn-secondary">❌ Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>