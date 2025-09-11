<?php
// _bootstrap.php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/config.php'; // définit DB_HOST, DB_NAME, DB_USER, DB_PASS

// -------------------- PDO --------------------
function db(): PDO {
  static $pdo = null;
  if ($pdo) return $pdo;
  $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', DB_HOST, DB_NAME);
  $pdo = new PDO($dsn, DB_USER, DB_PASS, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]);
  return $pdo;
}

// -------------------- Helpers JSON --------------------
function json($data, int $code = 200): void {
  http_response_code($code);
  echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  exit;
}

set_exception_handler(function(Throwable $e){
  // Log local éventuel ici
  json(['ok'=>false, 'error'=>$e->getMessage()], 500);
});

// -------------------- Auth helpers --------------------
function get_auth_token(): ?string {
  // 1) Authorization: Bearer ...
  $hdr = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['Authorization'] ?? '';
  if (preg_match('/Bearer\s+([A-Za-z0-9._-]+)/', $hdr, $m)) {
    return $m[1];
  }
  // 2) Cookie
  if (!empty($_COOKIE['aj_token'])) {
    return $_COOKIE['aj_token'];
  }
  // 3) param token (fallback)
  if (!empty($_POST['token'])) return $_POST['token'];
  return null;
}

function hydrate_user(array $row): array {
  // global role dans users.role (ex: admin/comite/visiteur)
  $row['roles'] = [];
  if (!empty($row['role'])) $row['roles'][] = $row['role'];

  // espaces via user_spaces
  $pdo = db();
  $q = $pdo->prepare("SELECT space_id, role FROM user_spaces WHERE user_id = ?");
  $q->execute([$row['id']]);
  $row['spaces'] = $q->fetchAll();
  return $row;
}

function current_user(): ?array {
  $token = get_auth_token();
  if (!$token) return null;

  $hash = hash('sha256', $token);
  $pdo  = db();
  $q = $pdo->prepare("
    SELECT u.id,u.email,u.first_name,u.family_name,u.role
    FROM tokens t
    JOIN users  u ON u.id=t.user_id
    WHERE t.token_hash=? AND t.expires_at > NOW()
    LIMIT 1
  ");
  $q->execute([$hash]);
  $row = $q->fetch();
  if (!$row) return null;

  return hydrate_user($row);
}

function require_auth(): array {
  $u = current_user();
  if (!$u) json(['ok'=>false,'error'=>'Non authentifié'], 401);
  return $u;
}

function require_admin(): array {
  $u = require_auth();
  if (!in_array('admin', $u['roles'] ?? [], true)) {
    json(['ok'=>false,'error'=>'Accès réservé aux administrateurs'], 403);
  }
  return $u;
}

// -------------------- Cookies --------------------
function set_auth_cookie(string $token): void {
  // cookie valable sur tout le domaine
  $params = [
    'expires'  => time()+60*60*24*7,
    'path'     => '/',
    'secure'   => true,       // site en https
    'httponly' => true,
    'samesite' => 'Lax',
  ];
  setcookie('aj_token', $token, $params);
}

function clear_auth_cookie(): void {
  setcookie('aj_token', '', [
    'expires' => time()-3600,
    'path'    => '/',
    'secure'  => true,
    'httponly'=> true,
    'samesite'=> 'Lax',
  ]);
}
