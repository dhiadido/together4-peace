<?php
class User {
    private ?int $id;
    private ?string $username;
    private ?string $email;
    private ?string $password;
    private ?string $full_name;
    private ?string $role;
    private ?DateTime $created_at;
    private ?DateTime $updated_at;

    // Constructor
    public function __construct(
        ?int $id = null,
        ?string $username = null,
        ?string $email = null,
        ?string $password = null,
        ?string $full_name = null,
        ?string $role = 'user',
        ?DateTime $created_at = null,
        ?DateTime $updated_at = null
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->full_name = $full_name;
        $this->role = $role;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function show() {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Full Name</th><th>Role</th><th>Created At</th></tr>";
        echo "<tr>";
        echo "<td>{$this->id}</td>";
        echo "<td>{$this->username}</td>";
        echo "<td>{$this->email}</td>";
        echo "<td>{$this->full_name}</td>";
        echo "<td>{$this->role}</td>";
        echo "<td>" . ($this->created_at ? $this->created_at->format('Y-m-d H:i:s') : '') . "</td>";
        echo "</tr>";
        echo "</table>";
    }

    // Getters and Setters
    public function getId(): ?int {
        return $this->id;
    }

    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function getUsername(): ?string {
        return $this->username;
    }

    public function setUsername(?string $username): void {
        $this->username = $username;
    }

    public function getEmail(): ?string {
        return $this->email;
    }

    public function setEmail(?string $email): void {
        $this->email = $email;
    }

    public function getPassword(): ?string {
        return $this->password;
    }

    public function setPassword(?string $password): void {
        $this->password = $password;
    }

    public function getFullName(): ?string {
        return $this->full_name;
    }

    public function setFullName(?string $full_name): void {
        $this->full_name = $full_name;
    }

    public function getRole(): ?string {
        return $this->role;
    }

    public function setRole(?string $role): void {
        $this->role = $role;
    }

    public function getCreatedAt(): ?DateTime {
        return $this->created_at;
    }

    public function setCreatedAt(?DateTime $created_at): void {
        $this->created_at = $created_at;
    }

    public function getUpdatedAt(): ?DateTime {
        return $this->updated_at;
    }

    public function setUpdatedAt(?DateTime $updated_at): void {
        $this->updated_at = $updated_at;
    }
}
?>