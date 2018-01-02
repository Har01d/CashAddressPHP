<?php

include('CashAddress.php');

$p2pk = '1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa';
$p2pkh = '12higDjoCCNXSA95xZMWUdPvXNmkAduhWv';
$p2sh = '342ftSRCvFHfCeFFBuz4xwbeqnDw6BGUey';

echo "\nTest P2PK addresses\n\n";

echo "Old ({$p2pk}) to new: " . ($r = \CashAddress\CashAddress::old2new($p2pk)) . "\n";
echo "New ({$r}) to old: " . ($r = \CashAddress\CashAddress::new2old($r)) . "\n";
assert(($p2pk == $r), 'Whoops');
echo "Ok. \n";

echo "\nTest P2PKH addresses\n\n";

echo "Old ({$p2pkh}) to new: " . ($r = \CashAddress\CashAddress::old2new($p2pkh)) . "\n";
echo "New ({$r}) to old: " . ($r = \CashAddress\CashAddress::new2old($r)) . "\n";
assert(($p2pkh == $r),'Whoops');
echo "Ok. \n";

echo "\nTest P2SH addresses\n\n";

echo "Old ({$p2sh}) to new: " . ($r = \CashAddress\CashAddress::old2new($p2sh)) . "\n";
echo "New ({$r}) to old: " . ($r = \CashAddress\CashAddress::new2old($r)) . "\n";
assert(($p2sh == $r), 'Whoops');
echo "Ok. \n\n";
