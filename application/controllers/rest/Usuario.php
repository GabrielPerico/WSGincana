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

class Usuario extends Rest_Controller    {
    public function __construct(){
        parent::__construct();
    }

    public function index_get(){

        $id = $this->get('id');

        if($id > 0){
            $this->load->model('Usuario_Model');
            $retorno = $this->Usuario_Model->getOne($id);
            if ($retorno != null){
                $this->set_response($retorno, REST_Controller_Definitions::HTTP_OK);
            }else{
                $this->set_response([
                    'status' => false,
                    'message' => 'Usuario não cadastrado!'
                ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
                return;
            }
        }else{
            $this->load->model('Usuario_Model');
            $retorno = $this->Usuario_Model->getAll();
    
            $this->set_response($retorno, REST_Controller_Definitions::HTTP_OK);
        }
    }

    public function index_post(){
        if(!$this->post('nome') || !$this->post('senha') || !$this->post('nivel')) {
            $this->set_response([
                'status' => false,
                'error' => 'Campos não preenchidos'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }

        $data = array(
            'nome' => $this->post('nome'),
            'senha' => sha1(sha1(sha1($this->post('senha')))),
            'nivel' => $this->post('nivel')
        );

        $this->load->model('Usuario_Model');
        if($this->Usuario_Model->insert($data)){
            $this->set_response([
                'status' => true,
                'message' => 'Usuario inserido com sucesso!'
            ], REST_Controller_Definitions::HTTP_OK);
        }else{
            $this->set_response([
                'status' => false,
                'message' => 'Falha ao inserir usuario!'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
        }
    }

    public function index_delete(){
        $id = $this->get('id');

        if($id > 0){
            $this->load->model("Usuario_Model");
            if($this->Usuario_Model->delete($id)){
                $this->set_response([
                    'status' => true,
                    'message' => 'Usuario deletado com sucesso!'
                ], REST_Controller_Definitions::HTTP_OK);
                return;
            }else{
                $this->set_response([
                    'status' => false,
                    'message' => 'Falha ao deletar usuario!'
                ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
                return;
            }
            
        }else{
            $this->set_response([
                'status' => false,
                'message' => 'Usuario invalida!'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }

    }

    public function index_put(){
        $id = (int) $this->get('id');
        if ($id > 0){
            $this->load->model('Usuario_Model');
            if(!$this->put('nome') || !$this->put('senha') || !$this->put('nivel')) {
                $this->set_response([
                    'status' => false,
                    'error' => 'Campos não preenchidos'
                ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
                return;
            }
            $data = array(
                'nome' => $this->put('nome'),
                'senha' => sha1(sha1(sha1($this->put('senha')))),
                'nivel' => $this->put('nivel')
            );

            if($this->Usuario_Model->alter($id,$data)){
                $this->set_response([
                    'status' => true,
                    'message' => 'Usuario alterado com sucesso!'
                ], REST_Controller_Definitions::HTTP_OK);
            }else{
                $this->set_response([
                    'status' => true,
                    'message' => 'Falha ao alterar usuario!'
                ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            }
        }else{
            $this->set_response([
                'status' => false,
                'message' => 'Usuario invalido!'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            return;
        }
    }
}