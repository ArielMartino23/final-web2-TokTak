<?php
require_once "ValoracionModel.php";
require_once "ValoracionView.php";
require_once "AuthHelper.php";

class ValoracionController{
    private $valoracionModel;
    private $videoModel;
    private $usuarioModel;
    private $view;
    private $authHelper;

    public function __construct() {
        $this->valoracionModel = new ValoracionModel();
        $this->videoModel = new VideoModel();
        $this->usuarioModel = new UsuarioModel();
        //$this->view = new ValoracionView();
        $this->authHelper = new AuthHelper();
    }

    public function addValoracion(){
        $this->authHelper->verifyLogin();
        $idVideo = $_POST["idVideo"];
        $idUsuario = $_POST["idUsuario"];

        $video = $this->videoModel->getVideo($idVideo);

        if ($video) {
            if ($video->id_usuario != $idUsuario){
                if (!$this->videoYaVotado($idVideo)){
                    if ( !empty($_POST["idVideo"]) && !empty($_POST["idUsuario"])  
                        && !empty($_POST["valoracion"]) ){
                            $this->valoracionModel->addValoracion($_POST["idVideo"], $_POST["idUsuario"], $_POST["valoracion"]);
                    }else {
                        $this->view->showMensaje("Los datos son incorrectos");
                    }
                } else {
                    $this->view->showMensaje("Ya votaste este video");
                }
            }else {
                $this->view->showMensaje("No puedes votar tu propio video");
            }
        }
    }

    private function videoYaVotado($idVideo){
        $valoracion = $this->valoracionModel->getValoracionPorVideo($idVideo);
        if (!empty($valoracion) && $valoracion[0]->id_video == $idVideo){
            return true;
        }

        return false;
    }

    public function ocultarVideos(){
        $this->authHelper->verifyLogin();

        if ($this->authHelper->esAdmin()){
            $valoracionPost = $_POST["valoracion"];
            $videos = $this->videoModel->getVideos();

            if (!empty($videos) && !empty($valoracionPost)){
                foreach ($videos as $video) {
                    $valoracion = $this->valoracionModel->getValoracionPromedioVideo($video->id);
                    if ($valoracion->promedio < $valoracionPost){
                        $this->videoModel->ocultarVideo($video->id);
                    }
                }
            }else {
                $this->view->showMensaje("No hay videos o faltan completar campos");
            }
        }else {
            $this->view->showMensaje("No tenes permiso de realizar esta accion");
        }
    }

    public function resumenResultados(){
        $this->authHelper->verifyLogin();
        $idVideo = $_POST["idVideo"];
        $video = $this->videoModel->getVideo($idVideo);

        if ($video){
            $valoracionPromedio = $this->valoracionModel->getValoracionPromedioVideo($idVideo);
            $autorVideo = $this->usuarioModel->getUsuario($video->id_usuario);

            if ($valoracionPromedio){
                $valoraciones = $this->valoracionModel->getValoracionPorVideo($idVideo);
                $this->view->showDetallesVideo($video->titulo, $autorVideo, $valoracionPromedio, $valoraciones);
            }else {
                $this->view->showMensaje("El video no tiene valoraciones asociadas");
            }
        }else {
            $this->view->showMensaje("El video no existe");
        }
    }
}
