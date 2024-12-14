const urlParams = new URLSearchParams(window.location.search);
    const courseId = urlParams.get('course_id');
    const courseIdInput = document.getElementById('course-id');

    if (courseId) {
      courseIdInput.value = courseId;

      // Fetch course details from the backend
      fetch(`../backend/get_course_details.php?course_id=${courseId}`)
        .then(response => response.json())
        .then(course => {
          if (course) {
            document.getElementById('order-info').innerHTML = `
              <h2>Order Information</h2>
              <p><strong>Course:</strong> ${course.title}</p>
              <p><strong>Description:</strong> ${course.description}</p>
              <p><strong>Price:</strong> $${course.price}</p>
            `;
          } else {
            alert('Invalid course details.');
            window.location.href = '../dashboard/dashboard.html';
          }
        })
        .catch(error => {
          console.error('Error fetching course details:', error);
        });

    } else {
      alert('Invalid course selection.');
      window.location.href = '../dashboard/dashboard.html';
    }

    function showPaymentDetails(method) {
      const paymentMethodInput = document.getElementById('payment-method');
      paymentMethodInput.value = method;

      const paymentDetails = document.getElementById('payment-details');
      const paymentFields = document.getElementById('payment-fields');
      const paymentTitle = document.getElementById('payment-title');

      paymentFields.innerHTML = '';

      if (method === 'PayPal') {
        paymentTitle.textContent = 'Enter your PayPal Email';
        paymentFields.innerHTML = '<input type="email" name="paypal_email" placeholder="Enter PayPal Email" required>';
      } else if (method === 'Credit Card') {
        paymentTitle.textContent = 'Enter your Credit Card Details';
        paymentFields.innerHTML = `
          <input type="text" name="card_number" placeholder="Card Number" required>
          <input type="text" name="card_expiry" placeholder="MM/YY" required>
          <input type="text" name="card_cvc" placeholder="CVC" required>
        `;
      } else if (method === 'Skrill') {
        paymentTitle.textContent = 'Enter your Skrill Email';
        paymentFields.innerHTML = '<input type="email" name="skrill_email" placeholder="Enter Skrill Email" required>';
      }

      paymentDetails.style.display = 'block';
    }