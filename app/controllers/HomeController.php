<?php

require_once __DIR__ . '/Controller.php';

class HomeController extends Controller {
    public function index() {
        ob_start();
        include __DIR__ . '/../views/layouts/main.php';
        echo ob_get_clean();
    }
} 