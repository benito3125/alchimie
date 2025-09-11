<?php
require __DIR__.'/_bootstrap.php';
require_admin($pdo);

$in = json_decode(file_get_contents('php://input'), true) ?: [];
$id = (int)($in['id'] ?? 0);
$role = $in['role'] ?? 'visiteur';
if (!$id) json(['error'=>'INVALID_ID'],400);

$q = $pdo->prepare("UPDATE users SET role=? WHERE id=?");
$q->execute([$role,$id]);

json(['ok'=>true]);