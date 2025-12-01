<?php

require_once __DIR__ . '/../models/Categoria.php';

class CategoriaController {

    public function index() {
        $categoria = new Categoria();
        echo json_encode($categoria->readAll());
    }

    public function show($id) {
        $categoria = new Categoria();
        echo json_encode($categoria->read($id));
    }

    public function store() {
        $data = json_decode(file_get_contents("php://input"), true);
        $categoria = new Categoria();

        $id = $categoria->create($data);
        echo json_encode(["id" => $id, "status" => "ok"]);
    }

    public function update($id) {
        $data = json_decode(file_get_contents("php://input"), true);
        $categoria = new Categoria();

        $categoria->update($id, $data);
        echo json_encode(["status" => "ok"]);
    }

    public function delete($id) {
        $categoria = new Categoria();
        $categoria->delete($id);

        echo json_encode(["status" => "ok"]);
    }
}
