<?php

    $str = file_get_contents('http://www.climatempo.com.br/previsao-do-tempo/cidade/107/belohorizonte-mg');
	preg_match_all('#<li>(.*?)</li>#i', $str, $matches);

  //print_r($matches);
	
	//print trim($matches[1][4]).'<br>'; // temperatura
	//print trim($matches[1][5]).'<br>'; // umidade
	//print trim($matches[1][6]).'<br>'; // pressao
	
	$regexp = "<a\s[^>]*href=\"([^\"]*)\"[^>]*>(.*)<\/a>";
    preg_match_all("/$regexp/siU", $str, $matchesd);

    //print_r($matchesd);
	
	//print trim(strip_tags($matchesd[0][52])).'<br>'; // vento

function tdrows($elements)
{
    $str = "";
    foreach ($elements as $element) {		
		$txtt = str_replace("  ", "@", trim($element->nodeValue));
		
		   $dadosex = explode("@",$txtt);
		   $dadosrec = NULL;
		   
			 foreach ($dadosex as $value) {
				 if(!empty($value)){
				 $dadosrec .= utf8_decode(trim($value)).'!';
				 }
			 }
			
		$dadosarray = explode("!",$dadosrec);
		
		if(!empty($dadosarray[0])){
		
		$str .= 'data:'.$dadosarray[0].'|temp_max:'.$dadosarray[1].'|temp_min:'.$dadosarray[2].'<br>';		  
		 					  
		}
					  
	}
    
	$str = substr($str, 0, -1);
    return $str;
}

function getdata($str)
{
    $contents = $str;
    $DOM = new DOMDocument;
    @$DOM->loadHTML($contents);

    $items = $DOM->getElementById("tbl-15days")->getElementsByTagName('tr');

    foreach ($items as $node) {
        return tdrows($node->childNodes);
    }
}

//print getdata($str);
	

?>