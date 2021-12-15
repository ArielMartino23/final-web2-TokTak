<?php
require_once "./Model/ValoracionModel.php";
require_once "./Model/VideoModel.php";
require_once "./View/ApiView.php";

class ApiValoracionesController {
    
    private $valoracionModel;
    private $videoModel;
    private $view;
    private $data;

    public function __construct() {
        $this->valoracionModel = new ValoracionModel();
        $this->videoModel = new VideoModel();
        $this->view = new ApiView();
        $this->data = file_get_contents("php://input");
    }

    public function getData(){
        return json_decode($this->data);
    }

    public function getValoracionesVideo($params = null){
        $idVideo = $params[":ID"];
        $body = $this->getData();
        $video = $this->videoModel->getVideo($idVideo);

        if($video){
            $valoraciones = $this->valoracionModel->getValoracionPorVideo($idVideo);
            return $this->view->response($valoraciones, 200);
        }else {
            return $this->view->response("No existe el video con el id=$idVideo", 404);
        }
    }
}
