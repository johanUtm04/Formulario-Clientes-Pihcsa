<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

require_once 'conexion.php';

$sql = "SELECT id, razon_social, rfc, fecha_registro FROM formulario_clientes ORDER BY fecha_registro DESC";
$resultado = mysqli_query($conexion, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración - PIHCSA</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css" />
    <style>
        .admin-container { max-width: 1200px; margin: 30px auto; padding: 0 20px; font-family: Arial, sans-serif; }
        .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; background: #005596; color: white; padding: 15px 20px; border-radius: 6px; }
        .admin-header h1 { margin: 0; font-size: 1.5rem; }
        .btn-logout { background-color: #d9534f; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 0.9rem; }
        .grid-expedientes { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; margin-top: 20px; }
        .tarjeta-expediente { background: white; border: 1px solid #ddd; border-radius: 8px; padding: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); transition: transform 0.2s; position: relative; }
        .tarjeta-expediente:hover { transform: translateY(-5px); box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .icon-folder { font-size: 40px; color: #ffc107; margin-bottom: 10px; }
        .tarjeta-titulo { font-weight: bold; color: #333; margin-bottom: 5px; font-size: 1.1rem; }
        .tarjeta-sub { color: #666; font-size: 0.85rem; margin-bottom: 15px; }
        .btn-ver { display: block; text-align: center; background: #005596; color: white; padding: 8px; text-decoration: none; border-radius: 4px; font-size: 0.9rem; font-weight: bold; }
        .btn-ver:hover { background: #003b69; }
        .no-data { text-align: center; color: #999; padding: 4px; grid-column: 1 / -1; }
    </style>
</head>
<body style="background-color: #f4f6f9; margin: 0;">

<div class="admin-container">
    
    <div class="admin-header">
        <h1> Panel de Expedientes Digitales</h1>
        <div>
            <span style="margin-right: 15px;">Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['admin_nombre']); ?></strong></span>
            <a href="logout.php" class="btn-logout">Cerrar Sesión</a>
        </div>
    </div>

    <h3>Expedientes de Clientes Recientes</h3>
    
    <div class="grid-expedientes">
        <?php 
        if ($resultado && mysqli_num_rows($resultado) > 0): 
            while($cliente = mysqli_fetch_assoc($resultado)): 
                $rfc_cliente = $cliente['rfc'];
                $ruta_carpeta = "uploads/" . $rfc_cliente;
                
                $existe_carpeta = is_dir($ruta_carpeta);
        ?>
            <div class="tarjeta-expediente">
                <div class="icon-folder" style="<?php echo !$existe_carpeta ? 'color: #ccc;' : ''; ?>">
                    <?php echo $existe_carpeta ? '📁' : '📂'; ?>
                </div>
                <div class="tarjeta-titulo"><?php echo htmlspecialchars($cliente['razon_social']); ?></div>
                <div class="tarjeta-sub">
                    <strong>RFC:</strong> <?php echo htmlspecialchars($rfc_cliente); ?><br>
                    <span style="font-size: 0.75rem; color: #999;">Registrado: <?php echo $cliente['fecha_registro']; ?></span>
                </div>
                
                <?php if ($existe_carpeta): ?>
                    <a href="ver_expediente.php?rfc=<?php echo urlencode($rfc_cliente); ?>" class="btn-ver">Ver Documentos</a>
                <?php else: ?>
                    <span style="display:block; text-align:center; color:#999; font-size:0.85rem; padding:8px; background:#eee; border-radius:4px;">Sin carpeta física</span>
                <?php endif; ?>
            </div>
        <?php 
            endwhile; 
        else: 
        ?>
            <div class="no-data">No se han encontrado registros de clientes en la base de datos.</div>
        <?php endif; ?>
    </div>

</div>

</body>
</html>