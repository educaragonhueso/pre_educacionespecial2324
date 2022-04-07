
function validarFormulario(fd,id,est)
{
   var valido='1';
   var res = fd.split("&");

   var renta=0;
   var bdiscal='0';
   var bdisch='0';
   var marcadopd='0';
   var marcadofn='0';
   var marcadofm='0';
   var bpd='0';
   var mdpd='0';
   var bfn='0';
   var bfm='0';
   var cadf='0';
   var cpdf='0';
   var locdf='0';
   console.log("VALIDANDO FORMULARIO, DATOS:");
   //comp datos sección datos de EXPONE
   for (let i = 0; i < res.length; i++)
   {
      d=res[i].split("=");
      if(d[0]==='fnac')
      {
         console.log("Comprobando fecha alumno: "+d[1]);
         if(d[1]=='') 
         {
            console.log("FECHA VACIA: "+d[1]);
            return 'Fecha nacimiento-fnac';
         }
         //comprobar edad alumno
         var edad=calcEdad(d[1]);
            //comprobamos q sea ebo o tva
          if(est=='ebo')
          {
            if(edad>17 || edad<3) return 'noebo';
          }
          else if(est=='tva')
          {
            if(edad<18 || edad>20) return 'notva';
          }
          if(edad>=14)
          {
                 if(campo_dnisol(fd)==0)
                 {
                     mensaje="Debes incluir un DNI del alumno por ser mayor de 14 años";
                     $('input[name=dni_alumno]').focus();	
                     return mensaje;
                 }

          }
      }
      //comp datos identificadores
      if(d[0].indexOf('apellido1')==0)
      {
         //comprobar q el primer apellido existe
         if(d[1].length<=1) {return 'Primer Apellido-apellido1';};
      }
      //comp datos identificadores
      if(d[0].indexOf('email')==0)
      {
           if(d[1].length<=2) {return 'CORREO ELECTRÓNICO INVÁLIDO';};
           if(d[1].indexOf("%40")) var nemail=d[1].replace("%40","@");
         //comprobar q el primer apellido existe
         if(!comprobarEmail(nemail)) {return 'CORREO ELECTRÓNICO INVÁLIDO-email';};
      }
      if(d[0].indexOf('nombre')==0)
      {
         if(d[1]=='') {return 'Nombre-nombre';};
      }
      if(d[0].indexOf('dni_tutor1')==0)
      {
         if(comprobar_nifnie(d[1])==0) {return 'DNI/NIE TUTOR VÁLIDO-dni_tutor1';};
      }
      //comp datos sección datos personales
      if(d[0].indexOf('datos_tutor1')==0)
      {
         if(d[1]=='') {return 'Datos de tutor/a-datos_tutor1';};
      }
      //comp datos domicilio, por si se marca la opcion del barmeo de prox de domicilio
      if(d[0].indexOf('calle_dfamiliar')==0)
      {
         if(d[1].length<2 | d[1]=='nodata')
            cadf='1';
      }
      if(d[0].indexOf('cp_dfamiliar')==0)
      {
         console.log("cpostal: "+d[1]);
         if(d[1].length<2 | d[1]=='nodata')
            cpdf='1';
      }
      if(d[0].indexOf('loc_dfamiliar')==0)
      {
         if(d[1].length<2 | d[1]=='nodata')
            locdf='1';
      }
      if(d[0].indexOf('nuevaesc')==-1)
      {
         var chn=$("input[id='nuevaesc']").is(':checked');
         var chr=$("input[id='renesc']").is(':checked');
         var centro_origen=$("input[id='id_centro_estudios_origen']").val();
         
         if(chn===false & chr===false)
            return 'Indica si es una renovación o nueva escolarización';
         //si se ha marcado la renovación debe indicarse un centro de estudios actual
         if(chr)
         {
            if(centro_origen.length<=3)
               return 'Centro de origen necesario';     
         }
      }
      if(d[0].indexOf('baremo_proximidad_domicilio')==0)
      {
         console.log("baremo proximidad : "+d[1]);
         bpd='1';
         var valor1=$("input[id='baremo_calle_dlaboral']").val();
         var valor2=$("input[id='baremo_calle_dllimitrofe']").val();
         if($("input[value='dlaboral']").is(':checked'))
         {
            console.log("domicilio laboral check"+d);
            if(valor1.length<=2) return "Valor para el domicilio laboral";
         }
         else if($("input[value='dllimitrofe']").is(':checked'))
         {
            console.log("CHECKED: "+valor2.length+" valor");
            if(valor2.length<=2) return "Valor para el domicilio laboral en zona limitrofe";
         }
         else //sino están marcados domicilio laboral en zon o dom laboral en zona limitrofe ponemos a 1 para exigir la dirección postal del alumno
            mdpd='1';
      }
      //comp datos sección expone
      if(d[0]=='id_centro_destino')
         if(d[1]=='') {return 'Debes indicar un centro de destino';}
      //si se marca proximidad de domicilio
      if(d[0]=='baremo_marcado_proximidad_domicilio')
         if(d[1]=='1') marcadopd='1';
      
      if(d[0]=='baremo_marcado_discapacidad')
      {
         var marcadodisc='0';
         if(d[1]=='1') marcadodisc='1';
      }
      if(d[0]=='baremo_discapacidad_alumno')
      {
         if(d[1]=='1') bdiscal='1';
      }
      if(d[0]=='baremo_discapacidad_hermanos')
      {
         if(d[1]=='1') bdisch='1';
      }
      if(d[0].indexOf('baremo_tipo_familia_numerosa')==0)
      {
         bfn='1';
      }
      if(d[0].indexOf('baremo_tipo_familia_monoparental')==0)
      {
         bfm='1';
      }
      if(d[0]=='baremo_marcado_numerosa')
         if(d[1]=='1') marcadofn='1';
      if(d[0]=='baremo_marcado_monoparental')
         if(d[1]=='1') marcadofm='1';
   }
   if(marcadodisc=='1')
   {
      if(bdiscal=='0' & bdisch=='0') return 'Debes completar la información de discapacidad';
   }
   if(marcadopd=='1')
   {
      if(bpd=='0') return 'Debes completar la información de proximidad de domicilio';
   }
   //si se marca la prox de domicilio debe haber datos
   if(marcadopd=='1' & (cadf=='1' | cpdf=='1' | locdf=='1') & mdpd=='1')
      return 'Completa la información del domicilio familiar, calle, cp y localidad';
   if(marcadofn=='1')
   {
      if(bfn=='0') return 'Debes completar la información de familia numerosa';
   }
   if(marcadofm=='1')
   {
      if(bfm=='0') return 'Debes completar la información de familia monoparental';
   }
return valido;
};

function validarHermanoBaremo(fd) {
   console.log("VALIDANDO HERMANOS BAREMO");
   var valido='1';
   var res = fd.split("&");
   for (let i = 0; i < res.length; i++)
   {
      d=res[i].split("=");
      var marcadohbaremo=$("input[name='num_hbaremo']:checked").val();
      if(marcadohbaremo=='1')
      {
        //debe haber datos de un hermano 
         var hn1=$('#hermanos_nombre_baremo1').val();
         var hpap1=$('#hermanos_apellido1_baremo1').val();
         var hsap1=$('#hermanos_apellido2_baremo1').val();
         var fnac1=$('#hermanos_fnacimiento_baremo1').val();
         var nivel1=$('#hermanos_nivel_baremo1').val();

         var hn2=$('#hermanos_nombre_baremo2').val();
         var hpap2=$('#hermanos_apellido1_baremo2').val();
         var hsap2=$('#hermanos_apellido2_baremo2').val();
         var fnac2=$('#hermanos_fnacimiento_baremo2').val();
         var mod2=$('#hermanos_modalidad_baremo2').val();
         var nivel2=$('#hermanos_nivel_baremo2').val();

         var hn3=$('#hermanos_nombre_baremo3').val();
         var hpap3=$('#hermanos_apellido1_baremo3').val();
         var hsap3=$('#hermanos_apellido2_baremo3').val();
         var fnac3=$('#hermanos_fnacimiento_baremo3').val();
         var mod3=$('#hermanos_modalidad_baremo3').val();
         var nivel3=$('#hermanos_nivel_baremo3').val();
         
         if((hn1=='' | hn1=='nodata') || (hpap1==''| hn1=='nodata') || (hsap1==''| hsap1=='nodata') ||(nivel1==''| nivel1=='nodata')) 
         {
            return 'Debes incluir todos los datos de al menos un hermano de baremo';
         }
         else
         {
            var mod1=$('#hermanos_modalidad_baremo1').val();
            if(mod1=='nodata')
               return 'Debes incluir una modalidad en el primer hermano de baremo';
         }
         //si el segundo hermano tienen escrito algo deben completarlo
         if((hn2!='' & hn2!='nodata') || (hpap2!='' & hpap2!='nodata') || (hsap2!='' & hsap2!='nodata') || (nivel2!='' & nivel2!='nodata'))
            if((hn2=='' | hn2=='nodata') || (hpap2==''| hn2=='nodata') || (hsap2==''| hsap2=='nodata') || nivel2=='nodata') 
            {
               console.log("hn2: "+hn2);
               console.log("hpap2: "+hpap2);
               console.log("hsap2: "+hsap2);
               console.log("nivel2: "+nivel2);
               return 'Debes incluir todos los datos del segundo hermano de baremo';
            }
         //si el tercer hermano tienen escrito algo deben completarlo
         if((hn3!='' & hn3!='nodata') || (hpap3!='' & hpap3!='nodata') || (hsap3!='' & hsap3!='nodata') || (nivel3!='' & nivel3!='nodata'))
            if((hn3=='' | hn3=='nodata') || (hpap3==''| hn3=='nodata') || (hsap3==''| hsap3=='nodata') || nivel3=='nodata') 
            {
               return 'Debes incluir todos los datos del tercer hermano de baremo';
            }
         }
   }
   return valido;
}

function validarHermanosDiscapacidad(fd) {
   console.log("VALIDANDO HERMANOS DISCAPACIDAD");
   var valido='1';
   var marcadocheck='0';
   var res = fd.split("&");
   for (let i = 0; i < res.length; i++)
   {
      d=res[i].split("=");
      //validar si es o no conjunta
      if(d[0].indexOf('baremo_discapacidad_hermanos')==0)
      {
         marcadocheck='1';
         //debe haber datos de un hermano 
         var hn1=$('#baremo_nombredisc1').val();
         var hpap1=$('#baremo_apellidodisc1').val();
         var dni1=$('#baremo_dnidisc1').val();
         var fnac1=$('#baremo_fnacdisc1').val();
            
         var hn2=$('#baremo_nombredisc2').val();
         var hpap2=$('#baremo_apellidodisc2').val();
         var dni2=$('#baremo_dnidisc2').val();
         var fnac2=$('#baremo_fnacdisc2').val();
            
         var hn3=$('#baremo_nombredisc3').val();
         var hpap3=$('#baremo_apellidodisc3').val();
         var dni3=$('#baremo_dnidisc3').val();
         var fnac3=$('#baremo_fnacdisc3').val();
            
         if(hn1=='' || hn1=='nodata' || hpap1=='' || hpap1=='nodata' || dni1=='' || dni1=='nodata' || fnac1=='') 
         {
               return 'Debes incluir todos los datos de al menos un hermano o padre con discapacidad';
         }
         //si el segundo hermano tienen escrito algo deben completarlo
         if((hn2!='' & hn2!='nodata') || (hpap2!='' & hpap2!='nodata') || (dni2!='' & dni2!='nodata'))
            if(hn2=='' || hn2=='nodata' || hpap2=='' || hpap2=='nodata' || dni2=='' || dni2=='nodata') 
               return 'Debes incluir todos los datos del segundo hermano de discaapcidad';
         //si el tercer hermano tienen escrito algo deben completarlo
         if((hn3!='' & hn3!='nodata') || (hpap3!='' & hpap3!='nodata') || (dni3!='' & dni3!='nodata'))
            if(hn3=='' || hn3=='nodata'|| hpap3=='' || hpap3=='nodata' || dni3=='' || dni3=='nodata')
               return 'Debes incluir todos los datos del tercer hermano';
      }
   }
   return valido;
}
function validarHermanoAdmision(fd) {
   console.log("VALIDANDO HERMANOS");
   var valido='1';
   var res = fd.split("&");
   for (let i = 0; i < res.length; i++)
   {
      d=res[i].split("=");
      //validar si es o no conjunta
      if(d[0].indexOf('conjunta')==0)
      {
         var conjunta=$("input[name='conjunta']:checked").val();
         if(conjunta=='si')
         {
           //debe haber datos de un hermano 
            var hn1=$('#hermanos_admision_nombre1').val();
            var hpap1=$('#hermanos_admision_apellido11').val();
            var hsap1=$('#hermanos_admision_nombre21').val();
            var fnac1=$('#hermanos_admision_fnac1').val();
            var dni1=$('#hermanos_admision_dni_alumno1').val();
            
            var hn2=$('#hermanos_admision_nombre2').val();
            var hpap2=$('#hermanos_admision_apellido12').val();
            var hsap2=$('#hermanos_admision_nombre2').val();
            var fnac2=$('#hermanos_admision_fnac2').val();
            var dni2=$('#hermanos_admision_dni_alumno2').val();
            
            var hn3=$('#hermanos_admision_nombre3').val();
            var hpap3=$('#hermanos_admision_apellido13').val();
            var hsap3=$('#hermanos_admision_apellido23').val();
            var fnac3=$('#hermanos_admision_fnac3').val();
            var dni3=$('#hermanos_admision_dni_alumno3').val();
           
            if(hn1=='' || hpap1=='' || hsap1=='' || fnac1=='') 
            {
               return 'Debes incluir todos los datos de al menos un hermano';
            }
            else
            {
               var est1=$('#hermanos_admision_tipoestudios1').val();
               var edad1=calcEdad(fnac1);
               var vaf=validarFecha(fnac1,est1,edad1);
               if(vaf!=1)
                  return 'La fecha del primer hermano es incorrecta ya que se ha marcado como '+est1;
               if(edad1>14 & dni1.length!=9)
                  return 'Debes incluir un dni en el primer hermano por ser mayor de 14 años';
            }
            //si el segundo hermano tienen escrito algo deben completarlo
            if(hn2!='' || hpap2!='' || hsap2!='' || fnac2!='')
               if(hn2=='' || hpap2=='' || hsap2=='' || fnac2=='') 
               {
                  return 'Debes incluir todos los datos del segundo hermano';
               }
               else
               {
                  console.log("datos hermano 2:");
                  console.log("n: "+hn2);
                  console.log("a1: "+hpap2);
                  console.log("a2: "+hsap2);
                  console.log("f2: "+fnac2);
                  var est2=$('#hermanos_admision_tipoestudios2').val();
                  var edad2=calcEdad(fnac2);
                  var vaf=validarFecha(fnac2,est2,edad2);
                  console.log("edad: "+edad2);
                  console.log("vaf: "+vaf);
                  console.log("est: "+est2);
                  if(vaf!=1)
                     return 'La fecha del segundo hermano es incorrecta ya que se ha marcado como '+est2;
                  if(edad2>14 & dni2.length!=9)
                     return 'Debes incluir un dni en el segundo hermano por ser mayor de 14 años';
               }
            if(hn3!='' || hpap3!='' || hsap3!='' || fnac3!='')
               if(hn3=='' || hpap3=='' || hsap3=='' || fnac3=='')
               {
                  console.log("datos hermano 3:");
                  console.log("n: "+hn3);
                  console.log("a1: "+hpap3);
                  console.log("a2: "+hsap3);
                  console.log("f2: "+fnac3);
                  return 'Debes incluir todos los datos del tercer hermano';
               }
               else
               {
                  var est3=$('#hermanos_admision_tipoestudios3').val();
                  var edad3=calcEdad(fnac3);
                  var vaf=validarFecha(fnac3,est3,edad3);
                  if(vaf!=1)
                     return 'La fecha del tercer hermano es incorrecta ya que es '+est3;
                  if(edad3>14 & dni3.length!=9)
                     return 'Debes incluir un dni en el tercer hermano por ser mayor de 14 años';
               }
         }
      }
   }
   return valido;
}
function validarFecha(fnac,est,edad) {
   //comprobamos q sea ebo o tva
   console.log("validando fecha");
   if(est=='ebo')
   {
   console.log("validando fecha ebo");
      if(edad>17 || edad<3) return 'noebo';
   }
   else if(est=='tva')
   {
   console.log("validando fecha tva");
      if(edad<18 || edad>20) return 'notva';
   }
   return 1;
}

function campo_dnisol(str) {
	//determina si 
  var res = str.match(/&dni_alumno=.*&email/g);
  res=res[0].replace('&email','');
  res=res.replace('&dni_alumno=','');
	if(res.length!=9) return 0;
	else return 1;
}
