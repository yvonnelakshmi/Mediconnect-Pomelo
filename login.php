<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('DVWA_WEB_PAGE_TO_ROOT', '');
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup(array('php'));

session_start();
$page = dvwaPageNewGrab();
$page['title'] = 'Patient Login';
$page['page_id'] = 'login';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_text'])) {
    $text = $_POST['user_text'];
    $now = date('Y-m-d H:i:s');
    $entry = $text . '|' . $now . PHP_EOL;
    file_put_contents('log.txt', $entry, FILE_APPEND);

    if (is_valid_jwt($text)) {
        $_SESSION['jwt_verified'] = true;
        header("Location: dashboard.php");
        exit;
    }
}

function is_valid_jwt($jwt) {
    $key = <<<EOD
-----BEGIN PUBLIC KEY-----
MFYwEAYHKoZIzj0CAQYFK4EEAAoDQgAEMU1JFVEO9FkVr0r041GpAWzKvQi1TBYm
arJj3+aNeC2aK9GT7Hct1OJGWQGbUkNWTeUr+Ui09PjBit+AMYuHgA==
-----END PUBLIC KEY-----
EOD;
    $parts = explode('.', $jwt);
    if (count($parts) !== 3) return false;
    [$header, $payload, $sig] = $parts;
    $data = "$header.$payload";
    $sig = base64url_decode($sig);
    $publicKey = openssl_pkey_get_public($key);
    return openssl_verify($data, $sig, $publicKey, OPENSSL_ALGO_SHA256) === 1;
}

function base64url_decode($data) {
    $pad = strlen($data) % 4;
    if ($pad) $data .= str_repeat('=', 4 - $pad);
    return base64_decode(strtr($data, '-_', '+/'));
}


$page['body'] = '
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
    margin: 20px auto;
    padding: 20px 30px;
    box-shadow: 0 0 12px rgba(0,0,0,0.08);
    border-radius: 16px;
    text-align: center;
  }
  .login-container img {
    width: 300px;
    margin-bottom: -10px;
  }
  .form-section {
    margin-top: 20px;
    text-align: center;
  }
  label {
    font-size: 17px;
    font-weight: bold;
    color: #003366;
    display: block;
    margin-bottom: 5px;
    text-align: left;
    width: 60%;
    margin: 0 auto 5px auto;
  }
  input[type="text"] {
    width: 60%;
    padding: 10px;
    background-color: #ffffdd;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 15px;
    margin-bottom: 15px;
  }
  input[type="submit"] {
    margin-top: 10px;
    width: 60%;
    padding: 12px 30px;
    background-color: #a3d4f7;
    color: #003366;
    border: none;
    border-radius: 8px;
    font-weight: bold;
    font-size: 16px;
    cursor: pointer;
  }
  input[type="submit"]:hover {
    background-color: #87c4f0;
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

<div class="login-container">
  <img src="images/mediconnect-logo.png" alt="MediConnect Logo">
  <div class="form-section">
    <form method="post">
      <label for="user_text">Enter Message or JWT</label>
      <input type="text" name="user_text" id="user_text" required>
      <input type="submit" value="Submit">
    </form>';

$table = "<table style='margin-top: 30px; width: 80%; margin-left: auto; margin-right: auto;' border='1'>
<tr><th>Message</th><th>Timestamp</th></tr>";

if (file_exists("log.txt")) {
    $lines = file("log.txt", FILE_IGNORE_NEW_LINES);
    foreach ($lines as $line) {
        [$msg, $time] = explode('|', $line);
        $highlight = is_valid_jwt($msg) ? "style=\"background-color:#ffcccc\"" : "";
        $table .= "<tr $highlight><td>$msg</td><td>$time</td></tr>";
    }
}
$table .= "</table>";

$page['body'] .= $table;
$page['body'] .= '
    <a href="index.php" class="back-link">← Back to Home</a>
  </div>
</div>
';

dvwaHtmlEcho($page);
?>

<!-- Firebase Scripts (outside PHP) -->
<script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-auth.js"></script>
<script src="firebase-config.js"></script>


