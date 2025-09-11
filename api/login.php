<?php
// login.php
declare(strict_types=1);
require __DIR__ . '/_bootstrap.php';

$input = $_POST;
if (empty($input)) {
  $raw = file_get_contents('php://input');
  if ($raw) $input = json_decode($raw, true) ?: [];
}

$email = trim($input['email'] ?? '');
$pass  = (string)($input['password'] ?? '');

if ($email === '' || $pass === '') {
  json(['ok'=>false, 'error'=>'Email et mot de passe requis'], 400);
}

$pdo = db();
$q = $pdo->prepare("SELECT id,email,password,first_name,family_name,role FROM users WHERE email=? LIMIT 1");
$q->execute([$email]);
$u = $q->fetch();
if (!$u || !password_verify($pass, (string)$u['password'])) {
  // éviter de révéler la cause
  json(['ok'=>false,'error'=>'Identifiants invalides'], 401);
}

// Génération du token (valeur renvoyée au client) + hash en BDD
$token = bin2hex(random_bytes(32));
$hash  = hash('sha256', $token);
$exp   = (new DateTime('+7 days'))->format('Y-m-d H:i:s');
$ua    = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255);
$ip    = substr($_SERVER['REMOTE_ADDR'] ?? '', 0, 45);

$pdo->prepare("INSERT INTO tokens (user_id, token_hash, issued_at, expires_at, user_agent, ip)
               VALUES (?, ?, NOW(), ?, ?, ?)")
    ->execute([$u['id'], $hash, $exp, $ua, $ip]);

// Cookie HttpOnly + retour JSON
set_auth_cookie($token);

$user = hydrate_user([
  'id'          => $u['id'],
  'email'       => $u['email'],
  'first_name'  => $u['first_name'],
  'family_name' => $u['family_name'],
  'role'        => $u['role'],
]);

json(['ok'=>true, 'token'=>$token, 'user'=>$user]);
