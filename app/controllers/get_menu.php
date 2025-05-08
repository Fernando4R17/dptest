<?php
require_once __DIR__ . '/../models/Menu.php';

header('Content-Type: application/json');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'ID is required'
    ]);
    exit;
}

$menuModel = new Menu();
$menuData = $menuModel->getMenuById($_GET['id']);

if (!$menuData) {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => 'Menu not found'
    ]);
    exit;
}

// Check if menu has children
$hasChildren = $menuModel->hasChildren($_GET['id']);

// Add has_children to the response
$menuData['has_children'] = $hasChildren;

echo json_encode([
    'success' => true,
    'menu' => $menuData
]); 