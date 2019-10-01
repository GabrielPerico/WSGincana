<?php

/**
 * Implementação da API rest usando a biblioteca do link abaixo
 * Essa biblioteca possui quatro arquivos distintos:
 * 1 - REST_Controller na pasta libraries, que altera o comportamento padrão das controllers padrões do CI
 * 2 - REST_Controller_Definitions na pasta libraries, que tras algumas definições para o REST_Controller,
 *     trabalha como um arquivo de padrões auxiliando o controller principal
 * 3 - Format na pasta Libraries, que faz o parsing (conversão) dos diferentes tipos de dados (JSON, XML, CSV e etc)
 * 4 - rest.php na pasta config, para as configurações desta biblioteca
 * 
 * @author      Aluno Gabriel Périco
 * @link        https://github.com/chriskacerguis/codeigniter-restserver
 */
use Restserver\Libraries\REST_Controller;
use Restserver\Libraries\REST_Controller_Definitions;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/REST_Controller_Definitions.php';
require APPPATH . '/libraries/Format.php';

class Pontuacao extends Rest_Controller    {
    public function __construct(){
        parent::__construct();
    }

    public function index_get(){

        $id = $this->get('id');

        if($id > 0){
            $this->load->model('Pontuacao_Model');
            $retorno = $this->Pontuacao_Model->getOne($id);
            if ($retorno != null){
                $this->set_response($retorno, REST_Controller_Definitions::HTTP_OK);
            }else{
                $this->set_response([
                    'status' => false,
                    'message' => 'Pontuacao não cadastrado!'
                ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
                return;
            }
        }else{
            $this->load->model('Pontuacao_Model');
            $retorno = $this->Pontuacao_Model->getAll();
    
            $this->set_response($retorno, REST_Controller_Definitions::HTTP_OK);
        }
    }

    public function index_post(){
        if(!$this->post('id_equipe') || !$this->post('id_prova') || !$this->post('id_usuario') || !$this->post('pontos') || !$this->post('data_hora')) {
            $this->set_response([
                'status' => false,
                'error' => 'Campos não preenchidos'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }
        $this->load->model('Pontuacao_Model');

        $id_equipe = $this->Pontuacao_Model->getEquipe($this->post('id_equipe'));
        $id_usuario = $this->Pontuacao_Model->getUsuario($this->post('id_usuario'));
        $id_prova = $this->Pontuacao_Model->getEquipe($this->post('id_prova'));

        if(!$id_equipe && !$id_prova && !$id_usuario){
        $this->set_response([
            'status' => false,
            'message' => 'Campos de id invalido!'
        ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
        return;
        }else{
            $data = array(
                'id_equipe' => $this->post('id_equipe'),
                'id_prova' => $this->post('id_prova'),
                'id_usuario' => $this->post('id_usuario'),
                'pontos' => $this->post('pontos'),
                'data_hora' => $this->post('data_hora')
            );
        }

        if($this->Pontuacao_Model->insert($data)){
            $this->set_response([
                'status' => true,
                'message' => 'Pontuacao inserida com sucesso!'
            ], REST_Controller_Definitions::HTTP_OK);
        }else{
            $this->set_response([
                'status' => false,
                'message' => 'Falha ao inserir pontuacao!'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
        }
    }
    

    public function index_delete(){
        $id = $this->get('id');

        if($id > 0){
            $this->load->model("Pontuacao_Model");
            if($this->Pontuacao_Model->delete($id)){
                $this->set_response([
                    'status' => true,
                    'message' => 'Pontuacao deletada com sucesso!'
                ], REST_Controller_Definitions::HTTP_OK);
                return;
            }else{
                $this->set_response([
                    'status' => false,
                    'message' => 'Falha ao deletar pontuacao!'
                ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
                return;
            }
            
        }else{
            $this->set_response([
                'status' => false,
                'message' => 'Pontuacao invalida!'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }

    }

    public function index_put(){
        $id = (int) $this->get('id');
        if ($id > 0){
            $this->load->model('Pontuacao_Model');
            if(!$this->put('id_equipe') || !$this->put('id_prova') || !$this->put('id_usuario') || !$this->put('pontos') || !$this->put('data_hora')) {
                $this->set_response([
                    'status' => false,
                    'error' => 'Campos não preenchidos'
                ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
                return;
            }

            $id_equipe = $this->Pontuacao_Model->getEquipe($this->put('id_equipe'));
            $id_prova = $this->Pontuacao_Model->getProva($this->put('id_prova'));
            $id_usuario = $this->Pontuacao_Model->getUsuario($this->put('id_usuario'));
    
            if(!$id_equipe && !$id_prova && !$id_usuario){
                $this->set_response([
                    'status' => true,
                    'message' => 'Campos de id invalido!'
                ], REST_Controller_Definitions::HTTP_BAD_REQUEST); 
                return;  
            }else{
                
                $data = array(
                    'id_equipe' => $this->put('id_equipe'),
                    'id_prova' => $this->put('id_prova'),
                    'id_usuario' => $this->put('id_usuario'),
                    'pontos' => $this->put('pontos'),
                    'data_hora' => $this->put('data_hora')
                );
            }
            if($this->Pontuacao_Model->alter($id,$data)){
                $this->set_response([
                    'status' => true,
                    'message' => 'Pontuacao alterada com sucesso!'
                ], REST_Controller_Definitions::HTTP_OK);
            }else{
                $this->set_response([
                    'status' => true,
                    'message' => 'Falha ao alterar pontuacao!'
                ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            }
        }else{
            $this->set_response([
                'status' => false,
                'message' => 'Pontuacao invalida!'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }
    }
}