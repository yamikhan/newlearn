fetch('../backend/dashboard.php')
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        if (data.error) {
          // Handle unauthorized access
          alert(data.error);
          window.location.href = '../login/login.html';
        } else {
          // Populate the dashboard with user data
          document.getElementById('user-name').textContent = `${data.prenom} ${data.nom}`;
          document.getElementById('user-role').textContent = data.role;
        }
      })
      .catch(error => {
        console.error('There was a problem with the fetch operation:', error);
      });