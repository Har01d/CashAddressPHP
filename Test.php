<?php

include('CashAddress.php');

$p2pkh = '12higDjoCCNXSA95xZMWUdPvXNmkAduhWv';
$p2sh = '342ftSRCvFHfCeFFBuz4xwbeqnDw6BGUey';
$malformed = 'bitcoincash:qpm2qsznhks23z7629mas6s4cwzf74vcwvy22gdx6a';
$bitpayp2pkh = 'CWUmjdL9q4G1Rz6o2MDGoMExivHgEnCgDx';
$bitpayp2sh = 'HHrv7h4TkshW2TGLdJ1NBg5LsQzPQwLFGE';
$testnetp2pkh = 'mipcBbFg9gMiCh81Kj8tqqdgoZub1ZJRfn';
$testnetp2sh = '2MzQwSSnBHWHqSAqtTVQ6v47XtaisrJa1Vc';

echo "<br>Test P2PKH addresses<br><br>";

echo "Old ({$p2pkh}) to new: " . ($r = \CashAddress\CashAddress::old2new($p2pkh)) . "<br>";
echo "New ({$r}) to old: " . ($r = \CashAddress\CashAddress::new2old($r, false)) . "<br>";
assert(($p2pkh == $r), 'Whoops');
echo "Ok. <br>";

echo "<br>Test P2SH addresses<br><br>";

echo "Old ({$p2sh}) to new: " . ($r = \CashAddress\CashAddress::old2new($p2sh)) . "<br>";
echo "New ({$r}) to old: " . ($r = \CashAddress\CashAddress::new2old($r, false)) . "<br>";
assert(($p2sh == $r), 'Whoops');
echo "Ok. <br><br>";

echo "<br>Test error correction<br><br>";

echo "Malformed ({$malformed}) to old: " . ($r = \CashAddress\CashAddress::new2old($malformed, true)) . "<br>";
echo "Malformed ({$malformed}) error correction: " . ($r = \CashAddress\CashAddress::fixCashAddrErrors($malformed)) . "<br>";
assert(("bitcoincash:qpm2qsznhks23z7629mms6s4cwef74vcwvy22gdx6a" == $r), 'Whoops');
echo "Ok. <br><br>";

echo "<br>Test BitPay P2PKH addresses<br><br>";

echo "BitPay ({$bitpayp2pkh}) to new: " . ($r = \CashAddress\CashAddress::old2new($bitpayp2pkh)) . "<br>";
assert(($r == 'bitcoincash:qzvmc7962aaftgglrg6y6nf2u40jlptmnqhpeu5t83'), 'Whoops');
echo "Ok. <br><br>";

echo "<br>Test BitPay P2SH addresses<br><br>";

echo "BitPay ({$bitpayp2sh}) to new: " . ($r = \CashAddress\CashAddress::old2new($bitpayp2sh)) . "<br>";
assert(($r == 'bitcoincash:pp7xwa0zpclf8rfd06whntp3qyyt55qamvfsugp2zx'), 'Whoops');
echo "Ok. <br><br>";

echo "<br>Test Testnet P2PKH addresses<br><br>";

echo "Old testnet ({$testnetp2pkh}) to new: " . ($r = \CashAddress\CashAddress::old2new($testnetp2pkh)) . "<br>";
echo "New ({$r}) to old: " . ($r = \CashAddress\CashAddress::new2old($r, false)) . "<br>";
assert(($testnetp2pkh == $r), 'Whoops');
echo "Ok. <br><br>";

echo "<br>Test Testnet P2SH addresses<br><br>";

echo "Old testnet ({$testnetp2sh}) to new: " . ($r = \CashAddress\CashAddress::old2new($testnetp2sh)) . "<br>";
echo "New ({$r}) to old: " . ($r = \CashAddress\CashAddress::new2old($r, false)) . "<br>";
assert(($testnetp2sh == $r), 'Whoops');
echo "Ok. <br><br>";
