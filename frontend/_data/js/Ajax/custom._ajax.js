
jQuery(document).ready(function(){

    /* RECUPERACAO PASSWORD */
    $("#recuperar_password").click(function(e){

        e.preventDefault();
        let email = $("#cli_email").val()
        let token = $("#cli_token").val()

        $.ajax({
        url: "/backend/Ajax.php",
        type: "POST",
        data: {'url':'email/clientNewPasswordConfirm/email/'+email+'/token/'+token+''},
        success: function(dados){
            console.log(dados)
            let return_success = $("input[id='return_success']").val();
            $("div[id='return']").html("<div class='text-center'><i class='fa fa-envelope fa-5x'><br><br><h6>"+return_success+"</h6></div>")
        }
        })

    });

    $('#cli_password').pstrength();
    $('#confirm_password').blur(function(){

        var senha = $('#cli_password').val();
        var confsenha = $('#confirm_password').val();

        var pw_error = $("#password_error").val();
        var pw_success = $("#password_success").val();

        if(confsenha != senha){

        $('#msgSenha').html(pw_error);
        $('#msgSenha').css('color','#ff0000');
        $("#validasenha").val(1);

        }else if(confsenha == ""){

        $('#msgSenha').html("<i class='icon-remove'></i>")

        }else{

        $('#msgSenha').html(pw_success)
        $('#msgSenha').css('color','#007F00')
        $("#validasenha").val(0);

        }

    });

    $("#bt_redefinir").click(function(event){

        event.preventDefault();

        var senha1 = $('#cli_password').val();
        var confsenha1 = $('#confirm_password').val();
        var validasenha = $("#validasenha").val();
        var validaemail = $("#validaemail").val();

        if(confsenha1 == ""){
            $("#validasenha").val(1);
        }
        if(confsenha1 != "" && validasenha == 1){
            $("#validasenha").val(1);
        }
        if(confsenha1 != "" && validasenha == 0){
            $("#validasenha").val(0);
        }

        var cliente = $("input[id='cli_id']").val();
        var senha = md5($("input[id='confirm_password']").val());
        var senha1 = $("input[id='confirm_password']").val();

        var ref = "";
        var count = 0;
        var valida = "";

        if(senha == ""){ref += "Password<br>"; count += 1;}

        if(ref != 0){

            $("#ref").html("<div class='alert alert-danger' style='font-size:12px;'><button type='button' class='close' data-dismiss='alert'>×</button><h4 style='font-size:12px;'>Falta dados Obrigatórios:</h4>"+ref+"</div> <div class='clearfix'></div>");

        }else{
            $("#ref").html("");
        }

        if(validasenha == 0 && ref == 0){

        $.ajax({
                url: "/backend/Ajax.php",
                type: "POST",
                data: {'url':'cliente/update/cliente/'+cliente+'/senha/'+senha+'/'},
                success: function(rs){

                console.log(rs);

                var cliente2 = $("input[id='cli_id']").val();

                if(rs == 1){
                    $.ajax({
                        url: "/backend/Ajax.php",
                        type: "POST",
                        data: {'url':'email/clientNewPasswordSuccess/cliente/'+cliente2+'/'},
                        success: function(dados){
                            console.log(dados)
                            let return_success = $("input[id='return_success']").val();
                            $("div[id='return']").html("<div class='text-center'><i class='fa fa-envelope fa-5x'><br><br><h6>"+return_success+"</h6></div>")

                        }
                    })
                }else{
                    $("input").val("").css({"background-color":"#ffffff"});
                    $("#retorno").html("<div class='alert alert-success' style='font-size:12px;'><button type='button' class='close' data-dismiss='alert'>×</button>"+ok+"</div>");
                    $("#ref").html("");
                    $("#msgSenha").html("");
                    $(".pstrength-bar").hide();
                    $("#validasenha").val(1);
                }
                }
            });

        }else if(validasenha == 1){
            valida = "SENHA INCORRETA.";
        }

        if(valida != ""){
            $("#retorno").html("<div class='alert alert-danger'><button type='button' class='close' data-dismiss='alert'>×</button><h6 style='font-size:11px;'>"+valida+"</h6></div>");

        }


    });
});