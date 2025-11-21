<?php
include(__DIR__ . '/../config.php');
include(__DIR__ . '/../model/User.php');

class UserController {

    // List all users
    public function listUsers() {
        $sql = "SELECT * FROM users ORDER BY created_at DESC";
        $db = config::getConnexion();
        try {
            $list = $db->query($sql);
            return $list;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    // Delete user by ID
    public function deleteUser($id) {
        $sql = "DELETE FROM users WHERE id = :id";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':id', $id);
        try {
            $req->execute();
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    // Add new user
    public function addUser(User $user) {
        $sql = "INSERT INTO users VALUES (NULL, :username, :email, :password, :full_name, :role, NOW(), NOW())";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'password' => password_hash($user->getPassword(), PASSWORD_DEFAULT),
                'full_name' => $user->getFullName(),
                'role' => $user->getRole()
            ]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    // Update user
    public function updateUser(User $user, $id) {
        try {
            $db = config::getConnexion();
            
            // Check if password needs to be updated
            if (!empty($user->getPassword())) {
                $query = $db->prepare(
                    'UPDATE users SET 
                        username = :username,
                        email = :email,
                        password = :password,
                        full_name = :full_name,
                        role = :role,
                        updated_at = NOW()
                    WHERE id = :id'
                );
                $query->execute([
                    'id' => $id,
                    'username' => $user->getUsername(),
                    'email' => $user->getEmail(),
                    'password' => password_hash($user->getPassword(), PASSWORD_DEFAULT),
                    'full_name' => $user->getFullName(),
                    'role' => $user->getRole()
                ]);
            } else {
                // Update without changing password
                $query = $db->prepare(
                    'UPDATE users SET 
                        username = :username,
                        email = :email,
                        full_name = :full_name,
                        role = :role,
                        updated_at = NOW()
                    WHERE id = :id'
                );
                $query->execute([
                    'id' => $id,
                    'username' => $user->getUsername(),
                    'email' => $user->getEmail(),
                    'full_name' => $user->getFullName(),
                    'role' => $user->getRole()
                ]);
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Show single user by ID
    public function showUser($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        $query->bindValue(':id', $id);

        try {
            $query->execute();
            $user = $query->fetch();
            return $user;
        } catch (Exception $e) {
            die('Error: '. $e->getMessage());
        }
    }

    // Search users by username or email
    public function searchUsers($search) {
        $sql = "SELECT * FROM users WHERE username LIKE :search OR email LIKE :search OR full_name LIKE :search";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['search' => "%$search%"]);
            return $query;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    // Count total users
    public function countUsers() {
        $sql = "SELECT COUNT(*) as total FROM users";
        $db = config::getConnexion();
        try {
            $result = $db->query($sql);
            return $result->fetch()['total'];
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }
}
?>