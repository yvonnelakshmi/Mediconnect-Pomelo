<?php
session_start();

$message = '';
$timestamp = '';
$highlight = '';
$latestMsg = '';
$latestTime = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_text'])) {
    $input = $_POST['user_text'];
    $timestamp = date('Y-m-d H:i:s');

    // Check if input is a JWT
    $parts = explode('.', $input);
    if (count($parts) === 3) {
        [$header, $payload, $signature] = $parts;
        $headerDecoded = json_decode(base64_decode(strtr($header, '-_', '+/')), true);
        if ($headerDecoded && $headerDecoded['alg'] === 'ES256') {
            $data = "$header.$payload";
            $signatureDecoded = base64_decode(strtr($signature, '-_', '+/'));

            $pubkey = <<<KEY
-----BEGIN PUBLIC KEY-----
MFYwEAYHKoZIzj0CAQYFK4EEAAoDQgAEMU1JFVEO9FkVr0r041GpAWzKvQi1TBYm
arJj3+aNeC2aK9GT7Hct1OJGWQGbUkNWTeUr+Ui09PjBit+AMYuHgA==
-----END PUBLIC KEY-----
KEY;

            $pubkey_res = openssl_pkey_get_public($pubkey);
            if ($pubkey_res !== false) {
                $result = openssl_verify($data, $signatureDecoded, $pubkey_res, OPENSSL_ALGO_SHA256);
                if ($result === 1) {
                    $highlight = 'highlight'; // JWT is valid
                }
            }
        }
    }

    $_SESSION['last_entry'] = ['message' => $input, 'time' => $timestamp, 'highlight' => $highlight];
}

// Load last entry if exists
if (!empty($_SESSION['last_entry'])) {
    $latestMsg = $_SESSION['last_entry']['message'];
    $latestTime = $_SESSION['last_entry']['time'];
    $highlight = $_SESSION['last_entry']['highlight'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Secure Login</title>
  <style>
    body {
      background-color: #f2f9ff;
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }

    .login-container {
      background-color: #ffffff;
      max-width: 900px;
      margin: 40px auto;
      padding: 30px;
      box-shadow: 0 0 12px rgba(0,0,0,0.08);
      border-radius: 16px;
      text-align: center;
    }

    .login-container img {
      width: 300px;
      margin-bottom: -20px;
    }

    h2 {
      font-size: 24px;
      color: #003366;
      margin-top: 10px;
      margin-bottom: 5px;
    }

    p.subtext {
      font-size: 14px;
      color: #555;
      margin-top: -5px;
      margin-bottom: 20px;
    }

    input[type="text"] {
      width: 60%;
      padding: 12px;
      font-size: 16px;
      margin-bottom: 10px;
    }

    input[type="submit"] {
      width: 180px;
      padding: 12px;
      font-size: 16px;
      background-color: #a3d4f7;
      color: #003366;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
      margin-top: 10px;
    }

    input[type="submit"]:hover {
      background-color: #87c4f0;
    }

    #google-btn {
      font-family: Arial, sans-serif;
      margin-top: 20px;
      padding: 12px 30px;
      background-color: #4285F4;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 15px;
      font-weight: bold;
      cursor: pointer;
    }

    #google-btn:hover {
      background-color: #3367D6;
    }

    table {
      width: 80%;
      margin: 30px auto;
      border-collapse: collapse;
    }

    th, td {
      padding: 12px;
      border: 1px solid #ccc;
      word-wrap: break-word;
    }

    .highlight {
      background-color: #ffcccc;
    }

    .back-link {
      display: block;
      margin-top: 20px;
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

  <div class="login-container">
    <img src="images/mediconnect-logo.png" alt="MediConnect Logo">
    <h2>Secure Login</h2>
    <p class="subtext">Enter Credentials</p>

    <form method="post">
      <div>
        <input type="text" name="user_text" placeholder="Enter JWT or Secure Message" required>
      </div>
      <div>
        <input type="submit" value="Submit">
      </div>
    </form>

    <button id="google-btn">Login using Google</button>

    <?php if (!empty($latestMsg)): ?>
      <table>
        <tr><th>Message</th><th>Timestamp</th></tr>
        <tr class="<?= $highlight ?>">
          <td><?= htmlspecialchars($latestMsg) ?></td>
          <td><?= htmlspecialchars($latestTime) ?></td>
        </tr>
      </table>
    <?php endif; ?>

    <a href="index.php" class="back-link">← Back to Home</a>
  </div>

  <!-- Firebase Scripts -->
  <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
  <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-auth.js"></script>
  <script>
    var firebaseConfig = {
      apiKey: "AIzaSyAduADfKzwtT7pqczt-aeqZLlzmmEaKS3s",
      authDomain: "mediconnect-pomelo.firebaseapp.com",
      projectId: "mediconnect-pomelo",
      storageBucket: "mediconnect-pomelo.appspot.com",
      messagingSenderId: "361684866524",
      appId: "1:361684866524:web:cd725aa5f209aea59b2b19"
    };
    firebase.initializeApp(firebaseConfig);

    document.getElementById('google-btn').addEventListener('click', function() {
      var provider = new firebase.auth.GoogleAuthProvider();
      firebase.auth().signInWithPopup(provider).then(function(result) {
        window.location.href = "dashboard.php";
      }).catch(function(error) {
        alert("Google Sign-In failed: " + error.message);
      });
    });
  </script>

</body>
</html>
