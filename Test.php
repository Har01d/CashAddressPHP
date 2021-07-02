<?php

include('CashAddress.php');

$p2pkh = '12higDjoCCNXSA95xZMWUdPvXNmkAduhWv';
$p2sh = '342ftSRCvFHfCeFFBuz4xwbeqnDw6BGUey';
$malformed = 'bitcoincash:qpm2qsznhks23z7629mas6s4cwzf74vcwvy22gdx6a';
$bitpayp2pkh = 'CWUmjdL9q4G1Rz6o2MDGoMExivHgEnCgDx';
$bitpayp2sh = 'HHrv7h4TkshW2TGLdJ1NBg5LsQzPQwLFGE';
$testnetp2pkh = 'mipcBbFg9gMiCh81Kj8tqqdgoZub1ZJRfn';
$testnetp2sh = '2MzQwSSnBHWHqSAqtTVQ6v47XtaisrJa1Vc';

echo "\nTest P2PKH addresses\n\n";

echo "Old ({$p2pkh}) to new: " . ($r = \CashAddress\CashAddress::old2new($p2pkh)) . "\n";
echo "New ({$r}) to old: " . ($r = \CashAddress\CashAddress::new2old($r, false)) . "\n";
assert(($p2pkh == $r), 'Whoops');
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

echo "\nTest BitPay P2PKH addresses\n\n";

echo "BitPay ({$bitpayp2pkh}) to new: " . ($r = \CashAddress\CashAddress::old2new($bitpayp2pkh)) . "\n";
assert(($r == 'bitcoincash:qzvmc7962aaftgglrg6y6nf2u40jlptmnqhpeu5t83'), 'Whoops');
echo "Ok. \n\n";

echo "\nTest BitPay P2SH addresses\n\n";

echo "BitPay ({$bitpayp2sh}) to new: " . ($r = \CashAddress\CashAddress::old2new($bitpayp2sh)) . "\n";
assert(($r == 'bitcoincash:pp7xwa0zpclf8rfd06whntp3qyyt55qamvfsugp2zx'), 'Whoops');
echo "Ok. \n\n";

echo "\nTest Testnet P2PKH addresses\n\n";

echo "Old testnet ({$testnetp2pkh}) to new: " . ($r = \CashAddress\CashAddress::old2new($testnetp2pkh)) . "\n";
echo "New ({$r}) to old: " . ($r = \CashAddress\CashAddress::new2old($r, false)) . "\n";
assert(($testnetp2pkh == $r), 'Whoops');
echo "Ok. \n\n";

echo "\nTest Testnet P2SH addresses\n\n";

echo "Old testnet ({$testnetp2sh}) to new: " . ($r = \CashAddress\CashAddress::old2new($testnetp2sh)) . "\n";
echo "New ({$r}) to old: " . ($r = \CashAddress\CashAddress::new2old($r, false)) . "\n";
assert(($testnetp2sh == $r), 'Whoops');
echo "Ok. \n\n";

echo "\nTest prefix detection\n\n";

$address = "qq7h7thq7seggqawtnlus5f2k62m7l07vud66vg4ge";
echo "Prefix for " . $address . " is: " . ($r = \CashAddress\CashAddress::getPrefix($address)) . "\n";
assert(($r === "ecash"), 'Whoops');
echo "Ok. \n\n";

$address = "qq7h7thq7seggqawtnlus5f2k62m7l07vu5hw8n0ww";
echo "Prefix for " . $address . " is: " . ($r = \CashAddress\CashAddress::getPrefix($address)) . "\n";
assert(($r === "bitcoincash"), 'Whoops');
echo "Ok. \n\n";

$address = "qrfekq9s0c8tcuh75wpcxqnyl5e7dhqk4gq6pjct44";
echo "Prefix for " . $address . " is: " . ($r = \CashAddress\CashAddress::getPrefix($address)) . "\n";
assert(($r === "ectest"), 'Whoops');
echo "Ok. \n\n";

$address = "qrfekq9s0c8tcuh75wpcxqnyl5e7dhqk4gmw07xth0";
echo "Prefix for " . $address . " is: " . ($r = \CashAddress\CashAddress::getPrefix($address)) . "\n";
assert(($r === "bchtest"), 'Whoops');
echo "Ok. \n\n";

echo "\nTest BCH to eCash address conversion\n\n";

$bch_p2pkh = "bitcoincash:qzvmc7962aaftgglrg6y6nf2u40jlptmnqhpeu5t83";
echo "eCash converted address for " . $bch_p2pkh . " is: " . ($r = \CashAddress\CashAddress::bch2xec($bch_p2pkh)) . "\n";
assert(($r === "ecash:qzvmc7962aaftgglrg6y6nf2u40jlptmnqwvdh03px"), 'Whoops');
echo "Ok. \n\n";

$bch_p2pkh_noPrefix = "qzvmc7962aaftgglrg6y6nf2u40jlptmnqhpeu5t83";
echo "eCash converted address for " . $bch_p2pkh_noPrefix . " is: " . ($r = \CashAddress\CashAddress::bch2xec($bch_p2pkh_noPrefix)) . "\n";
assert(($r === "ecash:qzvmc7962aaftgglrg6y6nf2u40jlptmnqwvdh03px"), 'Whoops');
echo "Ok. \n\n";

$bch_p2sh = "bitcoincash:pqv60krfqv3k3lglrcnwtee6ftgwgaykpccr8hujjz";
echo "eCash converted address for " . $bch_p2sh . " is: " . ($r = \CashAddress\CashAddress::bch2xec($bch_p2sh)) . "\n";
assert(($r === "ecash:pqv60krfqv3k3lglrcnwtee6ftgwgaykpcpwnu8g54"), 'Whoops');
echo "Ok. \n\n";

$bch_bitpayp2pkh = "bitcoincash:qzvmc7962aaftgglrg6y6nf2u40jlptmnqhpeu5t83";
echo "eCash converted address for " . $bch_bitpayp2pkh . " is: " . ($r = \CashAddress\CashAddress::bch2xec($bch_bitpayp2pkh)) . "\n";
assert(($r === "ecash:qzvmc7962aaftgglrg6y6nf2u40jlptmnqwvdh03px"), 'Whoops');
echo "Ok. \n\n";

$bch_bitpayp2sh = "bitcoincash:pp7xwa0zpclf8rfd06whntp3qyyt55qamvfsugp2zx";
echo "eCash converted address for " . $bch_bitpayp2sh . " is: " . ($r = \CashAddress\CashAddress::bch2xec($bch_bitpayp2sh)) . "\n";
assert(($r === "ecash:pp7xwa0zpclf8rfd06whntp3qyyt55qamvsagr6sy3"), 'Whoops');
echo "Ok. \n\n";

$bch_testnetp2pkh = "bchtest:qqjr7yu573z4faxw8ltgvjwpntwys08fysk07zmvce";
echo "eCash converted address for " . $bch_testnetp2pkh . " is: " . ($r = \CashAddress\CashAddress::bch2xec($bch_testnetp2pkh)) . "\n";
assert(($r === "ectest:qqjr7yu573z4faxw8ltgvjwpntwys08fysdmsw9v6r"), 'Whoops');
echo "Ok. \n\n";

$bch_testnetp2pkh_noPrefix = "qqjr7yu573z4faxw8ltgvjwpntwys08fysk07zmvce";
echo "eCash converted address for " . $bch_testnetp2pkh_noPrefix . " is: " . ($r = \CashAddress\CashAddress::bch2xec($bch_testnetp2pkh_noPrefix)) . "\n";
assert(($r === "ectest:qqjr7yu573z4faxw8ltgvjwpntwys08fysdmsw9v6r"), 'Whoops');
echo "Ok. \n\n";

$bch_testnetp2sh= "bchtest:pp8f7ww2g6y07ypp9r4yendrgyznysc9kqxh6acwu3";
echo "eCash converted address for " . $bch_testnetp2sh . " is: " . ($r = \CashAddress\CashAddress::bch2xec($bch_testnetp2sh)) . "\n";
assert(($r === "ectest:pp8f7ww2g6y07ypp9r4yendrgyznysc9kqar53xw7t"), 'Whoops');
echo "Ok. \n\n";

echo "\nTest eCash to BCH address conversion\n\n";

$xec_p2pkh = "ecash:qzvmc7962aaftgglrg6y6nf2u40jlptmnqwvdh03px";
echo "eCash converted address for " . $xec_p2pkh . " is: " . ($r = \CashAddress\CashAddress::xec2bch($xec_p2pkh)) . "\n";
assert(($r === "bitcoincash:qzvmc7962aaftgglrg6y6nf2u40jlptmnqhpeu5t83"), 'Whoops');
echo "Ok. \n\n";

$xec_p2pkh_noPrefix = "qzvmc7962aaftgglrg6y6nf2u40jlptmnqwvdh03px";
echo "eCash converted address for " . $xec_p2pkh_noPrefix . " is: " . ($r = \CashAddress\CashAddress::xec2bch($xec_p2pkh_noPrefix)) . "\n";
assert(($r === "bitcoincash:qzvmc7962aaftgglrg6y6nf2u40jlptmnqhpeu5t83"), 'Whoops');
echo "Ok. \n\n";

$xec_p2sh = "ecash:pqv60krfqv3k3lglrcnwtee6ftgwgaykpcpwnu8g54";
echo "eCash converted address for " . $xec_p2sh . " is: " . ($r = \CashAddress\CashAddress::xec2bch($xec_p2sh)) . "\n";
assert(($r === "bitcoincash:pqv60krfqv3k3lglrcnwtee6ftgwgaykpccr8hujjz"), 'Whoops');
echo "Ok. \n\n";

$xec_bitpayp2pkh = "ecash:qzvmc7962aaftgglrg6y6nf2u40jlptmnqwvdh03px";
echo "eCash converted address for " . $xec_bitpayp2pkh . " is: " . ($r = \CashAddress\CashAddress::xec2bch($xec_bitpayp2pkh)) . "\n";
assert(($r === "bitcoincash:qzvmc7962aaftgglrg6y6nf2u40jlptmnqhpeu5t83"), 'Whoops');
echo "Ok. \n\n";

$xec_bitpayp2sh = "ecash:pp7xwa0zpclf8rfd06whntp3qyyt55qamvsagr6sy3";
echo "eCash converted address for " . $xec_bitpayp2sh . " is: " . ($r = \CashAddress\CashAddress::xec2bch($xec_bitpayp2sh)) . "\n";
assert(($r === "bitcoincash:pp7xwa0zpclf8rfd06whntp3qyyt55qamvfsugp2zx"), 'Whoops');
echo "Ok. \n\n";

$xec_testnetp2pkh = "ectest:qqjr7yu573z4faxw8ltgvjwpntwys08fysdmsw9v6r";
echo "eCash converted address for " . $xec_testnetp2pkh . " is: " . ($r = \CashAddress\CashAddress::xec2bch($xec_testnetp2pkh)) . "\n";
assert(($r === "bchtest:qqjr7yu573z4faxw8ltgvjwpntwys08fysk07zmvce"), 'Whoops');
echo "Ok. \n\n";

$xec_testnetp2pkh_noPrefix = "qqjr7yu573z4faxw8ltgvjwpntwys08fysdmsw9v6r";
echo "eCash converted address for " . $xec_testnetp2pkh_noPrefix . " is: " . ($r = \CashAddress\CashAddress::xec2bch($xec_testnetp2pkh_noPrefix)) . "\n";
assert(($r === "bchtest:qqjr7yu573z4faxw8ltgvjwpntwys08fysk07zmvce"), 'Whoops');
echo "Ok. \n\n";

$xec_testnetp2sh= "ectest:pp8f7ww2g6y07ypp9r4yendrgyznysc9kqar53xw7t";
echo "eCash converted address for " . $xec_testnetp2sh . " is: " . ($r = \CashAddress\CashAddress::xec2bch($xec_testnetp2sh)) . "\n";
assert(($r === "bchtest:pp8f7ww2g6y07ypp9r4yendrgyznysc9kqxh6acwu3"), 'Whoops');
echo "Ok. \n\n";

