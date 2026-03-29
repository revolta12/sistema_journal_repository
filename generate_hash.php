<?php
// Hatama password ne'ebé Ita hakarak iha ne'e
$password_plain = 'donatos'; 

// Jeru hash
$password_hashed = password_hash($password_plain, PASSWORD_DEFAULT);
?>

<!DOCTYPE html>
<html lang="tp">
<head>
    <meta charset="UTF-8">
    <title>Jeru Password Hash</title>
    <style>
        body { font-family: sans-serif; padding: 20px; line-height: 1.6; }
        .container { max-width: 600px; margin: auto; border: 1px solid #ccc; padding: 20px; border-radius: 8px; }
        code { background: #eee; padding: 5px; display: block; margin: 10px 0; word-break: break-all; }
        .label { font-weight: bold; color: #333; }
    </style>
</head>
<body>

<div class="container">
    <h2><i class="fas fa-key"></i> Jeru Password Hash</h2>
    <hr>
    
    <p>
        <span class="label">Password Belun:</span><br>
        <code><?php echo htmlspecialchars($password_plain); ?></code>
    </p>

    <p>
        <span class="label">Hasil Hash (Rai ida-ne'e iha Database):</span><br>
        <code><?php echo $password_hashed; ?></code>
    </p>

    <div style="background: #fff3cd; padding: 10px; border-radius: 5px; font-size: 0.9em;">
        <strong>Informasaun:</strong> Hash ne'e sei troka beibeik bainhira Ita <em>refresh</em> pajina, maibé password <code>admin123</code> nafatin válidu.
    </div>
    
    <br>
    <a href="index.php">Fila ba Inísiu</a>
</div>

</body>
</html>
