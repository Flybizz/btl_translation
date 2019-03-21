
$(document).ready(function(){

    /*********************************      LEADS CONVERSION      ************************************/
    /*GET ROUTER*/
    let router = $("router").attr("base");
    let base = router.split("/");
    let controller = router[0];
    let action = router[1];  

    if(router == "lead/list"){

        var table = $('table[name=leadTable]').DataTable();
               
         $('input[name=check_lead]').on('click', function(event){

            event.stopPropagation();

            var val = $(this).prop("checked")
                
            if(val === false){
               $.fn.dataTable.ext.search.pop();
               $.fn.dataTable.ext.search.push(
                  function (settings, data, dataIndex){             
                     return $(table.row(dataIndex).node()).removeClass('selected');
                  }
               );
                
               table.draw();
            } 

            if(val === true){
                $.fn.dataTable.ext.search.pop();
                $.fn.dataTable.ext.search.push(
                   function (settings, data, dataIndex){             
                      return $(table.row(dataIndex).node()).addClass('selected');
                   }
                );
                 
                table.draw();
             } 
          
         });
                        
        /* $('table[name=leadTable] tbody').on( 'click', 'tr', function () {
            $(this).toggleClass('selected');            
        } ); */
             
        $('select[name=btn_leads]').change( function () {

            $(this).magnificPopup({
                items: {
                    src: '#modalAnim'
                },
                type: 'inline',
                fixedContentPos: false,
                fixedBgPos: true,    
                overflowY: 'auto',    
                closeBtnInside: true,
                preloader: false,        
                midClick: false,
                removalDelay: 300,
                mainClass: 'my-mfp-zoom-in',
                modal: true
            });
            
            let rows = table.rows('.selected').ids().toArray();
            let action = $(this).val();

            $("#modal-title").html("");
            $("#modal-body").html('<img src="/images/loading.gif" />');
            $("#modal-footer").html('');            

            $("#modal-title").text("Action Leads Conversion");
            $("#modal-body").html('<h5>Deseja realmente mover o(s) contato(s)?</h5><span id="retorno"></span>');
            $("#modal-footer").html('<div class="row"><div class="col-md-12 text-right"> <button name="del_conf_lead" id="del_conf_lead" class="btn btn-primary modal-confirm-lead">Confirmar</button> <button class="btn btn-default modal-dismiss">Cancelar</button></div></div>');

            $("button[name='del_conf_lead']").click(function(){
                $.magnificPopup.close();
                $.ajax({
                    url: "/backend/Ajax.php",
                    type: "POST",
                    data: {url:'lead/move/', data:rows, action:action},
                    success: function(result){
    
                        let rs = JSON.parse(result)                  
                        
                        if(rs.code == 1){
                            new PNotify({
                                title: 'Success!',
                                text: rs.text,
                                type: 'success'
                            }); 
                            setTimeout('location.reload()', 3000);
                        }else if(rs.code == 0){
                            new PNotify({
                                title: 'Erro!',
                                text: rs.text,
                                type: 'danger'
                            }); 
                        }
                                            
                    }
                });
            });
          
           
        });

    } /* end action move*/


})