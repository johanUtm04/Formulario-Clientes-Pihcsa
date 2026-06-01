<?php
include("conexion.php");

$rfc = isset($_GET['rfc']) ? mysqli_real_escape_string($conexion, $_GET['rfc']) : '';

if (!$rfc) {
    echo json_encode(['existe' => false]);
    exit;
}

$query = "SELECT * FROM formulario_clientes WHERE rfc = '$rfc' LIMIT 1";
$res = mysqli_query($conexion, $query);

if ($row = mysqli_fetch_assoc($res)) {
    // Ofuscamos el email para seguridad: j***n@gmail.com
    $correo = $row['email'];
    $partes = explode("@", $correo);
    $email_oculto = substr($partes[0], 0, 1) . "***" . substr($partes[0], -1) . "@" . $partes[1];

    echo json_encode([
        'existe' => true,
        'razon_social' => $row['razon_social'],
        'domicilio' => $row['domicilio'],
        'poblacion' => $row['poblacion'],
        'colonia' => $row['colonia'],
        'cp' => $row['cp'],
        'estado' => $row['estado'],
        'telefono'     => $row['telefono'],
        'email' => $email_oculto,
        'web' => $row['pagina_web'],
        'archivos' => [
            'licencia' => !empty($row['doc_licencia_sanitaria']),
            'aviso_rs' => !empty($row['doc_aviso_responsableSanitario']),
            'funcionamiento' => !empty($row['doc_aviso_funcionamiento']),
            'doc_comprobante_domicilio' => $row['doc_comprobante_domicilio'],
            'doc_ine_representanteLegal' => $row['doc_ine_representanteLegal'],
            'doc_ine_responsableSanitario' => $row['doc_ine_responsableSanitario'],
            'img_fachada' => $row['img_fachada'],
            'img_almacen' => $row['img_almacen'],


        ]
    ]);
} else {
    echo json_encode(['existe' => false]);
}