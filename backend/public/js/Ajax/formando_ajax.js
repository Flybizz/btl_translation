$(document).ready(function(){
   
    /*********************************      FORMAÇÕES       ************************************/
    /*GET ROUTER*/
    let router = $("router").attr("base");

    /*****************************************************************************************/ 
    /***********************                CRUD                  *************************/
    /*****************************************************************************************/

    if(router == "formando/listar"){

        apagar()
        gravar()
        alterar()
        limpar()

        function gravar(){
            $("button[name='bt_gravar'").click(function(e){
                e.preventDefault();            
                $("ul[id='formando_list']").html(`
                    <div class="loader loader--style7">
                        <img src='/images/loading.svg'>
                    </div>
                `);
    
                $.ajax({
                    url: "/backend/Ajax.php",
                    type: "POST",
                    data: {
                       'url':'formando/inserir',
                       'form_data' : $("form").serialize()
                    },
                    success: function(response){
    
                        $("ul[id='formando_list']").html(response);
                        new PNotify({
                            title: translate('Success!'),
                            text: translate("Dados gravados com sucesso."),
                            type: 'success'
                        });
                        //location.reload();
                        $("form[id=form_formandos]").trigger("reset")
                        apagar()
                        gravar()
                        alterar()
                        limpar()
                    }
                });
    
            });
        }

        function alterar(){
            $("a[class='formUpdate'").click(function(e){
            
                let itemId = $(this).attr("data-id"); 
                let itemNome = $(this).attr("data-name"); 
                let itemEmail = $(this).attr("data-email"); 
                let itemNif = $(this).attr("data-nif"); 
    
                $('input[name=form_nome]').val(itemNome)
                $('input[name=form_email]').val(itemEmail)
                $('input[name=form_nif]').val(itemNif)
            });
        }

        function limpar(){
            $("button[name='bt_clear'").click(function(e){
                e.preventDefault();  
                $("form[id=form_formandos]").trigger("reset")
             
            });
        }

        function apagar(){
            $("a[class='formDel']").magnificPopup({
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
    
    
            $("a[class='formDel'").click(function(e){
                
                $("#modal-title").html("");
                $("#modal-body").html(`
                    <div class="loader loader--style7">
                        <img src='/images/loading.svg'>
                    </div>
                `);
                $("#modal-footer").html('');
                
                let itemId = $(this).attr("data-id");
                let itemName = $(this).attr("data-name"); 
                let itemFormacao = $(this).attr("data-formacao"); 
    
                $("#modal-title").text(translate("Apagar Formando"));
                $("#modal-body").html('<h5>'+translate("Deseja realmente apagar o formando")+' <strong> '+itemName+' </strong>?</h5><span id="retorno"></span>');
                $("#modal-footer").html('<div class="row"><div class="col-md-12 text-right"> <button name="del_confirm" id="del_conf" class="btn btn-primary modal-confirm">Confirmar</button> <button class="btn btn-default modal-dismiss">'+translate("Cancelar")+'</button></div></div>');
    
                $("button[name='del_confirm']").click(function(){
    
                    $("ul[id='formando_list']").html(`
                        <div class="loader loader--style7">
                            <img src='/images/loading.svg'>
                        </div>
                    `);
    
                    $.ajax({
                        url: "/backend/Ajax.php",
                        type: "POST",
                        data: {
                        'url':'formando/deletar',
                        'form_data' : {'id':itemId, 'formacao': itemFormacao}
                        },
                        success: function(response){
    
                            $("ul[id='formando_list']").html(response);
                            new PNotify({
                                title: translate('Success!'),
                                text: translate("Dado(s) removido(s) com sucesso."),
                                type: 'success'
                            });

                            apagar()
                            gravar()
                            alterar()
                            limpar()
    
                        }
                    });
    
                });
    
             
            });
        }
       
    } 

 });