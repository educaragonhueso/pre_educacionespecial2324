<?php
$form_reclamaciones='
<div id="recbaremo+idalumno" class="collapse">
      <div class="row" >
        <div class="col-md-4">
            <div class="md-form mb-0">
            Describe el motivo de la reclamación
                <textarea cols="80" rows="20" type="text" id="motivo_reclamacion+idalumno" value name="motivo+idalumno" placeholder="Motivo reclamación, si es mayor de 250 caracteres introducirlo como una captura"  class="form-control" required></textarea>
            </div>
        </div>
      </div>
      <div class="row">
         <div id="drop-area" style="margin:25px">';
         if($rol=='alumno')
         {
$form_reclamaciones.='
            <form id="idformfiledata" class="my-form">
               <p>Añade los documentos necesarios (en pdf o jpg)</p>
               <input type="file" id="freclamaciones" name="files[]" multiple accept="*">
               <!--<label class="button" for="fileRec"></label>-->
            </form>
            <div id="gallery"></div>
         </div>
      </div>
      <button id="reclamacion+idalumno" name="breclamaciones" type="button" class="btn btn-outline-dark breclamaciones">Guardar datos</button>
</div>
';
         }
