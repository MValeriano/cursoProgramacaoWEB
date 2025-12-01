<?php

require_once __DIR__ . '/../models/Produto.php';

class ProdutoController {

    public function index() {
        $produto = new Produto();
        echo json_encode($produto->readAll());
    }

    public function show($id) {
        $produto = new Produto();
        echo json_encode($produto->read($id));
    }

    public function store() {
        $data = json_decode(file_get_contents("php://input"), true);
        $produto = new Produto();

        $id = $produto->create($data);
        echo json_encode(["id" => $id, "status" => "ok"]);
    }

    public function update($id) {
        $data = json_decode(file_get_contents("php://input"), true);
        $produto = new Produto();

        $produto->update($id, $data);
        echo json_encode(["status" => "ok"]);
    }

    public function delete($id) {
        $produto = new Produto();
        $produto->delete($id);

        echo json_encode(["status" => "ok"]);
    }
}
