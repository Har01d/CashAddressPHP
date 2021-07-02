<?php

namespace CashAddress;

// (c) uMCCCS
// with some minor additions from Har01d @ blockchair.com

// This script uses some of the code and ideas from the following repositories:

// https://github.com/deadalnix/cashaddressed
// https://github.com/cryptocoinjs/base-x/blob/master/index.js - base-x encoding
// Forked from https://github.com/cryptocoinjs/bs58
// Originally written by Mike Hearn for BitcoinJ
// Copyright (c) 2011 Google Inc
// Ported to JavaScript by Stefan Thomas
// Merged Buffer refactorings from base58-native by Stephen Pair
// Copyright (c) 2013 BitPay Inc

// The MIT License (MIT)
// Copyright base-x contributors (c) 2016
//
// Permission is hereby granted, free of charge, to any person obtaining a copy
// of this software and associated documentation files (the "Software"), to deal
// in the Software without restriction, including without limitation the rights
// to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
// copies of the Software, and to permit persons to whom the Software is
// furnished to do so, subject to the following conditions:
//
// The above copyright notice and this permission notice shall be included in
// all copies or substantial portions of the Software.
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
// AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
// OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
// THE SOFTWARE.

// Copyright (c) 2017 Pieter Wuille
//
// Permission is hereby granted, free of charge, to any person obtaining a copy
// of this software and associated documentation files (the "Software"), to deal
// in the Software without restriction, including without limitation the rights
// to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
// copies of the Software, and to permit persons to whom the Software is
// furnished to do so, subject to the following conditions:
//
// The above copyright notice and this permission notice shall be included in
// all copies or substantial portions of the Software.
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
// AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
// OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
// THE SOFTWARE.

// ISC License
//
// Copyright (c) 2013-2016 The btcsuite developers
//
// Permission to use, copy, modify, and distribute this software for any
// purpose with or without fee is hereby granted, provided that the above
// copyright notice and this permission notice appear in all copies.
//
// THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES
// WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF
// MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR
// ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES
// WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN
// ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF
// OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.

// https://github.com/Bit-Wasp/bitcoin-php/blob/master/src/Bech32.php
// This is free and unencumbered software released into the public domain.
//
// Anyone is free to copy, modify, publish, use, compile, sell, or
// distribute this software, either in source code form or as a compiled
// binary, for any purpose, commercial or non-commercial, and by any
// means.
//
// In jurisdictions that recognize copyright laws, the author or authors
// of this software dedicate any and all copyright interest in the
// software to the public domain. We make this dedication for the benefit
// of the public at large and to the detriment of our heirs and
// successors. We intend this dedication to be an overt act of
// relinquishment in perpetuity of all present and future rights to this
// software under copyright law.
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
// EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
// MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
// IN NO EVENT SHALL THE AUTHORS BE LIABLE FOR ANY CLAIM, DAMAGES OR
// OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
// ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
// OTHER DEALINGS IN THE SOFTWARE.
//
// For more information, please refer to <http://unlicense.org/>

class CashAddressException extends \Exception {

}

class CashAddress {

	const ALPHABET = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
	const CHARSET = 'qpzry9x8gf2tvdw0s3jn54khce6mua7l';
	const ALPHABET_MAP =
  					  [-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
  					  -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
  					  -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
  					  -1,  0,  1,  2,  3,  4,  5,  6,  7,  8, -1, -1, -1, -1, -1, -1,
  					  -1,  9, 10, 11, 12, 13, 14, 15, 16, -1, 17, 18, 19, 20, 21, -1,
  					  22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, -1, -1, -1, -1, -1,
  					  -1, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, -1, 44, 45, 46,
  					  47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, -1, -1, -1, -1, -1];
	const BECH_ALPHABET =
  					  [-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
  					  -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
  					  -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
  					  15, -1, 10, 17, 21, 20, 26, 30,  7,  5, -1, -1, -1, -1, -1, -1,
  					  -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
  					  -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
  					  -1, 29, -1, 24, 13, 25,  9,  8, 23, -1, 18, 22, 31, 27, 19, -1,
  					  1, 0, 3, 16, 11, 28, 12, 14, 6, 4, 2, -1, -1, -1, -1, -1];
	const BCH_EXPAND_PREFIX_UNPROCESSED = [2, 9, 20, 3, 15, 9, 14, 3, 1, 19, 8, 0];
	const BCH_EXPAND_PREFIX_TESTNET_UNPROCESSED = [2, 3, 8, 20, 5, 19, 20, 0];
	const BCH_EXPAND_PREFIX = 1058337025301;
	const BCH_EXPAND_PREFIX_TESTNET = 584719417569;
	const ECASH_EXPAND_PREFIX_UNPROCESSED = [5, 3, 1, 19, 8, 0];
	const ECASH_EXPAND_PREFIX_TESTNET_UNPROCESSED = [5, 3, 20, 5, 19, 20, 0];
	const BASE16 = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, -1, -1, -1, -1, -1, -1, -1, -1,
						  -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
							-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 10, 11, 12,
							13, 14, 15];

	public function __construct()
	{
		if (PHP_INT_SIZE < 5) {

			// Requires x64 system and PHP!
			throw new CashAddressException('Run it on a x64 system (+ 64 bit PHP)');
		}
	}
	/**
	 * convertBits is the internal function to convert 256-based bytes
	 * to base-32 grouped bit arrays and vice versa.
	 * @param  array $data Data whose bits to be re-grouped
	 * @param  integer $fromBits Bits per input group of the $data
	 * @param  integer $toBits Bits to be put to each output group
	 * @param  boolean $pad Whether to add extra zeroes
	 * @return array $ret
	 * @throws CashAddressException
	 */
	static private function convertBits(array $data, $fromBits, $toBits, $pad = true)
	{
		$acc    = 0;
		$bits   = 0;
		$ret    = [];
		$maxv   = (1 << $toBits) - 1;
		$maxacc = (1 << ($fromBits + $toBits - 1)) - 1;

		$datalen = sizeof($data);
		for ($i = 0; $i < $datalen; $i++)
		{
			$value = $data[$i];

			if ($value < 0 || $value >> $fromBits !== 0)
			{
				throw new CashAddressException('Error!');
			}

			$acc  = (($acc << $fromBits) | $value) & $maxacc;
			$bits += $fromBits;

			while ($bits >= $toBits)
			{
				$bits  -= $toBits;
				$ret[] = (($acc >> $bits) & $maxv);
			}
		}

		if ($pad)
		{
			if ($bits)
			{
				$ret[] = ($acc << $toBits - $bits) & $maxv;
			}
		}
		else if ($bits >= $fromBits || ((($acc << ($toBits - $bits))) & $maxv))
		{
			throw new CashAddressException('Error!');
		}

		return $ret;
	}

	/**
	* polyMod is the internal function create BCH codes.
	* @param  array $var 5-bit grouped data array whose polyMod to be calculated.
	* @param  integer c Starting value, 1 if the prefix is appended to the array.
	* @return integer $polymodValue polymod result
	*/
	static private function polyMod($var, $c = 1)
	{
		$varlen = sizeof($var);;
		for ($i = 0; $i < $varlen; $i++)
		{
			$c0 = $c >> 35;
			$c = (($c & 0x07ffffffff) << 5) ^
			($var[$i]) ^
			(-($c0 & 1) & 0x98f2bc8e61) ^
			(-($c0 & 2) & 0x79b76d99e2) ^
			(-($c0 & 4) & 0xf33e5fb3c4) ^
			(-($c0 & 8) & 0xae2eabe2a8) ^
			(-($c0 & 16) & 0x1e4f43e470);
		}

		return $c ^ 1;
	}

	/**
	* rebuildAddress is the internal function to recreate error
	* corrected addresses.
	* @param  array $addressBytes
	* @return string $correctedAddress
	*/
	static private function rebuildAddress($addressBytes)
	{
		$ret = '';
		$i   = 0;

		while ($addressBytes[$i] !== 0)
		{
			// 96 = ord('a') & 0xe0
			$ret .= chr(96 + $addressBytes[$i]);
			$i++;
		}

		$ret .= ':';
		$len = sizeof($addressBytes);
		for ($i++; $i < $len; $i++)
		{
			$ret .= self::CHARSET[$addressBytes[$i]];
		}

		return $ret;
	}

	/**
	* old2new converts an address in old format to the new Cash Address format.
	* @param  string $oldAddress (either Mainnet or Testnet)
	* @return string $newAddress Cash Address result
	* @throws CashAddressException
	*/
	static public function old2new($oldAddress)
	{
		$bytes = [0];

		for ($x = 0; $x < strlen($oldAddress); $x++)
		{
			$carry = ord($oldAddress[$x]);
			if ($carry > 127 || ((($carry = self::ALPHABET_MAP[$carry]) === -1)))
			{
				throw new CashAddressException('Unexpected character in address!');
			}

			$bytes_len = sizeof($bytes);
			for ($j = 0; $j < $bytes_len; $j++)
			{
				$carry     += $bytes[$j] * 58;
				$bytes[$j] = $carry & 0xff;
				$carry     >>= 8;
			}

			while ($carry !== 0)
			{
				array_push($bytes, $carry & 0xff);
				$carry >>= 8;
			}
		}

		for ($numZeros = 0; $numZeros < strlen($oldAddress) && $oldAddress[$numZeros] === '1'; $numZeros++)
		{
			array_push($bytes, 0);
		}

		// reverse array
		$answer = [];

		for ($i = sizeof($bytes) - 1; $i >= 0; $i--)
		{
			array_push($answer, $bytes[$i]);
		}

		$version = $answer[0];
		$payload = array_slice($answer, 1, sizeof($answer) - 5);

		if (sizeof($payload) % 4 !== 0)
		{
			throw new CashAddressException('Unexpected address length!');
		}

		// Assume the checksum of the old address is right
		// Here, the Cash Address conversion starts
		if ($version === 0x00)
		{
			// P2PKH
			$addressType = 0;
			$realNet = true;
		}
		else if ($version === 0x05)
		{
			// P2SH
			$addressType = 1;
			$realNet = true;
		}
		else if ($version === 0x6f)
		{
			// Testnet P2PKH
			$addressType = 0;
			$realNet = false;
		}
		else if ($version === 0xc4)
		{
			// Testnet P2SH
			$addressType = 1;
			$realNet = false;
		}
		else if ($version === 0x1c)
		{
			// BitPay P2PKH
			$addressType = 0;
			$realNet = true;
		}
		else if ($version === 0x28)
		{
			// BitPay P2SH
			$addressType = 1;
			$realNet = true;
		}
		else
		{
			throw new CashAddressException('Unknown address type!');
		}

		$encodedSize = (sizeof($payload) - 20) / 4;

		$versionByte      = ($addressType << 3) | $encodedSize;
		$data             = array_merge([$versionByte], $payload);
		$payloadConverted = self::convertBits($data, 8, 5, true);
		$arr              = array_merge($payloadConverted, [0, 0, 0, 0, 0, 0, 0, 0]);
		if ($realNet) {
			$expand_prefix = self::BCH_EXPAND_PREFIX;
			$ret = 'bitcoincash:';
		} else {
			$expand_prefix = self::BCH_EXPAND_PREFIX_TESTNET;
			$ret = 'bchtest:';
		}
		$mod          = self::polymod($arr, $expand_prefix);
		$checksum     = [0, 0, 0, 0, 0, 0, 0, 0];

		for ($i = 0; $i < 8; $i++)
		{
			// Convert the 5-bit groups in mod to checksum values.
			// $checksum[$i] = ($mod >> 5*(7-$i)) & 0x1f;
			$checksum[$i] = ($mod >> (5 * (7 - $i))) & 0x1f;
		}

		$combined     = array_merge($payloadConverted, $checksum);
		$ret .= self::bechEncode($combined);

		return $ret;
	}

	/**
	 * Decodes Cash Address.
	 * @param  string $inputNew New address to be decoded.
	 * @param  boolean $shouldFixErrors Whether to fix typing errors.
	 * @param  boolean &$isTestnetAddressResult Is pointer, set to whether it's
	 * a testnet address.
	 * @return array $decoded Returns decoded byte array if it can be decoded.
	 * @return string $correctedAddress Returns the corrected address if there's
	 * a typing error.
	 * @throws CashAddressException
	 */
	static public function decodeNewAddr($inputNew, $shouldFixErrors, &$isTestnetAddressResult) {
		$inputNew = strtolower($inputNew);
		if (strpos($inputNew, ':') === false) {
			$afterPrefix = 0;
			$expand_prefix = self::BCH_EXPAND_PREFIX;
			$isTestnetAddressResult = false;
		}
		else if (substr($inputNew, 0, 12) === 'bitcoincash:')
		{
			$afterPrefix = 12;
			$expand_prefix = self::BCH_EXPAND_PREFIX;
			$isTestnetAddressResult = false;
		}
		else if (substr($inputNew, 0, 8) === 'bchtest:')
		{
			$afterPrefix = 8;
			$expand_prefix = self::BCH_EXPAND_PREFIX_TESTNET;
			$isTestnetAddressResult = true;
		}
		else
		{
			throw new CashAddressException('Unknown address type');
		}

		$data = self::bechDecode(substr($inputNew, $afterPrefix));

		$checksum = self::polyMod($data, $expand_prefix);

		if ($checksum !== 0)
		{
			if ($expand_prefix === self::BCH_EXPAND_PREFIX_TESTNET) {
				$unexpand_prefix = self::BCH_EXPAND_PREFIX_TESTNET_UNPROCESSED;
			} else {
				$unexpand_prefix = self::BCH_EXPAND_PREFIX_UNPROCESSED;
			}
			// Checksum is wrong!
			// Try to fix up to two errors
			if ($shouldFixErrors) {
				$syndromes = Array();
				$datalen = sizeof($data);
				for ($p = 0; $p < $datalen; $p++)
				{
					for ($e = 1; $e < 32; $e++)
					{
						$data[$p] ^= $e;
						$c        = self::polyMod($data, $expand_prefix);
						if ($c === 0)
						{
							return self::rebuildAddress(array_merge($unexpand_prefix, $data));
						}
						$syndromes[$c ^ $checksum] = $p * 32 + $e;
						$data[$p]                  ^= $e;
					}
				}

				foreach ($syndromes as $s0 => $pe)
				{
					if (array_key_exists($s0 ^ $checksum, $syndromes))
					{
						$data[$pe >> 5]                         ^= $pe % 32;
						$data[$syndromes[$s0 ^ $checksum] >> 5] ^= $syndromes[$s0 ^ $checksum] % 32;
						return self::rebuildAddress(array_merge($unexpand_prefix, $data));
					}
				}
				throw new CashAddressException('Can\'t correct typing errors!');
			}
		}
		return $data;
	}

	/**
	 * Corrects Cash Address typing errors.
	 * @param  string $inputNew Cash Address to be corrected.
	 * @return string $correctedAddress Error corrected address, or the input itself
	 * if there are no errors.
	 * @throws CashAddressException
	 */
	static public function fixCashAddrErrors($inputNew) {
		try {
			$corrected = self::decodeNewAddr($inputNew, true, $isTestnet);
			if (gettype($corrected) === 'array') {
				return $inputNew;
			} else {
				return $corrected;
			}
		}
		catch(CashAddressException $e) {
			throw $e;
		}
	}


	/**
	* new2old converts an address in the Cash Address format to the old format.
	* @param  string $inputNew Cash Address (either mainnet or testnet)
	* @param  boolean $shouldFixErrors Whether to fix typing errors.
	* @return string $oldAddress Old style 1... or 3... address
	* @throws CashAddressException
	*/
	static public function new2old($inputNew, $shouldFixErrors)
	{
		try {
			$corrected = self::decodeNewAddr($inputNew, $shouldFixErrors, $isTestnet);
			if (gettype($corrected) === 'array') {
				$values = $corrected;
			} else {
				$values = self::decodeNewAddr($corrected, false, $isTestnet);
			}
		}
		catch(Exception $e) {
			throw new CashAddressException('Error');
		}

		$values      = self::convertBits(array_slice($values, 0, sizeof($values) - 8), 5, 8, false);
		$addressType = $values[0] >> 3;
		$addressHash = array_slice($values, 1, 21);

		// Encode Address
		if ($isTestnet) {
			if ($addressType) {
				$bytes = [0xc4];
			} else {
				$bytes = [0x6f];
			}
		} else {
			if ($addressType) {
				$bytes = [0x05];
			} else {
				$bytes = [0x00];
			}
		}
		$bytes      = array_merge($bytes, $addressHash);
		$merged     = array_merge($bytes, self::doubleSha256ByteArray($bytes));
		$digits     = [0];
		$merged_len = sizeof($merged);
		for ($i = 0; $i < $merged_len; $i++)
		{
			$carry = $merged[$i];
			$digits_len = sizeof($digits);
			for ($j = 0; $j < $digits_len; $j++)
			{
				$carry      += $digits[$j] << 8;
				$digits[$j] = $carry % 58;
				$carry      = intdiv($carry, 58);
			}

			while ($carry !== 0)
			{
				array_push($digits, $carry % 58);
				$carry = intdiv($carry, 58);
			}
		}

		// leading zero bytes
		for ($i = 0; $i < $merged_len && $merged[$i] === 0; $i++)
		{
			array_push($digits, 0);
		}

		// reverse
		$converted = '';
		for ($i = sizeof($digits) - 1; $i >= 0; $i--)
		{
			if ($digits[$i] > strlen(self::ALPHABET))
			{
				throw new CashAddressException('Error!');
			}
			$converted .= self::ALPHABET[$digits[$i]];
		}

		return $converted;
	}

	/**
	 * internal function to calculate sha256
	 * @param  array $byteArray Byte array of data to be hashed
	 * @return array $hashResult First four bytes of sha256 result
	 */
	private static function doubleSha256ByteArray($byteArray) {
		$stringToBeHashed = '';
		$byteArrayLen = sizeof($byteArray);
		for ($i = 0; $i < $byteArrayLen; $i++)
		{
			$stringToBeHashed .= chr($byteArray[$i]);
		}
		$hash = hash('sha256', $stringToBeHashed);
		$hashArray = [];
		for ($i = 0; $i < 32; $i++)
		{
			array_push($hashArray, self::BASE16[ord($hash[2 * $i]) - 48] * 16 + self::BASE16[ord($hash[2 * $i + 1]) - 48]);
		}
		$stringToBeHashed = '';
		for ($i = 0; $i < 32; $i++)
		{
			$stringToBeHashed .= chr($hashArray[$i]);
		}

		$hashArray = [];
		$hash      = hash('sha256', $stringToBeHashed);
		for ($i = 0; $i < 4; $i++)
		{
			array_push($hashArray, self::BASE16[ord($hash[2 * $i]) - 48] * 16 + self::BASE16[ord($hash[2 * $i + 1]) - 48]);
		}
		return $hashArray;
	}

	/**
	 * Internal function to decode a Bech32 encoded address.
	 * @param  string $address The address to be decoded
	 * @return array $addressData The decoded address data
	 */
	private static function bechDecode($address) {
		$addressData = [];
		$len  = strlen($address);
		for ($pos = 0; $pos < $len; $pos++)
		{
			$i = ord($address[$pos]);
			if ($i > 127 || (($i = self::BECH_ALPHABET[$i]) === -1))
			{
				throw new CashAddressException('Unexpected character in address!');
			}
			array_push($addressData, $i);
		}

		return $addressData;
	}

	/**
	 * Internal function to encode cash address data using Bech32 encoding.
	 * @param array $addressData The address data to encode
	 * @return string $address The encoded address
	 */
	private static function bechEncode($addressData) {
		$address = '';
		$data_len = sizeof($addressData);

		for ($i = 0; $i < $data_len; $i++)
		{
			$address .= self::CHARSET[$addressData[$i]];
		}

		return $address;
	}

	/**
	 * Internal function to check if the given prefix matches the address
	 * @param  string $prefix The prefix to check the address against
	 * @param  string $addressNoPrefix The address and checksum without the
	 *         prefix
	 * @return bool $prefixMatch True if the prefix matches the address
	 */
	private static function checkPrefix($prefixData, $addressNoPrefix) {
		$addressData = self::bechDecode($addressNoPrefix);
		return self::polymod(array_merge($prefixData, $addressData)) === 0;
	}

	/**
	 * Attempt to retrieve the prefix from an address. Throws on failure.
	 * @param  string $addressNoPrefix The address and checksum without the
	 *         prefix
	 * @return string $prefix The address prefix
	 */
	public static function getPrefix($addressNoPrefix)
	{
		$prefixDatas = [
			'bitcoincash' => self::BCH_EXPAND_PREFIX_UNPROCESSED,
			'ecash' => self::ECASH_EXPAND_PREFIX_UNPROCESSED,
			'bchtest' => self::BCH_EXPAND_PREFIX_TESTNET_UNPROCESSED,
			'ectest' => self::ECASH_EXPAND_PREFIX_TESTNET_UNPROCESSED,
		];
		foreach ($prefixDatas as $prefix => $prefixData)
		{
			if (self::checkPrefix($prefixData, $addressNoPrefix) === True)
			{
				return $prefix;
			}
		}

		throw new CashAddressException('Cannot determine the prefix for the cash address: ' . $addressNoPrefix . '!');
		return "unknown";
	}

	/**
	 * Internal function to convert the output of the polymod computation to a
	 * properly formatted cash address checksum.
	 * @param  string $polymod The computed polymod value
	 * @return array $checksum The properly formatted checksum
	 */
	private static function checksum($polymod)
	{
		$checksum     = [0, 0, 0, 0, 0, 0, 0, 0];

		for ($i = 0; $i < 8; $i++)
		{
			// Convert the 5-bit groups in mod to checksum values.
			// $checksum[$i] = ($mod >> 5*(7-$i)) & 0x1f;
			$checksum[$i] = ($polymod >> (5 * (7 - $i))) & 0x1f;
		}

		return $checksum;
	}

	/**
	 * Internal function to build a cash address from the prefix and address.
	 * The address is expected to contain a checksum but might not match the
	 * prefix. The checksum of the output address is recomputed to match the
	 * given prefix.
	 * @param  string $prefix The prefix to use for building the cash address
	 * @param  array $prefixData The prefix formatted as the 5 lsb for each byte
	 *                           plus a trailing 0
	 * @param  string $addressNoPrefix The address and checksum without the
	 *         prefix
	 * @return string $address The new cash address
	 */
	private static function buildCashAddress($prefix, $prefixData, $addressNoPrefix)
	{
		$payload = self::bechDecode(substr($addressNoPrefix, 0, -8));

		$mod = self::polymod(array_merge($prefixData, $payload, [0, 0, 0, 0, 0, 0, 0, 0]));

		$addressData = array_merge($payload, self::checksum($mod));

		return $prefix . ':' . self::bechEncode($addressData);
	}

	/**
	 * Internal function to split a cash address parts. If the input address has
	   no prefix, it is determined from the remaining of the address.
	 * @param  string $address The cash address to split
	 * @return array $parts An array containg the prefix and the address content
	 */
	static private function splitCashAddress($address)
	{
		$parts = explode(':', $address);

		$addressNoPrefix = $parts[0];

		if (count($parts) > 2)
		{
			throw new CashAddressException('Multiple \':\' characters in address!');
		}
		else if (count($parts) > 1)
		{
			$prefix = $parts[0];
			$addressNoPrefix = $parts[1];
		}
		else
		{
			$prefix = self::getPrefix($address);
		}

		return array($prefix, $addressNoPrefix);
	}

	/**
	 * Convert a Bitcoin Cash address to an eCash address
	 * @param  string $address The Bitcoin Cash address to convert
	 * @return string $eCash The eCash address
	 */
	public static function bch2xec($address)
	{
		list($inputPrefix, $addressNoPrefix) = self::splitCashAddress($address);

		$prefix = "";
		$unexpanded_prefix = [];
		if ($inputPrefix === "bitcoincash")
		{
			$prefix = "ecash";
			$unexpanded_prefix = self::ECASH_EXPAND_PREFIX_UNPROCESSED;
		}
		else if ($inputPrefix === "bchtest")
		{
			$prefix = "ectest";
			$unexpanded_prefix = self::ECASH_EXPAND_PREFIX_TESTNET_UNPROCESSED;
		}
		else
		{
			throw new CashAddressException('Wrong prefix for bitcoincash address: ' . $inputPrefix . '!');
		}

		return self::buildCashAddress($prefix, $unexpanded_prefix, $addressNoPrefix);
	}

	/**
	 * Convert an eCash address to a Bitcoin Cash address
	 * @param  string $address The eCash address to convert
	 * @return string $bch The Bitcoin Cash address
	 */
	public static function xec2bch($address)
	{
		list($inputPrefix, $addressNoPrefix) = self::splitCashAddress($address);

		$prefix = "";
		$unexpanded_prefix = [];
		if ($inputPrefix === "ecash")
		{
			$prefix = "bitcoincash";
			$unexpanded_prefix = self::BCH_EXPAND_PREFIX_UNPROCESSED;
		}
		else if ($inputPrefix === "ectest")
		{
			$prefix = "bchtest";
			$unexpanded_prefix = self::BCH_EXPAND_PREFIX_TESTNET_UNPROCESSED;
		}
		else
		{
			throw new CashAddressException('Wrong prefix for eCash address: ' . $inputPrefix . '!');
		}

		return self::buildCashAddress($prefix, $unexpanded_prefix, $addressNoPrefix);
	}
}

?>
