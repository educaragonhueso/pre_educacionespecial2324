<?php
$form_reclamaciones='
<div id="recprovisional+idalumno" class="collapse">
      <div class="row" >
        <div class="col-md-4">
            <div class="md-form mb-0">
            Describe el motivo de la reclamación
               <textarea value="" rows = "10" cols = "180" id="motivo_reclamacion+idalumno" name="motivo+idalumno" cont></textarea><br>
            </div>
        </div>
      </div>
      <div class="row">
         <div id="drop-area" style="margin:25px">
            <form id="idformfiledata" class="my-form">
               <p>Añade los documentos necesarios</p>
               <input type="file" id="fileRec" name="files[]" multiple accept="pdf,image/*">
               <label class="button" for="fileRec">Selecciona documentos</label>
            </form>
            <progress id="progress-bar" max=100 value=0></progress>
            <div id="gallery"></div>
         </div>
      </div>
      <button id="reclamacion+idalumno" name="preclamaciones" type="button" class="btn btn-outline-dark preclamaciones">Guardar datos</button>
</div>
';
