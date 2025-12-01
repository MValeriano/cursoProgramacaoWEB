<?php

require_once __DIR__ . '/../models/Favorito.php';

class FavoritoController {

    public function index() {
        $favorito = new Favorito();
        echo json_encode($favorito->readAll());
    }

    public function show($id) {
        $favorito = new Favorito();
        echo json_encode($favorito->read($id));
    }

    public function store() {
        $data = json_decode(file_get_contents("php://input"), true);
        $favorito = new Favorito();

        $id = $favorito->create($data);
        echo json_encode(["id" => $id, "status" => "ok"]);
    }

    public function update($id) {
        $data = json_decode(file_get_contents("php://input"), true);
        $favorito = new Favorito();

        $favorito->update($id, $data);
        echo json_encode(["status" => "ok"]);
    }

    public function delete($id) {
        $favorito = new Favorito();
        $favorito->delete($id);

        echo json_encode(["status" => "ok"]);
    }
}
