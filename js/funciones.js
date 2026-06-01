    /**
     * PIHCSA - Funciones de Control de Formulario y Modales
     */

document.addEventListener('DOMContentLoaded', function() {
    // 1. Elementos para validación de Privacidad y Firma
    const checkbox = document.getElementById('checkPrivacidad');
    const inputFirma = document.getElementById('inputFirma');
    const boton = document.getElementById('btnFinalizar');

    function validarAceptacion() {
        if (!inputFirma || !checkbox || !boton) return; // Seguridad extra
        
        const nombreValido = inputFirma.value.trim().length > 3; 
        const checkAceptado = checkbox.checked;

        if (checkAceptado && nombreValido) {
            boton.disabled = false;
            boton.classList.remove('btn_deshabilitado');
        } else {
            boton.disabled = true;
            boton.classList.add('btn_deshabilitado');
        }
    }

    if (checkbox && inputFirma) {
        checkbox.addEventListener('change', validarAceptacion);
        inputFirma.addEventListener('input', validarAceptacion);
        // Ejecutar una vez al cargar por si el navegador recordó los datos
        validarAceptacion();
    }

    // 2. Formateo de RFC
    const campoRFC = document.querySelector('input[name="rfc"]');
    if (campoRFC) {
        campoRFC.addEventListener('input', function() {
            this.value = this.value.toUpperCase().replace(/\s/g, '');
            if (this.value.length > 13) this.value = this.value.slice(0, 13);
        });
    }

    // 2.5 Consultar RFC en tiempo real (AJAX)
// 2.5 Consultar RFC en tiempo real (AJAX) - VERSIÓN BLINDADA
    if (campoRFC) {
        campoRFC.addEventListener('blur', function() {
            const rfcValue = this.value.trim();
            if (rfcValue.length < 12) return;

            fetch('buscar_cliente.php?rfc=' + rfcValue)
                .then(response => {
                    if (!response.ok) throw new Error('Error en el servidor');
                    return response.json();
                })
                .then(data => {
                    // Definimos los campos que queremos controlar
                    const camposInteres = [
                        { name: 'razon_social', value: data.razon_social || "" },
                        { name: 'email', value: data.email || "" },
                        { name: 'domicilio', value: data.domicilio || "" },
                        { name: 'poblacion', value: data.poblacion || "" },
                        { name: 'colonia', value: data.colonia || "" },
                        { name: 'cp', value: data.cp || "" },
                        { name: 'estado', value: data.estado || "" },
                        { name: 'telefono', value: data.telefono || "" },
                        { name: 'web', value: data.web || "" },  
                    ];

                    if (data.existe) {
                    // --- ACCIÓN: BLOQUEAR Y LLENAR ---
                    camposInteres.forEach(item => {
                        const campo = document.querySelector(`input[name="${item.name}"]`);
                        if (campo) {
                            campo.value = item.value;

                            // CONDICIÓN: Si el campo tiene datos, bloquéalo. Si está vacío, déjalo libre.
                            if (item.value !== "" && item.value !== null) {
                                campo.readOnly = true;
                                campo.style.background = "#e9ecef"; 
                                campo.style.color = "#6c757d";      
                                campo.style.cursor = "not-allowed"; 
                                campo.style.borderColor = "#ced4da";
                            } else {
                                // Por si acaso el campo estaba bloqueado de una consulta anterior y ahora está vacío
                                campo.readOnly = false;
                                campo.style.background = "#ffffff";
                                campo.style.color = "#212529";
                                campo.style.cursor = "text";
                                campo.style.borderColor = ""; 
                            }
                        }
                    });

                        // Marcar archivos que ya existen en el servidor
                        gestionarChecksVisuales(data.archivos);

                    } else {
                        // --- ACCIÓN: LIBERAR (Si el RFC es nuevo o se borró) ---
                        camposInteres.forEach(item => {
                            const campo = document.querySelector(`input[name="${item.name}"]`);
                            if (campo && campo.readOnly) {
                                campo.readOnly = false;
                                campo.style.background = "#ffffff";
                                campo.style.color = "#212529";
                                campo.style.cursor = "text";
                                campo.style.borderColor = ""; // Regresa al color original del CSS
                            }
                        });

                        // Limpiar avisos visuales de archivos anteriores si los hubiera
                        document.querySelectorAll('[id^="check_file_"]').forEach(el => el.remove());
                    }
                })
                .catch(error => console.error('Error en la consulta:', error));
        });
    }

    // 3. Manejo de Notificaciones (Status y Error)
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('error') === 'rfc_existente') {
        alert("⚠️ RFC YA REGISTRADO: El RFC ya cuenta con un expediente.");
        window.history.replaceState({}, document.title, window.location.pathname);
    }
    
    if (urlParams.get('status') === 'success') {
        const modalExito = document.getElementById('modalExito');
        if (modalExito) modalExito.style.display = 'block';
    }

    // INICIALIZACIÓN DE ARCHIVOS (Con manejo de errores para evitar que el JS truene)
// INICIALIZACIÓN DE ARCHIVOS (Protección completa contra elementos nulos)
try {
    const inLic = document.getElementById('file_licencia');
    const inAvi = document.getElementById('file_aviso_rs');
    const inFun = document.getElementById('file_funcionamiento');
    const inDom = document.getElementById('file_domicilio');
    const inIneR = document.getElementById('file_ine_r');
    const inIneS = document.getElementById('file_ine_s');
    const inFac = document.getElementById('file_fachada');
    const inAlm = document.getElementById('file_almacen');

    // 1. Lógica de dependencia: Licencia -> Aviso RS
    if(inLic && inAvi && inLic.files.length === 0) {
        inAvi.disabled = true;
        inAvi.style.opacity = "0.5";
        inAvi.style.cursor = "not-allowed";
    }

    // 2. Limpieza de estados visuales para el resto de los campos
    const otrosCampos = [inFun, inDom, inIneR, inIneS, inFac, inAlm];
    otrosCampos.forEach(campo => {
        if (campo && campo.files.length === 0) {
            campo.style.borderColor = "";
            campo.style.backgroundColor = "";
        }
    });

} catch (e) {
    console.log("Aviso: Algunos campos de archivo no están presentes en esta vista.");
}

});

    /**
     * Funciones Globales
     */

    function cerrarModal() {
        const modal = document.getElementById('modalExito');
        if (modal) {
            modal.style.display = 'none';
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    }
    function marcarAdjunto(input) {
        const inAvi = document.getElementById('file_aviso_rs');
        if (!inAvi) return;

        if (input.files.length > 0) {
            input.style.borderColor = "#28a745";
            input.classList.add('archivo-adjuntado');

            if (input.id === 'file_licencia') {
                inAvi.disabled = false;
                inAvi.style.opacity = "1";
                inAvi.style.filter = "none"; 
                inAvi.style.cursor = "pointer";
                if(inAvi.parentElement) inAvi.parentElement.style.opacity = "1";
            }
        } else {
            input.style.borderColor = "";
            input.classList.remove('archivo-adjuntado');

            if (input.id === 'file_licencia') {
                inAvi.disabled = true;
                inAvi.value = ""; 
                inAvi.style.opacity = "0.4";
                inAvi.style.filter = "grayscale(100%)";
                inAvi.style.cursor = "not-allowed";
                if(inAvi.parentElement) inAvi.parentElement.style.opacity = "0.5";
            }
        }
    }

function seleccionarTipoLegal(tipo) {
    const btnLic = document.getElementById('btnOpcionLicencia');
    const btnFun = document.getElementById('btnOpcionFuncionamiento');
    const divLic = document.getElementById('campos_licencia');
    const divFun = document.getElementById('campos_funcionamiento');
    const inLic = document.getElementById('file_licencia');
    const inAvi = document.getElementById('file_aviso_rs');
    const inFun = document.getElementById('file_funcionamiento');
    const inDom = document.getElementById('file_domicilio');
    const inIneR = document.getElementById('file_ine_r');

    // --- ESTADO 100% OPCIONAL ---
    // Forzamos a que TODO sea false siempre, sin importar el historial
    inLic.required = false; 
    inAvi.required = false; 
    inFun.required = false;
    inDom.required = false;
    inIneR.required = false;

    if (tipo === 'licencia') {
        btnLic.style.background = '#005596'; btnLic.style.color = 'white';
        btnFun.style.background = 'white'; btnFun.style.color = '#005596';
        divLic.style.display = 'block';
        divFun.style.display = 'none';
        
        // Quitamos el bloqueo del Aviso RS para que sea libre de subirlo o no
        inAvi.disabled = false;
        inAvi.style.opacity = "1";
        inAvi.style.cursor = "default";

    } else {
        btnFun.style.background = '#005596'; btnFun.style.color = 'white';
        btnLic.style.background = 'white'; btnLic.style.color = '#005596';
        divLic.style.display = 'none';
        divFun.style.display = 'block';
    }
}


function gestionarChecksVisuales(archivos) {
    // Definimos qué ID de input corresponde a qué campo de la base de datos
    const mapeo = {
        'licencia': 'file_licencia',
        'aviso_rs': 'file_aviso_rs',
        'funcionamiento': 'file_funcionamiento',
        'doc_comprobante_domicilio': 'file_domicilio',
        'doc_ine_representanteLegal': 'file_ine_r',
        'doc_ine_responsableSanitario': 'file_ine_s',
        'img_fachada': 'file_fachada',
        'img_almacen': 'file_almacen'
    };
    for (const [key, id] of Object.entries(mapeo)) {
        if (archivos[key]) {
            const input = document.getElementById(id);
            if (input && !document.getElementById('check_' + id)) {
                // Crear un aviso visual de "Completado"
                const aviso = document.createElement('small');
                aviso.id = 'check_' + id;
                aviso.innerHTML = " ✅ Ya contamos con este REGISTRO !";
                aviso.style.color = "#28a745";
                aviso.style.display = "block";
                aviso.style.fontWeight = "bold";
                input.after(aviso);
                
                // Quitamos el borde verde si ya estaba para no confundir
                input.style.borderColor = "#28a745";
            }
        }
    }
}


document.querySelector('form').addEventListener('submit', function(e) {
    const LIMITE_MAXIMO = 8 * 1024 * 1024; 
    let pesoTotal = 0;
    
    // Seleccionamos todos los inputs de tipo file
    const inputsArchivos = document.querySelectorAll('input[type="file"]');
    
    inputsArchivos.forEach(input => {
        if (input.files.length > 0) {
            pesoTotal += input.files[0].size;
        }
    });

    // Convertir a MB para el mensaje
    let pesoEnMB = (pesoTotal / (1024 * 1024)).toFixed(2);

    if (pesoTotal > LIMITE_MAXIMO) {
        e.preventDefault(); // Detiene el envío del formulario
        alert(`¡Error! Los archivos son muy pesados.\n` +
              `Peso total: ${pesoEnMB} MB.\n` +
              `El límite permitido por el servidor es de 8 MB.\n\n` +
              `Por favor, reduce el tamaño de las fotos o PDFs.`);
    } else {
        console.log("Peso total aceptable: " + pesoEnMB + " MB. Enviando...");
    }
});
