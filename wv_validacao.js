/*
 * jQuery WV Validador 1.5
 * http://www.wviveiro.com.br/?p=269
 *
 * Author: Wellington Viveiro
 * Data: 2011-03-01
 *
 * Plugin para jQuery com validações de formulário que normalmente precisamos.
 */

/*Primeiro impedimos o conflito*/
jQuery(function($){
	
	//Função para chamar as validações
	$.fn.wvmask = function(param){
		if(param == "numero") this.wvnumero();
		else if(param == "telefone") this.wvtelefone();
		else if(param == "data") this.wvdata();
		else if(param == "cep") this.wvcep();
	}
	
	//Função para permitir apenas números no campo.
	$.fn.wvnumero = function(){
		this.keypress(function(event){
			if(event.charCode || event.charCode == "0"){
				var keyCode = event.charCode;	
			}else if(event.keyCode){
				var keyCode = event.keyCode;	
			}else{
				var keyCode = 0;	
			}
			
			if((keyCode>47 && keyCode<58) || (keyCode == 44) || (keyCode == 0)){
				return true;
			}else{
				return false;
			}
		});
	}
	
	/*Função de MÁSCARA para números de Telefone (xxxx-xxxx)*/
	$.fn.wvtelefone = function(){
		var $tamanho,$texto;
		this.wvnumero();
		function wv_tiraFinalNumeroTel(obj){
			obj.val(obj.val().replace(/-/gi,""));
			$tamanho = obj.val().length;
			$texto = obj.val().substr(0,4)+"-"+obj.val().substr(4);
			obj.val($texto);
			$tamanho = obj.val().length;
			if($tamanho>9){
				obj.val(obj.val().substr(0,9));
			}

		}
		
		function wv_mantemSoNumeroTel(obj){
			$texto = "";
			$tamanho = obj.val().length;
			for($i=0;$i<$tamanho;$i++){
				$val = obj.val().substr($i,1);
				if($val == "0" || $val == "1" || $val == "2" || $val == "3" || $val == "4" || $val == "5" || $val == "6" || $val == "7" || $val == "8" || $val == "9" || $val == "-"){
					$texto += $val;
				}
			}
			obj.val($texto);
		}
		this.keyup(function(){
			wv_tiraFinalNumeroTel($(this));
			wv_mantemSoNumeroTel($(this));
		});
		this.blur(function(){
			wv_tiraFinalNumeroTel($(this));
			wv_mantemSoNumeroTel($(this));
		});
	}
	
	$.fn.wvdata = function(){
		var $tamanho,$texto;
		this.wvnumero();
		function wv_tiraFinalNumeroData(obj){
			obj.val(obj.val().replace(/\//gi,""));
			$tamanho = obj.val().length;
			$texto = obj.val().substr(0,2)+"/"+obj.val().substr(2,2)+"/"+obj.val().substr(4);
			obj.val($texto);
			$tamanho = obj.val().length;
			if($tamanho>10){
				obj.val(obj.val().substr(0,10));	
			}
		}
		function wv_mantemSoNumeroData(obj){
			$texto = "";
			$tamanho = obj.val().length;
			for($i=0;$i<$tamanho;$i++){
				$val = obj.val().substr($i,1);
				if($val == "0" || $val == "1" || $val == "2" || $val == "3" || $val == "4" || $val == "5" || $val == "6" || $val == "7" || $val == "8" || $val == "9" || $val == "/"){
					$texto += $val;
				}
			}
			obj.val($texto);
		}
		this.keyup(function(){
			wv_tiraFinalNumeroData($(this));
			wv_mantemSoNumeroData($(this));
		});
		this.blur(function(){
			wv_tiraFinalNumeroData($(this));
			wv_mantemSoNumeroData($(this));
		});
	}

	/*Função de MÁSCARA para CEP (xxxxx-xxx)*/
	$.fn.wvcep = function(){
		var $tamanho,$texto;
		this.wvnumero();
		function wv_tiraFinalNumeroCep(obj){
			obj.val(obj.val().replace(/-/gi,""));
			$tamanho = obj.val().length;
			$texto = obj.val().substr(0,5)+"-"+obj.val().substr(5);
			obj.val($texto);
			$tamanho = obj.val().length;
			if($tamanho>9){
				obj.val(obj.val().substr(0,9));
			}

		}
		function wv_mantemSoNumeroCep(obj){
			$texto = "";
			$tamanho = obj.val().length;
			for($i=0;$i<$tamanho;$i++){
				$val = obj.val().substr($i,1);
				if($val == "0" || $val == "1" || $val == "2" || $val == "3" || $val == "4" || $val == "5" || $val == "6" || $val == "7" || $val == "8" || $val == "9" || $val == "-"){
					$texto += $val;
				}
			}
			obj.val($texto);
		}
		this.keyup(function(){
			wv_tiraFinalNumeroCep($(this));
			wv_mantemSoNumeroCep($(this));
		});
		this.blur(function(){
			wv_tiraFinalNumeroCep($(this));
			wv_mantemSoNumeroCep($(this));
		});
	}
	
	/*Função de validação de campo*/
	$.fn.valida = function(param){
		var padrao = {
			tipo : "vazio",
			minimo : 0,
			maximo : 0,
			cartao : '',
			sucesso :  function(){},
			erro : function(){}
		}
		var erro = false;
		var extErro = '';
		$.extend(padrao,param);
		if(padrao["tipo"] == "vazio"){
			if(padrao["minimo"]==0 && (this.val().length==0 || this.val() == "" || this.val() == " ")){
				erro = true;
				extErro = 'O campo está vazio.';
			}
			if(padrao["minimo"]>0 && this.val().length<padrao["minimo"]){
				erro = true;
				extErro = 'Não foi digitado número suficiente de caracteres no campo.';
			}
			if(padrao["maximo"]>0 && this.val().length>padrao["maximo"]){
				erro = true;
				extErro = 'O campo ultrapassou o número permitido de caracteres.';
			}
		}
		if(padrao["tipo"] == "email"){
			if(this.val().indexOf("@") == -1){
				erro = true;
				extErro = 'O campo não é um e-mail válido';
			}else if(this.val().substr((this.val().indexOf("@")+1)).indexOf(".") == -1){
				erro = true;
				extErro = 'O campo não é um e-mail válido';
			}
		}
		if(padrao["tipo"] == "check"){
			if(padrao["minimo"] == 0) padrao["minimo"] = 1;
			var $campos = this.selector;
			var $vetor = $campos.split(",");
			var $campo,$id,$tot,$totCampos,$encontrados;
			for($i=0;$i<$vetor.length;$i++){
				$campo = $vetor[$i];
				if($campo.indexOf("#") > -1){
					$id = $campo.replace(/#/gi,"");
					$tot = 0;
					while($("#"+$id).length>0){
						$tot++;
						$("#"+$id).attr("id",$id+$tot);
					}
					for($j=1;$j<=$tot;$j++){
						$("#"+$id+$j).addClass("wvCheck");
						$("#"+$id+$j).attr("id",$id);
					}
				}else{
					$($campo).addClass("wvCheck");
				}
			}
			$totCampos = $(".wvCheck").size();
			$encontrados = 0;
			for($i=0;$i<$totCampos;$i++){
				if($(".wvCheck:eq("+$i+")").attr('checked')){
					$encontrados++;
				}
			}
			$(".wvCheck").removeClass('wvCheck');
			if($encontrados<padrao["minimo"]){
				erro = true;
				extErro = 'Não foi selecionado número o suficiente de campos.';
			}else if($encontrados>padrao["maximo"] && padrao["maximo"]>0){
				erro = true;
				extErro = 'Foi selecionado mais campos do que o permitido.';
			}
		}
		if(padrao["tipo"] == "cpf"){		

			var $f_texto,$f_primeiraParte,$i,$j,$f_total,$f_aux,$f_dig1,$f_dig2,$verdade;
			$verdade = false;
			$f_texto = this.val();

		    strCPF = $f_texto;

    		strCPF = strCPF.replace(/[^\d]+/g, '');
    		var Soma;
    		var Resto;
    		var cboll = true;
    		Soma = 0;

    if (strCPF.length != 11 || strCPF == "00000000000" || strCPF == "11111111111" || strCPF == "22222222222" || strCPF == "33333333333" || strCPF == "44444444444" || strCPF == "55555555555" || strCPF == "66666666666" || strCPF == "77777777777" || strCPF == "88888888888" || strCPF == "99999999999") cboll = false;

    for (i = 1; i <= 9; i++) Soma = Soma + parseInt(strCPF.substring(i - 1, i)) * (11 - i);
    Resto = (Soma * 10) % 11;

    if ((Resto == 10) || (Resto == 11)) Resto = 0;
    if (Resto != parseInt(strCPF.substring(9, 10))) cboll = false;

    Soma = 0;
    for (i = 1; i <= 10; i++) Soma = Soma + parseInt(strCPF.substring(i - 1, i)) * (12 - i);
    Resto = (Soma * 10) % 11;

    if ((Resto == 10) || (Resto == 11)) Resto = 0;
    if (Resto != parseInt(strCPF.substring(10, 11))) cboll = false;

    if (!cboll) {
				erro = true;
				extErro = 'CPF inválido';
    } else {
				$verdade = true;
    }

		}
		if(padrao["tipo"] == 'cnpj'){
			var $f_texto,$f_primeiraParte,$i,$j,$f_total,$f_aux,$f_dig1,$f_dig2,$verdade;
			$verdade = false;
			$f_texto = this.val();
			//Limpa pontos e Traços da string
			$f_texto = $f_texto.replace(/\./g, "");
			$f_texto = $f_texto.replace(/\-/g, "");
			$f_texto = $f_texto.replace(/\_/g, "");
			$f_texto = $f_texto.replace(/\//g, "");
			
			
			if($f_texto.length == 14){
				$f_primeiraParte = $f_texto.substr(0,12);
				$j = 6;
				$f_total = 0
				for($i=0;$i<12;$i++){
					$j--;
					if($j==1){
						$j = 9;	
					}
					$f_total += (parseInt($f_primeiraParte.substr($i,1))*$j);
				}
				$f_aux = $f_total/11;
				$f_aux = $f_aux + "";
				if($f_aux.indexOf(".")>=0){
					$f_aux = $f_aux.substr(0,$f_aux.indexOf("."));	
				}
				$f_aux = parseInt($f_aux);
				$f_aux = $f_aux*11;
				$f_aux = $f_total - $f_aux;
				$f_dig1 = "0";
				if($f_aux>=2){
					$f_aux = 11-$f_aux;
					$f_dig1 = $f_aux+"";
				}
				$j = 7;
				$f_total = 0;
				$f_primeiraParte = $f_primeiraParte + $f_dig1;
				for($i=0;$i<13;$i++){
					$j--;
					if($j==1){
						$j = 9;	
					}
					$f_total += (parseInt($f_primeiraParte.substr($i,1))*$j);
				}
				$f_aux = $f_total/11;
				$f_aux = $f_aux + "";
				if($f_aux.indexOf(".")>=0){
					$f_aux = $f_aux.substr(0,$f_aux.indexOf("."));	
				}
				$f_aux = parseInt($f_aux);
				$f_aux = $f_aux*11;
				$f_aux = $f_total - $f_aux;
				$f_dig2 = "0";
				if($f_aux>=2){
					$f_aux = 11-$f_aux;
					$f_dig2 = $f_aux+"";
				}
				if(($f_dig1+$f_dig2) == $f_texto.substr(12,2)){
					$verdade = true;
				}
			}
			if(!$verdade){
				erro = true;
				extErro = 'CNPJ inválido';
			}
		}
		if(padrao["tipo"] == 'cartao'){
			var $e_tipo = padrao["cartao"];
			var $valido = true;
			var isValid = false;
			var ccCheckRegExp = /[^\d ]/;
			var cardNumber = this.val();
			var cardType = $e_tipo;
			isValid = !ccCheckRegExp.test(cardNumber);
			if (isValid){
				var cardNumbersOnly = cardNumber.replace(/ /g,"");
				var cardNumberLength = cardNumbersOnly.length;
				var lengthIsValid = false;
				var prefixIsValid = false;
				var prefixRegExp;
			switch(cardType){
				case "mastercard":
					lengthIsValid = (cardNumberLength == 16);
					prefixRegExp = /^5[1-5]/;
				break;
				case "visa":
					lengthIsValid = (cardNumberLength == 16 || cardNumberLength == 13);
					prefixRegExp = /^4/;
				break;
				case "amex":
					lengthIsValid = (cardNumberLength == 15);
					prefixRegExp = /^3(4|7)/;
				break;
				default:
					prefixRegExp = /^$/;
			}
		
			prefixIsValid = prefixRegExp.test(cardNumbersOnly);
			isValid = prefixIsValid && lengthIsValid;
			}
			if (isValid){
				var numberProduct;
				var numberProductDigitIndex;
				var checkSumTotal = 0;
				for (digitCounter = cardNumberLength - 1; digitCounter >= 0; digitCounter--){
					checkSumTotal += parseInt (cardNumbersOnly.charAt(digitCounter));
					digitCounter--;
					numberProduct = String((cardNumbersOnly.charAt(digitCounter) * 2));
					for (var productDigitCounter = 0; productDigitCounter < numberProduct.length; productDigitCounter++){
						checkSumTotal += parseInt(numberProduct.charAt(productDigitCounter));
					}
				}
				isValid = (checkSumTotal % 10 == 0);
			}
			$valido = isValid;
			if(!$valido){
				erro = true;
				extErro = 'Cartão Inválido.';
			}
		}
		if(erro){
			padrao["erro"](extErro);
		}else{
			padrao['sucesso']();
		}
	}

});





function Teclanum(e)
{
if(document.all) // Internet Explorer
var tecla = event.keyCode;

else if(document.getElementById) // Nestcape
var tecla = e.which;

if(tecla > 47 && tecla < 58) // numeros de 0 a 9
return true;
else

{
if (tecla != 8) // backspace
return false;
else
return true;
}

}

function mascara(o,f){
    v_obj=o
    v_fun=f
    setTimeout("execmascara()",1)
}

function execmascara(){
    v_obj.value=v_fun(v_obj.value)
}

function leech(v){
    v=v.replace(/o/gi,"0")
    v=v.replace(/i/gi,"1")
    v=v.replace(/z/gi,"2")
    v=v.replace(/e/gi,"3")
    v=v.replace(/a/gi,"4")
    v=v.replace(/s/gi,"5")
    v=v.replace(/t/gi,"7")
    return v
}

function soNumeros(v){
    return v.replace(/\D/g,"")
}

function itelefone(v){
    v=v.replace(/\D/g,"")             //Remove tudo o que não é dígito
    v=v.replace(/^(\d{2})(\d)/g,"($1) $2") //Coloca parênteses em volta dos dois primeiros dígitos
    v=v.replace(/(\d)(\d{4})$/,"$1-$2")   //Coloca hífen entre o quarto e o quinto dígitos
    return v
}

function icpf(v){
    v=v.replace(/\D/g,"")                    //Remove tudo o que não é dígito
    v=v.replace(/(\d{3})(\d)/,"$1.$2")       //Coloca um ponto entre o terceiro e o quarto dígitos
    v=v.replace(/(\d{3})(\d)/,"$1.$2")       //Coloca um ponto entre o terceiro e o quarto dígitos
                                             //de novo (para o segundo bloco de números)
    v=v.replace(/(\d{3})(\d{1,2})$/,"$1-$2") //Coloca um hífen entre o terceiro e o quarto dígitos
    return v
}

function idata(v){
    v=v.replace(/\D/g,"")                    //Remove tudo o que não é dígito
    v=v.replace(/(\d{2})(\d)/,"$1/$2")       //Coloca um ponto entre o terceiro e o quarto dígitos
    v=v.replace(/(\d{2})(\d)/,"$1/$2")       //Coloca um ponto entre o terceiro e o quarto dígitos
    return v
}

function icep(v){
    v=v.replace(/D/g,"")                //Remove tudo o que não é dígito
    v=v.replace(/^(\d{5})(\d)/,"$1-$2") //Esse é tão fácil que não merece explicações
    return v
}

function cnpj(v){
    v=v.replace(/\D/g,"")                           //Remove tudo o que não é dígito
    v=v.replace(/^(\d{2})(\d)/,"$1.$2")             //Coloca ponto entre o segundo e o terceiro dígitos
    v=v.replace(/^(\d{2})\.(\d{3})(\d)/,"$1.$2.$3") //Coloca ponto entre o quinto e o sexto dígitos
    v=v.replace(/\.(\d{3})(\d)/,".$1/$2")           //Coloca uma barra entre o oitavo e o nono dígitos
    v=v.replace(/(\d{4})(\d)/,"$1-$2")              //Coloca um hífen depois do bloco de quatro dígitos
    return v
}

function romanos(v){
    v=v.toUpperCase()             //Maiúsculas
    v=v.replace(/[^IVXLCDM]/g,"") //Remove tudo o que não for I, V, X, L, C, D ou M
    //Essa é complicada! Copiei daqui: http://www.diveintopython.org/refactoring/refactoring.html
    while(v.replace(/^M{0,4}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})$/,"")!="")
        v=v.replace(/.$/,"")
    return v
}

function site(v){
    //Esse sem comentarios para que você entenda sozinho ;-)
    v=v.replace(/^http:\/\/?/,"")
    dominio=v
    caminho=""
    if(v.indexOf("/")>-1)
        dominio=v.split("/")[0]
        caminho=v.replace(/[^\/]*/,"")
    dominio=dominio.replace(/[^\w\.\+-:@]/g,"")
    caminho=caminho.replace(/[^\w\d\+-@:\?&=%\(\)\.]/g,"")
    caminho=caminho.replace(/([\?&])=/,"$1")
    if(caminho!="")dominio=dominio.replace(/\.+$/,"")
    v="http://"+dominio+caminho
    return v
}