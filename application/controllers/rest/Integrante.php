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

class Integrante extends Rest_Controller    {
    public function __construct(){
        parent::__construct();
    }

    public function index_get(){

        $id = $this->get('id');

        if($id > 0){
            $this->load->model('Integrante_Model');
            $retorno = $this->Integrante_Model->getOne($id);
            if ($retorno != null){
                $this->set_response($retorno, REST_Controller_Definitions::HTTP_OK);
            }else{
                $this->set_response([
                    'status' => false,
                    'message' => 'Integrante não cadastrado!'
                ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
                return;
            }
        }else{
            $this->load->model('Integrante_Model');
            $retorno = $this->Integrante_Model->getAll();
    
            $this->set_response($retorno, REST_Controller_Definitions::HTTP_OK);
        }
    }

    public function index_post(){
        if(!$this->post('nome') || !$this->post('rg') || !$this->post('cpf') || !$this->post('data_nasc') || !$this->post('id_equipe')) {
            $this->set_response([
                'status' => false,
                'error' => 'Campos não preenchidos'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }
        $this->load->model('Integrante_Model');

        $id_equipe = $this->Integrante_Model->getEquipe($this->post('id_equipe'));

        if($id_equipe > 0){

            
            $data = array(
                'nome' => $this->post('nome'),
                'rg' => $this->post('rg'),
                'cpf' => $this->post('cpf'),
                'data_nasc' => $this->post('data_nasc'),
                'id_equipe' => $this->post('id_equipe')
            );
        }

        if($this->Integrante_Model->insert($data)){
            $this->set_response([
                'status' => true,
                'message' => 'Integrante inserido com sucesso!'
            ], REST_Controller_Definitions::HTTP_OK);
        }else{
            $this->set_response([
                'status' => false,
                'message' => 'Falha ao inserir integrante!'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
        }
    }

    public function index_delete(){
        $id = $this->get('id');

        if($id > 0){
            $this->load->model("Integrante_Model");
            if($this->Integrante_Model->delete($id)){
                $this->set_response([
                    'status' => true,
                    'message' => 'Integrante deletado com sucesso!'
                ], REST_Controller_Definitions::HTTP_OK);
                return;
            }else{
                $this->set_response([
                    'status' => false,
                    'message' => 'Falha ao deletar integrante!'
                ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
                return;
            }
            
        }else{
            $this->set_response([
                'status' => false,
                'message' => 'Integrante invalida!'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }

    }

    public function index_put(){
        $id = (int) $this->get('id');
        if ($id > 0){
            $this->load->model('Integrante_Model');
            if(!$this->put('nome') || !$this->put('rg') || !$this->put('cpf') || !$this->put('data_nasc') || !$this->put('id_equipe')) {
                $this->set_response([
                    'status' => false,
                    'error' => 'Campos não preenchidos'
                ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
                return;
            }

            $id_equipe = $this->Integrante_Model->getEquipe($this->put('id_equipe'));
    
            if($id_equipe > 0){
            $data = array(
                'nome' => $this->put('nome'),
                'rg' => $this->put('rg'),
                'cpf' => $this->put('cpf'),
                'data_nasc' => $this->put('data_nasc'),
                'id_equipe' => $this->put('id_equipe')
            );
        }
            if($this->Integrante_Model->alter($id,$data)){
                $this->set_response([
                    'status' => true,
                    'message' => 'Integrante alterado com sucesso!'
                ], REST_Controller_Definitions::HTTP_OK);
            }else{
                $this->set_response([
                    'status' => true,
                    'message' => 'Falha ao alterar integrante!'
                ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            }
        }else{
            $this->set_response([
                'status' => false,
                'message' => 'Integrante invalido!'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }
    }
}