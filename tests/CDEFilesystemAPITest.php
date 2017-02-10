<?php

namespace Ixolit\Dislo\CDE;

use Ixolit\CDE\CDEFilesystemAPI;
use Ixolit\CDE\Exceptions\FileNotFoundException;
use Ixolit\CDE\WorkingObjects\FilesystemEntry;
use Ixolit\Dislo\CDE\UnitTest\CDEUnitTest;

class CDEFilesystemAPITest extends CDEUnitTest {
	public function testExists() {
		//setup
		$api = new CDEFilesystemAPI();
		//act
		$this->assertFalse($api->exists(__DIR__ . '/../testdata/filesystem/file0'));
		$this->assertTrue($api->exists(__DIR__ . '/../testdata/filesystem/file1'));
		$this->assertTrue($api->exists(__DIR__ . '/../testdata/filesystem/subdirectory'));
		$this->assertFalse($api->exists(__DIR__ . '/../testdata/filesystem/nonexistent'));
	}

	public function testNonexistentPathInfo() {
		//setup
		$api = new CDEFilesystemAPI();
		//act
		try {
			$api->pathInfo(__DIR__ . '/../testdata/filesystem/nonexistent');
			$this->fail();
		} catch (FileNotFoundException $e) {

		}
	}

	public function testFilePathInfo() {
		//setup
		$api = new CDEFilesystemAPI();
		//act
		$pathInfo = $api->pathInfo(__DIR__ . '/../testdata/filesystem/file1');
		//assert
		$this->assertEquals(
			preg_replace('/^vfs:/', '/', __DIR__ . '/../testdata/filesystem/file1'),
			$pathInfo->getName());
		$this->assertEquals(0, $pathInfo->getSize());
		$this->assertEquals('file', $pathInfo->getType());
	}

	public function testDirectoryPathInfo() {
		//setup
		$api = new CDEFilesystemAPI();
		//act
		$pathInfo = $api->pathInfo(__DIR__ . '/../testdata/filesystem/subdirectory');
		//assert
		$this->assertEquals(
			preg_replace('/^vfs:/', '/', __DIR__ . '/../testdata/filesystem/subdirectory'),
			$pathInfo->getName()
		);
		$this->assertEquals('directory', $pathInfo->getType());
	}

	public function testNonexistentListDirectory() {
		//setup
		$api = new CDEFilesystemAPI();
		//act
		try {
			$api->listDirectory(__DIR__ . '/../testdata/filesystem/nonexistent');
			$this->fail();
		} catch (FileNotFoundException $e) {

		}
	}

	public function testListDirectory() {
		//setup
		$api = new CDEFilesystemAPI();
		//act
		/**
		 * @var FilesystemEntry[] $files
		 */
		$files = $api->listDirectory(__DIR__ . '/../testdata/filesystem/subdirectory');
		//assert
		$this->assertEquals(1, count($files));
		$file = $files[0];
		$this->assertEquals(
			preg_replace('/^vfs:/', '/', __DIR__ . '/../testdata/filesystem/subdirectory/file3'),
			$file->getName()
		);
		$this->assertEquals('file', $file->getType());
	}
}