<?php

require_once __DIR__.'/includes/config.php';


/*session_start();

$_SESSION['adasdf'] ='hola';
print_r(password_hash('12345', PASSWORD_DEFAULT));
var_dump(password_verify('12345', '$2y$10$0eR.KhfTH5ybn/jlB86hwe/1nQeCKXk2RcLEjBscJbpUaF504kSOi'));

echo $_GET['hola'] ?? 'nada';
*/
//$app = \es\ucm\fdi\aw\Aplicacion::getSingleton();
//clone $app;
print_r($_SERVER);

function test($a, $b = new DateTime())
{
    print_r($a);
    print_r($b);
}

test('a', 'b');
test('a');
