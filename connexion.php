<?php
session_start();
include_once('bd.php');

$erreur = ""; // Variable pour stocker le message d'erreur

if (isset($_POST['ok'])) {
    $email = htmlspecialchars($_POST['email']);
    $mdp   = htmlspecialchars($_POST['mdp']);

    if (empty($email) || empty($mdp)) {
        $erreur = "Veuillez remplir tous les champs.";
    } else {
        $req = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ? AND mot_de_passe = ? AND actif = 1");
        $req->execute([$email, $mdp]);

        if ($req->rowCount() === 1) {
            $user = $req->fetch(PDO::FETCH_ASSOC);

            $_SESSION['id_user']     = $user['id_user'];
            $_SESSION['nom']         = $user['nom'];
            $_SESSION['prenom']      = $user['prenom'];
            $_SESSION['tel']         = $user['tel'];
            $_SESSION['email']       = $user['email'];
            $_SESSION['mot_de_passe']= $user['mot_de_passe'];
            $_SESSION['role']        = $user['role'];

            switch ($user['role']) {
                case 'ADMIN':
                    header('Location: dashboardadmin2.php');
                    break;
                case 'RH':
                    header('Location: pageRH.php');
                    break;
                case 'EMPLOYE':
                    header('Location: pageemployé.php');
                    break;
                default:
                    $erreur = "Rôle non reconnu. Contactez l'administrateur.";
            }
            exit();
        } else {
            $erreur = "Email ou mot de passe incorrect.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>AG-TIME — Connexion</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="60x60" href="img/Logo contemporain AG-Time sur fond blanc.png">

    <style>
        * { font-family: 'Roboto', sans-serif; }

        body {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 40px 36px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .login-logo {
            text-align: center;
            margin-bottom: 28px;
        }

        .login-logo img {
            height: 60px;
            margin-bottom: 10px;
        }

        .login-logo h2 {
            font-size: 26px;
            font-weight: 700;
            color: #0f3460;
            margin: 0;
            letter-spacing: 2px;
        }

        .login-logo p {
            font-size: 13px;
            color: #888;
            margin: 4px 0 0;
        }

        .form-control {
            border-radius: 8px;
            border: 1.5px solid #dee2e6;
            padding: 12px 14px;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            border-color: #0f3460;
            box-shadow: 0 0 0 3px rgba(15,52,96,0.1);
        }

        .input-group-text {
            background: #f8f9fa;
            border: 1.5px solid #dee2e6;
            border-radius: 8px 0 0 8px;
            color: #0f3460;
        }

        .input-group .form-control {
            border-radius: 0 8px 8px 0;
            border-left: none;
        }

        .btn-login {
            background: #0f3460;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-size: 15px;
            font-weight: 600;
            width: 100%;
            transition: background 0.2s, transform 0.1s;
        }

        .btn-login:hover {
            background: #16213e;
            color: white;
            transform: translateY(-1px);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* Message d'erreur intégré — pas de redirection */
        .alert-erreur {
            background: #fff5f5;
            border: 1.5px solid #f5c6cb;
            border-radius: 8px;
            color: #c0392b;
            padding: 10px 14px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .form-label {
            font-size: 13px;
            font-weight: 500;
            color: #444;
            margin-bottom: 5px;
        }

        .lien-inscription {
            font-size: 13px;
            color: #888;
            text-align: center;
            margin-top: 18px;
        }

        .lien-inscription a {
            color: #0f3460;
            font-weight: 600;
            text-decoration: none;
        }

        .lien-inscription a:hover {
            text-decoration: underline;
        }

        .divider {
            border-top: 1px solid #eee;
            margin: 20px 0;
        }
    </style>
</head>
<body>

<div class="login-card">

    <!-- Logo -->
    <div class="login-logo">
        <img src="img/Logo contemporain AG-Time sur fond blanc.png" alt="AG-TIME"
             onerror="this.style.display='none'">
        <h2>AG-TIME</h2>
        <p>Gestion des congés et permissions</p>
    </div>

    <div class="divider"></div>

    <!-- Message d'erreur — affiché seulement s'il y a une erreur -->
    <?php if (!empty($erreur)): ?>
        <div class="alert-erreur mb-3">
            <i class="bi bi-exclamation-circle-fill"></i>
            <?= htmlspecialchars($erreur) ?>
        </div>
    <?php endif; ?>

    <!-- Formulaire -->
    <form action="connexion.php" method="POST">

        <div class="mb-3">
            <label class="form-label">Adresse email</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" class="form-control <?= !empty($erreur) ? 'border-danger' : '' ?>"
                       name="email" placeholder="exemple@email.com"
                       value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                       required>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label">Mot de passe</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" class="form-control <?= !empty($erreur) ? 'border-danger' : '' ?>"
                       name="mdp" placeholder="••••••••" required>
            </div>
        </div>

        <button type="submit" name="ok" class="btn-login">
            <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
        </button>

    </form>

    <p class="lien-inscription">
        Pas encore de compte ?
        <a href="enregistrement.html">S'enregistrer</a>
    </p>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>