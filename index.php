<?php include 'includes/header.php'; ?>

<div id="modalExito">
    <div class="modal-contenido">
        <div style="font-size: 50px; color: #28a745; margin-bottom: 10px;">✔</div>
        <h2 style="color: #005596; margin-top: 0;">¡Registro Exitoso!</h2>
        <p style="color: #666;">La información y los documentos han sido guardados correctamente.</p>
        <button class="btn_cerrar" onclick="cerrarModal()">Aceptar</button>
    </div>
</div>

<div id="modalPrivacidad" style="display:none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); backdrop-filter: blur(2px);">
    <div class="modal-contenido" style="width: 90%; max-width: 900px; margin: 1% auto; padding: 0; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 25px rgba(0,0,0,0.5);">
        <div style="background: #005596; color: white; padding: 15px 25px; display: flex; justify-content: space-between; align-items: center;">
            <h2 style="margin: 0; font-size: 1.2rem;">POLÍTICA DE PRIVACIDAD - PIHCSA</h2>
            <span onclick="document.getElementById('modalPrivacidad').style.display='none'" style="cursor: pointer; font-size: 24px; font-weight: bold;">&times;</span>
        </div>

        <div class="modal-legal">
            <?php include 'includes/politica_privacidad.php'; ?>
        </div>

        <div style="background: #f8f9fa; padding: 15px; text-align: right; border-top: 1px solid #ddd;">
            <button class="btn_pihcsa" onclick="document.getElementById('modalPrivacidad').style.display='none'">ACEPTO</button>
        </div>
    </div>
</div>

<div class="contenedor_formulario">
    <div class="producto-titulo">
        <h3>REGISTRO DE CLIENTES Y EXPEDIENTE DIGITAL</h3>
    </div>

    <form name="registro_pihcsa" action="procesar.php" method="post" enctype="multipart/form-data" id="formPihcsa">
        <div class="columnas-flex">
            <?php include 'includes/form_datos_generales.php'; ?>

            <?php include 'includes/form_documentos.php'; ?>
        </div>

        <?php include 'includes/seccion_firma.php'; ?>

        <div style="text-align: center;">
        <input type="submit" id="btnFinalizar" class="btn_pihcsa" value="FINALIZAR REGISTRO">
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>