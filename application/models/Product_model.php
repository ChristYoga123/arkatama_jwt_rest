<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model {
  
    /**
     * CONSTRUCTOR | LOAD DB
    */
    public function __construct() {
       parent::__construct();
       $this->load->database();
    }

    public function all()
    {
        $data = $this->db->get("products");
        return $data->result();
    }

    /**
     * SHOW | GET method.
     *
     * @return Response
    */
	public function show($id)
	{
        $query = $this->db->get_where("products", ['product_id' => $id])->row_array();
        return $query;
	}
      
    /**
     * INSERT | POST method.
     *
     * @return Response
    */
    public function insert($data)
    {
        $this->db->insert('products',$data);
        $data = $this->db->get_where("products", ["product_id" => $this->db->insert_id()])->row_array();
        return $data; 
    } 
     
    /**
     * UPDATE | PUT method.
     *
     * @return Response
    */
    public function update($data, $id)
    {
        $data = $this->db->update('products', $data, array('product_id' => $id));
        //echo $this->db->last_query();
		$data = $this->db->get_where("products", ["product_id" => $id])->row_array();
        return $data;
    }
     
    /**
     * DELETE method.
     *
     * @return Response
    */
    public function delete($id)
    {
        $this->db->delete('products', array('product_id'=>$id));
        return $this->db->affected_rows();
    }
}
