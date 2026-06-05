<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include("conexion.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

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

$base_dir = 'uploads/' . $rfc . "/";
if (!file_exists($base_dir)) { 
    mkdir($base_dir, 0777, true); 
}

function subirArchivo($file_input, $dest_dir, $nombre_forzado) {
    if (isset($_FILES[$file_input]) && $_FILES[$file_input]['error'] === UPLOAD_ERR_OK) {
        $file_name = $_FILES[$file_input]['name'];
        $tmp_name = $_FILES[$file_input]['tmp_name'];
        $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (strpos($nombre_forzado, 'foto_') === 0 || strpos($nombre_forzado, 'img_') === 0) {
            $allowed_extensions = ['jpg', 'jpeg', 'png'];
            $allowed_mimes = ['image/jpeg', 'image/png'];
        } else {
            $allowed_extensions = ['pdf'];
            $allowed_mimes = ['application/pdf'];
        }

        if (!in_array($extension, $allowed_extensions)) {
            die("Error de Seguridad: El archivo '$file_name' tiene una extensión no permitida (.$extension).");
        }

        if (function_exists('mime_content_type')) { 
            $real_mime = mime_content_type($tmp_name);
            if (!in_array($real_mime, $allowed_mimes)) {
                die("Error de Seguridad: El contenido real de '$file_name' no coincide con su extensión ($real_mime).");
            }
        }

        $nombre_final = $nombre_forzado . "." . $extension;
        if (move_uploaded_file($tmp_name, $dest_dir . $nombre_final)) {
            return $nombre_final;
        }
    }
    return null;
}

$p_lic   = subirArchivo('pdf_licencia', $base_dir, 'doc_licencia_sanitaria');
$p_av_rs = subirArchivo('pdf_aviso_rs', $base_dir, 'doc_aviso_responsableSanitario');
$p_fun   = subirArchivo('pdf_funcionamiento', $base_dir, 'doc_aviso_funcionamiento');
$p_dom   = subirArchivo('pdf_domicilio', $base_dir, 'doc_comprobante_domicilio');
$p_ine_r = subirArchivo('pdf_ine_responsable', $base_dir, 'doc_ine_representanteLegal');
$p_ine_s = subirArchivo('pdf_ine_responsable_sanitario', $base_dir, 'doc_ine_responsableSanitario');
$p_fac   = subirArchivo('img_fachada', $base_dir, 'img_fachada');
$p_alm   = subirArchivo('img_almacen', $base_dir, 'img_almacen');

require('fpdf/fpdf.php');
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'COMPROBANTE PIHCSA', 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(0, 6, "Razon Social: $razon_social\nRFC: $rfc\nFirma: $firma");
$pdf->Output('F', $base_dir . "AVISO_PRIVACIDAD_FIRMADO.pdf");

$checkRFC = "SELECT id FROM clients_form WHERE rfc = '$rfc' LIMIT 1";
$resCheck = mysqli_query($conexion, $checkRFC);
$existe = (mysqli_num_rows($resCheck) > 0);

if ($existe) {
    $query = "UPDATE clients_form SET 
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
    $query = "INSERT INTO clients_form (
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

if (mysqli_query($conexion, $query)) {
    header("Location: index.php?status=success");
} else {
    die("Error en MariaDB: " . mysqli_error($conexion));
}
?>