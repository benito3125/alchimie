<?php
require __DIR__.'/_bootstrap.php';

try {
  $me = bearer_user();
  if (!$me || !in_array('admin', $me['roles'] ?? [])) {
    http_response_code(403);
    json(['error' => 'Accès refusé']);
  }

  $in = json_input();
  $uid = (int)($in['user_id'] ?? 0);
  $spaces = (array)($in['spaces'] ?? []);

  if (!$uid) throw new Exception('user_id requis');

  // rôles d’espace autorisés
  $ALLOWED = ['benevole','responsable'];

  $pdo->beginTransaction();

  // On nettoie et on réinsère
  $del = $pdo->prepare("DELETE FROM user_spaces WHERE user_id=?");
  $del->execute([$uid]);

  if (!empty($spaces)) {
    $ins = $pdo->prepare("INSERT INTO user_spaces (user_id, space_id, role) VALUES (?,?,?)");
    foreach ($spaces as $sid => $role) {
      $sid = trim($sid);
      $role = trim((string)$role);
      if ($sid === '' || !in_array($role, $ALLOWED, true)) continue;
      $ins->execute([$uid, $sid, $role]);
    }
  }

  $pdo->commit();
  json(['ok'=>true]);

} catch (Throwable $e) {
  if ($pdo->inTransaction()) $pdo->rollBack();
  http_response_code(500);
  json(['error'=>$e->getMessage()]);
}
