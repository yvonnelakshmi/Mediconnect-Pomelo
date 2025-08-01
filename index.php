<?php

<!DOCTYPE html>
<html>
  <head>
    <title>My App</title>
  </head>
  <body>
    <h1>Welcome to MediConnect</h1>
  </body>
</html>

<div style="background-color: #ffffff; font-family: Arial, sans-serif; color: #222; padding: 20px; max-width: 900px; margin: 20px auto 10px auto; box-shadow: 0 0 12px rgba(0,0,0,0.08); border-radius: 16px;">
  <div style="text-align: center;">
    <img src="images/mediconnect-logo.png" alt="MediConnect Logo" style="width: 300px; margin-top: -30px; margin-bottom: -15px;"> <!-- Raised logo -->
    <h1 style="font-family: Georgia, serif; color: #003366; font-size: 36px; margin-top: 0px;">MediConnect</h1>
    <p style="font-size: 17px; color: #444;">Securely managing health data ensuring quality care.</p>
    <a href="login.php" style="display: inline-block; margin-top: 12px; padding: 12px 30px; background-color: #a3d4f7; color: #003366; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 16px;">Patient Login</a>
  </div>

  <div style="margin-top: 30px;">
    <h2 style="color: #003366;">Patient Portal Guidance</h2>
    <p>This portal simulates access to electronic health records. Users may explore how patient data can be protected or compromised under common security configurations.</p>

    <h3 style="color: #d00000; font-size: 18px; font-weight: normal;">Security Notice</h3>
    <p style="font-size: 14px;">This is a simulation containing intentionally insecure settings.</p>
    <p style="text-align: center;"><strong style="color: #d00000;">Do not deploy this environment publicly.</strong></p>

    <h3 style="color: #003366;">Disclosure</h3>
    <p>This training environment is for educational use only. No real patient information is used. Please proceed ethically and responsibly.</p>

    <h2 style="color: #003366;">Further Learning</h2>
    <ul>
      <li>' . dvwaExternalLinkUrlGet("https://owasp.org/www-project-top-ten/", "OWASP Top Ten Project") . '</li>
      <li>' . dvwaExternalLinkUrlGet("https://portswigger.net/web-security", "PortSwigger Web Security Academy") . '</li>
    </ul>
  </div>
</div>
';

dvwaHtmlEcho($page);
?>


