<?php


class Pontuacao_Model extends CI_Model{

    public function getAll(){
        $query = $this->db->get('pontuacao');
        return $query->result();
    }

    public function getOne($id){
        if ($id > 0){
            $this->db->where('id =',$id);
            $query = $this->db->get('pontuacao');
            return $query->row();
        }
    }

    public function insert($data = array()){
        $this->db->insert('pontuacao',$data);
        return $this->db->affected_rows();
    }

    public function delete($id){
        if($id > 0){
            $this->db->where('id =',$id);
            $this->db->delete('pontuacao');
            return $this->db->affected_rows();
        }
    }

    public function alter($id,$data = array()){
        if ($id > 0){
            $this->db->where('id =',$id);
            $this->db->update('pontuacao',$data);
            return $this->db->affected_rows();
        }
    }

    public function getEquipe($id){
        if($id > 0){
            $this->db->where('id =',$id);
            $query = $this->db->get('equipe');
            return $query->row();
        }
    }

    public function getProva($id){
        if($id > 0){
            $this->db->where('id =',$id);
            $query = $this->db->get('prova');
            return $query->row();
        }
    }

    public function getUsuario($id){
        if ($id > 0){
            $this->db->where('id =',$id);
            $query = $this->db->get('usuario');
            return $query->row();
        }
    }
}