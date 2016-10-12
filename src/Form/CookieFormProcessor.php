<?php

namespace Ixolit\Dislo\CDE\Form;

use Ixolit\Dislo\CDE\Interfaces\FormProcessorInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * This class was ported from the Piccolo form library with permission.
 */
class CookieFormProcessor implements FormProcessorInterface {
	/**
	 * {@inheritdoc}
	 */
	public function store(Form $form, ResponseInterface $response) {
		$dataset = [];

		foreach ($form->getFields() as $field) {
			$dataset[$field->getName()] = $field->getValue();
		}

		return $response->withAddedHeader(
			'Set-Cookie',
			\urlencode($form->getKey() . '-form') . '=' .
			\urlencode(\json_encode($dataset)));
	}

	/**
	 * {@inheritdoc}
	 */
	public function cleanup(Form $form, ResponseInterface $response) {
		return $response->withAddedHeader(
			'Set-Cookie',
			\urlencode($form->getKey() . '-form') . '=; expires=' . \date('r', 0)
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function restore(Form $form, ServerRequestInterface $request) {
		$cookies = $request->getCookieParams();

		if (\array_key_exists($form->getKey() . '-form', $cookies)) {
			try {
				$data = \json_decode($cookies[$form->getKey() . '-form'], true, 10);

				foreach ($form->getFields() as $field) {
					if (\array_key_exists($field->getName(), $data)) {
						$field->setValue($data[$field->getName()]);
					}
				}
			} catch (\Exception $e) {
				$form->validate();
			} catch (\Throwable $e) {
				$form->validate();
			}
			return true;
		} else {
			return false;
		}
	}
}
