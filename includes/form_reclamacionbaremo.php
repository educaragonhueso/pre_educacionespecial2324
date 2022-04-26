<?php
$form_reclamaciones='
<div id="recbaremo+idalumno" class="collapse">
      <div class="row" >
        <div class="col-md-4">
            <div class="md-form mb-0">
            Describe el motivo de la reclamación
                <textarea cols="80" rows="20" type="text" id="motivo_reclamacion+idalumno" value="" name="motivo+idalumno" placeholder="Motivo reclamación, si es mayor de 250 caracteres introducirlo como una captura"  class="form-control" required></textarea>
            </div>
        </div>
      </div>
      <div class="row">
         <div id="drop-area" style="margin:25px">
            <form id="idformfiledata" class="my-form">
               <p>Añade los documentos necesarios</p>
               <input type="file" id="freclamaciones" name="files[]" multiple accept="pdf,image/*">
               <label class="button" for="fileRec">Selecciona documentos</label>
            </form>
            <progress id="progress-bar" max=100 value=0></progress>
            <div id="gallery"></div>
         </div>
      </div>
      <button id="reclamacion+idalumno" name="breclamaciones" type="button" class="btn btn-outline-dark breclamaciones">Guardar datos</button>
</div>
';
