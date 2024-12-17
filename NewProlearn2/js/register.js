
  document.querySelector('form').addEventListener('submit', function(event) {
    event.preventDefault(); // Empêche l'envoi du formulaire tant que les conditions ne sont pas remplies
    
    // Récupération des champs
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm-password').value;

    // Regex pour valider l'email et le mot de passe
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

    // Vérification de l'email
    if (!emailRegex.test(email)) {
      alert("L'adresse e-mail n'est pas correcte. Veuillez saisir une adresse valide.");
      return;
    }

    // Vérification du mot de passe
    if (!passwordRegex.test(password)) {
      alert("Le mot de passe doit contenir au moins :\n- Une lettre majuscule\n- Une lettre minuscule\n- Un chiffre\n- Un caractère spécial (@$!%*?&)\n- Et être d'au moins 8 caractères.");
      return;
    }

    // Vérification si les mots de passe sont identiques
    if (password !== confirmPassword) {
      alert("Les mots de passe ne correspondent pas.");
      return;
    }

    // Envoi du formulaire si tout est valide
    alert("Inscription réussie !");
    event.target.submit(); // Envoie du formulaire
  });
