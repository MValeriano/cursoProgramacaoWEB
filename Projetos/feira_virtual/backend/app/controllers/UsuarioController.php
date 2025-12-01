<?php

require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController {

    public function index() {
        $usuario = new Usuario();
        echo json_encode($usuario->readAll());
    }

    public function show($id) {
        $usuario = new Usuario();
        echo json_encode($usuario->read($id));
    }

    public function store() {
        $data = json_decode(file_get_contents("php://input"), true);
        $usuario = new Usuario();
        $id = $usuario->create($data);

        echo json_encode(["id" => $id, "status" => "ok"]);
    }

    public function update($id) {
        $data = json_decode(file_get_contents("php://input"), true);
        $usuario = new Usuario();

        $usuario->update($id, $data);
        echo json_encode(["status" => "ok"]);
    }

    public function delete($id) {
        $usuario = new Usuario();
        $usuario->delete($id);

        echo json_encode(["status" => "ok"]);
    }
}