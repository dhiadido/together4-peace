<?php
class PasswordReset {
    private $conn;
    private $table = "password_resets";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Générer et enregistrer un code
    public function generateCode($email) {
        // Générer un code à 6 chiffres
        $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Expiration dans 15 minutes
        $expiresAt = date('Y-m-d H:i:s', strtotime('+15 minutes'));
        
        // Supprimer les anciens codes non utilisés pour cet email
        $deleteSql = "DELETE FROM " . $this->table . " WHERE email = :email AND used = 0";
        $stmt = $this->conn->prepare($deleteSql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        // Insérer le nouveau code
        $sql = "INSERT INTO " . $this->table . " (email, code, expires_at) 
                VALUES (:email, :code, :expires_at)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':code', $code);
        $stmt->bindParam(':expires_at', $expiresAt);
        
        if ($stmt->execute()) {
            return $code;
        }
        return false;
    }

    // Vérifier le code
    public function verifyCode($email, $code) {
        $sql = "SELECT * FROM " . $this->table . " 
                WHERE email = :email AND code = :code 
                AND used = 0 AND expires_at > NOW() 
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':code', $code);
        $stmt->execute();
        
        if ($stmt->rowCount() === 1) {
            // Marquer le code comme utilisé
            $updateSql = "UPDATE " . $this->table . " 
                         SET used = 1 WHERE email = :email AND code = :code";
            $updateStmt = $this->conn->prepare($updateSql);
            $updateStmt->bindParam(':email', $email);
            $updateStmt->bindParam(':code', $code);
            $updateStmt->execute();
            
            return true;
        }
        return false;
    }
}
?>