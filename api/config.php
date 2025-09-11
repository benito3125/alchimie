<?php
// ——————————————————————————————
//  CONFIG BDD + Cookie
// ——————————————————————————————
define('DB_HOST', 'bb621576-002.eu.clouddb.ovh.net');
define('DB_PORT', 35512);
define('DB_NAME', 'repas');
define('DB_USER', 'admin');
define('DB_PASS', 'swaQtVv18ViUud9W');

// Cookie de session (token côté client)
define('AUTH_COOKIE', 'aaj_token');
define('AUTH_COOKIE_PATH', '/preprod/'); // adapte si besoin
define('AUTH_COOKIE_AGE', 60 * 60 * 24 * 30); // 30 jours
define('AUTH_COOKIE_SECURE', true);  // true en prod HTTPS
define('AUTH_COOKIE_SAMESITE', 'Lax');

// Chemin racine utile pour les entêtes CORS si nécessaire (ici même origine → rien)
