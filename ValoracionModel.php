<?php

// VIDEO(id: int; titulo: string, oculto: boolean, id_usuario: int)
// USUARIO(id: int; nombre: string, email: string)
// VALORACION(id: int; id_video: int, id_usuario: int, valoracion: int)

class ValoracionModel{
    
    private $db;

    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;'.'dbname=db_toktak;charset=utf8', 'root', '');
    }

    public function addValoracion($id_video, $id_usuario, $valoracion){
        $query = $this->db->prepare("INSERT INTO valoracion (id_video, id_usuario, valoracion)
        VALUES (?, ?, ?)");
        $query->execute([$id_video, $id_usuario, $valoracion]);
    }

    public function getValoracionPorVideo($id_video){
        $query = $this->db->prepare("SELECT * FROM valoracion WHERE id_video = ?");
        $query->execute([$id_video]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function getValoracionPromedioVideo($id_video){
        $query = $this->db->prepare("SELECT AVG(valoracion) AS promedio
            FROM valoracion WHERE id_video = ?");

        $query->execute([$id_video]);
        return $query->fetch(PDO::FETCH_OBJ);
    }
}
