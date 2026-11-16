<?php
class AuthController {
 private User $model;
 public function __construct() { $this->model = new User(); }
 public function register(): void {
 $data = json_decode(file_get_contents('php://input'), true);
 if (empty($data['username']) || empty($data['email']) ||
empty($data['password'])) {
 Response::json(400, false, 'Username, email, dan password wajib
diisi');
 }
 $data['password'] = password_hash($data['password'],
PASSWORD_DEFAULT);
 $id = $this->model->create($data);
 Response::json(201, true, 'Registrasi berhasil', ['id' => $id]);
 }
}