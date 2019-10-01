<?php


class Usuario_Model extends CI_Model{



    public function getAll(){
        $query = $this->db->get('usuario');
        return $query->result();
    }

    public function getOne($id){
        if($id > 0){
            $this->db->where('id =',$id);
            $query = $this->db->get('usuario');
            return $query->row();
        }
    }

    public function insert($data = array()){
        $this->db->insert('usuario',$data);
        return $this->db->affected_rows();
    }

    public function delete($id){
        if($id > 0){
            $this->db->where('id =',$id);
            $this->db->delete('usuario');
            return $this->db->affected_rows();
        }
    }

    public function alter($id,$data = array()){
        if($id > 0){
            $this->db->where('id =',$id);
            $this->db->update('usuario',$data);
            return $this->db->affected_rows();
        }
    }
}