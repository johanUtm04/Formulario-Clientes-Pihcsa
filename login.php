<?php
session_start();

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin.php");
    exit;
}

require_once 'conexion.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conexion, trim($_POST['username']));
    $password = trim($_POST['password']);

    // --- DEBUGGING BLOCK START ---
    echo "<h3>--- Debugging Mode ---</h3>";
    echo "Form Username Submitted: [" . $username . "]<br>";
    echo "Form Password Submitted: [" . $password . "]<br>";
    // --- DEBUGGING BLOCK END ---

    if (!empty($username) && !empty($password)) {
        $sql = "SELECT id, password, nombre FROM usuarios_admin WHERE username = '$username' LIMIT 1";
        $resultado = mysqli_query($conexion, $sql);

        if ($resultado && mysqli_num_rows($resultado) == 1) {
            $usuario = mysqli_fetch_assoc($resultado);
            
            // --- DEBUGGING BLOCK START ---
            echo "User found in database!<br>";
            echo "Database Hash: [" . $usuario['password'] . "]<br>";
            
            // Test if password matches the hash
            if (password_verify($password, $usuario['password'])) {
                echo "<strong>SUCCESS: Password matches!</strong><br>";
                // --- DEBUGGING BLOCK END ---

                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $usuario['id'];
                $_SESSION['admin_nombre'] = $usuario['nombre'];

                header("Location: admin.php");
                exit;
            } else {
                // --- DEBUGGING BLOCK START ---
                echo "<strong>ERROR: password_verify failed. The hash does not match the password.</strong><br>";
                die(); // Stop script execution so you can read the debug messages
                // --- DEBUGGING BLOCK END ---
                $error = "Contraseña incorrecta.";
            }
        } else {
            // --- DEBUGGING BLOCK START ---
            echo "<strong>ERROR: Username not found in the database. Rows found: " . mysqli_num_rows($resultado) . "</strong><br>";
            echo "MySQL Error if any: " . mysqli_error($conexion) . "<br>";
            die();
            // --- DEBUGGING BLOCK END ---
            $error = "El usuario no existe.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Administrador PIHCSA</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 100%; max-width: 360px; }
        h2 { text-align: center; color: #004687; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; color: #333; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn-submit { width: 100%; padding: 10px; background-color: #004687; border: none; color: white; font-size: 16px; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .btn-submit:hover { background-color: #002d59; }
        .error-msg { color: #d9534f; background: #fdf7f7; padding: 10px; border-radius: 4px; margin-bottom: 15px; text-align: center; font-size: 14px; border: 1px solid #d9534f; }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Admin Login</h2>
    
    <?php if (!empty($error)): ?>
        <div class="error-msg"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="form-group">
            <label for="username">Usuario</label>
            <input type="text" id="username" name="username" required autocomplete="off">
        </div>
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn-submit">Iniciar Sesión</button>
    </form>
</div>

</body>
</html>