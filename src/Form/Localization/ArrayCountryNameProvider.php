<?php

namespace Ixolit\Dislo\CDE\Form\Localization;

abstract class ArrayCountryNameProvider implements CountryNameProvider  {
	protected abstract function getMapping();

	public function getCountryName($code) {
		if (isset($this->getMapping()[$code])) {
			return $this->getMapping()[$code];
		} else {
			return '';
		}
	}

	public static function getNameProvider($languageCode) {
		switch ($languageCode) {
			case 'de':
				return new GermanCountryNameProvider();
			case 'nl':
				return new DutchCountryNameProvider();
			default:
				return new EnglishCountryNameProvider();
		}
	}
}
