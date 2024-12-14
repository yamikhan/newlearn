
fetch('../backend/getallcourses.php')
  .then(response => response.json())
  .then(courses => {
    const coursesContainer = document.getElementById('courses');

    if (courses.length === 0) {
      coursesContainer.innerHTML += '<p>No courses available at the moment.</p>';
      return;
    }

    courses.forEach(course => {
      const courseItem = document.createElement('div');
      courseItem.classList.add('course-item');
      courseItem.innerHTML = `
        <img src="${course.image || 'https://via.placeholder.com/350x200'}" alt="${course.title}">
        <h3>${course.title}</h3>
        <p>${course.description}</p>
        <a href="#" class="get-started" data-course-id="${course.id}">Enroll Now</a>
      `;
      coursesContainer.appendChild(courseItem);
    });
    
  })
  .catch(error => {
    console.error('Error fetching courses:', error);
  });


  document.addEventListener("click", (event) => {
    if (event.target.classList.contains("get-started")) {
      event.preventDefault(); // Prevent default link behavior

      const courseId = event.target.dataset.courseId; // Course ID from the button
      
      // Check login status
      fetch('../backend/check_login_status.php')
        .then(response => response.json())
        .then(data => {
          if (data.logged_in) {
            // Redirect to payment page if logged in
            window.location.href = `../pages/payment_courses.html?course_id=${courseId}`;
          } else {
            // Redirect to register page if not logged in
            window.location.href = '../register/register.html';
          }
        })
        .catch(error => {
          console.error("Error checking login status:", error);
        });
    }
  });


