<?php
// 1. Configuración de errores y sesión
ini_set('display_errors', 1);
error_reporting(E_ALL);

include("conexion.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

// 2. Recibir datos de texto
$razon_social = mysqli_real_escape_string($conexion, $_POST['razon_social']);
$rfc          = mysqli_real_escape_string($conexion, strtoupper(trim($_POST['rfc'])));
$domicilio    = mysqli_real_escape_string($conexion, $_POST['domicilio']);
$poblacion    = mysqli_real_escape_string($conexion, $_POST['poblacion']);
$colonia      = mysqli_real_escape_string($conexion, $_POST['colonia']);
$cp           = mysqli_real_escape_string($conexion, $_POST['cp']);
$estado       = mysqli_real_escape_string($conexion, $_POST['estado']);
$email        = mysqli_real_escape_string($conexion, $_POST['email']);
$telefono     = mysqli_real_escape_string($conexion, $_POST['telefono']);
$web          = mysqli_real_escape_string($conexion, $_POST['web']);
$firma        = mysqli_real_escape_string($conexion, $_POST['firma_digital']);

// 3. Preparar Directorio de Archivos
$base_dir = '/srv/www/htdocs/clientes/uploads/' . $rfc . "/";
if (!file_exists($base_dir)) { 
    mkdir($base_dir, 0777, true); 
}

// 4. Función para subir archivos con nombre forzado
function subirArchivo($file_input, $dest_dir, $nombre_forzado) {
    if (isset($_FILES[$file_input]) && $_FILES[$file_input]['error'] === UPLOAD_ERR_OK) {
        // Obtenemos la extensión original (.pdf, .jpg, etc.)
        $extension = pathinfo($_FILES[$file_input]['name'], PATHINFO_EXTENSION);
        
        // El nuevo nombre será: nombre_campo.extension
        $nombre_final = $nombre_forzado . "." . $extension;
        
        if (move_uploaded_file($_FILES[$file_input]['tmp_name'], $dest_dir . $nombre_final)) {
            return $nombre_final;
        }
    }
    return null;
}

// 5. Procesar las subidas con nombres normalizados
$p_lic   = subirArchivo('pdf_licencia', $base_dir, 'doc_licencia_sanitaria');
$p_av_rs = subirArchivo('pdf_aviso_rs', $base_dir, 'doc_aviso_responsableSanitario');
$p_fun   = subirArchivo('pdf_funcionamiento', $base_dir, 'doc_aviso_funcionamiento');
$p_dom   = subirArchivo('pdf_domicilio', $base_dir, 'doc_comprobante_domicilio');
$p_ine_r = subirArchivo('pdf_ine_responsable', $base_dir, 'doc_ine_representanteLegal');
$p_ine_s = subirArchivo('pdf_ine_responsable_sanitario', $base_dir, 'doc_ine_responsableSanitario');
$p_fac   = subirArchivo('img_fachada', $base_dir, 'img_fachada');
$p_alm   = subirArchivo('img_almacen', $base_dir, 'img_almacen');


// 6. Generar el PDF del Comprobante
require('fpdf/fpdf.php');
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'COMPROBANTE PIHCSA', 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(0, 6, "Razon Social: $razon_social\nRFC: $rfc\nFirma: $firma");
$pdf->Output('F', $base_dir . "AVISO_PRIVACIDAD_FIRMADO.pdf");

// 7. LÓGICA DE BASE DE DATOS (INSERT O UPDATE)
$checkRFC = "SELECT id FROM formulario_clientes WHERE rfc = '$rfc' LIMIT 1";
$resCheck = mysqli_query($conexion, $checkRFC);
$existe = (mysqli_num_rows($resCheck) > 0);

if ($existe) {
    // MODO UPDATE
    $query = "UPDATE formulario_clientes SET 
        razon_social = '$razon_social', 
        domicilio = '$domicilio', 
        poblacion = '$poblacion', 
        colonia = '$colonia', 
        cp = '$cp', 
        estado = '$estado', 
        pagina_web = '$web', 
        telefono = '$telefono', 
        email = '$email', 
        firma_digital = '$firma'";
    
    // Solo agregar el campo a la consulta si se subió un archivo nuevo
    if($p_lic)  $query .= ", doc_licencia_sanitaria = '$p_lic'";
    if($p_av_rs) $query .= ", doc_aviso_responsableSanitario = '$p_av_rs'";
    if($p_fun)  $query .= ", doc_aviso_funcionamiento = '$p_fun'";
    if($p_dom)  $query .= ", doc_comprobante_domicilio = '$p_dom'";
    if($p_ine_r) $query .= ", doc_ine_representanteLegal = '$p_ine_r'";
    if($p_ine_s) $query .= ", doc_ine_responsableSanitario = '$p_ine_s'";
    if($p_fac)  $query .= ", img_fachada = '$p_fac'";
    if($p_alm)  $query .= ", img_almacen = '$p_alm'";

    $query .= " WHERE rfc = '$rfc'";
} else {
    // MODO INSERT
    $query = "INSERT INTO formulario_clientes (
        razon_social, domicilio, poblacion, colonia, cp, estado, rfc, pagina_web, telefono, email, firma_digital,
        doc_licencia_sanitaria, doc_aviso_responsableSanitario, doc_aviso_funcionamiento, 
        doc_ine_responsableSanitario, doc_ine_representanteLegal, doc_comprobante_domicilio,
        img_fachada, img_almacen
    ) VALUES (
        '$razon_social', '$domicilio', '$poblacion', '$colonia', '$cp', '$estado', '$rfc', '$web', '$telefono', '$email', '$firma',
        ".($p_lic?"'$p_lic'":"NULL").", ".($p_av_rs?"'$p_av_rs'":"NULL").", ".($p_fun?"'$p_fun'":"NULL").", 
        ".($p_ine_s?"'$p_ine_s'":"NULL").", ".($p_ine_r?"'$p_ine_r'":"NULL").", ".($p_dom?"'$p_dom'":"NULL").", 
        ".($p_fac?"'$p_fac'":"NULL").", ".($p_alm?"'$p_alm'":"NULL")."
    )";
}

// 8. Ejecución Final
if (mysqli_query($conexion, $query)) {
    header("Location: index.php?status=success");
} else {
    die("Error en MariaDB: " . mysqli_error($conexion));
}
?>