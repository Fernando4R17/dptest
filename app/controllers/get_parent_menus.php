<?php
require_once __DIR__ . '/../models/Menu.php';

header('Content-Type: application/json');

$menuModel = new Menu();
$parentMenus = $menuModel->getAllParentMenus();

echo json_encode($parentMenus); 