<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Mahasiswa extends REST_Controller
{
    public function __construct($config='rest')
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        parent::__construct();
        $this->load->model('Model_mahasiswa', 'mahasiswa');
        // $this->methods['METHOD_NAME']['limit'] = [NUM_REQUESTS_PER_HOUR];
        $this->methods['index_get']['limit'] = 100000;


    }

    public function index_get()
    {
        $id = $this->get('id');
        if($id === null){
            $mahasiswa = $this->mahasiswa->getMahasiswa();
        }else{
            $mahasiswa = $this->mahasiswa->getMahasiswa($id);
        }
        
        if($mahasiswa){
            $this->response([
                'status' => true,
                'data' => $mahasiswa
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                'status' => false,
                'message' => 'id not found'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function index_delete()
    {
        $id = $this->delete('id');
        if($id === null){
            $this->response([
                'status' => false,
                'message' => 'provide an id.'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }else{
            if($this->mahasiswa->deleteMahasiswa($id) > 0){
                $this->response([
                    'status' => true,
                    'id' => $id,
                    'message' => 'deleted.'
                ], REST_Controller::HTTP_NO_CONTENT);
            }else{
                $this->response([
                    'status' => false,
                    'message' => 'id not found.'
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
    }

    public function index_post()
    {
        $data = [
            'nrp'=>$this->post('nrp'),
            'nama'=>$this->post('nama'),
            'email'=>$this->post('email'),
            'jurusan'=>$this->post('jurusan')
        ];
        
        if($this->mahasiswa->createMahasiswa($data)>0){
            $this->response([
                'status' => true,
                'message' => 'Data Mahasiswa has been created.'
            ], REST_Controller::HTTP_CREATED);
        }else{
            $this->response([
                'status' => true,
                'message' => 'Failed to create new data.'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function index_put()
    {
        $data = [
            'nrp'=>$this->put('nrp'),
            'nama'=>$this->put('nama'),
            'email'=>$this->put('email'),
            'jurusan'=>$this->put('jurusan')
        ];

        $id = $this->put('id');
        if($this->mahasiswa->updateMahasiswa($data, $id)>0){
            $this->response([
                'status' => true,
                'message' => 'Data Mahasiswa has been updated.'
            ], REST_Controller::HTTP_NO_CONTENT);
        }else{
            $this->response([
                'status' => true,
                'message' => 'Failed to update data.'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

}
