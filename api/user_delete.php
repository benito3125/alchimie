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

  $st = $pdo->prepare("DELETE FROM users WHERE id=?");
  $st->execute([$uid]);

  // user_spaces est en ON DELETE CASCADE si tu as mis la contrainte FK
  json(['ok'=>true]);

} catch (Throwable $e) {
  http_response_code(500);
  json(['error'=>$e->getMessage()]);
}
