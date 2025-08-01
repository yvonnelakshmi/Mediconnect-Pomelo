<?php
// Debugging (optional in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Protect page: must be logged in
session_start();
if (!isset($_SESSION['jwt_verified']) || $_SESSION['jwt_verified'] !== true) {
    header("Location: login.php");
    exit;
}
// Sample patient data
$samplePatients = [
  ['001', 'Jane Smith', 'Hypertension', '2025-04-30', 'Lisinopril 10mg'],
  ['002', 'Carlos Rivera', 'Type II Diabetes', '2025-05-02', 'Metformin 500mg'],
  ['003', 'Emily Nguyen', 'Asthma', '2025-04-25', 'Albuterol Inhaler'],
  ['004', 'Fatima Ali', 'Anxiety', '2025-05-01', 'Sertraline 50mg']
];
?>
<!DOCTYPE html>
<html>
<head>
  <title>Patient Dashboard</title>
  <style>
    body {
      background-color: #f2f9ff;
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }
    .dashboard-container {
      background-color: #ffffff;
      max-width: 900px;
      margin: 40px auto;
      padding: 30px;
      box-shadow: 0 0 12px rgba(0,0,0,0.08);
      border-radius: 16px;
      text-align: center;
    }

    .dashboard-container img {
      width: 300px;
      margin-bottom: -10px;
    }

    h2 {
      font-size: 24px;
      color: #003366;
      margin-bottom: 10px;
    }

    p {
      font-size: 16px;
      color: #555;
      margin-bottom: 30px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: #ffffff;
      font-size: 15px;
    }

    th, td {
      padding: 14px;
      border: 1px solid #ccc;
      text-align: left;
    }

    th {
      background-color: #cce5ff;
      color: #003366;
      font-weight: bold;
    }

    tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    .note {
      font-size: 14px;
      color: #888;
      margin-top: 20px;
    }

    .back-link {
      display: block;
      margin-top: 30px;
      font-size: 15px;
      color: #003366;
      text-decoration: none;
      font-weight: bold;
    }

    .back-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<div class="dashboard-container">
  <img src="images/mediconnect-logo.png" alt="MediConnect Logo">
  <h2>Patient Dashboard</h2>
  <p>Review your most recent appointment and health records below.</p>

  <table>
    <tr>
      <th>Patient ID</th>
      <th>Name</th>
      <th>Diagnosis</th>
      <th>Last Visit</th>
      <th>Prescription</th>
    </tr>
    <?php foreach ($samplePatients as $p): ?>
      <tr>
        <td><?= htmlspecialchars($p[0]) ?></td>
        <td><?= htmlspecialchars($p[1]) ?></td>
        <td><?= htmlspecialchars($p[2]) ?></td>
        <td><?= htmlspecialchars($p[3]) ?></td>
        <td><?= htmlspecialchars($p[4]) ?></td>
      </tr>
    <?php endforeach; ?>
  </table>

  <p class="note">* This information is fictional and for demonstration purposes only.</p>
  <a href="index.php" class="back-link">← Back to Home</a>
</div>

</body>
</html>


