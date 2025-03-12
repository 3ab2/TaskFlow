<?php
class AuthController {
    private $db;

    public function __construct() {
        require_once __DIR__ . "/../config/database.php";
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function register($username, $email, $password, $confirm_password) {
        $errors = [];

        // Validation
        if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
            $errors[] = "Tous les champs sont obligatoires";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Format d'email invalide";
        }

        if ($password !== $confirm_password) {
            $errors[] = "Les mots de passe ne correspondent pas";
        }

        if (strlen($password) < 8) {
            $errors[] = "Le mot de passe doit contenir au moins 8 caractères";
        }

        // Vérifier si l'email existe déjà
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = "Cet email est déjà utilisé";
        }

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Hasher le mot de passe
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insérer l'utilisateur
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password, status) VALUES (?, ?, ?, 'available')");
        
        if ($stmt->execute([$username, $email, $hashed_password])) {
            return [
                'success' => true,
                'message' => 'Inscription réussie ! Vous pouvez maintenant vous connecter.'
            ];
        }

        return [
            'success' => false,
            'errors' => ['Une erreur est survenue lors de l\'inscription.']
        ];
    }

    public function login($email, $password) {
        $errors = [];

        // Validation
        if (empty($email) || empty($password)) {
            $errors[] = "Tous les champs sont obligatoires";
        }

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Vérifier les identifiants
        $stmt = $this->db->prepare("SELECT id, username, email, password, status FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Démarrer la session et stocker les informations de l'utilisateur
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['status'] = $user['status'];

            return [
                'success' => true,
                'redirect' => '/pfe/views/dashboard.php'
            ];
        }

        return [
            'success' => false,
            'errors' => ['Email ou mot de passe incorrect']
        ];
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: /pfe/index.php');
        exit();
    }
}
?>
