<?php


class Prova_Model extends CI_Model{

    public function getAll(){
        $query = $this->db->get('prova');
        return $query->result();
    }

    public function getOne($id){
        if($id > 0){
            $this->db->where('id =',$id);
            $query = $this->db->get('prova');
            return $query->row();
        }
    }

    public function insert($data = array()){
        $this->db->insert('prova',$data);
        return $this->db->affected_rows();
    }

    public function delete($id){
        if($id > 0){
            $this->db->where('id =',$id);
            $this->db->delete('prova');
            return $this->db->affected_rows();
        }
    }

    public function alter($id,$data = array()){
        if($id > 0){
            $this->db->where('id =',$id);
            $this->db->update('prova',$data);
            return $this->db->affected_rows();
        }
    }
} 