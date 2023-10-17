<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model {
  
    /**
     * CONSTRUCTOR | LOAD DB
    */
    public function __construct() {
       parent::__construct();
       $this->load->database();
    }

    /**
     * SHOW | GET method.
     *
     * @return Response
    */

    public function all()
    {
        $data = $this->db->get("categories");
        return $data->result();
    }

	public function show($id)
	{
        $query = $this->db->get_where("categories", ['category_id' => $id])->row_array();
        return $query;
	}
      
    /**
     * INSERT | POST method.
     *
     * @return Response
    */
    public function insert($data)
    {
        $this->db->insert('categories',$data);
        $data = $this->db->get_where("categories", ["category_id" => $this->db->insert_id()]);
        return $data->row_array();
    } 
     
    /**
     * UPDATE | PUT method.
     *
     * @return Response
    */
    public function update($data, $id)
    {
        $data = $this->db->update('categories', $data, array('category_id'=>$id));
        //echo $this->db->last_query();
		$data = $this->db->get_where("categories", ["category_id" => $id])->row_array();
        return $data;
    }
     
    /**
     * DELETE method.
     *
     * @return Response
    */
    public function delete($id)
    {
        $this->db->delete('categories', array('category_id'=>$id));
        return $this->db->affected_rows();
    }
}
