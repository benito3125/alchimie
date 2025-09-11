<?php
require __DIR__.'/_bootstrap.php';
require_admin($pdo);

$in = json_decode(file_get_contents('php://input'), true) ?: [];
$email = trim($in['email'] ?? '');
$password = trim($in['password'] ?? 'alchimie');
$role = $in['role'] ?? 'visiteur';
$first = trim($in['first_name'] ?? '');
$last  = trim($in['family_name'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) json(['error'=>'INVALID_EMAIL'], 400);
$hash = password_hash($password, PASSWORD_DEFAULT);

$q = $pdo->prepare("INSERT INTO users (email,password,role,first_name,family_name) VALUES (?,?,?,?,?)");
$q->execute([$email,$hash,$role,$first,$last]);

json(['ok'=>true,'id'=>(int)$pdo->lastInsertId()]);