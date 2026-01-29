<?php
/**
 * Base Controller Class
 * NGO Donor Management System
 */

abstract class Controller {
    protected $layout = 'layouts/main';
    
    public function view($view, $data = []) {
        extract($data);
        
        // Convert dots to slashes for directory structure (admin.dashboard -> admin/dashboard)
        $viewPath = APP_PATH . '/views/' . str_replace('.', '/', $view) . '.php';
        
        if (!file_exists($viewPath)) {
            throw new Exception("View not found: $view");
        }
        
        ob_start();
        include $viewPath;
        $content = ob_get_clean();
        
        // Render with layout if set
        if ($this->layout) {
            $layoutPath = APP_PATH . '/views/' . $this->layout . '.php';
            if (file_exists($layoutPath)) {
                include $layoutPath;
                return;
            }
        }
        
        echo $content;
    }
    
    public function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
    
    public function redirect($url) {
        header("Location: $url");
        exit;
    }
    
    public function back() {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->redirect($referer);
    }
    
    public function with($key, $value) {
        Session::set($key, $value);
        return $this;
    }
    
    public function withErrors($errors) {
        Session::set('errors', $errors);
        return $this;
    }
    
    public function old($key, $default = '') {
        return Session::get('old_' . $key, $default);
    }
    
    protected function validate($data, $rules) {
        $errors = [];
        
        foreach ($rules as $field => $rule) {
            $ruleList = explode('|', $rule);
            
            foreach ($ruleList as $r) {
                if ($r === 'required' && empty($data[$field] ?? '')) {
                    $errors[$field][] = "The $field field is required.";
                }
                
                if ($r === 'email' && !filter_var($data[$field] ?? '', FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = "The $field must be a valid email.";
                }
                
                if (strpos($r, 'min:') === 0) {
                    $min = (int) substr($r, 4);
                    if (strlen($data[$field] ?? '') < $min) {
                        $errors[$field][] = "The $field must be at least $min characters.";
                    }
                }
                
                if (strpos($r, 'max:') === 0) {
                    $max = (int) substr($r, 4);
                    if (strlen($data[$field] ?? '') > $max) {
                        $errors[$field][] = "The $field may not be greater than $max characters.";
                    }
                }
                
                if ($r === 'numeric' && !is_numeric($data[$field] ?? '')) {
                    $errors[$field][] = "The $field must be a number.";
                }
            }
        }
        
        return $errors;
    }
    
    protected function requireLogin() {
        if (!Session::isLoggedIn()) {
            $this->redirect('/login');
        }
    }
    
    protected function requireAdmin() {
        $this->requireLogin();
        if (!Session::isAdmin()) {
            $this->redirect('/dashboard');
        }
    }
}
