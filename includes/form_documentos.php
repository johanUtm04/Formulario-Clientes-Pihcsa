            <div class="segmento">
                <h3>2. CARGA DE DOCUMENTOS Y FOTOS</h3>

                <h4 style="color: #005596; border-bottom: 1px solid #eee; padding-bottom: 5px; margin-top: 15px;">Documentación en PDF</h4>
                

            <div style="display: flex; justify-content: center; margin-bottom: 20px; gap: 0; width: 100%;">
                <button type="button" id="btnOpcionLicencia" 
                    onclick="seleccionarTipoLegal('licencia')" 
                    style="padding: 12px; border: 1px solid #005596; border-radius: 8px 0 0 8px; cursor: pointer; background: #005596; color: white; font-weight: bold; flex: 1;">
                    Tengo Licencia Sanitaria
                </button>
                
                <button type="button" id="btnOpcionFuncionamiento" 
                    onclick="seleccionarTipoLegal('funcionamiento')" 
                    style="padding: 12px; border: 1px solid #005596; border-radius: 0 8px 8px 0; cursor: pointer; background: white; color: #005596; font-weight: bold; flex: 1;">
                    Solo Aviso de Funcionamiento
                </button>
            </div>

            <div id="campos_licencia" style="display: block; background: #f0f7ff; padding: 15px; border-radius: 8px; margin-bottom: 10px;">
                <div class="segmento-campo">
                    <p><b>Licencia Sanitaria:</b> <span style="color:red">*</span></p>
                    <input type="file" id="file_licencia" name="pdf_licencia" accept=".pdf"  class="campo_file" onchange="marcarAdjunto(this)" >
                </div>
                <div class="segmento-campo">
                    <p><b>Aviso Responsable Sanitario:</b> <span style="color:red">*</span></p>
                    <input type="file" id="file_aviso_rs" name="pdf_aviso_rs" accept=".pdf"  class="campo_file" onchange="marcarAdjunto(this)" >
                </div>
            </div>

            <div id="campos_funcionamiento" style="display: none; background: #f0f7ff; padding: 15px; border-radius: 8px; margin-bottom: 10px;">
                <div class="segmento-campo">
                    <p><b>Aviso de Funcionamiento:</b> <span style="color:red">*</span></p>
                    <input type="file" id="file_funcionamiento" name="pdf_funcionamiento" accept=".pdf" class="campo_file" onchange="marcarAdjunto(this)" >
                </div>
            </div>

                <div class="segmento-campo">
                    <p>Comprobante de Domicilio: </p>
                    <input type="file" class="campo_file"
                    id="file_domicilio"
                    name="pdf_domicilio" accept=".pdf"  onchange="marcarAdjunto(this)" >
                </div>

                <div class="segmento-campo">
                    <p>Ine Representante legal: </p>
                    <input type="file" class="campo_file" 
                    id="file_ine_r"
                    name="pdf_ine_responsable" accept=".pdf"  onchange="marcarAdjunto(this)" >
                </div>

                <div class="segmento-campo">
                    <p>Ine Responsable Sanitario: </p>
                    <input type="file" class="campo_file" 
                    id="file_ine_s"
                    name="pdf_ine_responsable_sanitario" accept=".pdf"  onchange="marcarAdjunto(this)" >
                </div>

                <h4 style="color: #005596; border-bottom: 1px solid #eee; padding-bottom: 5px; margin-top: 25px;">Evidencia Fotográfica (JPG/PNG)</h4>

                <div class="segmento-campo">
                    <p>Fotografía Fachada de la calle: </p>
                    <input type="file" class="campo_file" 
                    id="file_fachada"
                    name="img_fachada" accept=".jpg,.jpeg,.png"  onchange="marcarAdjunto(this)" >
                </div>

                <div class="segmento-campo">
                    <p>Fotografía vista interna general: </p>
                    <input type="file" class="campo_file" 
                    id="file_almacen"
                    name="img_almacen" accept=".jpg,.jpeg,.png"  onchange="marcarAdjunto(this)" >
                </div>
            </div>
