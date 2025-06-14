<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: user_dash.html");
  exit();
}

$conn = new mysqli("localhost", "root", "", "vastu_users");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION['username'];
$user_query = $conn->prepare("SELECT id FROM users WHERE username = ?");
$user_query->bind_param("s", $username);
$user_query->execute();
$result = $user_query->get_result();
$user = $result->fetch_assoc();
$user_id = $user['id'];

$stmt = $conn->prepare("SELECT * FROM appointments WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$appointments = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Past Appointments</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #fff;
      margin: 0;
      padding: 20px;
      color: #000;
    }

    h2 {
      text-align: center;
      color: #000;
      margin-bottom: 30px;
    }

    table {
      width: 90%;
      margin: auto;
      border-collapse: collapse;
      background-color: #fff;
      border: 1px solid #000;
    }

    th, td {
      padding: 12px 15px;
      border: 1px solid #000;
      text-align: left;
    }

    th {
      background-color: #000;
      color: #fff;
    }

    tr:hover {
      background-color: #f2f2f2;
    }

    .back-button {
      display: block;
      width: fit-content;
      margin: 40px auto 0;
      padding: 10px 20px;
      font-size: 16px;
      background-color: #000;
      color: #fff;
      text-decoration: none;
      border-radius: 5px;
    }

    .back-button:hover {
      background-color: #333;
    }
  </style>
</head>
<body>
  <h2>Your Past Appointments</h2>
  <table>
    <tr>
      <th>Name</th>
      <th>DOB</th>
      <th>Birthplace</th>
      <th>Referred By</th>
      <th>Profession</th>
      <th>Location</th>
      <th>Property Type</th>
      <th>Phone</th>
      <th>Mode</th>
      <th>Status</th>
      <th>Date</th>
      <th>Time</th>
    </tr>

    <?php while ($row = $appointments->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['dob']) ?></td>
        <td><?= htmlspecialchars($row['birthplace']) ?></td>
        <td><?= htmlspecialchars($row['referred_by']) ?></td>
        <td><?= htmlspecialchars($row['profession']) ?></td>
        <td><?= htmlspecialchars($row['location']) ?></td>
        <td><?= htmlspecialchars($row['property_type']) ?></td>
        <td><?= htmlspecialchars($row['phone']) ?></td>
        <td><?= htmlspecialchars($row['mode']) ?></td>
        <td><?= htmlspecialchars($row['status']) ?></td>
        <td><?= htmlspecialchars($row['date']) ?></td>
        <td><?= htmlspecialchars($row['time']) ?></td>
      </tr>
    <?php endwhile; ?>
  </table>

  <a class="back-button" href="appointment_form.php">← Back to Appointment Form</a>
</body>
</html>
