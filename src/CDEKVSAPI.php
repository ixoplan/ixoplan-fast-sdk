<?php

namespace Ixolit\Dislo\CDE;

use Ixolit\Dislo\CDE\Exceptions\CDEFeatureNotSupportedException;
use Ixolit\Dislo\CDE\Exceptions\KVSKeyNotFoundException;
use Ixolit\Dislo\CDE\WorkingObjects\KVSEntry;
use Ixolit\Dislo\CDE\WorkingObjects\KVSKey;

/**
 * This API implements the KVS API using the CDE API calls.
 */
class CDEKVSAPI implements Interfaces\KVSAPI {
	/**
	 * {@inheritdoc}
	 */
	public function find($expression, $limit = null, $offset = null, $ordering = null) {
		if (!\function_exists('kvsFind')) {
			throw new CDEFeatureNotSupportedException('kvsFind');
		}
		$kvsEntries = \kvsFind($expression, $limit = null, $offset = null, $ordering = null);
		$result = [];
		foreach ($kvsEntries as $kvsEntry) {
			$result[] = new KVSEntry(
				$kvsEntry->key,
				$kvsEntry->version,
				$kvsEntry->value
			);
		}
		return $result;
	}

	/**
	 * {@inheritdoc}
	 */
	public function findKeys($expression, $limit = null, $offset = null, $ordering = null) {
		if (!\function_exists('kvsFindKey')) {
			throw new CDEFeatureNotSupportedException('kvsFindKey');
		}
		$kvsEntries = \kvsFindKey($expression, $limit = null, $offset = null, $ordering = null);
		$result = [];
		foreach ($kvsEntries as $kvsEntry) {
			$result[] = new KVSKey(
				$kvsEntry->key,
				$kvsEntry->version
			);
		}
		return $result;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get($key) {
		if (!\function_exists('kvsGet')) {
			throw new CDEFeatureNotSupportedException('kvsGet');
		}
		$result = \kvsGet($key);

		if ($result === null) {
			throw new KVSKeyNotFoundException($key);
		}

		return $result;
	}
}
