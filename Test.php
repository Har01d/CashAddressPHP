<?php

include('CashAddress.php');

$p2pkh = '12higDjoCCNXSA95xZMWUdPvXNmkAduhWv';
$p2sh = '342ftSRCvFHfCeFFBuz4xwbeqnDw6BGUey';
$malformed = 'bitcoincash:qpm2qsznhks23z7629mas6s4cwzf74vcwvy22gdx6a';

echo "\nTest P2PKH addresses\n\n";

echo "Old ({$p2pkh}) to new: " . ($r = \CashAddress\CashAddress::old2new($p2pkh)) . "\n";
echo "New ({$r}) to old: " . ($r = \CashAddress\CashAddress::new2old($r, false)) . "\n";
assert(($p2pkh == $r),'Whoops');
echo "Ok. \n";

echo "\nTest P2SH addresses\n\n";

echo "Old ({$p2sh}) to new: " . ($r = \CashAddress\CashAddress::old2new($p2sh)) . "\n";
echo "New ({$r}) to old: " . ($r = \CashAddress\CashAddress::new2old($r, false)) . "\n";
assert(($p2sh == $r), 'Whoops');
echo "Ok. \n\n";

echo "\nTest error correction\n\n";

echo "Malformed ({$malformed}) to old: " . ($r = \CashAddress\CashAddress::new2old($malformed, true)) . "\n";
echo "Malformed ({$malformed}) error correction: " . ($r = \CashAddress\CashAddress::fixCashAddrErrors($malformed)) . "\n";
assert(("bitcoincash:qpm2qsznhks23z7629mms6s4cwef74vcwvy22gdx6a" == $r), 'Whoops');
echo "Ok. \n\n";
