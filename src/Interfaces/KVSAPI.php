<?php

namespace Dislo\CDE\SDK\Interfaces;

use Dislo\CDE\SDK\Exceptions\KVSKeyNotFoundException;
use Dislo\CDE\SDK\WorkingObjects\KVSEntry;
use Dislo\CDE\SDK\WorkingObjects\KVSKey;

interface KVSAPI {
	const ORDERING_NONE = 'none';
	const ORDERING_ASC = 'asc';
	const ORDERING_DESC = 'desc';

	/**
	 * Returns the list of entries found in the key-value store (KVS) that match $expr.
	 *
	 * @param string      $expression describes the hierachical path in the KVS. It consists of parts in the key
	 *                                hierachy that are concatenated by `"."`. Each part can either be an exact key
	 *                                part (like `"blog_posts"`), match the prefix of a key (like `"blog_*"`) or a
	 *                                wildcard that matches any key part (`"*"`). A complete $expr could look like this:
	 *                                `"blog_posts.y201*.*"`.
	 * @param null|int    $limit      defines the maximum number of results returned by kvsFind.
	 * @param null|int    $offset     if given, the result list starts at that offset (`$offset` without `$limit` is
	 *                                not allowed)
	 * @param null|string $ordering   sorts the results by their key, it can either be asc, desc or none.
	 *
	 * @return KVSEntry[]
	 */
	public function find($expression, $limit = null, $offset = null, $ordering = null);

	/**
	 * Returns the list of keys found in the key-value store (KVS) that match $expr.
	 *
	 * @param string      $expression describes the hierarchical path in the KVS. It consists of parts in the key
	 *                                hierarchy that are concatenated by `"."`. Each part can either be an exact key
	 *                                part (like `"blog_posts"`), match the prefix of a key (like `"blog_*"`) or a
	 *                                wildcard that matches any key part (`"*"`). A complete $expr could look like this:
	 *                                `"blog_posts.y201*.*"`.
	 * @param null|int    $limit      defines the maximum number of results returned by kvsFind.
	 * @param null|int    $offset     if given, the result list starts at that offset (`$offset` without `$limit` is
	 *                                not allowed)
	 * @param null|string $ordering   sorts the results by their key, it can either be asc, desc or none.
	 *
	 * @return KVSKey[]
	 */
	public function findKeys($expression, $limit = null, $offset = null, $ordering = null);

	/**
	 * Returns the value for a given key in the key-value store (KVS).
	 *
	 * @param string $key describes the hierarchical path in the KVS. It consists of parts in the key hierarchy that
	 *                    are concatenated by `"."`. A complete $key could look like this: `"blog_posts.y2014.m03"`.
	 *
	 * @return \stdClass
	 *
	 * @throws KVSKeyNotFoundException if the key was not found in the KVS.
	 */
	public function get($key);
}
