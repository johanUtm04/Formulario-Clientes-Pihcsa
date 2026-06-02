<?php
session_start();

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin.php");
    exit;
}

require_once 'conexion.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conexion, strtolower(trim($_POST['username'])));
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $sql = "SELECT id, password, nombre FROM usuarios_admin WHERE username = '$username' LIMIT 1";
        $resultado = mysqli_query($conexion, $sql);

        if ($resultado && mysqli_num_rows($resultado) == 1) {
            $usuario = mysqli_fetch_assoc($resultado);
            
            if (password_verify($password, $usuario['password'])) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $usuario['id'];
                $_SESSION['admin_nombre'] = $usuario['nombre'];

                header("Location: admin.php");
                exit;
            } else {
                $error = "Contraseña incorrecta.";
            }
        } else {
            $error = "El usuario no existe.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Administrador PIHCSA</title>
    <style>
        .contenedor_formulario {
            width: 1000px;
            margin: 20px auto;
            border: 1px solid #ddd;
            padding: 40px 50px;
            background: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            border-radius: 4px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .producto-titulo h3 {
            color: #005596; 
            border-bottom: 2px solid #005596; 
            padding-bottom: 10px;
            margin-top: 0;
            margin-bottom: 25px;
            font-size: 22px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .segmento-campo {
            margin-bottom: 20px;
        }

        .segmento-campo p {
            margin: 0 0 8px 0;
            font-size: 13px;
            font-weight: bold;
            color: #333;
        }

        .campo {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 3px;
            font-size: 14px;
            box-sizing: border-box;
            outline: none;
            transition: border-color 0.2s ease;
        }

        .campo:focus {
            border-color: #005596;
        }

        .btn_pihcsa {
            background: #005596;
            color: #fff;
            border: none;
            padding: 12px 40px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
            border-radius: 4px;
            font-size: 14px;
            transition: background 0.3s ease;
        }

        .btn_pihcsa:hover { 
            background: #003d6b; 
        }

        body { 
            background-color: #f4f6f9; 
            margin: 0; 
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }

        .btn-back {
            color: #555;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            font-family: 'Segoe UI', sans-serif;
            transition: color 0.2s ease;
        }

        .btn-back:hover {
            color: #005596;
            text-decoration: underline;
        }

        .error-msg { 
            color: #d9534f; 
            background: #fdf7f7; 
            padding: 12px; 
            border-radius: 4px; 
            margin-bottom: 20px; 
            text-align: center; 
            font-size: 14px; 
            border: 1px solid #d9534f;
            font-family: 'Segoe UI', sans-serif;
        }
    </style>
</head>
<body>

<div class="contenedor_formulario">
    
    <div class="producto-titulo">
        <h3>Portal de Administración PIHCSA</h3>
    </div>
    
    <?php if (!empty($error)): ?>
        <div class="error-msg"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        
        <div class="segmento-campo">
            <p>USUARIO ADMINISTRADOR</p>
            <input type="text" id="username" name="username" class="campo" required autocomplete="off">
        </div>
        
        <div class="segmento-campo">
            <p>CONTRASEÑA</p>
            <input type="password" id="password" name="password" class="campo" required>
        </div>
        
        <div class="login-actions">
            <button type="submit" class="btn_pihcsa">Iniciar Sesión</button>
            <a href="index.php" class="btn-back">← Volver al Formulario</a>
        </div>

    </form>
</div>

</body>
</html>