<?php
session_start();

// Simuler des données d'utilisateur pour l'exemple
$valid_username = 'admin';
$valid_password = 'password';

// Récupérer les données du formulaire
$username = $_POST['username'];
$password = $_POST['password'];

// Vérifier les informations de connexion
if ($username === $valid_username && $password === $valid_password) {
    $_SESSION['loggedin'] = true;
    header('Location: dashboard.html');
} else {
    echo "<p>Nom d'utilisateur ou mot de passe incorrect. <a href='login.html'>Réessayer</a></p>";
}
?>
