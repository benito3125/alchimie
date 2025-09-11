<?php
require __DIR__ . '/_bootstrap.php';

$ok = true; $err = null; $dbInfo = [];

try {
  $pdo = pdo();
  $t0 = microtime(true);
  $pdo->query('SELECT 1');
  $ping = (microtime(true) - $t0) * 1000;
  $users = $pdo->query('SELECT COUNT(*) c FROM users')->fetch()['c'] ?? null;

  $dbInfo = [
    'ok'         => true,
    'host'       => DB_HOST . ':' . DB_PORT,
    'db'         => DB_NAME,
    'version'    => $pdo->query('SELECT VERSION() v')->fetch()['v'] ?? null,
    'ping_ms'    => round($ping, 1),
    'users_count'=> (int)$users,
    'threads'    => $pdo->query('SHOW STATUS LIKE "Threads_connected"')->fetch()['Value'] ?? null,
  ];
} catch (Throwable $e) {
  $ok = false; $err = $e->getMessage();
}

json([
  'ok'      => $ok,
  'time'    => date('c'),
  'php'     => PHP_VERSION,
  'whoami'  => get_current_user(),
  'cwd'     => getcwd(),
  'ext'     => [
    'pdo'      => extension_loaded('pdo'),
    'pdo_mysql'=> extension_loaded('pdo_mysql'),
    'openssl'  => extension_loaded('openssl'),
    'mbstring' => extension_loaded('mbstring'),
    'json'     => extension_loaded('json'),
  ],
  'db'      => $dbInfo,
  'error'   => $err
]);
