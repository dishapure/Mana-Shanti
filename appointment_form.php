<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: user_dash.html");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard</title>
  <link rel="stylesheet" href="style.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f7f3fb;
    }
    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #4b2a68;
      padding: 10px 20px;
      color: white;
    }
    .logo-group {
      display: flex;
      align-items: center;
      gap: 20px;
    }
    .logo {
      width: 40px;
      height: 40px;
      border-radius: 50%;
    }
    .nav-links {
      list-style: none;
      display: flex;
      gap: 20px;
      margin: 0;
      padding: 0;
    }
    .nav-links li a {
      color: white;
      text-decoration: none;
    }
    .nav-links li a.cta-button {
      background-color: #6d3c8f;
      padding: 8px 16px;
      border-radius: 10px;
    }
    .container {
      display: flex;
    }
    .sidebar {
      background-color: #4b2a68;
      color: #fff;
      width: 250px;
      height: 100vh;
      padding: 20px;
      box-sizing: border-box;
    }
    .sidebar h2 {
      font-size: 18px;
      margin: 20px 0;
    }
    .sidebar button {
      display: block;
      width: 100%;
      margin: 10px 0;
      padding: 12px;
      background-color: #6d3c8f;
      border: none;
      border-radius: 10px;
      color: #fff;
      font-size: 16px;
      cursor: pointer;
    }
    .sidebar button:hover {
      background-color: #8548aa;
    }
    .main {
      flex-grow: 1;
      padding: 30px;
    }
    .dashboard-section {
      display: flex;
      gap: 30px;
      margin-bottom: 40px;
    }
    .card {
      background-color: #fff;
      padding: 20px;
      border-radius: 20px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
      flex: 1;
    }
    .form-section, .messages-section, .products-section, .past-appointments-section {
      background: #fff;
      border-radius: 20px;
      padding: 20px;
      margin-bottom: 40px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
    .form-section h2, .messages-section h2, .products-section h2, .past-appointments-section h2 {
      margin-top: 0;
    }
    .form-group {
      margin-bottom: 15px;
    }
    .form-group label {
      display: block;
      margin-bottom: 5px;
    }
    .form-group input, .form-group select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 8px;
    }
    .submit-btn {
      padding: 10px 20px;
      background-color: #6d3c8f;
      color: #fff;
      border: none;
      border-radius: 10px;
      cursor: pointer;
    }
    .submit-btn:hover {
      background-color: #8548aa;
    }
  </style>
</head>
<body>
  <header>
    <div class="navbar">
      <a href="index.html" class="logo-group" style="text-decoration: none; color: inherit;">
        <img src="vastu_logo.jpg" alt="Vastu Logo" class="logo" />
        <span class="company-name">Vastu Consultancies</span>
      </a>
      <ul class="nav-links">
        <li><a href="index.html">Home</a></li>
        <li><a href="about.html">About Us</a></li>
        <li><a href="#">Our Services</a></li>
        <li><a href="review.html">Reviews</a></li>
        <li><a href="logout.php" class="cta-button">Logout (<?= $_SESSION['username'] ?>)</a></li>
      </ul>
    </div>
  </header>

  <div class="container">
    <div class="sidebar">
      <img src="avatar.jpg" alt="Avatar" style="width: 80px; border-radius: 50%;" />
      <h2>Welcome, <?= $_SESSION['username'] ?></h2>
      <button onclick="scrollToSection('dashboard')">Dashboard</button>
      <button onclick="scrollToSection('form-section')">Book Appointment</button>
      <button onclick="scrollToSection('past-appointments-section')">Past Appointments</button>
      <button onclick="scrollToSection('messages-section')">Messages</button>
      <button onclick="scrollToSection('products-section')">Products</button>
    </div>
    <div class="main">
      <div id="dashboard" class="dashboard-section">
        <div class="card">
          <h3>Appointments: Solved vs. Pending</h3>
          <canvas id="solvedPendingChart"></canvas>
        </div>
        <div class="card">
          <h3>Monthly Booking Count</h3>
          <canvas id="monthlyChart"></canvas>
        </div>
      </div>

      <div id="form-section" class="form-section">
        <h2>Book Your Appointment</h2>
        <form id="appointmentForm" action="submit.php" method="POST">
          <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" required />
          </div>
          <div class="form-group">
            <label>Date of Birth & Time</label>
            <input name="dob" required />
          </div>
          <div class="form-group">
            <label>Birth Place</label>
            <input type="text" name="birthplace" required />
          </div>
          <div class="form-group">
            <label>Who Referred You?</label>
            <input type="text" name="referred_by" />
          </div>
          <div class="form-group">
            <label>Profession/Job</label>
            <input type="text" name="profession" />
          </div>
          <div class="form-group">
            <label>Location for Vastu</label>
            <input type="text" name="location" required />
          </div>
          <div class="form-group">
            <label>Property Type</label>
            <select name="property_type" required>
              <option value="">-- Please Select --</option>
              <option value="commercial">Commercial</option>
              <option value="residential">Residential</option>
            </select>
          </div>
          <div class="form-group">
            <label>Phone</label>
            <input type="tel" name="phone" required />
          </div>
          <div class="form-group">
            <label>Meeting Mode</label>
            <label><input type="radio" name="mode" value="online" checked />Online</label>
            <label><input type="radio" name="mode" value="offline" /> Offline</label>
          </div>
          <button type="submit" class="submit-btn">Submit Appointment</button>
        </form>
      </div>

      <div id="past-appointments-section" class="past-appointments-section">
        <h2>Past Appointments</h2>
        <form action="view_past_appointments.php" method="GET" style="margin-top: 20px; text-align: center;">
          <button type="submit" class="submit-btn">View Past Appointments</button>
        </form>
      </div>

      <div id="messages-section" class="messages-section">
        <h2>Messages from Astrologer</h2>
        <p>No messages from the astrologer at this time.</p>
      </div>

      <div id="products-section" class="products-section">
        <h2>Products You May Need to Buy</h2>
        <ul>
          <li>Yantra - ₹599</li>
          <li>Crystal Pyramid - ₹899</li>
          <li>Vastu Salt - ₹199</li>
          <li>Camphor Lamp - ₹349</li>
        </ul>
      </div>
    </div>
  </div>

  <script>
    function scrollToSection(id) {
      document.getElementById(id).scrollIntoView({ behavior: 'smooth' });
    }

    new Chart(document.getElementById("solvedPendingChart"), {
      type: 'bar',
      data: {
        labels: ['Solved', 'Pending'],
        datasets: [{
          label: 'Appointments',
          data: [40, 15],
          backgroundColor: ['#53c4e0', '#e480c7']
        }]
      }
    });

    new Chart(document.getElementById("monthlyChart"), {
      type: 'line',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
          label: 'Appointments',
          data: [5, 8, 10, 9, 14, 10],
          borderColor: '#8548aa',
          backgroundColor: 'rgba(133, 72, 170, 0.1)',
          tension: 0.4,
          fill: true
        }]
      }
    });

    document.getElementById("appointmentForm").addEventListener("submit", function(event) {
      const phone = document.querySelector("input[name='phone']").value.trim();
      const phoneRegex = /^[6-9]\d{9}$/;

      if (!phoneRegex.test(phone)) {
        alert("Please enter a valid 10-digit phone number starting with 6-9.");
        event.preventDefault();
        return;
      }

      const emailField = document.querySelector("input[name='email']");
      if (emailField) {
        const email = emailField.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
          alert("Please enter a valid email address.");
          event.preventDefault();
        }
      }
    });
  </script>
</body>
</html>
