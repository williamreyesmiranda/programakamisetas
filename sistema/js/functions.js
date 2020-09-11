$(document).ready(function() {     
  /* trabajar con un onchange para cargar datos en convenio */
      $.ajax({
          type: 'POST',
          url: 'php/cargar_entidades.php'
        })
        .done(function(listas_rep){
          $('#entidades').html(listas_rep)
         })
        .fail(function(){
          alert('Hubo un error al cargar las ENTIDADES')
        })
       
       /* dinamico de estudios y borrar datos tabla*/
        $('#entidades').on('change', function(){
         
          var id = $('#entidades').val()
             $.ajax({
            type: 'POST',
            url: 'php/cargar_estudios.php',
            data: {'id': id}
          })
          .done(function(estudio){
            $('#estudios').html(estudio),
            $('#resultado1').html('')
           })
          .fail(function(){
            alert('Hubo un error al cargar los estudios')
          }) 
        })
       
       /* dinamico de tabla */
        $('#estudios').on('change', function(){
          var id = $('#estudios').val()
             $.ajax({
            type: 'POST',
            url: 'php/cargar_datos.php',
            data: {'id': id}
          })
          .done(function(datos){
            $('#resultado1').html(datos)
          })
          .fail(function(){
            alert('Hubo un error al cargar los datos')
          }) 
        }) 
       
         
       /* uso de boton enviar */
       $('#enviar').on('click', function(){
           var resultado = 'la entidad es: ' + $('#entidades').val() +
           ' el estudio es: ' + $('#estudios').val()
       
           $('#resultado').html(resultado)
         })
  /* fin del onchange para convenios */
  
  
  
  
      /* datatable */
      
      $('#tabla').DataTable({
          "order": [[ 0, "desc" ]],
          "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
          },
     })
    
    
  
   
  
  } );//fin de document ready
  
  
  /* *************** infopaciente*************************************** */
  $(document).ready(function() {     
  /* modal info cliente */
    $('.emergente1').click(function(e){
        e.preventDefault();
        var emergente1 =$(this).attr('idcliente');
      
        var action ='infoconsulta';
    
    $.ajax({
      url: 'php/cargar_infopaciente.php',
      type: 'POST',
      async: true,
      data: {action:action, emergente1:emergente1},
    
        success: function(response){
        
          if(response!='error'){
            var info =JSON.parse(response);
            console.log(info);
            
            $('#idcliente').html(info.idcliente);
            $('#fecha_ingreso').html(info.fecha_ingreso);
            $('#tipodoc').html(info.tipodoc);
            $('#num_identificacion').html(info.num_identificacion);
            $('#nombre').html(info.nombre);
            $('#sexo').html(info.sexo);
            $('#telefono').html(info.telefono);
            $('#entidad').html(info.entidad);
            $('#usuario').html(info.usuario);
            $('#usuarioact').html(info.usuarioact);
            $('#fecha_actualizacion').html(info.fecha_actualizacion);
          }
    
        },
        error: function(error){
          console.log(error);
        }
    
    }) ;
  
        $('.paciente').fadeIn();
        
    });
   
    $(document).keyup(function(e){
      if(e.which==27) {//se puede colocar >=0 para que cierre con cualquier tecla
          $(".paciente").fadeOut();
      }
    });
   
    } );
    
    function closeinfo(){
      $('.paciente').fadeOut();
    };
  
  /* ***************estado admisiones*************************************** */
  
    $(document).ready(function() {     
      /* modal info estado admisiones */
        $('.emergente2').click(function(e){
            e.preventDefault();
            var emergente2 =$(this).attr('admision');
            
          
            var action ='infoadmision';
        
        $.ajax({
          url: 'php/cargar_estadoadmision.php',
          type: 'POST',
          async: true,
          data: {action:action, emergente2:emergente2},
        
            success: function(respon){
              
              if(respon!='error'){
                var info =JSON.parse(respon);
                console.log(info);
                
                $('#id_admision').html(info.id_admision);
                $('#nro_cita').html(info.nro_cita);
                $('#cod_autorizacion').html(info.cod_autorizacion);
                $('#copago').html(info.copago);
                $('#valor_copago').html(info.valor_copago);
                $('#observaciones').html(info.observaciones);
                $('#estado').html(info.estado);
                $('#usuario_admisiones').html(info.usuario_admisiones);
              }
        
            },
            error: function(error){
              console.log(error);
            }
        
        }) ;
      
            $('.admision').fadeIn();
        });
       
        $(document).keyup(function(e){
          if(e.which==27) {//se puede colocar >=0 para que cierre con cualquier tecla
              $(".admision").fadeOut();
          }
        });
       
        } );
        
        function closeadmision(){
          $('.admision').fadeOut();
        };
  /* ***************estado cita*************************************** */
  
        $(document).ready(function() {     
          /* modal info estado cita */
            $('.emergente3').click(function(e){
                e.preventDefault();
                var emergente3 =$(this).attr('cita');
                
              
                var action ='infocita';
            
            $.ajax({
              url: 'php/cargar_estadocita.php',
              type: 'POST',
              async: true,
              data: {action:action, emergente3:emergente3},
            
                success: function(response){
                  
                  if(response!='error'){
                    
                    var info =JSON.parse(response);
                    console.log(info);
                    $('#estado_cita').html(info.estado_cita);
                    $('#id_admisiones').html(info.id_admision);
                    $('#nro_citas').html(info.nro_cita);
                    $('#pendiente').html(info.pendiente);
                    $('#gestion').html(info.gestion);
                    $('#observaciones_pendiente').html(info.observaciones_pendiente);
                    
                  }
            
                },
                error: function(error){
                  console.log(error);
                }
            
            }) ;
          
                $('.cita').fadeIn();
            });
           
            $(document).keyup(function(e){
              if(e.which==27) {//se puede colocar >=0 para que cierre con cualquier tecla
                  $(".cita").fadeOut();
              }
            });
           
            } );
            
            function closecita(){
              $('.cita').fadeOut();
            };
  

/* ***************gestion cita tabla pendiente*************************************** */
  
$(document).ready(function() {     
  /* modal info estado cita */
    $('.emergente3g').click(function(e){
        e.preventDefault();
        var emergente3g =$(this).attr('citag');
        
      
        var action ='infocitag';
    
    $.ajax({
      url: 'php/cargar_gestionpendiente.php',
      type: 'POST',
      async: true,
      data: {action:action, emergente3g:emergente3g},
    
        success: function(response){
          
          if(response!='error'){
            
            var info =JSON.parse(response);
            console.log(info);
            $('#estado_citag').html(info.estado_cita);
            $('#nro_citasg').html(info.nro_cita);
            $('#gestiong').html(info.gestion);
            $('#usuario_actualizacion_gestion').html(info.usuario_actualizacion);
            $('#fecha_actualizacion_gestion').html(info.fecha_actualizacion);
            
          }
    
        },
        error: function(error){
          console.log(error);
        }
    
    }) ;
  
        $('.citag').fadeIn();
    });
   
    $(document).keyup(function(e){
      if(e.which==27) {//se puede colocar >=0 para que cierre con cualquier tecla
          $(".citag").fadeOut();
      }
    });
   
    } );
    
    function closecitag(){
      $('.citag').fadeOut();
    };


  /* ***************ventana gestion*************************************** */
  
  $(document).ready(function() {     
    /* modal info estado admisiones */
      $('.emergente4').click(function(e){
          e.preventDefault();
          var emergente4 =$(this).attr('gestion');
          
        
          var action ='infogestion';
      
      $.ajax({
        url: 'php/cargar_gestion.php',
        type: 'POST',
        async: true,
        data: {action:action, emergente4:emergente4},
      
          success: function(response){
           /*  console.log(response); */
             if(response!='error'){
              var infor =JSON.parse(response);
              /* console.log(infor); */
              
              
              $('#nro_cita_gestion').html(infor.nro_cita);
              $('#cita_gestion').val(infor.nro_cita);
              $('#txtgestion').html(infor.gestion_pendiente);
              $('#addGestion').val('addGestion');

              
            }
            
          },
          error: function(error){
            console.log(error);
          }
      
      }) ;
      
   
          $('.gestion').fadeIn();
          
          
      });
     
     
     
      } );

      function SendGestion(){

        $.ajax({
          url: 'php/cargar_gestion.php',
          type: 'POST',
          async: true,
          data: $("#formulario_gestion").serialize(),
        
            success: function(response){
              
              var info =JSON.parse(response);
              /* console.log(info); */ 
            
              
               $('#pte'+info.nro_cita+' .celgestion').html(info.gestion_pendiente);
               $('.mensajeguardado').html('<center><p>Gestión guardada<br>Presione ESC para salir</p></center>');
                
              
            },
            error: function(error){
              console.log(error);
            }
        
        }) ;
      }
      
      function closegestion(){
        $('#txtgestion').val('');
        $('.mensajeguardado').html('<center><p></p></center>');
        $('.gestion').fadeOut();
      };
      $(document).keyup(function(e){
        if(e.which==27) {//se puede colocar >=0 para que cierre con cualquier tecla
            $(".gestion").fadeOut();
            $('#txtgestion').val('');
            $('.mensajeguardado').html('<center><p></p></center>');
        }
      });



      /* ***************ventana pendiente*************************************** */
  
  $(document).ready(function() {     
   
      $('.cita_pendiente').click(function(e){
          e.preventDefault();
          var cita_pendiente =$(this).attr('pendiente');
          
        
          var action ='cita_pendiente';
      
      $.ajax({
        url: 'php/cargar_citapendiente.php',
        type: 'POST',
        async: true,
        data: {action:action, cita_pendiente:cita_pendiente},
      
          success: function(response){
           /* console.log(response); */
             if(response!='error'){
              var infor =JSON.parse(response);
              console.log(infor);
              
              
              $('#nro_cita_pendiente').html(infor.nro_cita);
              $('#cita_pendiente').val(infor.nro_cita);
              $('#txtpendiente').html(infor.gestion_pendiente);
              $('#addPendiente').val('addPendiente');

            
            }
            
          },
          error: function(error){
            console.log(error);
          }
      
      }) ;
      
   
          $('.pendiente').fadeIn();
          
          
      });
     
     
     
      } );

      function SendPendiente(){

        $.ajax({
          url: 'php/cargar_citapendiente.php',
          type: 'POST',
          async: true,
          data: $("#formulario_pendiente").serialize(),
        
            success: function(response){
              console.log(response); 
             var info =JSON.parse(response);
              console.log(info.nro_cita);
              
              $('#esp'+info.nro_cita).remove();           
               $('.mensajeguardadopendiente').html('<center><p>Esta cita se ha guardado a pendientes <br> Presione ESC para salir</p></center>');
                
                
            },
            error: function(error){
              console.log(error);
            }
        
        }) ;
      }
      
      function closependiente(){
        $('#txtpendiente').val('');
        $('.mensajeguardadopendiente').html('<center><p></p></center>');
        $('.pendiente').fadeOut();
      };
      $(document).keyup(function(e){
        if(e.which==27) {//se puede colocar >=0 para que cierre con cualquier tecla
            $(".pendiente").fadeOut();
            $('#txtpendiente').val('');
            $('.mensajeguardadopendiente').html('<center><p></p></center>');
        }
      });


      /* ***************ventana finalizado*************************************** */
  
  $(document).ready(function() {     
   
    $('.cita_finalizado').click(function(e){
        e.preventDefault();
        var cita_finalizado =$(this).attr('finalizado');
        
      
        var action ='cita_finalizado';
    
    $.ajax({
      url: 'php/cargar_citafinalizado.php',
      type: 'POST',
      async: true,
      data: {action:action, cita_finalizado:cita_finalizado},
    
        success: function(response){
         /* console.log(response); */
           if(response!='error'){
            var infor =JSON.parse(response);
            console.log(infor);
            
            
            $('#nro_cita_finalizado').html(infor.nro_cita);
            $('#cita_finalizado').val(infor.nro_cita);
            $('#txtfinalizado').html(infor.observaciones_pendiente);
            $('#addFinalizado').val('addFinalizado');

          
          }
          
        },
        error: function(error){
          console.log(error);
        }
    
    }) ;
    
 
        $('.finalizado').fadeIn();
        
        
    });
   
   
   
    } );

    function SendFinalizado(){

      $.ajax({
        url: 'php/cargar_citafinalizado.php',
        type: 'POST',
        async: true,
        data: $("#formulario_finalizado").serialize(),
      
          success: function(response){
            console.log(response); 
           var info =JSON.parse(response);
            console.log(info);
          
              $('#esp'+info.nro_cita).remove();               
              $('.mensajeguardadofinalizado').html('<center><p>Esta cita se ha gestionado exitósamente <br> Presione ESC para cerrar</p></center>');
              
              
          },
          error: function(error){
            console.log(error);
          }
      
      }) ;
    }
    
    function closefinalizado(){
      $('#txtfinalizado').val('');
      $('.mensajeguardadofinalizado').html('<center><p></p></center>');
      $('.finalizado').fadeOut();
    };
    $(document).keyup(function(e){
      if(e.which==27) {//se puede colocar >=0 para que cierre con cualquier tecla
          $(".finalizado").fadeOut();
          $('#txtfinalizado').val('');
          $('.mensajeguardadofinalizado').html('<center><p></p></center>');
      }
    });

     /* ***************ventana cancelado*************************************** */
  
  $(document).ready(function() {     
   
    $('.cita_cancelado').click(function(e){
        e.preventDefault();
        var cita_cancelado =$(this).attr('cancelado');
        
      
        var action ='cita_cancelado';
    
    $.ajax({
      url: 'php/cargar_citacancelado.php',
      type: 'POST',
      async: true,
      data: {action:action, cita_cancelado:cita_cancelado},
    
        success: function(response){
         /* console.log(response); */
           if(response!='error'){
            var infor =JSON.parse(response);
            console.log(infor);
            
            
            $('#nro_cita_cancelado').html(infor.nro_cita);
            $('#cita_cancelado').val(infor.nro_cita);
            $('#txtcancelado').html(infor.observaciones_pendiente);
            $('#addCancelado').val('addCancelado');

          
          }
          
        },
        error: function(error){
          console.log(error);
        }
    
    }) ;
    
 
        $('.cancelado').fadeIn();
        
        
    });
   
   
   
    } );

    function SendCancelado(){

      $.ajax({
        url: 'php/cargar_citacancelado.php',
        type: 'POST',
        async: true,
        data: $("#formulario_cancelado").serialize(),
      
          success: function(response){
            console.log(response); 
           var info =JSON.parse(response);
            console.log(info);
          
             $('#esp'+info.nro_cita).remove();                
             $('.mensajeguardadocancelado').html('<center><p>Esta cita ha sido cancelada <br> Presione ESC para cerrar</p></center>');
              
              
          },
          error: function(error){
            console.log(error);
          }
      
      }) ;
    }
    
    function closecancelado(){
      $('#txtcancelado').val('');
      $('.mensajeguardadocancelado').html('<center><p></p></center>');
      $('.cancelado').fadeOut();
    };
    $(document).keyup(function(e){
      if(e.which==27) {//se puede colocar >=0 para que cierre con cualquier tecla
          $(".cancelado").fadeOut();
          $('#txtcancelado').val('');
          $('.mensajeguardadocancelado').html('<center><p></p></center>');
      }
    });

  
  /* ***************ventana finalizado de pendiente*************************************** */
  
  $(document).ready(function() {     
   
    $('.cita_finalizadop').click(function(e){
        e.preventDefault();
        var cita_finalizadop =$(this).attr('finalizadop');
  /*  */      
      
        var action ='cita_finalizadop';
    
    $.ajax({
      url: 'php/cargar_citafinalizadopendiente.php',
      type: 'POST',
      async: true,
      data: {action:action, cita_finalizadop:cita_finalizadop},
    
        success: function(response){
         console.log(response);
            if(response!='error'){
            var infor =JSON.parse(response);
            console.log(infor);
            
            
            $('#nro_cita_finalizadop').html(infor.nro_cita);
            $('#cita_finalizadop').val(infor.nro_cita);
            $('#txtfinalizadop').html(infor.observaciones_pendiente);
            $('#addFinalizadop').val('addFinalizadop');

          
          } 
          
        },
        error: function(error){
          console.log(error);
        }
    
    }) ;
    
 
        $('.finalizadop').fadeIn();
        
        
    });
   
   
   
    } );

    function SendFinalizadop(){

      $.ajax({
        url: 'php/cargar_citafinalizadopendiente.php',
        type: 'POST',
        async: true,
        data: $("#formulario_finalizadop").serialize(),
      
          success: function(respons){
            /* console.log(respons); */ 
            var inform =JSON.parse(respons);
            console.log(inform); 
          
             $('#pte'+inform.nro_cita).remove();                
             $('.mensajeguardadofinalizadop').html('<center><p>Esta cita se ha gestionado exitósamente <br> Presione ESC para cerrar</p></center>');
             
              
          },
          error: function(error){
            console.log(error);
          }
      
      }) ;
    }
    
    function closefinalizadop(){
      $('#txtfinalizadop').val('');
      $('.mensajeguardadofinalizadop').html('<center><p></p></center>');
      $('.finalizadop').fadeOut();
    };
    $(document).keyup(function(e){
      if(e.which==27) {//se puede colocar >=0 para que cierre con cualquier tecla
          $(".finalizadop").fadeOut();
          $('#txtfinalizadop').val('');
          $('.mensajeguardadofinalizadop').html('<center><p></p></center>');
      }
    });
  

      /* ***************ventana cancelado del pendiente*************************************** */
  
  $(document).ready(function() {     
   
    $('.cita_canceladop').click(function(e){
        e.preventDefault();
        var cita_canceladop =$(this).attr('canceladop');
        
      
        var action ='cita_canceladop';
    
    $.ajax({
      url: 'php/cargar_citacanceladopendiente.php',
      type: 'POST',
      async: true,
      data: {action:action, cita_canceladop:cita_canceladop},
    
        success: function(response){
         /* console.log(response); */
           if(response!='error'){
            var infor =JSON.parse(response);
            console.log(infor);
            
            
            $('#nro_cita_canceladop').html(infor.nro_cita);
            $('#cita_canceladop').val(infor.nro_cita);
            $('#txtcanceladop').html(infor.observaciones_pendiente);
            $('#addCanceladop').val('addCanceladop');

          
          }
          
        },
        error: function(error){
          console.log(error);
        }
    
    }) ;
    
 
        $('.canceladop').fadeIn();
        
        
    });
   
   
   
    } );

    function SendCanceladop(){

      $.ajax({
        url: 'php/cargar_citacanceladopendiente.php',
        type: 'POST',
        async: true,
        data: $("#formulario_canceladop").serialize(),
      
          success: function(response){
            console.log(response); 
           var info =JSON.parse(response);
            console.log(info);
          
             $('#pte'+info.nro_cita).remove();               
             $('.mensajeguardadocanceladop').html('<center><p>Esta cita ha sido cancelada <br> Presione ESC para cerrar</p></center>');
              
              
          },
          error: function(error){
            console.log(error);
          }
      
      }) ;
    }
    
    function closecanceladop(){
      $('#txtcanceladop').val('');
      $('.mensajeguardadocanceladop').html('<center><p></p></center>');
      $('.canceladopp').fadeOut();
    };
    $(document).keyup(function(e){
      if(e.which==27) {//se puede colocar >=0 para que cierre con cualquier tecla
          $(".canceladop").fadeOut();
          $('#txtcanceladop').val('');
          $('.mensajeguardadocanceladop').html('<center><p></p></center>');
      }
    });

     /* ***************ventana pendiente admisiones*************************************** */
  
  $(document).ready(function() {     
   
    $('.cita_pendientead').click(function(e){
        e.preventDefault();
        var cita_pendientead =$(this).attr('pendientead');
        
      
        var action ='cita_pendientead';
    
    $.ajax({
      url: 'php/cargar_citapendienteadmisiones.php',
      type: 'POST',
      async: true,
      data: {action:action, cita_pendientead:cita_pendientead},
    
        success: function(response){
         /* console.log(response); */
           if(response!='error'){
            var infor =JSON.parse(response);
            console.log(infor);
            
            
            $('#nro_cita_pendientead').html(infor.nro_cita);
            $('#cita_pendientead').val(infor.nro_cita);
            $('#txtpendientead').html(infor.gestion_pendiente);
            $('#addPendientead').val('addPendientead');

          
          }
          
        },
        error: function(error){
          console.log(error);
        }
    
    }) ;
    
 
        $('.pendientead').fadeIn();
        
        
    });
   
   
   
    } );

    function SendPendientead(){

      $.ajax({
        url: 'php/cargar_citapendienteadmisiones.php',
        type: 'POST',
        async: true,
        data: $("#formulario_pendientead").serialize(),
      
          success: function(response){
            console.log(response); 
           var info =JSON.parse(response);
            
            
            $('#espad'+info.nro_cita).remove();           
             $('.mensajeguardadopendientead').html('<center><p>Esta cita se ha guardado a pendientes <br> Presione ESC para salir</p></center>');
              
              
          },
          error: function(error){
            console.log(error);
          }
      
      }) ;
    }
    
    function closependientead(){
      $('#txtpendientead').val('');
      $('.mensajeguardadopendientead').html('<center><p></p></center>');
      $('.pendientead').fadeOut();
    };
    $(document).keyup(function(e){
      if(e.which==27) {//se puede colocar >=0 para que cierre con cualquier tecla
          $(".pendientead").fadeOut();
          $('#txtpendientead').val('');
          $('.mensajeguardadopendientead').html('<center><p></p></center>');
      }
    });

   /* ***************ventana finalizado*************************************** */
  
  $(document).ready(function() {     
   
    $('.cita_finalizado').click(function(e){
        e.preventDefault();
        var cita_finalizado =$(this).attr('finalizado');
        
      
        var action ='cita_finalizado';
    
    $.ajax({
      url: 'php/cargar_citafinalizado.php',
      type: 'POST',
      async: true,
      data: {action:action, cita_finalizado:cita_finalizado},
    
        success: function(response){
         /* console.log(response); */
           if(response!='error'){
            var infor =JSON.parse(response);
            console.log(infor);
            
            
            $('#nro_cita_finalizado').html(infor.nro_cita);
            $('#cita_finalizado').val(infor.nro_cita);
            $('#txtfinalizado').html(infor.observaciones_pendiente);
            $('#addFinalizado').val('addFinalizado');

          
          }
          
        },
        error: function(error){
          console.log(error);
        }
    
    }) ;
    
 
        $('.finalizado').fadeIn();
        
        
    });
   
   
   
    } );

    function SendFinalizado(){

      $.ajax({
        url: 'php/cargar_citafinalizado.php',
        type: 'POST',
        async: true,
        data: $("#formulario_finalizado").serialize(),
      
          success: function(response){
            console.log(response); 
           var info =JSON.parse(response);
            console.log(info);
          
              $('#esp'+info.nro_cita).remove();               
              $('.mensajeguardadofinalizado').html('<center><p>Esta cita se ha gestionado exitósamente <br> Presione ESC para cerrar</p></center>');
              
              
          },
          error: function(error){
            console.log(error);
          }
      
      }) ;
    }
    
    function closefinalizado(){
      $('#txtfinalizado').val('');
      $('.mensajeguardadofinalizado').html('<center><p></p></center>');
      $('.finalizado').fadeOut();
    };
    $(document).keyup(function(e){
      if(e.which==27) {//se puede colocar >=0 para que cierre con cualquier tecla
          $(".finalizado").fadeOut();
          $('#txtfinalizado').val('');
          $('.mensajeguardadofinalizado').html('<center><p></p></center>');
      }
    });   

    /* ***************ventana finalizado de admisiones*************************************** */
  
  $(document).ready(function() {     
   
    $('.cita_finalizadoad').click(function(e){
        e.preventDefault();
        var cita_finalizadoad =$(this).attr('finalizadoad');
        
      
        var action ='cita_finalizadoad';
    
    $.ajax({
      url: 'php/cargar_citafinalizadoadmisiones.php',
      type: 'POST',
      async: true,
      data: {action:action, cita_finalizadoad:cita_finalizadoad},
    
        success: function(response){
         /* console.log(response); */
           if(response!='error'){
            var infor =JSON.parse(response);
            console.log(infor);
            
            
            $('#nro_cita_finalizadoad').html(infor.id_admision);
            $('#cita_finalizadoad').val(infor.id_admision);
            $('#txtfinalizadoad').html(infor.observaciones);
            $('#addFinalizadoad').val('addFinalizadoad');
            $('#copago').change();
            $("#copago").val('0');
            
          
          }
          
        },
        error: function(error){
          console.log(error);
        }
    
    }) ;
    
 
        $('.finalizadoad').fadeIn();
        
        
    });
   
   
   
    } );

    function SendFinalizadoad(){

      $.ajax({
        url: 'php/cargar_citafinalizadoadmisiones.php',
        type: 'POST',
        async: true,
        data: $("#formulario_finalizadoad").serialize(),
      
          success: function(response){
            console.log(response); 
           var info =JSON.parse(response);
            console.log(info);
          
              $('#espad'+info.id_admision).remove();               
              $('.mensajeguardadofinalizadoad').html('<center><p>Esta cita se ha gestionado exitósamente <br> Presione ESC para cerrar</p></center>');
              $('#codaut').val('');
              $('#valor').val('');
              $('#txtfinalizadoad').val('');
              $("#copago").val('0');
              $('#copago').change();
              },
          error: function(error){
            console.log(error);
          }
      
      }) ;
    }
    
    function closefinalizadoad(){
      $('#codaut').val('');
      document.getElementById("copago").selectedIndex = 0
      $('#valor').val('');
      $('#txtfinalizadoad').val('');
      $('.mensajeguardadofinalizadoad').html('<center><p></p></center>');
      $('.finalizadoad').fadeOut();
      $("#copago").val('0');
      $('#copago').change();
    };
    $(document).keyup(function(e){
      if(e.which==27) {//se puede colocar >=0 para que cierre con cualquier tecla
          $(".finalizadoad").fadeOut();
          $('#codaut').val('');
          $('#valor').val('');
          $('#txtfinalizadoad').val('');
          $('.mensajeguardadofinalizadoad').html('<center><p></p></center>');
          $("#copago").val('0');
          $('#copago').change();
      }
    });

    /* ***************ventana finalizado de admisiones de pendiente*************************************** */
  
  $(document).ready(function() {     
   
    $('.cita_finalizadoadp').click(function(e){
        e.preventDefault();
        var cita_finalizadoadp =$(this).attr('finalizadoadp');
        
      
        var action ='cita_finalizadoadp';
    
    $.ajax({
      url: 'php/cargar_citafinalizadoadmisionespendiente.php',
      type: 'POST',
      async: true,
      data: {action:action, cita_finalizadoadp:cita_finalizadoadp},
    
        success: function(response){
         console.log(response);
           if(response!='error'){
            var infor =JSON.parse(response);
            console.log(infor);
            $('#nro_cita_finalizadoadp').html(infor.id_admision);
            $('#cita_finalizadoadp').val(infor.id_admision);
            $('#txtfinalizadoadp').html(infor.observaciones);
            $('#addFinalizadoadp').val('addFinalizadoadp');
            $('#copagop').change();
            $("#copagop").val('0');
            }
          
        },
        error: function(error){
          console.log(error);
        }
    
    }) ;
    
 
        $('.finalizadoadp').fadeIn();
        
        
    });
   
   
   
    } );

    function SendFinalizadoadp(){

      $.ajax({
        url: 'php/cargar_citafinalizadoadmisionespendiente.php',
        type: 'POST',
        async: true,
        data: $("#formulario_finalizadoadp").serialize(),
      
          success: function(response){
            console.log(response); 
           var info =JSON.parse(response);
            console.log(info);
          
              $('#ptep'+info.id_admision).remove();               
              $('.mensajeguardadofinalizadoadp').html('<center><p>Esta cita se ha gestionado exitósamente <br> Presione ESC para cerrar</p></center>');
              $('#codautp').val('');
              $('#valorp').val('');
              $('#txtfinalizadoadp').val('');
              $("#copagop").val('0');
              $('#copagop').change();
              },
          error: function(error){
            console.log(error);
          }
      
      }) ;
    }
    
    function closefinalizadoadp(){
      $('#codautp').val('');
      $('#valorp').val('');
      $('#txtfinalizadoadp').val('');
      $('.mensajeguardadofinalizadoadp').html('<center><p></p></center>');
      $('.finalizadoadp').fadeOut();
      $("#copagop").val('0');
      $('#copagop').change();
    };
    $(document).keyup(function(e){
      if(e.which==27) {//se puede colocar >=0 para que cierre con cualquier tecla
          $(".finalizadoadp").fadeOut();
          $('#codautp').val('');
          $('#valorp').val('');
          $('#txtfinalizadoadp').val('');
          $('.mensajeguardadofinalizadoadp').html('<center><p></p></center>');
          $("#copagop").val('0');
          $('#copagop').change();
      }
    });