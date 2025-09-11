<?php
// logout.php
declare(strict_types=1);
require __DIR__ . '/_bootstrap.php';

$token = get_auth_token();
if ($token) {
  $hash = hash('sha256', $token);
  db()->prepare("DELETE FROM tokens WHERE token_hash=?")->execute([$hash]);
}
clear_auth_cookie();
json(['ok'=>true]);
