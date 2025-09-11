<?php
require __DIR__.'/_bootstrap.php';

try {
  $me = bearer_user();
  if (!$me || !in_array('admin', $me['roles'] ?? [])) {
    http_response_code(403);
    json(['error'=>'Accès refusé']);
  }

  $in = json_input();
  $uid = (int)($in['user_id'] ?? 0);
  if (!$uid) throw new Exception('user_id requis');

  // nouveau MP
  $plain = bin2hex(random_bytes(6)); // 12 chars
  $hash  = password_hash($plain, PASSWORD_DEFAULT);

  $st = $pdo->prepare("UPDATE users SET password=? WHERE id=?");
  $st->execute([$hash, $uid]);

  json(['ok'=>true,'temp_password'=>$plain]);

} catch (Throwable $e) {
  http_response_code(500);
  json(['error'=>$e->getMessage()]);
}
