<?php
require __DIR__.'/_bootstrap.php';

try {
  $me = bearer_user();
  if (!$me || !in_array('admin', $me['roles'] ?? [])) {
    http_response_code(403);
    json(['error'=>'Accès refusé']);
  }

  $in = json_input();
  $uid  = (int)($in['user_id'] ?? 0);
  $role = $in['role'] ?? null; // "admin" | "comite" | "visiteur" | null

  if (!$uid) throw new Exception('user_id requis');

  $allowed = [null, 'admin','comite','visiteur'];
  if (!in_array($role, $allowed, true)) throw new Exception('Rôle non autorisé');

  $st = $pdo->prepare("UPDATE users SET role=? WHERE id=?");
  $st->execute([$role, $uid]);

  json(['ok'=>true]);

} catch (Throwable $e) {
  http_response_code(500);
  json(['error'=>$e->getMessage()]);
}
