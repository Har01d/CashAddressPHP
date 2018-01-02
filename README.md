# CashAddressPHP

Two functions to convert from the legacy Bitcoin Cash address format to the new one and vice versa.

### Example of usage: 

#### P2PK:

old2new('1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa'): 'bitcoincash:qp3wjpa3tjlj042z2wv7hahsldgwhwy0rq9sywjpyy'

new2old('bitcoincash:qp3wjpa3tjlj042z2wv7hahsldgwhwy0rq9sywjpyy'): '1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa'

#### P2PKH:

old2new('12higDjoCCNXSA95xZMWUdPvXNmkAduhWv'): 'bitcoincash:qqf2hrw93r9f64u8mhn7k22knknrcw3r3s0mkt0zxa'

new2old('bitcoincash:qqf2hrw93r9f64u8mhn7k22knknrcw3r3s0mkt0zxa'): '12higDjoCCNXSA95xZMWUdPvXNmkAduhWv'

#### P2SH:

old2new('342ftSRCvFHfCeFFBuz4xwbeqnDw6BGUey'): 'bitcoincash:pqv60krfqv3k3lglrcnwtee6ftgwgaykpccr8hujjz'

new2old('bitcoincash:pqv60krfqv3k3lglrcnwtee6ftgwgaykpccr8hujjz'): '342ftSRCvFHfCeFFBuz4xwbeqnDw6BGUey'
