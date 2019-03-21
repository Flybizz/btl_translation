<?php
class email extends App_Controller{
    public function enviar(){
             // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $dados = $id->getParamsAjax();
            $horario = date("H");       
            if($horario > 5 && $horario < 12)
            {
                $HoraDay = 'Good Morning';                            
            }
            else if($horario >= 12 && $horario < 18) 
            {
                $HoraDay = 'Good afternoon';                                                                             
            }else{
                $HoraDay = 'good evening';
            }
           
            $config = unserialize (CONFIG_DB);  
            //CONTATO
            if($dados['status'] == "contatocliente" || $dados['status'] == "contatoadm" ):
             
                if($dados['status'] == "contatocliente"): //Orcamento de cliente
                    
                    $mensagem = $HoraDay.", ".$dados['nome'].".
                    Thanks for reaching out. We'll get back to you as soon as possible.  
                    ------------------------------------------------------
                    Name: ".$dados['nome']."
                    Email: ".$dados['email']."
                    City: ".$dados['cidade']."  
                    Country: ".$dados['estado']."
                    Phone: ".$dados['celular']."
                    Description: ".$dados['descricao']."
                    ------------------------------------------------------
                    Thank you for contacting us!
                    ".$config[0]['D001_Empresa']."
                    ".$config[0]['D001_Email']."
                    ".$config[0]['D001_Telefone']."";
                    $assunto = $config[0]['D001_Empresa']." - YOUR MESSAGE WAS RECEIVED WITH SUCCESS!";
                     //pego os dados enviados pelo formulário 
                    $nome_para = $dados['nome']; 
                    $email = htmlentities($dados['email'],ENT_NOQUOTES,'utf-8');
                    $mensagem = htmlentities($mensagem,ENT_NOQUOTES,'utf-8');                 
                
                elseif($dados['status'] == "contatoadm"): //alerta de cliente cadastrado
                    $mensagem = $HoraDay.",
                     New booking request received on our site ".$config[0]['D001_Empresa'].".
                    ------------------------------------------------------                   
                    Name: ".$dados['nome']."
                    Email: ".$dados['email']."
                    City: ".$dados['cidade']."  
                    Country: ".$dados['estado']."
                    Telefone: ".$dados['celular']."
                    Description: ".$dados['descricao']."
                    ------------------------------------------------------
                    Waiting for the return!
                    ".$config[0]['D001_Empresa']."
                    ".$config[0]['D001_Email']." 
                    ".$config[0]['D001_Telefone']."";
                    $assunto = "RESERVATION RECEIVED BY THE SITE - ".$config[0]['D001_Empresa'];
                     //pego os dados enviados pelo formulário 
                    $nome_para = "Atendimento"; 
                    $email = htmlentities($config[0]['D001_Email'],ENT_NOQUOTES,'utf-8');
                    $mensagem = htmlentities($mensagem,ENT_NOQUOTES,'utf-8');                 
                endif;
            endif;
            //ORCAMENTO
            if($dados['status'] == "orcamentocliente" || $dados['status'] == "orcamentoadm" ):
                $model = new App_Model_pacoteModel();
                $model_pacote = $model->pacoteSelecionar($dados['pacote']);
             
                if($dados['status'] == "orcamentocliente"): //Orcamento de cliente
                    
                    $mensagem = $HoraDay.", ".$dados['nome'].".
                    Thanks for reaching out. We'll get back to you as soon as possible.
                    ------------------------------------------------------
                    Name: ".$dados['nome']."
                    Email: ".$dados['email']."
                    City: ".$dados['cidade']."  
                    Country: ".$dados['uf']."                  
                    Pack: ".$model_pacote[0]['D011_Titulo']."
                    Phone: ".$dados['celular']."
                    Description: ".$dados['descricao']."
                    ------------------------------------------------------
                    Thank you for contacting us!
                    ".$config[0]['D001_Empresa']."
                    ".$config[0]['D001_Email']."
                    ".$config[0]['D001_Telefone']."";
                    $assunto = $config[0]['D001_Empresa']." - YOUR RESERVATION REQUEST WAS RECEIVED WITH SUCCESS!";
                     //pego os dados enviados pelo formulário 
                    $nome_para = $dados['nome']; 
                    $email = htmlentities($dados['email'],ENT_NOQUOTES,'utf-8');
                    $mensagem = htmlentities($mensagem,ENT_NOQUOTES,'utf-8');                 
                
                elseif($dados['status'] == "orcamentoadm"): //alerta de cliente cadastrado
                    $mensagem = $HoraDay.",
                    New booking request received on our site ( ".$config[0]['D001_Empresa']." ).
                    ------------------------------------------------------                   
                    Name: ".$dados['nome']."
                    Email: ".$dados['email']."
                    City: ".$dados['cidade']."  
                    Country: ".$dados['uf']."                  
                    Pack: ".$model_pacote[0]['D011_Titulo']."
                    Phone: ".$dados['celular']."
                    Description: ".$dados['descricao']."
                    ------------------------------------------------------
                    Waiting for the return!
                    ".$config[0]['D001_Empresa']."
                    ".$config[0]['D001_Email']." 
                    ".$config[0]['D001_Telefone']."";
                    $assunto = "RESERVATION RECEIVED BY THE SITE - ".$config[0]['D001_Empresa'];
                     //pego os dados enviados pelo formulário 
                    $nome_para = "Atendimento"; 
                    $email = htmlentities($config[0]['D001_Email'],ENT_NOQUOTES,'utf-8');
                    $mensagem = htmlentities($mensagem,ENT_NOQUOTES,'utf-8');                 
                endif;
            endif;
           //Pega os dados postados pelo formulário HTML e os coloca em variaveis
            $email_from= htmlentities($config[0]['D001_Email'],ENT_NOQUOTES,'utf-8');  
            $email_reply = htmlentities($config[0]['D001_Email'],ENT_NOQUOTES,'utf-8');
            //$email_copia = htmlentities("sistemas.rabisco@gmail.com",ENT_NOQUOTES,'utf-8');
            /*$email_copia2 = htmlentities("vendas@rabiscoembalagens.com.br",ENT_NOQUOTES,'utf-8');
            $email_copia3 = htmlentities("henriquerabisco@gmail.com",ENT_NOQUOTES,'utf-8');
            $email_copia4 = htmlentities("henrique@rabiscoembalagens.com.br",ENT_NOQUOTES,'utf-8');*/
            if( PATH_SEPARATOR ==';'){ $quebra_linha="\r\n";
             
            } elseif (PATH_SEPARATOR==':'){ $quebra_linha="\n";
             
            } elseif ( PATH_SEPARATOR!=';' and PATH_SEPARATOR!=':' )  {echo ('Esse script não funcionará corretamente neste servidor, a função PATH_SEPARATOR não retornou o parâmetro esperado.');
             
            }
             
            //pego os dados enviados pelo formulário 
            /*$nome_para = $_POST["nome_para"]; 
            $email = htmlentities($_POST["email"],ENT_NOQUOTES,'utf-8');*/
            //$mensagem = htmlentities($mensagem,ENT_NOQUOTES,'utf-8'); 
            /*$assunto = $_POST["assunto"];*/ 
            // $assunto = htmlentities($assunto,ENT_NOQUOTES,'utf-8'); 
            $assunto = '=?UTF-8?B?'.base64_encode($assunto).'?=';
            //formato o campo da mensagem 
            //$mensagem = wordwrap( $mensagem, 130, "<br>", 1); 
            
            //$ordem = array("\r\n", "\n", "\r");
            //$substituir = array("<br><br>", "<br>", "<br>");
            //$mensagem = str_replace($ordem, $substituir, $mensagem);
            //valido os emails 
            /*if (!ereg("^([0-9,a-z,A-Z]+)([.,_]([0-9,a-z,A-Z]+))*[@]([0-9,a-z,A-Z]+)([.,_,-]([0-9,a-z,A-Z]+))*[.]([0-9,a-z,A-Z]){2}([0-9,a-z,A-Z])?$", $email)){ 
             
            echo"<center>Digite um email valido</center>"; 
            echo "<center><a href=\"javascript:history.go(-1)\">Voltar</center></a>"; 
            exit; 
             
            }*/
            //Conteudo do email
            //*********************************************************** 
            $boundary = "XYZ-" . date("dmYis") . "-ZYX"; 
             
            //$mens = "--$boundary" . $quebra_linha . ""; 
            //$mens .= "Content-Transfer-Encoding: 8bits" . $quebra_linha . ""; 
            //$mens .= "Content-Type: text/html; charset=utf-8" . $quebra_linha . "" . $quebra_linha . ""; //plain 
            $mens = "$mensagem" . $quebra_linha . "";
             
            //***********************************************************
         
            $headers = "MIME-Version: 1.0" . $quebra_linha . ""; 
            $headers .= "Content-Type: text/html; charset=utf-8" . $quebra_linha . ""; 
            $headers .= "From: $email_from " . $quebra_linha . ""; 
            $headers .= "Return-Path: $email_from " . $quebra_linha . "";
            $headers.= "Reply-To: $email_reply" . $quebra_linha . "";
            if($dados['status'] == "orcamentocliente" || $dados['status'] == "contatocliente"):
            $headers.= "Disposition-Notification-To: $email_from" . $quebra_linha . "";
            endif;
            //$headers.= "Bcc: $email_copia" . $quebra_linha . "";
            //$headers.= "Cc: $email_copia1,$email_copia2,$email_copia3,$email_copia4" . $quebra_linha . "";  
            //echo $email." , ".$assunto." , ".$mensagem." , ".$headers;
            //envia o email sem anexo 
            
            if(mail($email,nl2br($assunto),nl2br($mensagem),$headers)):                 
             
                $retorno = "Email successfully sent!";
            else:
                $retorno = "ERROR: It was not possible to send the email!";
            endif; 
            echo $retorno;
    }
       
    public function usuarioNewPasswordConfirm(){
        // seta o id do cliente
        $id = new App_System();
        $id->_urlAjax = $_POST['url'];
        $id->setExplodeAjax();
        $id->setControllerAjax();
        $id->setActionAjax();
        $id->setParamsAjax();
        $dados = $id->getParamsAjax();
        $exp = explode("-",$dados["token"]);
  
        if(!empty($dados["token"])):
  
            $model = new App_Model_usuarioModel();
            $model_usuario = $model->usuarioVerifica($dados["email"]);    
  
            if(empty($model_usuario)):
                $rs_email = 0;
            else:
                $rs_email = 1;
                $model_registar = $model->usuarioRecuperar($model_usuario[0]["usu_id"],$exp[1],$exp[0]);
                $model_verifica = $model->usuarioSelecionar($model_usuario[0]["usu_id"]);
  
                $horario = date("H");
  
                if($horario > 6 && $horario < 12)
                {
                    $HoraDay = 'Bom dia';
                }
                else if($horario >= 12 && $horario < 20)
                {
                    $HoraDay = 'Boa tarde';
                }else{
                    $HoraDay = 'Boa noite';
                }
  
                $config = unserialize (CONFIG_DB);
    
                $mensagem = '<!DOCTYPE html>
                  <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml" xmlns="http://www.w3.org/1999/xhtml">
                  <head>
                        <!-- NAME: SELL PRODUCTS -->
                        <!--[if gte mso 15]>
                        <xml>
                            <o:OfficeDocumentSettings>
                            <o:AllowPNG/>
                            <o:PixelsPerInch>96</o:PixelsPerInch>
                            </o:OfficeDocumentSettings>
                        </xml>
                        <![endif]-->
                        <meta charset="UTF-8">
                        <meta http-equiv="X-UA-Compatible" content="IE=edge">
                        <meta name="viewport" content="width=device-width, initial-scale=1">
                        <title><b>'.$config[0]['D001_Empresa'].'</b></title>
                        <style type="text/css">
                        p{
                          margin:10px 0;
                          padding:0;
                        }
                        table{
                          border-collapse:collapse;
                        }
                        h1,h2,h3,h4,h5,h6{
                          display:block;
                          margin:0;
                          padding:0;
                        }
                        img,a img{
                          border:0;
                          height:auto;
                          outline:none;
                          text-decoration:none;
                        }
                        body,#bodyTable,#bodyCell{
                          height:100%;
                          margin:0;
                          padding:0;
                          width:100%;
                        }
                        .mcnPreviewText{
                          display:none !important;
                        }
                        #outlook a{
                          padding:0;
                        }
                        img{
                          -ms-interpolation-mode:bicubic;
                        }
                        table{
                          mso-table-lspace:0pt;
                          mso-table-rspace:0pt;
                        }
                        .ReadMsgBody{
                          width:100%;
                        }
                        .ExternalClass{
                          width:100%;
                        }
                        p,a,li,td,blockquote{
                          mso-line-height-rule:exactly;
                        }
                        a[href^=tel],a[href^=sms]{
                          color:inherit;
                          cursor:default;
                          text-decoration:none;
                        }
                        p,a,li,td,body,table,blockquote{
                          -ms-text-size-adjust:100%;
                          -webkit-text-size-adjust:100%;
                        }
                        .ExternalClass,.ExternalClass p,.ExternalClass td,.ExternalClass div,.ExternalClass span,.ExternalClass font{
                          line-height:100%;
                        }
                        a[x-apple-data-detectors]{
                          color:inherit !important;
                          text-decoration:none !important;
                          font-size:inherit !important;
                          font-family:inherit !important;
                          font-weight:inherit !important;
                          line-height:inherit !important;
                        }
                        .templateContainer{
                          max-width:600px !important;
                        }
                        a.mcnButton{
                          display:block;
                        }
                        .mcnImage,.mcnRetinaImage{
                          vertical-align:bottom;
                        }
                        .mcnTextContent{
                          word-break:break-word;
                        }
                        .mcnTextContent img{
                          height:auto !important;
                        }
                        .mcnDividerBlock{
                          table-layout:fixed !important;
                        }
                        h1{
                          color:#222222;
                          font-family:Helvetica;
                          font-size:40px;
                          font-style:normal;
                          font-weight:bold;
                          line-height:150%;
                          letter-spacing:normal;
                          text-align:center;
                        }
                        h2{
                          color:#222222;
                          font-family:Helvetica;
                          font-size:34px;
                          font-style:normal;
                          font-weight:bold;
                          line-height:150%;
                          letter-spacing:normal;
                          text-align:left;
                        }
                        h3{
                          color:#444444;
                          font-family:Helvetica;
                          font-size:22px;
                          font-style:normal;
                          font-weight:bold;
                          line-height:150%;
                          letter-spacing:normal;
                          text-align:left;
                        }
                        h4{
                          color:#999999;
                          font-family:Georgia;
                          font-size:20px;
                          font-style:italic;
                          font-weight:normal;
                          line-height:125%;
                          letter-spacing:normal;
                          text-align:left;
                        }
                        #templateHeader{
                          background-color:#f7f7f7;
                          background-image:none;
                          background-repeat:no-repeat;
                          background-position:center;
                          background-size:cover;
                          border-top:0;
                          border-bottom:0;
                          padding-top:45px;
                          padding-bottom:45px;
                        }
                        .headerContainer{
                          background-color:#transparent;
                          background-image:none;
                          background-repeat:no-repeat;
                          background-position:center;
                          background-size:cover;
                          border-top:0;
                          border-bottom:0;
                          padding-top:0;
                          padding-bottom:0;
                        }
                        .headerContainer .mcnTextContent,.headerContainer .mcnTextContent p{
                          color:#808080;
                          font-family:Helvetica;
                          font-size:16px;
                          line-height:150%;
                          text-align:left;
                        }
                        .headerContainer .mcnTextContent a,.headerContainer .mcnTextContent p a{
                          color:#00ADD8;
                          font-weight:normal;
                          text-decoration:underline;
                        }
                        #templateBody{
                          background-color:#FFFFFF;
                          background-image:none;
                          background-repeat:no-repeat;
                          background-position:center;
                          background-size:cover;
                          border-top:0;
                          border-bottom:0;
                          padding-top:36px;
                          padding-bottom:45px;
                        }
                        .bodyContainer{
                          background-color:transparent;
                          background-image:none;
                          background-repeat:no-repeat;
                          background-position:center;
                          background-size:cover;
                          border-top:0;
                          border-bottom:0;
                          padding-top:0;
                          padding-bottom:0;
                        }
                        .bodyContainer .mcnTextContent,.bodyContainer .mcnTextContent p{
                          color:#808080;
                          font-family:Helvetica;
                          font-size:16px;
                          line-height:150%;
                          text-align:left;
                        }
                        .bodyContainer .mcnTextContent a,.bodyContainer .mcnTextContent p a{
                          color:#00ADD8;
                          font-weight:normal;
                          text-decoration:underline;
                        }
                        #templateFooter{
                          background-color:#333333;
                          background-image:none;
                          background-repeat:no-repeat;
                          background-position:center;
                          background-size:cover;
                          border-top:0;
                          border-bottom:0;
                          padding-top:45px;
                          padding-bottom:63px;
                        }
                        .footerContainer{
                          background-color:transparent;
                          background-image:none;
                          background-repeat:no-repeat;
                          background-position:center;
                          background-size:cover;
                          border-top:0;
                          border-bottom:0;
                          padding-top:0;
                          padding-bottom:0;
                        }
                        .footerContainer .mcnTextContent,.footerContainer .mcnTextContent p{
                          color:#FFFFFF;
                          font-family:Helvetica;
                          font-size:12px;
                          line-height:150%;
                          text-align:center;
                        }
                        .footerContainer .mcnTextContent a,.footerContainer .mcnTextContent p a{
                          color:#FFFFFF;
                          font-weight:normal;
                          text-decoration:underline;
                        }
                      @media only screen and (min-width:768px){
                        .templateContainer{
                          width:600px !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        body,table,td,p,a,li,blockquote{
                          -webkit-text-size-adjust:none !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        body{
                          width:100% !important;
                          min-width:100% !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        .mcnRetinaImage{
                          max-width:100% !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        .mcnImage{
                          width:100% !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        .mcnCartContainer,.mcnCaptionTopContent,.mcnRecContentContainer,.mcnCaptionBottomContent,.mcnTextContentContainer,.mcnBoxedTextContentContainer,.mcnImageGroupContentContainer,.mcnCaptionLeftTextContentContainer,.mcnCaptionRightTextContentContainer,.mcnCaptionLeftImageContentContainer,.mcnCaptionRightImageContentContainer,.mcnImageCardLeftTextContentContainer,.mcnImageCardRightTextContentContainer,.mcnImageCardLeftImageContentContainer,.mcnImageCardRightImageContentContainer{
                          max-width:100% !important;
                          width:100% !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        .mcnBoxedTextContentContainer{
                          min-width:100% !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        .mcnImageGroupContent{
                          padding:9px !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        .mcnCaptionLeftContentOuter .mcnTextContent,.mcnCaptionRightContentOuter .mcnTextContent{
                          padding-top:9px !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        .mcnImageCardTopImageContent,.mcnCaptionBottomContent:last-child .mcnCaptionBottomImageContent,.mcnCaptionBlockInner .mcnCaptionTopContent:last-child .mcnTextContent{
                          padding-top:18px !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        .mcnImageCardBottomImageContent{
                          padding-bottom:9px !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        .mcnImageGroupBlockInner{
                          padding-top:0 !important;
                          padding-bottom:0 !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        .mcnImageGroupBlockOuter{
                          padding-top:9px !important;
                          padding-bottom:9px !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        .mcnTextContent,.mcnBoxedTextContentColumn{
                          padding-right:18px !important;
                          padding-left:18px !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        .mcnImageCardLeftImageContent,.mcnImageCardRightImageContent{
                          padding-right:18px !important;
                          padding-bottom:0 !important;
                          padding-left:18px !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        .mcpreview-image-uploader{
                          display:none !important;
                          width:100% !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        h1{
                          font-size:30px !important;
                          line-height:125% !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        h2{
                          font-size:26px !important;
                          line-height:125% !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        h3{
                          font-size:20px !important;
                          line-height:150% !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        h4{
                          font-size:18px !important;
                          line-height:150% !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        .mcnBoxedTextContentContainer .mcnTextContent,.mcnBoxedTextContentContainer .mcnTextContent p{
                          font-size:14px !important;
                          line-height:150% !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        .headerContainer .mcnTextContent,.headerContainer .mcnTextContent p{
                          font-size:16px !important;
                          line-height:150% !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        .bodyContainer .mcnTextContent,.bodyContainer .mcnTextContent p{
                          font-size:16px !important;
                          line-height:150% !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        .footerContainer .mcnTextContent,.footerContainer .mcnTextContent p{
                          font-size:14px !important;
                          line-height:150% !important;
                        }
  
                    }
                    </style>
                    </head>
                    <body style="margin: 0px; padding: 0px; width: 100%; height: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">
                        <center>
                            <table width="100%" height="100%" align="center" id="bodyTable" style="margin: 0px; padding: 0px; width: 100%; height: 100%; border-collapse: collapse; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                                <tbody><tr>
                                    <td align="center" id="bodyCell" valign="top" style="margin: 0px; padding: 0px; width: 100%; height: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                                        <!-- BEGIN TEMPLATE // -->
                                        <table width="100%" style="border-collapse: collapse; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                                            <tbody><tr>
                                                <td align="center" id="templateHeader" valign="top" style="background: no-repeat center / cover rgb(247, 247, 247); padding-top: 45px; padding-bottom: 45px; border-top-color: currentColor; border-bottom-color: currentColor; border-top-width: 0px; border-bottom-width: 0px; border-top-style: none; border-bottom-style: none; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;" data-template-container="">
                                                    <!--[if (gte mso 9)|(IE)]>
                                                    <table align="center" border="0" cellspacing="0" cellpadding="0" width="600" style="width:600px;">
                                                    <tr>
                                                    <td align="center" valign="top" width="600" style="width:600px;">
                                                    <![endif]-->
                                                    <table width="100%" align="center" class="templateContainer" style="border-collapse: collapse; max-width: 600px !important; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                                                        <tbody><tr>
                                                            <td class="headerContainer" valign="top" style="background-position: center; padding-top: 0px; padding-bottom: 0px; border-top-color: currentColor; border-bottom-color: currentColor; border-top-width: 0px; border-bottom-width: 0px; border-top-style: none; border-bottom-style: none; -ms-text-size-adjust: 100%; background-image: none; background-repeat: no-repeat; background-size: cover; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;"><table width="100%" class="mcnImageBlock" style="border-collapse: collapse; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                          <tbody class="mcnImageBlockOuter">
                            <tr>
                                <td class="mcnImageBlockInner" valign="top" style="padding: 9px; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                                    <table width="100%" align="left" class="mcnImageContentContainer" style="border-collapse: collapse; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                                        <tbody><tr>
                                            <td class="mcnImageContent" valign="top" style="padding: 0px 9px; text-align: center; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
  
                                                    <a title="'.$config[0]['D001_Empresa'].'" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;" href="'.$config[0]['D001_Site'].'" target="_blank">
                                                        <img width="284" align="center" class="mcnImage" style="border: 0px currentColor; border-image: none; height: auto; padding-bottom: 0px; text-decoration: none; vertical-align: bottom; display: inline !important; -ms-interpolation-mode: bicubic; max-width: 284px;" alt="" src="'.$config[0]['D001_Site'].'/images/config/'.$config[0]['D001_Logosite'].'" alt="'.$config[0]['D001_Empresa'].'">
                                                    </a>
  
                                            </td>
                                        </tr>
                                    </tbody></table>
                                </td>
                            </tr>
                          </tbody>
                        </table>
                        <table width="100%" class="mcnTextBlock" style="border-collapse: collapse; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                          <tbody class="mcnTextBlockOuter">
                            <tr>
                              <td class="mcnTextBlockInner" valign="top" style="padding-top: 9px; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                                <!--[if mso]>
                                    <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;">
                                      <tr>
                                      <![endif]-->
  
                                      <!--[if mso]>
                                      <td valign="top" width="600" style="width:600px;">
                                      <![endif]-->
                                              <table width="100%" align="left" class="mcnTextContentContainer" style="border-collapse: collapse; min-width: 100%; max-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                                                  <tbody><tr>
  
                                                      <td class="mcnTextContent" valign="top" style="padding: 0px 18px 9px; text-align: left; color: rgb(128, 128, 128); line-height: 150%; font-family: Helvetica; font-size: 16px; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
  
                                                          <h1 style="margin: 0px; padding: 0px; text-align: center; color: rgb(34, 34, 34); line-height: 150%; letter-spacing: normal; font-family: Helvetica; font-size: 32px; font-style: normal; font-weight: bold; display: block;">Pedido de redefinição de senha</h1>
  
                                                      </td>
                                                  </tr>
                                              </tbody></table>
                                      <!--[if mso]>
                                      </td>
                                      <![endif]-->
  
                                      <!--[if mso]>
                                      </tr>
                                      </table>
                                      <![endif]-->
                                          </td>
                                      </tr>
                                  </tbody>
                              </table></td>
                                                        </tr>
                                                    </tbody></table>
                                                    <!--[if (gte mso 9)|(IE)]>
                                                    </td>
                                                    </tr>
                                                    </table>
                                                    <![endif]-->
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="center" id="templateBody" valign="top" style="background: no-repeat center / cover rgb(255, 255, 255); padding-top: 36px; padding-bottom: 45px; border-top-color: currentColor; border-bottom-color: currentColor; border-top-width: 0px; border-bottom-width: 0px; border-top-style: none; border-bottom-style: none; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;" data-template-container="">
                                                    <!--[if (gte mso 9)|(IE)]>
                                                    <table align="center" border="0" cellspacing="0" cellpadding="0" width="600" style="width:600px;">
                                                    <tr>
                                                    <td align="center" valign="top" width="600" style="width:600px;">
                                                    <![endif]-->
                                                    <table width="100%" align="center" class="templateContainer" style="border-collapse: collapse; max-width: 600px !important; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                                                        <tbody><tr>
                                                            <td class="bodyContainer" valign="top" style="background: no-repeat center / cover; padding-top: 0px; padding-bottom: 0px; border-top-color: currentColor; border-bottom-color: currentColor; border-top-width: 0px; border-bottom-width: 0px; border-top-style: none; border-bottom-style: none; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;"><table width="100%" class="mcnTextBlock" style="border-collapse: collapse; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                    <tbody class="mcnTextBlockOuter">
                        <tr>
                            <td class="mcnTextBlockInner" valign="top" style="padding-top: 9px; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                                <!--[if mso]>
                        <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;">
                        <tr>
                        <![endif]-->
  
                        <!--[if mso]>
                        <td valign="top" width="600" style="width:600px;">
                        <![endif]-->
                                <table width="100%" align="left" class="mcnTextContentContainer" style="border-collapse: collapse; min-width: 100%; max-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                                    <tbody><tr>
                                        <td class="mcnTextContent" valign="top" style="padding: 0px 18px 9px; text-align: center; color: rgb(128, 128, 128); line-height: 150%; font-family: Helvetica; font-size: 16px; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                                            <h3 style="margin: 0px; padding: 0px; text-align: center; color: rgb(68, 68, 68); line-height: 150%; letter-spacing: normal; font-family: Helvetica; font-size: 22px; font-style: normal; font-weight: bold; display: block;"><span style="font-size: 16px;">
                                            '.$HoraDay.', <b>'.$model_verifica[0]['usu_nome'].'</b>.<br>
                                            Você pediu para redefinir sua senha <a href="'.$config[0]['D001_Site'].'">'.$config[0]['D001_Empresa'].'</a>.<br>
                                            Por favor, clique no botão abaixo para redefinir sua senha.</h3>
                                            <br>
                                            OBS: link expirará após 24 horas.
                                            <br>
                                            Se não é você que está a solicitar a redefinição da senha contacte-nos <a href="mailto:'.$config[0]['D001_Email'].'">Clique aqui</a>.<br><br>
  
                                            <b>Atenciosamente</b>,<br>
                                            Equipa '.$config[0]['D001_Empresa'].'.
                                            <br>
                                            <br>
                                        </td>
                                    </tr>
                                </tbody></table>
                        <!--[if mso]>
                        </td>
                        <![endif]-->
  
                        <!--[if mso]>
                        </tr>
                        </table>
                        <![endif]-->
                            </td>
                        </tr>
                    </tbody>
                </table><table width="100%" class="mcnDividerBlock" style="border-collapse: collapse; table-layout: fixed !important; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                    <tbody class="mcnDividerBlockOuter">
                        <tr>
                            <td class="mcnDividerBlockInner" style="padding: 18px; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                                <table width="100%" class="mcnDividerContent" style="border-collapse: collapse; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                                    <tbody><tr>
                                        <td style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                                            <span></span>
                                        </td>
                                    </tr>
                                </tbody></table>
                                <!--
                                <td class="mcnDividerBlockInner" style="padding: 18px;">
                                <hr class="mcnDividerContent" style="border-bottom-color:none; border-left-color:none; border-right-color:none; border-bottom-width:0; border-left-width:0; border-right-width:0; margin-top:0; margin-right:0; margin-bottom:0; margin-left:0;" />
                                -->
                            </td>
                        </tr>
                    </tbody>
                    </table><table width="100%" class="mcnButtonBlock" style="border-collapse: collapse; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                      <tbody class="mcnButtonBlockOuter">
                        <tr>
                            <td align="center" class="mcnButtonBlockInner" valign="top" style="padding: 0px 18px 18px; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                                <table class="mcnButtonContentContainer" style="border-radius: 3px; border-collapse: separate !important; -ms-text-size-adjust: 100%; background-color: rgb(43, 170, 223); -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                                    <tbody>
                                        <tr>
                                            <td align="center" class="mcnButtonContent" valign="middle" style="padding: 15px; font-family: Arial; font-size: 16px; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                                                <a title="Concordo" class="mcnButton " style="text-align: center; color: rgb(255, 255, 255); line-height: 100%; letter-spacing: normal; font-weight: bold; text-decoration: none; display: block; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;" href="'.$config[0]['D001_Site'].'/backend/redefinir?token='.$model_verifica[0]['usu_token'].'" target="_blank">Redefinir senha</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        </tbody>
                    </table></td>
                                                        </tr>
                                                    </tbody></table>
                                                    <!--[if (gte mso 9)|(IE)]>
                                                    </td>
                                                    </tr>
                                                    </table>
                                                    <![endif]-->
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="center" id="templateFooter" valign="top" style="background: no-repeat center / cover rgb(51, 51, 51); padding-top: 45px; padding-bottom: 63px; border-top-color: currentColor; border-bottom-color: currentColor; border-top-width: 0px; border-bottom-width: 0px; border-top-style: none; border-bottom-style: none; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;" data-template-container="">
                                                    <!--[if (gte mso 9)|(IE)]>
                                                    <table align="center" border="0" cellspacing="0" cellpadding="0" width="600" style="width:600px;">
                                                    <tr>
                                                    <td align="center" valign="top" width="600" style="width:600px;">
                                                    <![endif]-->
                                                    <table width="100%" align="center" class="templateContainer" style="border-collapse: collapse; max-width: 600px !important; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                                                        <tbody><tr>
                                                            <td class="footerContainer" valign="top" style="background: no-repeat center / cover; padding-top: 0px; padding-bottom: 0px; border-top-color: currentColor; border-bottom-color: currentColor; border-top-width: 0px; border-bottom-width: 0px; border-top-style: none; border-bottom-style: none; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;"><table width="100%" class="mcnFollowBlock" style="border-collapse: collapse; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                    <tbody class="mcnFollowBlockOuter">
                        <tr>
                            <td align="center" class="mcnFollowBlockInner" valign="top" style="padding: 9px; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                                <table width="100%" class="mcnFollowContentContainer" style="border-collapse: collapse; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                    <tbody><tr>
                        <td align="center" style="padding-right: 9px; padding-left: 9px; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                            <table width="100%" class="mcnFollowContent" style="border-collapse: collapse; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                                <tbody><tr>
                                    <td align="center" valign="top" style="padding-top: 9px; padding-right: 9px; padding-left: 9px; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                                        <table align="center" style="border-collapse: collapse; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                                            <tbody><tr>
                                                <td align="center" valign="top" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                                                    <!--[if mso]>
                                                    <table align="center" border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                    <![endif]-->
  
                                                        <!--[if mso]>
                                                        <td align="center" valign="top">
                                                        <![endif]-->
  
  
                                                            <table align="left" style="display: inline; border-collapse: collapse; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                                                                <tbody><tr>
                                                                    <td class="mcnFollowContentItemContainer" valign="top" style="padding-right: 10px; padding-bottom: 9px; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                                                                        <table width="100%" class="mcnFollowContentItem" style="border-collapse: collapse; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                                                                            <tbody><tr>
                                                                                <td align="left" valign="middle" style="padding: 5px 10px 5px 9px; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                                                                                    <table width="" align="left" style="border-collapse: collapse; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                                                                                        <tbody><tr>
  
                                                                                                <td width="24" align="center" class="mcnFollowIconContent" valign="middle" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                                                                                                    <a style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;" href="http://www.facebook.com/BTLIndustriesBrasil" target="_blank"><img width="24" height="24" style="border: 0px currentColor; border-image: none; height: auto; text-decoration: none; display: block; -ms-interpolation-mode: bicubic;" src="https://cdn-images.mailchimp.com/icons/social-block-v2/outline-light-facebook-48.png"></a>
                                                                                                </td>
  
  
                                                                                        </tr>
                                                                                    </tbody></table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody></table>
                                                                    </td>
                                                                </tr>
                                                            </tbody></table>
  
                                                        <!--[if mso]>
                                                        </td>
                                                        <![endif]-->
  
  
                                                        <!--[if mso]>
                                                        <td align="center" valign="top">
                                                        <![endif]-->
  
  
                                                            <table align="left" style="display: inline; border-collapse: collapse; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                                                                <tbody><tr>
                                                                    <td class="mcnFollowContentItemContainer" valign="top" style="padding-right: 10px; padding-bottom: 9px; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                                                                        <table width="100%" class="mcnFollowContentItem" style="border-collapse: collapse; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                                                                            <tbody><tr>
                                                                                <td align="left" valign="middle" style="padding: 5px 10px 5px 9px; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                                                                                    <table width="" align="left" style="border-collapse: collapse; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                                                                                        <tbody><tr>
  
                                                                                                <td width="24" align="center" class="mcnFollowIconContent" valign="middle" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                                                                                                    <a style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;" href="http://www.instagram.com/btlaestheticsbr" target="_blank"><img width="24" height="24" style="border: 0px currentColor; border-image: none; height: auto; text-decoration: none; display: block; -ms-interpolation-mode: bicubic;" src="https://cdn-images.mailchimp.com/icons/social-block-v2/outline-light-instagram-48.png"></a>
                                                                                                </td>
  
  
                                                                                        </tr>
                                                                                    </tbody></table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody></table>
                                                                    </td>
                                                                </tr>
                                                            </tbody></table>
  
                                                        <!--[if mso]>
                                                        </td>
                                                        <![endif]-->
                                                    <!--[if mso]>
                                                    </tr>
                                                    </table>
                                                    <![endif]-->
                                                </td>
                                            </tr>
                                        </tbody></table>
                                    </td>
                                </tr>
                            </tbody></table>
                        </td>
                    </tr>
                </tbody></table>
  
                            </td>
                        </tr>
                    </tbody>
                </table><table width="100%" class="mcnDividerBlock" style="border-collapse: collapse; table-layout: fixed !important; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                    <tbody class="mcnDividerBlockOuter">
                        <tr>
                            <td class="mcnDividerBlockInner" style="padding: 18px; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                                <table width="100%" class="mcnDividerContent" style="border-top-color: rgb(80, 80, 80); border-top-width: 2px; border-top-style: solid; border-collapse: collapse; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                                    <tbody><tr>
                                        <td style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                                            <span></span>
                                        </td>
                                    </tr>
                                </tbody></table>
                <!--
                                <td class="mcnDividerBlockInner" style="padding: 18px;">
                                <hr class="mcnDividerContent" style="border-bottom-color:none; border-left-color:none; border-right-color:none; border-bottom-width:0; border-left-width:0; border-right-width:0; margin-top:0; margin-right:0; margin-bottom:0; margin-left:0;" />
                -->
                            </td>
                        </tr>
                    </tbody>
                </table><table width="100%" class="mcnTextBlock" style="border-collapse: collapse; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                    <tbody class="mcnTextBlockOuter">
                        <tr>
                            <td class="mcnTextBlockInner" valign="top" style="padding-top: 9px; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                                <!--[if mso]>
                        <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;">
                        <tr>
                        <![endif]-->
  
                        <!--[if mso]>
                        <td valign="top" width="600" style="width:600px;">
                        <![endif]-->
                                <table width="100%" align="left" class="mcnTextContentContainer" style="border-collapse: collapse; min-width: 100%; max-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                                    <tbody><tr>
  
                                        <td class="mcnTextContent" valign="top" style="padding: 0px 18px 9px; text-align: center; color: rgb(255, 255, 255); line-height: 150%; font-family: Helvetica; font-size: 12px; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
  
                                            <em>Copyright © '.date("Y").' '.$config[0]['D001_Empresa'].', Todos direitos reservados.</em>
  
  
                                        </td>
                                    </tr>
                                </tbody></table>
                        <!--[if mso]>
                        </td>
                        <![endif]-->
  
                        <!--[if mso]>
                        </tr>
                        </table>
                        <![endif]-->
                            </td>
                        </tr>
                    </tbody>
                </table></td>
                                                        </tr>
                                                    </tbody></table>
                                                    <!--[if (gte mso 9)|(IE)]>
                                                    </td>
                                                    </tr>
                                                    </table>
                                                    <![endif]-->
                                                </td>
                                            </tr>
                                        </tbody></table>
                                        <!-- // END TEMPLATE -->
                                    </td>
                                </tr>
                            </tbody></table>
                        </center></body></html>';
                          
                        $nome_para = $model_verifica[0]["usu_nome"];                        
  
                        //...and fire               
                        $mail = new App_PHPMailer();
                        $mail->isSMTP();
                        $mail->SMTPDebug = 0; //2 verbose client + serverside
                        $mail->Host = $config[0]["D001_SMTP_host"];
                        $mail->CharSet = "UTF-8";
                        $mail->Port = $config[0]["D001_SMTP_port"]; //Set the SMTP port number - likely to be 25, 465 or 587
                        $mail->SMTPAuth = true; //Whether to use SMTP authentication
                        $mail->Username = $config[0]["D001_SMTP_email"]; //Username to use for SMTP authentication
                        $mail->Password = $config[0]["D001_SMTP_password"]; //Password to use for SMTP authentication
                        $mail->setFrom($config[0]["D001_SMTP_email"], $config[0]["D001_Empresa"]);
                        $mail->addAddress($model_verifica[0]["usu_login"], !empty($model_verifica[0]["usu_nome"]) ? $model_verifica[0]["usu_nome"] : false);
                        //$mail->addAddress("franco.silva@flybizz.net");
                        $mail->Subject = $config[0]['D001_Empresa']." - Pedido de redefinição de senha!";
                        $mail->isHTML(true);
                        $mail->Body = $mensagem;
                        $send = $mail->send();
                        echo $send;
  
            endif;
            //echo $rs_email;
  
        endif;
      }
  
      public function usuarioNewPasswordSuccess(){
  
        // seta o id do cliente
        $id = new App_System();
        $id->_urlAjax = $_POST['url'];
        $id->setExplodeAjax();
        $id->setControllerAjax();
        $id->setActionAjax();
        $id->setParamsAjax();
        $dados = $id->getParamsAjax();
  
        if(!empty($dados["usuario"])):
  
            $model = new App_Model_usuarioModel();
            $model_verifica = $model->usuarioSelecionar($dados["usuario"]);
 
   
            if(empty($model_verifica)):
                $rs_email = 0;
            else:
                $rs_email = 1;
  
                $horario = date("H");
  
                if($horario > 6 && $horario < 12)
                {
                    $HoraDay = 'Bom dia';
                }
                else if($horario >= 12 && $horario < 20)
                {
                    $HoraDay = 'Boa tarde';
                }else{
                    $HoraDay = 'Boa noite';
                }
  
                $config = unserialize (CONFIG_DB);
      
                $mensagem = '<!DOCTYPE html>
                  <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml" xmlns="http://www.w3.org/1999/xhtml">
                  <head>
                        <!-- NAME: SELL PRODUCTS -->
                        <!--[if gte mso 15]>
                        <xml>
                            <o:OfficeDocumentSettings>
                            <o:AllowPNG/>
                            <o:PixelsPerInch>96</o:PixelsPerInch>
                            </o:OfficeDocumentSettings>
                        </xml>
                        <![endif]-->
                        <meta charset="UTF-8">
                        <meta http-equiv="X-UA-Compatible" content="IE=edge">
                        <meta name="viewport" content="width=device-width, initial-scale=1">
                        <title><b>'.$config[0]['D001_Empresa'].'</b></title>
                        <style type="text/css">
                        p{
                          margin:10px 0;
                          padding:0;
                        }
                        table{
                          border-collapse:collapse;
                        }
                        h1,h2,h3,h4,h5,h6{
                          display:block;
                          margin:0;
                          padding:0;
                        }
                        img,a img{
                          border:0;
                          height:auto;
                          outline:none;
                          text-decoration:none;
                        }
                        body,#bodyTable,#bodyCell{
                          height:100%;
                          margin:0;
                          padding:0;
                          width:100%;
                        }
                        .mcnPreviewText{
                          display:none !important;
                        }
                        #outlook a{
                          padding:0;
                        }
                        img{
                          -ms-interpolation-mode:bicubic;
                        }
                        table{
                          mso-table-lspace:0pt;
                          mso-table-rspace:0pt;
                        }
                        .ReadMsgBody{
                          width:100%;
                        }
                        .ExternalClass{
                          width:100%;
                        }
                        p,a,li,td,blockquote{
                          mso-line-height-rule:exactly;
                        }
                        a[href^=tel],a[href^=sms]{
                          color:inherit;
                          cursor:default;
                          text-decoration:none;
                        }
                        p,a,li,td,body,table,blockquote{
                          -ms-text-size-adjust:100%;
                          -webkit-text-size-adjust:100%;
                        }
                        .ExternalClass,.ExternalClass p,.ExternalClass td,.ExternalClass div,.ExternalClass span,.ExternalClass font{
                          line-height:100%;
                        }
                        a[x-apple-data-detectors]{
                          color:inherit !important;
                          text-decoration:none !important;
                          font-size:inherit !important;
                          font-family:inherit !important;
                          font-weight:inherit !important;
                          line-height:inherit !important;
                        }
                        .templateContainer{
                          max-width:600px !important;
                        }
                        a.mcnButton{
                          display:block;
                        }
                        .mcnImage,.mcnRetinaImage{
                          vertical-align:bottom;
                        }
                        .mcnTextContent{
                          word-break:break-word;
                        }
                        .mcnTextContent img{
                          height:auto !important;
                        }
                        .mcnDividerBlock{
                          table-layout:fixed !important;
                        }
                        h1{
                          color:#222222;
                          font-family:Helvetica;
                          font-size:40px;
                          font-style:normal;
                          font-weight:bold;
                          line-height:150%;
                          letter-spacing:normal;
                          text-align:center;
                        }
                        h2{
                          color:#222222;
                          font-family:Helvetica;
                          font-size:34px;
                          font-style:normal;
                          font-weight:bold;
                          line-height:150%;
                          letter-spacing:normal;
                          text-align:left;
                        }
                        h3{
                          color:#444444;
                          font-family:Helvetica;
                          font-size:22px;
                          font-style:normal;
                          font-weight:bold;
                          line-height:150%;
                          letter-spacing:normal;
                          text-align:left;
                        }
                        h4{
                          color:#999999;
                          font-family:Georgia;
                          font-size:20px;
                          font-style:italic;
                          font-weight:normal;
                          line-height:125%;
                          letter-spacing:normal;
                          text-align:left;
                        }
                        #templateHeader{
                          background-color:#f7f7f7;
                          background-image:none;
                          background-repeat:no-repeat;
                          background-position:center;
                          background-size:cover;
                          border-top:0;
                          border-bottom:0;
                          padding-top:45px;
                          padding-bottom:45px;
                        }
                        .headerContainer{
                          background-color:#transparent;
                          background-image:none;
                          background-repeat:no-repeat;
                          background-position:center;
                          background-size:cover;
                          border-top:0;
                          border-bottom:0;
                          padding-top:0;
                          padding-bottom:0;
                        }
                        .headerContainer .mcnTextContent,.headerContainer .mcnTextContent p{
                          color:#808080;
                          font-family:Helvetica;
                          font-size:16px;
                          line-height:150%;
                          text-align:left;
                        }
                        .headerContainer .mcnTextContent a,.headerContainer .mcnTextContent p a{
                          color:#00ADD8;
                          font-weight:normal;
                          text-decoration:underline;
                        }
                        #templateBody{
                          background-color:#FFFFFF;
                          background-image:none;
                          background-repeat:no-repeat;
                          background-position:center;
                          background-size:cover;
                          border-top:0;
                          border-bottom:0;
                          padding-top:36px;
                          padding-bottom:45px;
                        }
                        .bodyContainer{
                          background-color:transparent;
                          background-image:none;
                          background-repeat:no-repeat;
                          background-position:center;
                          background-size:cover;
                          border-top:0;
                          border-bottom:0;
                          padding-top:0;
                          padding-bottom:0;
                        }
                        .bodyContainer .mcnTextContent,.bodyContainer .mcnTextContent p{
                          color:#808080;
                          font-family:Helvetica;
                          font-size:16px;
                          line-height:150%;
                          text-align:left;
                        }
                        .bodyContainer .mcnTextContent a,.bodyContainer .mcnTextContent p a{
                          color:#00ADD8;
                          font-weight:normal;
                          text-decoration:underline;
                        }
                        #templateFooter{
                          background-color:#333333;
                          background-image:none;
                          background-repeat:no-repeat;
                          background-position:center;
                          background-size:cover;
                          border-top:0;
                          border-bottom:0;
                          padding-top:45px;
                          padding-bottom:63px;
                        }
                        .footerContainer{
                          background-color:transparent;
                          background-image:none;
                          background-repeat:no-repeat;
                          background-position:center;
                          background-size:cover;
                          border-top:0;
                          border-bottom:0;
                          padding-top:0;
                          padding-bottom:0;
                        }
                        .footerContainer .mcnTextContent,.footerContainer .mcnTextContent p{
                          color:#FFFFFF;
                          font-family:Helvetica;
                          font-size:12px;
                          line-height:150%;
                          text-align:center;
                        }
                        .footerContainer .mcnTextContent a,.footerContainer .mcnTextContent p a{
                          color:#FFFFFF;
                          font-weight:normal;
                          text-decoration:underline;
                        }
                      @media only screen and (min-width:768px){
                        .templateContainer{
                          width:600px !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        body,table,td,p,a,li,blockquote{
                          -webkit-text-size-adjust:none !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        body{
                          width:100% !important;
                          min-width:100% !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        .mcnRetinaImage{
                          max-width:100% !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        .mcnImage{
                          width:100% !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        .mcnCartContainer,.mcnCaptionTopContent,.mcnRecContentContainer,.mcnCaptionBottomContent,.mcnTextContentContainer,.mcnBoxedTextContentContainer,.mcnImageGroupContentContainer,.mcnCaptionLeftTextContentContainer,.mcnCaptionRightTextContentContainer,.mcnCaptionLeftImageContentContainer,.mcnCaptionRightImageContentContainer,.mcnImageCardLeftTextContentContainer,.mcnImageCardRightTextContentContainer,.mcnImageCardLeftImageContentContainer,.mcnImageCardRightImageContentContainer{
                          max-width:100% !important;
                          width:100% !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        .mcnBoxedTextContentContainer{
                          min-width:100% !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        .mcnImageGroupContent{
                          padding:9px !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        .mcnCaptionLeftContentOuter .mcnTextContent,.mcnCaptionRightContentOuter .mcnTextContent{
                          padding-top:9px !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        .mcnImageCardTopImageContent,.mcnCaptionBottomContent:last-child .mcnCaptionBottomImageContent,.mcnCaptionBlockInner .mcnCaptionTopContent:last-child .mcnTextContent{
                          padding-top:18px !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        .mcnImageCardBottomImageContent{
                          padding-bottom:9px !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        .mcnImageGroupBlockInner{
                          padding-top:0 !important;
                          padding-bottom:0 !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        .mcnImageGroupBlockOuter{
                          padding-top:9px !important;
                          padding-bottom:9px !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        .mcnTextContent,.mcnBoxedTextContentColumn{
                          padding-right:18px !important;
                          padding-left:18px !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        .mcnImageCardLeftImageContent,.mcnImageCardRightImageContent{
                          padding-right:18px !important;
                          padding-bottom:0 !important;
                          padding-left:18px !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        .mcpreview-image-uploader{
                          display:none !important;
                          width:100% !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        h1{
                          font-size:30px !important;
                          line-height:125% !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        h2{
                          font-size:26px !important;
                          line-height:125% !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        h3{
                          font-size:20px !important;
                          line-height:150% !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        h4{
                          font-size:18px !important;
                          line-height:150% !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        .mcnBoxedTextContentContainer .mcnTextContent,.mcnBoxedTextContentContainer .mcnTextContent p{
                          font-size:14px !important;
                          line-height:150% !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        .headerContainer .mcnTextContent,.headerContainer .mcnTextContent p{
                          font-size:16px !important;
                          line-height:150% !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        .bodyContainer .mcnTextContent,.bodyContainer .mcnTextContent p{
                          font-size:16px !important;
                          line-height:150% !important;
                        }
  
                    }	@media only screen and (max-width: 480px){
                        .footerContainer .mcnTextContent,.footerContainer .mcnTextContent p{
                          font-size:14px !important;
                          line-height:150% !important;
                        }
  
                    }
                    </style>
                    </head>
                    <body style="margin: 0px; padding: 0px; width: 100%; height: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">
                        <center>
                            <table width="100%" height="100%" align="center" id="bodyTable" style="margin: 0px; padding: 0px; width: 100%; height: 100%; border-collapse: collapse; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                                <tbody><tr>
                                    <td align="center" id="bodyCell" valign="top" style="margin: 0px; padding: 0px; width: 100%; height: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                                        <!-- BEGIN TEMPLATE // -->
                                        <table width="100%" style="border-collapse: collapse; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                                            <tbody><tr>
                                                <td align="center" id="templateHeader" valign="top" style="background: no-repeat center / cover rgb(247, 247, 247); padding-top: 45px; padding-bottom: 45px; border-top-color: currentColor; border-bottom-color: currentColor; border-top-width: 0px; border-bottom-width: 0px; border-top-style: none; border-bottom-style: none; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;" data-template-container="">
                                                    <!--[if (gte mso 9)|(IE)]>
                                                    <table align="center" border="0" cellspacing="0" cellpadding="0" width="600" style="width:600px;">
                                                    <tr>
                                                    <td align="center" valign="top" width="600" style="width:600px;">
                                                    <![endif]-->
                                                    <table width="100%" align="center" class="templateContainer" style="border-collapse: collapse; max-width: 600px !important; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                                                        <tbody><tr>
                                                            <td class="headerContainer" valign="top" style="background-position: center; padding-top: 0px; padding-bottom: 0px; border-top-color: currentColor; border-bottom-color: currentColor; border-top-width: 0px; border-bottom-width: 0px; border-top-style: none; border-bottom-style: none; -ms-text-size-adjust: 100%; background-image: none; background-repeat: no-repeat; background-size: cover; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;"><table width="100%" class="mcnImageBlock" style="border-collapse: collapse; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                          <tbody class="mcnImageBlockOuter">
                            <tr>
                                <td class="mcnImageBlockInner" valign="top" style="padding: 9px; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                                    <table width="100%" align="left" class="mcnImageContentContainer" style="border-collapse: collapse; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                                        <tbody><tr>
                                            <td class="mcnImageContent" valign="top" style="padding: 0px 9px; text-align: center; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
  
                                                    <a title="'.$config[0]['D001_Empresa'].'" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;" href="'.$config[0]['D001_Site'].'" target="_blank">
                                                        <img width="284" align="center" class="mcnImage" style="border: 0px currentColor; border-image: none; height: auto; padding-bottom: 0px; text-decoration: none; vertical-align: bottom; display: inline !important; -ms-interpolation-mode: bicubic; max-width: 284px;" alt="" src="'.$config[0]['D001_Site'].'/images/config/'.$config[0]['D001_Logosite'].'" alt="'.$config[0]['D001_Empresa'].'">
                                                    </a>
  
                                            </td>
                                        </tr>
                                    </tbody></table>
                                </td>
                            </tr>
                          </tbody>
                        </table>
                        <table width="100%" class="mcnTextBlock" style="border-collapse: collapse; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                          <tbody class="mcnTextBlockOuter">
                            <tr>
                              <td class="mcnTextBlockInner" valign="top" style="padding-top: 9px; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                                <!--[if mso]>
                                    <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;">
                                      <tr>
                                      <![endif]-->
  
                                      <!--[if mso]>
                                      <td valign="top" width="600" style="width:600px;">
                                      <![endif]-->
                                              <table width="100%" align="left" class="mcnTextContentContainer" style="border-collapse: collapse; min-width: 100%; max-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                                                  <tbody><tr>
  
                                                      <td class="mcnTextContent" valign="top" style="padding: 0px 18px 9px; text-align: left; color: rgb(128, 128, 128); line-height: 150%; font-family: Helvetica; font-size: 16px; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
  
                                                          <h1 style="margin: 0px; padding: 0px; text-align: center; color: rgb(34, 34, 34); line-height: 150%; letter-spacing: normal; font-family: Helvetica; font-size: 32px; font-style: normal; font-weight: bold; display: block;">Confirmação de redefinição de senha</h1>
  
                                                      </td>
                                                  </tr>
                                              </tbody></table>
                                      <!--[if mso]>
                                      </td>
                                      <![endif]-->
  
                                      <!--[if mso]>
                                      </tr>
                                      </table>
                                      <![endif]-->
                                          </td>
                                      </tr>
                                  </tbody>
                              </table></td>
                                                        </tr>
                                                    </tbody></table>
                                                    <!--[if (gte mso 9)|(IE)]>
                                                    </td>
                                                    </tr>
                                                    </table>
                                                    <![endif]-->
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="center" id="templateBody" valign="top" style="background: no-repeat center / cover rgb(255, 255, 255); padding-top: 36px; padding-bottom: 45px; border-top-color: currentColor; border-bottom-color: currentColor; border-top-width: 0px; border-bottom-width: 0px; border-top-style: none; border-bottom-style: none; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;" data-template-container="">
                                                    <!--[if (gte mso 9)|(IE)]>
                                                    <table align="center" border="0" cellspacing="0" cellpadding="0" width="600" style="width:600px;">
                                                    <tr>
                                                    <td align="center" valign="top" width="600" style="width:600px;">
                                                    <![endif]-->
                                                    <table width="100%" align="center" class="templateContainer" style="border-collapse: collapse; max-width: 600px !important; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                                                        <tbody><tr>
                                                            <td class="bodyContainer" valign="top" style="background: no-repeat center / cover; padding-top: 0px; padding-bottom: 0px; border-top-color: currentColor; border-bottom-color: currentColor; border-top-width: 0px; border-bottom-width: 0px; border-top-style: none; border-bottom-style: none; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;"><table width="100%" class="mcnTextBlock" style="border-collapse: collapse; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                    <tbody class="mcnTextBlockOuter">
                        <tr>
                            <td class="mcnTextBlockInner" valign="top" style="padding-top: 9px; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                                <!--[if mso]>
                        <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;">
                        <tr>
                        <![endif]-->
  
                        <!--[if mso]>
                        <td valign="top" width="600" style="width:600px;">
                        <![endif]-->
                                <table width="100%" align="left" class="mcnTextContentContainer" style="border-collapse: collapse; min-width: 100%; max-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                                    <tbody><tr>
                                        <td class="mcnTextContent" valign="top" style="padding: 0px 18px 9px; text-align: center; color: rgb(128, 128, 128); line-height: 150%; font-family: Helvetica; font-size: 16px; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                                            <h3 style="margin: 0px; padding: 0px; text-align: center; color: rgb(68, 68, 68); line-height: 150%; letter-spacing: normal; font-family: Helvetica; font-size: 22px; font-style: normal; font-weight: bold; display: block;"><span style="font-size: 16px;">
                                            '.$HoraDay.', <b>'.$model_verifica[0]['usu_nome'].'</b>.<br>
                                            A sua palavra-chave foi alterada com sucesso.</h3>
                                            <br>
                                            <br>
                                            Se não é você que está a solicitar a redefinição da palavra-chave contacte-nos <a href="mailto:'.$config[0]['D001_Email'].'">Clique aqui</a>.<br><br>
  
                                            <b>Atenciosamente</b>,<br>
                                            Equipa '.$config[0]['D001_Empresa'].'.
                                            <br>
                                            <br>
                                        </td>
                                    </tr>
                                </tbody></table>
                        <!--[if mso]>
                        </td>
                        <![endif]-->
  
                        <!--[if mso]>
                        </tr>
                        </table>
                        <![endif]-->
                            </td>
                        </tr>
                    </tbody>
                </table></td>
                                                        </tr>
                                                    </tbody></table>
                                                    <!--[if (gte mso 9)|(IE)]>
                                                    </td>
                                                    </tr>
                                                    </table>
                                                    <![endif]-->
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="center" id="templateFooter" valign="top" style="background: no-repeat center / cover rgb(51, 51, 51); padding-top: 45px; padding-bottom: 63px; border-top-color: currentColor; border-bottom-color: currentColor; border-top-width: 0px; border-bottom-width: 0px; border-top-style: none; border-bottom-style: none; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;" data-template-container="">
                                                    <!--[if (gte mso 9)|(IE)]>
                                                    <table align="center" border="0" cellspacing="0" cellpadding="0" width="600" style="width:600px;">
                                                    <tr>
                                                    <td align="center" valign="top" width="600" style="width:600px;">
                                                    <![endif]-->
                                                    <table width="100%" align="center" class="templateContainer" style="border-collapse: collapse; max-width: 600px !important; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                                                        <tbody><tr>
                                                            <td class="footerContainer" valign="top" style="background: no-repeat center / cover; padding-top: 0px; padding-bottom: 0px; border-top-color: currentColor; border-bottom-color: currentColor; border-top-width: 0px; border-bottom-width: 0px; border-top-style: none; border-bottom-style: none; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;"><table width="100%" class="mcnFollowBlock" style="border-collapse: collapse; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                    <tbody class="mcnFollowBlockOuter">
                        <tr>
                            <td align="center" class="mcnFollowBlockInner" valign="top" style="padding: 9px; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                                <table width="100%" class="mcnFollowContentContainer" style="border-collapse: collapse; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                    <tbody><tr>
                        <td align="center" style="padding-right: 9px; padding-left: 9px; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                            <table width="100%" class="mcnFollowContent" style="border-collapse: collapse; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                                <tbody><tr>
                                    <td align="center" valign="top" style="padding-top: 9px; padding-right: 9px; padding-left: 9px; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                                        <table align="center" style="border-collapse: collapse; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                                            <tbody><tr>
                                                <td align="center" valign="top" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                                                    <!--[if mso]>
                                                    <table align="center" border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                    <![endif]-->
  
                                                        <!--[if mso]>
                                                        <td align="center" valign="top">
                                                        <![endif]-->
  
  
                                                            <table align="left" style="display: inline; border-collapse: collapse; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                                                                <tbody><tr>
                                                                    <td class="mcnFollowContentItemContainer" valign="top" style="padding-right: 10px; padding-bottom: 9px; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                                                                        <table width="100%" class="mcnFollowContentItem" style="border-collapse: collapse; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                                                                            <tbody><tr>
                                                                                <td align="left" valign="middle" style="padding: 5px 10px 5px 9px; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                                                                                    <table width="" align="left" style="border-collapse: collapse; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                                                                                        <tbody><tr>
  
                                                                                                <td width="24" align="center" class="mcnFollowIconContent" valign="middle" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                                                                                                    <a style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;" href="http://www.facebook.com/BTLIndustriesBrasil" target="_blank"><img width="24" height="24" style="border: 0px currentColor; border-image: none; height: auto; text-decoration: none; display: block; -ms-interpolation-mode: bicubic;" src="https://cdn-images.mailchimp.com/icons/social-block-v2/outline-light-facebook-48.png"></a>
                                                                                                </td>
  
  
                                                                                        </tr>
                                                                                    </tbody></table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody></table>
                                                                    </td>
                                                                </tr>
                                                            </tbody></table>
  
                                                        <!--[if mso]>
                                                        </td>
                                                        <![endif]-->
  
  
                                                        <!--[if mso]>
                                                        <td align="center" valign="top">
                                                        <![endif]-->
  
  
                                                            <table align="left" style="display: inline; border-collapse: collapse; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                                                                <tbody><tr>
                                                                    <td class="mcnFollowContentItemContainer" valign="top" style="padding-right: 10px; padding-bottom: 9px; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                                                                        <table width="100%" class="mcnFollowContentItem" style="border-collapse: collapse; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                                                                            <tbody><tr>
                                                                                <td align="left" valign="middle" style="padding: 5px 10px 5px 9px; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                                                                                    <table width="" align="left" style="border-collapse: collapse; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                                                                                        <tbody><tr>
  
                                                                                                <td width="24" align="center" class="mcnFollowIconContent" valign="middle" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                                                                                                    <a style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;" href="http://www.instagram.com/btlaestheticsbr" target="_blank"><img width="24" height="24" style="border: 0px currentColor; border-image: none; height: auto; text-decoration: none; display: block; -ms-interpolation-mode: bicubic;" src="https://cdn-images.mailchimp.com/icons/social-block-v2/outline-light-instagram-48.png"></a>
                                                                                                </td>
  
  
                                                                                        </tr>
                                                                                    </tbody></table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody></table>
                                                                    </td>
                                                                </tr>
                                                            </tbody></table>
  
                                                        <!--[if mso]>
                                                        </td>
                                                        <![endif]-->
                                                    <!--[if mso]>
                                                    </tr>
                                                    </table>
                                                    <![endif]-->
                                                </td>
                                            </tr>
                                        </tbody></table>
                                    </td>
                                </tr>
                            </tbody></table>
                        </td>
                    </tr>
                </tbody></table>
  
                            </td>
                        </tr>
                    </tbody>
                </table><table width="100%" class="mcnDividerBlock" style="border-collapse: collapse; table-layout: fixed !important; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                    <tbody class="mcnDividerBlockOuter">
                        <tr>
                            <td class="mcnDividerBlockInner" style="padding: 18px; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                                <table width="100%" class="mcnDividerContent" style="border-top-color: rgb(80, 80, 80); border-top-width: 2px; border-top-style: solid; border-collapse: collapse; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                                    <tbody><tr>
                                        <td style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                                            <span></span>
                                        </td>
                                    </tr>
                                </tbody></table>
                <!--
                                <td class="mcnDividerBlockInner" style="padding: 18px;">
                                <hr class="mcnDividerContent" style="border-bottom-color:none; border-left-color:none; border-right-color:none; border-bottom-width:0; border-left-width:0; border-right-width:0; margin-top:0; margin-right:0; margin-bottom:0; margin-left:0;" />
                -->
                            </td>
                        </tr>
                    </tbody>
                </table><table width="100%" class="mcnTextBlock" style="border-collapse: collapse; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                    <tbody class="mcnTextBlockOuter">
                        <tr>
                            <td class="mcnTextBlockInner" valign="top" style="padding-top: 9px; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
                                <!--[if mso]>
                        <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;">
                        <tr>
                        <![endif]-->
  
                        <!--[if mso]>
                        <td valign="top" width="600" style="width:600px;">
                        <![endif]-->
                                <table width="100%" align="left" class="mcnTextContentContainer" style="border-collapse: collapse; min-width: 100%; max-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0">
                                    <tbody><tr>
  
                                        <td class="mcnTextContent" valign="top" style="padding: 0px 18px 9px; text-align: center; color: rgb(255, 255, 255); line-height: 150%; font-family: Helvetica; font-size: 12px; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-line-height-rule: exactly;">
  
                                            <em>Copyright © '.date("Y").' '.$config[0]['D001_Empresa'].', Todos direitos reservados.</em>
  
  
                                        </td>
                                    </tr>
                                </tbody></table>
                        <!--[if mso]>
                        </td>
                        <![endif]-->
  
                        <!--[if mso]>
                        </tr>
                        </table>
                        <![endif]-->
                            </td>
                        </tr>
                    </tbody>
                </table></td>
                                                        </tr>
                                                    </tbody></table>
                                                    <!--[if (gte mso 9)|(IE)]>
                                                    </td>
                                                    </tr>
                                                    </table>
                                                    <![endif]-->
                                                </td>
                                            </tr>
                                        </tbody></table>
                                        <!-- // END TEMPLATE -->
                                    </td>
                                </tr>
                            </tbody></table>
                        </center></body></html>';
                           
                        $nome_para = $model_verifica[0]["usu_nome"];                        
  
                        //...and fire               
                        $mail = new App_PHPMailer();
                        $mail->isSMTP();
                        $mail->SMTPDebug = 0; //2 verbose client + serverside
                        $mail->Host = $config[0]["D001_SMTP_host"];
                        $mail->CharSet = "UTF-8";
                        $mail->Port = $config[0]["D001_SMTP_port"]; //Set the SMTP port number - likely to be 25, 465 or 587
                        $mail->SMTPAuth = true; //Whether to use SMTP authentication
                        $mail->Username = $config[0]["D001_SMTP_email"]; //Username to use for SMTP authentication
                        $mail->Password = $config[0]["D001_SMTP_password"]; //Password to use for SMTP authentication
                        $mail->setFrom($config[0]["D001_SMTP_email"], $config[0]["D001_Empresa"]);
                        $mail->addAddress($model_verifica[0]["usu_login"], !empty($model_verifica[0]["usu_nome"]) ? $model_verifica[0]["usu_nome"] : false);
                        //$mail->addAddress("franco.silva@flybizz.net");
                        $mail->Subject = $config[0]['D001_Empresa']." - Pedido de redefinição de senha!";
                        $mail->isHTML(true);
                        $mail->Body = $mensagem;
                        $send = $mail->send();
                        echo $send;
                           
            endif;
            //echo $rs_email;
  
        endif;
    }
        
                
}
