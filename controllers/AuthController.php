<?php
/**
 * Authentication Controller
 * NGO Donor Management System
 */

namespace controllers;

class AuthController extends \Controller {
    
    public function showLogin() {
        if (\Session::isLoggedIn()) {
            $this->redirect('/dashboard');
        }
        
        $this->layout = 'layouts/guest';
        $this->view('auth/login');
    }
    
    public function login() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        $user = \User::findByEmail($email);
        
        if (!$user) {
            $this->with('error', 'Invalid email or password')
                ->redirect('/login');
        }
        
        if (!$user->isActive()) {
            $this->with('error', 'Your account has been deactivated.')
                ->redirect('/login');
        }
        
        if (!$user->verifyPassword($password)) {
            $this->with('error', 'Invalid email or password')
                ->redirect('/login');
        }
        
        \Session::setUser($user);
        \Session::regenerate();
        
        $this->with('success', 'Welcome back, ' . $user->name . '!')
            ->redirect($user->isAdmin() ? '/admin' : '/dashboard');
    }
    
    public function showRegister() {
        if (\Session::isLoggedIn()) {
            $this->redirect('/dashboard');
        }
        
        $this->layout = 'layouts/guest';
        $this->view('auth/register');
    }
    
    public function register() {
        $data = [
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
            'phone' => $_POST['phone'] ?? '',
        ];
        
        // Validation
        $errors = $this->validate($data, [
            'name' => 'required|min:2',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        
        // Check if email already exists
        if (\User::findByEmail($data['email'])) {
            $errors['email'][] = 'This email is already registered.';
        }
        
        if (!empty($errors)) {
            $this->withErrors($errors)->with('old', $data)->redirect('/register');
        }
        
        // Create user
        $user = \User::createUser($data);
        
        // Log in the user
        \Session::setUser($user);
        \Session::regenerate();
        
        $this->with('success', 'Registration successful! Welcome to our donor community.')
            ->redirect('/dashboard');
    }
    
    public function logout() {
        \Session::logout();
        $this->redirect('/');
    }
    
    public function showForgotPassword() {
        $this->layout = 'layouts/guest';
        $this->view('auth/forgot-password');
    }
}
