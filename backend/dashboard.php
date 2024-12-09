<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

echo "Bienvenue, " . $_SESSION['prenom'] . " " . $_SESSION['nom'] . "<br>";
echo "Votre rôle : " . $_SESSION['role'] . "<br>";
echo "<a href='logout.php'>Déconnexion</a>";
?>
