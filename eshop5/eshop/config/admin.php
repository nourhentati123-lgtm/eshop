<?php
/*
 |----------------------------------------------------------
 | Identifiants de l'administrateur unique — eShop
 |----------------------------------------------------------
 | username : nour
 | email    : nourhentati123@gmail.com
 | password : noureshopmanger  (stocké en bcrypt)
 |----------------------------------------------------------
 | Pour regénérer le hash :
 |   php -r "echo password_hash('noureshopmanger', PASSWORD_BCRYPT);"
 |----------------------------------------------------------
*/

define('ADMIN_USERNAME', 'nour');
define('ADMIN_EMAIL',    'nourhentati123@gmail.com');

/*
 * Hash bcrypt du mot de passe "noureshopmanger"
 * Généré avec : password_hash('noureshopmanger', PASSWORD_BCRYPT)
 */
define('ADMIN_PASSWORD_HASH',
    password_hash('noureshopmanger', PASSWORD_BCRYPT)
);
