<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Menu.php';

class MenuController extends Controller {
    private $menuModel;
    
    public function __construct() {
        $this->menuModel = new Menu();
    }
    
    public function index() {
        require_once __DIR__ . '/../views/layouts/main.php';
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->index();
            return;
        }
        
        $data = [
            'id_parent' => !empty($_POST['id_parent']) ? $_POST['id_parent'] : null,
            'status' => 1,
            'name' => $_POST['name'],
            'description' => $_POST['description']
        ];
        
        $result = $this->menuModel->createMenu($data);
        
        if ($result) {
            $_SESSION['success'] = "Menú creado exitosamente.";
        } else {
            $_SESSION['error'] = "Error al crear el menú.";
        }
        
        header('Location: /dportenis/public');
    }
    
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->index();
            return;
        }
        
        $data = [
            'id_parent' => !empty($_POST['id_parent']) ? $_POST['id_parent'] : null,
            'status' => 1,
            'name' => $_POST['name'],
            'description' => $_POST['description']
        ];
        
        $result = $this->menuModel->updateMenu($_POST['id_menu'], $data);
        
        if ($result) {
            $_SESSION['success'] = "Menú actualizado exitosamente.";
        } else {
            $_SESSION['error'] = "Error al actualizar el menú.";
        }
        
        header('Location: /dportenis/public');
    }
    
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id_menu'])) {
            $this->index();
            return;
        }
        
        $id_menu = $_POST['id_menu'];
        
        if ($this->menuModel->hasChildren($id_menu)) {
            $_SESSION['error'] = "No se puede eliminar porque tiene elementos hijos.";
            header('Location: /dportenis/public');
            return;
        }
        
        $result = $this->menuModel->updateStatus($id_menu, 0);
        
        if ($result) {
            $_SESSION['success'] = "Menú desactivado exitosamente.";
        } else {
            $_SESSION['error'] = "Error al desactivar el menú.";
        }
        
        header('Location: /dportenis/public');
    }
} 