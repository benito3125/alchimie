<?php
// users.php
declare(strict_types=1);
require __DIR__ . '/_bootstrap.php';

require_admin();

$q = db()->query("SELECT id,email,role,first_name,family_name FROM users ORDER BY id DESC");
json(['ok'=>true, 'items'=>$q->fetchAll()]);
