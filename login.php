<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email']);
    $password = sanitize($_POST['password']);
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            
            // Update last login and streak
            $today = date('Y-m-d');
            $yesterday = date('Y-m-d', strtotime('-1 day'));
            $streak = $user['streak'];
            
            if ($user['last_login'] === $yesterday) {
                $streak++;
            } elseif ($user['last_login'] !== $today) {
                $streak = 1;
            }
            
            $updateStmt = $pdo->prepare("UPDATE users SET last_login = ?, streak = ? WHERE id = ?");
            $updateStmt->execute([$today, $streak, $user['id']]);
            
            redirect('index.php');
        } else {
            $_SESSION['error'] = "Email ou mot de passe incorrect.";
            redirect('index.php');
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur de connexion : " . $e->getMessage();
        redirect('index.php');
    }
}
?>