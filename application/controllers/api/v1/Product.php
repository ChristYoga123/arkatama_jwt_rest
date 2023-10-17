<?php

/* Table structure for table `products` */
// CREATE TABLE `products` (
//   `id` int(10) UNSIGNED NOT NULL,
//   `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
//   `price` double NOT NULL,
//   `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
//   `updated_at` datetime DEFAULT NULL
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
// ALTER TABLE `products` ADD PRIMARY KEY (`id`);
// ALTER TABLE `products` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1; COMMIT;

/**
 * Product class.
 * 
 * @extends REST_Controller
 */
   require APPPATH . '/libraries/REST_Controller.php';
   use Restserver\Libraries\REST_Controller;
     
class Product extends REST_Controller {
    
	  /**
     * CONSTRUCTOR | LOAD MODEL
     *
     * @return Response
    */
    public function __construct() {
       parent::__construct();
       $this->load->library('Authorization_Token');	
       $this->load->model('Product_model');
    }
       
    /**
     * SHOW | GET method.
     *
     * @return Response
    */
	public function index_get()
	{
        $headers = $this->input->request_headers(); 
        if (isset($headers['Authorization'])) {
            $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
            if ($decodedToken['status'])
            {
                // ------- Main Logic part -------
                $data = $this->Product_model->all();
                $final["message"] = "Berhasil mendapatkan seluruh data produk";
                $final["data"] = $data;
                $this->response($final, REST_Controller::HTTP_OK);
                // ------------- End -------------
            } 
            else {
                $this->response($decodedToken);
            }
        } else {
            $final["message"] = "Tidak ada token";
            $this->response($final, REST_Controller::HTTP_BAD_REQUEST);
        }
	}

    public function detail_get($id)
    {
        $headers = $this->input->request_headers(); 
        if (isset($headers['Authorization'])) {
            $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
            if ($decodedToken['status'])
            {
                // ------- Main Logic part -------
                $data = $this->Product_model->show($id);
                if($data)
                {
                    $final["message"] = "Berhasil mendapatkan data product";
                    $final["data"] = $data;
                    $this->response($final, REST_Controller::HTTP_OK);
                } else{
                    $final["message"] = "ID tidak ditemukan";
                    $final["data"] = $data;
                    $this->response($final, REST_Controller::HTTP_NOT_FOUND);
                }
                // ------------- End -------------
            } 
            else {
                $this->response($decodedToken);
            }
        } else {
            $final["message"] = "Tidak ada token";
            $this->response($final, REST_Controller::HTTP_BAD_REQUEST);
        }
    }
      
    /**
     * INSERT | POST method.
     *
     * @return Response
    */
    public function index_post()
    {
        $data = json_decode(trim(file_get_contents('php://input')), true);
        $_POST = $data;
        $headers = $this->input->request_headers(); 
		if (isset($headers['Authorization'])) {
			$decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
            if ($decodedToken['status'])
            {
                // set validation rules
                $this->form_validation->set_rules('product_name', 'Nama', 'required');                
                $this->form_validation->set_rules('category_id', 'Kategori', 'required|numeric');                
                $this->form_validation->set_rules('price', 'Harga', 'required|numeric');                
                $this->form_validation->set_rules('quantity', 'Kuantitas', 'required|numeric');                
                $this->form_validation->set_rules('description', 'Deskripsi', 'required');                
                if ($this->form_validation->run() == false) {
                    
                    // validation not ok, send validation errors to the view
                    $final["message"] = "Validasi post produk gagal";
                    $final["error"] = $this->form_validation->error_array();
                    $this->response($final, REST_Controller::HTTP_BAD_REQUEST);

                } else {
                    // ------- Main Logic part -------
                    $result = $this->Product_model->insert($data);
                    $final["message"] = "Post kategori sukses";
                    $final["data"] = $result;
            
                    $this->response($final, REST_Controller::HTTP_CREATED);
                    // ------------- End -------------
                }
            }
            else {
                $this->response($decodedToken);
            }
		}
		else {
            $final["message"] = "Tidak ada token";
            $this->response($final, REST_Controller::HTTP_BAD_REQUEST);
		}
    } 
     
    /**
     * UPDATE | PUT method.
     *
     * @return Response
    */
    public function index_put($id)
    {
        $headers = $this->input->request_headers(); 
		if (isset($headers['Authorization'])) {
			$decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
            if ($decodedToken['status'])
            {
                // ------- Main Logic part -------
                $res = $this->Product_model->show($id);
                if($res)
                {
                    $data = $this->put();
                    $result = $this->Product_model->update($data, $id);
                    $final["message"] = "Update produk sukses";
                    $final["data"] = $result;
            
                    $this->response($final, REST_Controller::HTTP_OK);
                } else 
                {
                    $final["message"] = "ID tidak ditemukan";
                    $final["data"] = null;
            
                    $this->response($final, REST_Controller::HTTP_NOT_FOUND);
                }
                // ------------- End -------------
            }
            else {
                $this->response($decodedToken);
            }
		}
		else {
			$final["message"] = "Tidak ada token";
            $this->response($final, REST_Controller::HTTP_BAD_REQUEST);
		}
    }
     
    /**
     * DELETE method.
     *
     * @return Response
    */
    public function index_delete($id)
    {
        
        $headers = $this->input->request_headers(); 
		if (isset($headers['Authorization'])) {
			$decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
            if ($decodedToken['status'])
            {
                // ------- Main Logic part -------
                $res = $this->Product_model->show($id);
                if($res)
                {
                    $this->Product_model->delete($id);
                    $final["message"] = "Delete produk sukses";
                    $this->response($final, REST_Controller::HTTP_OK);
                } else 
                {
                    $final["message"] = "ID tidak ditemukan";
                    $final["data"] = null;
            
                    $this->response($final, REST_Controller::HTTP_NOT_FOUND);
                }
                // ------------- End -------------
            }
            else {
                $this->response($decodedToken);
            }
		}
		else {
			$final["message"] = "Tidak ada token";
            $this->response($final, REST_Controller::HTTP_BAD_REQUEST);
		}
    }
    	
}