<?php

namespace Dislo\CDE\SDK\Interfaces;

interface FilesystemAPI {
	public function exists($path);

	public function pathInfo($path);

	public function listDirectory($directory);
}
