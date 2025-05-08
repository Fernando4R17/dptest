<?php
require_once __DIR__ . '/MenuController.php';

$controller = new MenuController();

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        $controller->index();
    }
} else {
    $controller->index();
} 