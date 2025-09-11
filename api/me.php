<?php
// me.php
declare(strict_types=1);
require __DIR__ . '/_bootstrap.php';

$u = require_auth();
json(['ok'=>true, 'user'=>$u]);
