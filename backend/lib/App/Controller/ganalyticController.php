<?php
  
  //echo strpos($_SERVER['REQUEST_URI'],"backend");


    class index extends App_Controller{

        public function index_action(){

          define('ga_profile_id','110958323');

          $ga = new App_gapi("842514300553-jse3g2pns0ij84emfm2l1sjffvps3fo6@developer.gserviceaccount.com", "index-1ff14d702a45.p12");



          // GEO CIDADES
          $city = $ga->requestReportData(ga_profile_id,array('city','region','country'),array('visits'));

          foreach($city as $result1):

            $string = substr($result1, 0, -1);

            $exp = explode("-", $string);

            $uf = str_replace("State of", "", $exp[1]);

            $cidade = $exp[0];

            $pais = $exp[2];

            if($cidade != "(not set)"):

              $arr[] = array("City" => $cidade,
                             "Region" => $uf, 
                             "Country" => $pais, 
                             "visits" => $result1->getVisits()
                            );

            endif;
          endforeach;


          //GEO ESTADOS
          $region = $ga->requestReportData(ga_profile_id,array('region','country'),array('visits'));

          foreach($region as $result1):

            $string = substr($result1, 0, -1);

            $exp = explode("-", $string);

            $uf = str_replace("State of", "", $exp[0]);

            $country = $exp[1];

            if($uf != "(not set)"):

              $arr2[] = array("Region" => $uf,
                             "Country" => $country,
                             "visits" => $result1->getVisits()
                            );

            endif;
          endforeach;
          

          //CHAMA OS DADOS
          $dados['geo_cidade'] = $arr;
          $dados['geo_estado'] = $arr2;


          //CHAMA INDEX BACKEND
          parent::View("index", $dados);

        } 

        public function geoPais(){

          define('ga_profile_id','110958323');

          $ga = new App_gapi("842514300553-jse3g2pns0ij84emfm2l1sjffvps3fo6@developer.gserviceaccount.com", "index-1ff14d702a45.p12");

          //$ga->requestReportData(ga_profile_id,array('browser','browserVersion','country'),array('pageviews','visits'));

          //$city = $ga->requestReportData(ga_profile_id,array('city','region','country'),array('visits'));

          $country = $ga->requestReportData(ga_profile_id,array('country'),array('visits'));

          //$arr = array(array("country"), array("visualization"));

          foreach($country as $result1):

            $pais = substr($result1, 0, -1);

            if($pais != "(not set)"):

              $arr[] = array("country" => $pais , 
                             "visits" => $result1->getVisits()
                            );



            // echo $pais;
            // echo $result1->getVisits();

            endif;
          endforeach;

          //print_r($arr);

          $json = json_encode($arr);

          echo $json;

        }

        public function previsaotempo(){

          header('Content-Type: text/plain; charset=utf-8;'); 
          exit(file_get_contents("http://www.previsaodotempo.org/api.php?city=governador+valadares"));
          print_r(json_decode($file));


        
        }

        
    }