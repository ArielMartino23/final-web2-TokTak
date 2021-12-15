<?php

// VIDEO(id: int; titulo: string, oculto: boolean, id_usuario: int)
// USUARIO(id: int; nombre: string, email: string)
// VALORACION(id: int; id_video: int, id_usuario: int, valoracion: int)

class VideoModel{
    
    private $db;

    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;'.'dbname=db_toktak;charset=utf8', 'root', '');
    }

    public function getVideo($id_video){
        $query = $this->db->prepare("SELECT * FROM video WHERE id = ?");
        $query->execute([$id_video]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function getVideos(){
        $query = $this->db->prepare("SELECT * FROM video");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function ocultarVideo($id_video){
        $query = $this->db->prepare("UPDATE video SET oculto = 1 WHERE id = ?");
        $query->execute([$id_video]);
    }

    public function updateVideo($id_video){
        
    }
}