<?php
require_once "./Model/VideoModel.php";
require_once "./View/ApiView.php";

class ApiVideoController {
    
    private $model;
    private $view;
    private $data;

    public function __construct() {
        $this->model = new VideoModel();
        $this->view = new ApiView();
        $this->data = file_get_contents("php://input");
    }

    function getData(){
        return json_decode($this->data);
    }

    public function editarTituloVideo($params = null){
        $idVideo = $params[":ID"];
        $body = $this->getData();
        $video = $this->model->getVideo($idVideo);

        if ($video && !empty($body)){
            try {
                $this->model->updateVideo($idVideo, $body->titulo);
                return $this->view->response("El video se actualizó correctamente", 200);
            } catch (\Throwable $th) {
                return $this->view->response("El video no se pudo editar", 500);
            }
        }else {
            return $this->view->response("El video con el id=$idVideo no existe", 404);
        }
    }

    
}
