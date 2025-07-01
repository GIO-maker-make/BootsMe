<?php
require_once 'config.php';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $password_hash = password_hash(sanitize($_POST['password']), PASSWORD_BCRYPT);
    
    try {
        // Vérifier si l'utilisateur existe déjà
        $checkStmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $checkStmt->execute([$email]);
        
        if ($checkStmt->fetch()) {
            $_SESSION['error'] = "Un compte avec cet email existe déjà.";
            redirect('register.php');
        }
        
        // Créer le nouvel utilisateur
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $password_hash]);
        
        $_SESSION['success'] = "Compte créé avec succès. Vous pouvez maintenant vous connecter.";
        redirect('index.php');
    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur lors de la création du compte : " . $e->getMessage();
        redirect('register.php');
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - BoostMe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-purple-50 to-blue-50 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
        <!-- Affichage des messages d'erreur/succès -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?= $_SESSION['error'] ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="text-center mb-6">
            <img src="images/logo.png" alt="Logo BoostMe" class="h-16 mx-auto mb-3">
            <h2 class="text-2xl font-bold text-primary-600">Créer un compte</h2>
            <p class="text-sm text-gray-500 mt-1">Commencez votre voyage vers la productivité</p>
        </div>
        
        <form method="POST" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent transition"
                >
            </div>
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent transition"
                >
            </div>
            
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    minlength="8"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent transition"
                >
            </div>
            
            <button type="submit" class="w-full bg-primary-500 hover:bg-primary-600 text-white py-3 rounded-xl font-medium transition-all flex items-center justify-center gap-2">
                <i class="fas fa-user-plus"></i>
                S'inscrire
            </button>
        </form>
        
        <div class="mt-6 pt-4 border-t border-gray-100 text-center">
            <p class="text-sm text-gray-600">
                Déjà inscrit ?
                <a href="index.php" class="text-primary-600 font-medium hover:underline">Se connecter</a>
            </p>
        </div>
    </div>
</body>
</html>