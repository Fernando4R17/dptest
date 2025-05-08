<?php

class Controller {
    protected function render($view, $data = []) {
        extract($data);
        ob_start();
        include __DIR__ . "/../views/{$view}.php";
        $content = ob_get_clean();
        
        return $content;
    }
    
    protected function redirect($url) {
        header("Location: {$url}");
        exit;
    }
} 