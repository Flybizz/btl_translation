<?php
    class produto extends App_Controller{
        public function index_action(){
            return $this->View("indexProduto");
        }
        /****************** CRUD ******************************/
        public function registar(){

            global $start;
            $parm = $start->_params;

            $dados['lang'] = $parm['lang'];

           /*  $categoria = new App_Model_paginacatModel();
            $model_categoria = $categoria->listaCategoria($parm['lang'],"produto");
            $dados['categoria'] = $model_categoria;

            $parceiro = new App_Model_parceiroModel();
            $model_parceiro = $parceiro->listaMarca();
            $dados['parceiro'] = $model_parceiro; */

            $modulo = new App_Model_moduloModel();
            $model_modulo = $modulo->listaModulo();
            $dados['modulo'] = $model_modulo;

            return $this->View("cadastrarProduto",$dados);
        }


        public function chamaSubCategoria(){

            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $dados = $id->getParamsAjax();

            $categoria = new App_Model_paginacatModel();

            if($dados["level"] == 1):
            $subcategoria = $categoria->paginaSubcategoria("produto",$dados["id"],$dados['lang']);
            endif;
            if($dados["level"] == 2):
            echo $dados["sub1"]."\n";
            echo $dados["id"];
            $subcategoria = $categoria->paginaSubcategoria2("produto",$dados["sub1"],$dados["id"],$dados['lang']);
            endif;

            if($dados["id"] != 0):
              $html = "";
              if(!empty($subcategoria)):
                  $html .= "<option value='0'>Selecione</option>";
                  foreach ($subcategoria as $sub):
                      $html .= "<option value='".$sub["prod_Uid"]."'>".$sub["prod_titulo"]."</option>";
                  endforeach;
              else:
                  $html .= "<option value='0'>-</option>";
              endif;
            endif;
            echo $html;

        }

        public function inserir(){

            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $dados = $id->getParamsAjax();
            //print_r($dados);
            $model = new App_Model_produtoModel();
            $languageLIST = unserialize (LANGUAGE_LIST);
            $ref = rand(100, 999999);

            if(!empty($languageLIST)):
                foreach ($languageLIST as $idioma):

                    $arr = array(
                        'titulo' => trim($dados['titulo']),                                                
                        'chamada' => $dados['chamada'],
                        'texto' => $dados['texto'],
                        'img' => $dados['img'],
                        'controller' => $dados['controller'],
                        'idioma' => $idioma["D008_Sigla"],
                        'destaque' =>  $dados['destaque'],                        
                        'ordem' =>  $dados['ordem'],
                        'preco' =>  $dados['preco'],                                                
                        'referencia' => $ref
                    );
                    $model_inserir = $model->produtoCadastrar($arr);
                endforeach;
            endif;
            print_r($model_inserir);
        }

        /* ALTERAR */
        public function selecionar($view = NULL){
            // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $ident = $id->getParamsAjax("id");
            $produto_alt = new App_Model_produtoModel();
            $produto_lista_alt = $produto_alt->produtoSelecionar($ident);
            $dados['produto_lista_alt'] = $produto_lista_alt;
            /*DADOS CATEGORIA*/
            $model = new App_Model_produtocatModel();
            $model_categoria = $model->listaProduto();
            $dados['produtocat'] = $model_categoria;
            /*DADOS GALERIA*/
            $model = new App_Model_galeriaModel();
            $model_galeria = $model->listaGaleria();
            $dados['galeria'] = $model_galeria;
            if($view != NULL):
                return $dados;
            else:
                $this->View("alterarProduto", $dados);
            endif;
        }

        public function alterar(){
            global $start;
            $parm = $start->_params;

            $produto_alt = new App_Model_produtoModel();
            $produto_lista_alt = $produto_alt->produtoSelecionarRef($parm['ref'], $parm['lang']);
            $dados['produto_lista_alt'] = $produto_lista_alt;

            /* $categoria = new App_Model_paginacatModel();
            $model_categoria = $categoria->listaCategoria($parm['lang'],"produto");
            $dados['categoria'] = $model_categoria;

            $model_subcategoria = $categoria->paginaSubcategoria("produto",$produto_lista_alt[0]["prod_Categoria"],$parm['lang']);
            $dados['subcategoria'] = $model_subcategoria;

            $model_ssubcategoria = $categoria->paginaSubcategoria2("produto",$produto_lista_alt[0]["prod_Categoria"],$produto_lista_alt[0]["prod_Subcategoria"],$parm['lang']);
            $dados['ssubcategoria'] = $model_ssubcategoria;

            $parceiro = new App_Model_parceiroModel();
            $model_parceiro = $parceiro->listaMarca();
            $dados['parceiro'] = $model_parceiro; */

            $modulo = new App_Model_moduloModel();
            $model_modulo = $modulo->listaModulo();
            $dados['modulo'] = $model_modulo;

            $this->View("alterarProduto", $dados);
        }

        //ALTERAÇÃO
        public function alteracao(){
            // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $dados = $id->getParamsAjax();

            $model = new App_Model_produtoModel();
            $dados_produto = $model->produtoSelecionar($dados['id']);

            if(empty($dados["img"])):
                $dados["img"] = $dados_produto[0]['prod_foto'];
            elseif(!empty($dados_produto[0]['prod_foto'])):
                unlink($_SERVER['DOCUMENT_ROOT'].'/images/produto/'.$dados_produto[0]['prod_foto']);
            endif;

            $model_update = $model->produtoAlteracao($dados);
            echo $model_update;
        }

        public function alteracao_banner(){
            // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $dados = $id->getParamsAjax();
            $model = new App_Model_produtoModel();
            $dados_produto = $model->produtoSelecionarRef2($dados['id']);
            if(!empty($dados_produto)):
                foreach ($dados_produto as $produto):

                    if(empty($dados["img"])):
                        $dados["img"] = $produto['prod_Banner'];
                    elseif(!empty($produto['prod_Banner'])):
                        unlink($_SERVER['DOCUMENT_ROOT'].'/images/produto/'.$produto['prod_Banner']);
                    endif;
                    $model_update = $model->produtoAlteracaoBanner($dados,$produto['prod_Uid']);
                endforeach;
            endif;
            echo $model_update;
        }

        //DELETAR IMAGEM
        public function img_deletar(){
            // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $ident = $id->getParamsAjax();
            $model = new App_Model_produtoModel();
            $dados_produto = $model->produtoSelecionar($ident['id']);
            unlink($_SERVER['DOCUMENT_ROOT'].'/images/produto/'.$dados_produto[0]['prod_foto']);
            $model_delete = $model->produtoDeletarIMG($dados_produto[0]["prod_id"]);
            echo $model_delete;
        }

        public function img_deletar_banner(){
            // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $ident = $id->getParamsAjax();
            $model = new App_Model_produtoModel();
            $dados_produto = $model->produtoSelecionarRef2($ident['id']);
            foreach ($dados_produto as $produto):
                unlink($_SERVER['DOCUMENT_ROOT'].'/images/produto/'.$produto['prod_Banner']);
                $model_delete = $model->produtoDeletarBanner($produto["prod_Uid"]);
            endforeach;
            echo $model_delete;
        }

        //DELETAR
        public function deletar(){
            // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $ident = $id->getParamsAjax();
            $model = new App_Model_produtoModel();
            $dados_produto = $model->produtoSelecionar($ident['id']);
            unlink($_SERVER['DOCUMENT_ROOT'].'/images/produto/'.$dados_produto[0]['prod_foto']);
            $model_delete = $model->produtoDeletar($ident['id']);
            echo $model_delete;
        }

        /***************     Produto     *********************/
        public function listar(){

            global $start;
            $parm = $start->_params;

            $dados['lang'] = $parm['lang'];

            $languageDEFAULT = unserialize (LANGUAGE_DEFAULT);
            $produto_cad = new App_Model_produtoModel();
            $produto_lista_cad = $produto_cad->listaProdutoDefault($parm['lang']);

            //$categoria = new App_Model_paginacatModel();

            /*DADOS produto*/
            if(!empty($produto_lista_cad)):
              $num = 0;
              foreach ($produto_lista_cad as $prod):
                //$sel_categoria = $categoria->paginaSelectCtrl($prod['prod_Controle'],$prod['prod_Categoria'],$prod['prod_Idioma']);
                //$sel_subcategoria = $categoria->paginaSubcategoria($prod['prod_Controle'],$prod['prod_Categoria'],$prod['prod_Idioma']);
                $arr['prod'][$num]["prod_id"] = $prod['prod_id'];
                $arr['prod'][$num]["prod_nome"] = $prod['prod_nome'];
                $arr['prod'][$num]["prod_foto"] = $prod['prod_foto'];
                $arr['prod'][$num]["prod_controle"] = $prod['prod_controle'];
                $arr['prod'][$num]["prod_idioma"] = $prod['prod_idioma'];
                $arr['prod'][$num]["prod_ordem"] = $prod['prod_ordem'];
                $arr['prod'][$num]["prod_chave"] = $prod['prod_chave'];
                $arr['prod'][$num]["prod_referencia"] = $prod['prod_referencia'];
                /*$arr['prod'][$num]["prod_Categoria"] = $sel_categoria[0]['prod_titulo'];
                $arr['prod'][$num]["prod_CategoriaSlug"] = $sel_categoria[0]['SEO_Titulo'];
                $arr['prod'][$num]["prod_Subcategoria"] = $sel_subcategoria[0]['prod_titulo'];
                $arr['prod'][$num]["prod_SubcategoriaSlug"] = $sel_subcategoria[0]['SEO_Titulo'];*/
                $arr['prod'][$num]["prod_status"] = $prod['prod_status'];
                $num++;
              endforeach;

                $dados['produto'] = $arr['prod'];
                //funcao que chama a view
                $this->View("cadastradoProduto",$dados);
            else:
                //funcao que chama a view
                $this->View("cadastradoProduto");
            endif;
        }

        /***************     CATEGORIA   *********************/
        public function listarcat(){
            global $start;
            $parm = $start->_params;

            $produto = new App_Model_produtoModel();
            $sel_produto = $produto->produtoSelecionarRef($parm['ref'], $parm['lang']);
            $dados['produto'] = $sel_produto;

            $categoria = new App_Model_produtocatModel();
            $sel_categoria = $categoria->produtoSelecionarRef($parm['ref'], $parm['lang']);
            $dados['categoria'] = $sel_categoria;

            $this->View("categoriaProduto", $dados);

            $produto_cad = new App_Model_produtoModel();
            $produto_lista_cad = $produto_cad->listaProduto();
            if(!empty($produto_lista_cad)):
                $dados['produto_lista'] = $produto_lista_cad;
                //echo json_encode($usuario_lista_cad);
                //funcao que chama a view
                $this->View("cadastradoCatProduto",$dados);
            else:
                //funcao que chama a view
                $this->View("cadastradoCatProduto");
            endif;
        }

        /***************     GALERIA     *********************/
        public function galeria(){

            global $start;
            $parm = $start->_params;

            $produto_alt = new App_Model_produtoModel();
            $sel_produto = $produto_alt->produtoSelecionarRef($parm['ref'], $parm['lang']);
            $dados['produto'] = $sel_produto;

            $produto = new App_Model_produtogaleriaModel();
            $sel_produto_galeria = $produto->produtoSelecionarRef($parm['ref'], $parm['lang']);
            $dados['produto_galeria'] = $sel_produto_galeria;

            $this->View("galeriaProduto", $dados);

        }

        public function galeria_inserir(){
            // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $dados = $id->getParamsAjax();

            $languageLIST = unserialize (LANGUAGE_LIST);
            $model = new App_Model_produtogaleriaModel();

            if(!empty($languageLIST)):
                foreach ($languageLIST as $idioma):
                    $arr = array(
                        'img' => $dados['img'],
                        'controller' => $dados['controller'],
                        'idioma' => $idioma["D008_Sigla"],
                        'referencia' => $dados['referencia']
                    );
                    $model_inserir = $model->produtoCadastrar($arr);
                endforeach;
            endif;
            print_r($model_inserir);
        }

        /* DELETAR IMAGEM */
        public function galeria_deletar(){
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $ident = $id->getParamsAjax();
            $model = new App_Model_produtogaleriaModel();
            $dados_produto = $model->produtoSelecionar($ident['id']);
            unlink($_SERVER['DOCUMENT_ROOT'].'/images/produto/galeria/'.$dados_produto[0]['prod_foto']);
            $model_delete = $model->produtoDeletarIMG($dados_produto[0]["prod_foto"]);
            echo $model_delete;
        }

        public function galeria_info(){
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $ident = $id->getParamsAjax();
            $model = new App_Model_produtogaleriaModel();
            $dados_galeria = $model->produtoSelecionar($ident['id']);

            if($dados_galeria[0]['prod_status'] == "true"):
              $checked1 = "checked";
              $checked2 = "";
              $active1 = "active";
              $active2 = "";
            elseif($dados_galeria[0]['prod_status'] == "false"):
              $checked1 = "";
              $checked2 = "checked";
              $active1 = "";
              $active2 = "active";
            endif;

            $html = '
                <div class="widget-header clearfix">
                    <h6 class="title float-left mt-1 text-light" >Informações</h6>
                    <div class="float-right">
                        <a href="javascript:;" id="edit_info_galeria" class="btn btn-dark btn-sm btn-widget-act">Guardar</a>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-12 control-label text-lg-left pt-2">Título</label>
                    <div class="col-lg-12">
                        <input type="text" id="gal_titulo" class="form-control" value="'.$dados_galeria[0]["prod_titulo"].'">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-12 control-label text-lg-left pt-2">Texto Alternativo</label>
                    <div  class="col-lg-12">
                        <input type="text" id="gal_alternativo" class="form-control" value="'.$dados_galeria[0]["prod_alternativo"].'">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 control-label text-sm-right pt-2">Destaque</label>
                    <div class="btn-group col-sm-9 pull-right" data-toggle="buttons">
                        <label class="btn btn-primary '.$active1.' ">
                            <input type="radio" name="gal_destaque" id="option1" autocomplete="off" value="true" '.$checked1.'> Ativo
                        </label>
                        <label class="btn btn-primary '.$active2.' ">
                            <input type="radio" name="gal_destaque" id="option2" autocomplete="off"  value="false" '.$checked2.' > Inativo
                        </label>
                    </div>
                </div>
                <input type="hidden" id="gal_id" value="'.$dados_galeria[0]['prod_id'].'">
            ';

            echo $html;
        }


        /* ALTERAÇÃO */
        public function galeria_info_inserir(){
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();

            $dados = $id->getParamsAjax();
            $model = new App_Model_produtogaleriaModel();
            $model_update = $model->produtoAlteracao($dados);
            echo $model_update;
        }


        /* ORDENAR */
        public function ordenar()

        {

            /* seta o id do cliente */
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $dados = $id->getParamsAjax();
            $ordem = explode(",", $dados["ordem"]);
            $model_fotos = new App_Model_produtogaleriaModel();
            foreach ($ordem as $key => $value):
                $alt_ordem = $model_fotos->produtoOrdenar($key, $value, $dados["idioma"]);
            endforeach;
            /*$model = new App_Model_pacoteModel();
            $model_update = $model->pacoteOrdenar($dados);
            echo $alt_ordem;*/

        }
}
