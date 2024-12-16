<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../backend/db.php'; // Ensure the correct path to db.php

    // Sanitize and validate input data
    $role = $_POST['role'];
    $prenom = htmlspecialchars($_POST['prenom']);
    $nom = htmlspecialchars($_POST['nom']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Adresse email invalide.";
        exit();
    }

    // Check if the password and confirm password match
    if ($password !== $confirmPassword) {
        echo "Les mots de passe ne correspondent pas.";
        exit();
    }

    // Hash the password for secure storage
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    // Check if email already exists in the database
    $checkEmailSql = "SELECT email FROM users WHERE email = ?";
    if ($stmt = $conn->prepare($checkEmailSql)) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "Cet e-mail est déjà utilisé. Veuillez en choisir un autre.";
            $stmt->close();
            exit();
        }
        $stmt->close();
    }

    // Prepare SQL query to insert the new user into the database
    $sql = "INSERT INTO users (role, prenom, nom, email, password) VALUES (?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param('sssss', $role, $prenom, $nom, $email, $passwordHash);

        // Execute the statement
        if ($stmt->execute()) {
            echo "Inscription réussie! <a href='../login/login.html'>Se connecter</a>";
        } else {
            echo "Erreur d'inscription: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Erreur de préparation de la requête: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>
