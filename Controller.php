<?php
class Controller {
    // Méthode existante pour Frontoffice
    public function view($view, $data = []) {
        $viewPath = __DIR__ . '/View/Frontoffice/' . $view . '.php';
        if(file_exists($viewPath)) {
            require_once __DIR__ . '/View/Frontoffice/template.php';
        } else {
            die('View not found: ' . $viewPath);
        }
    }
    
    // Nouvelle méthode pour Backoffice
    public function viewAdmin($view, $data = []) {
        $viewPath = __DIR__ . '/View/Backoffice/' . $view . '.php';
        if(file_exists($viewPath)) {
            require_once __DIR__ . '/View/Backoffice/template.php';
        } else {
            die('Admin view not found: ' . $viewPath);
        }
    }
    
}