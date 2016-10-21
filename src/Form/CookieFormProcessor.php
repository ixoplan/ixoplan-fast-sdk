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
			$dataset[$field->getName()] = [];
			if (!$field->isMasked()) {
				$dataset[$field->getName()]['value'] = $field->getValue();
			}
			$dataset[$field->getName()]['errors'] = $field->getErrors();
		}

		$dataset['_errors'] = $form->getErrors();

		return $response->withAddedHeader(
			'Set-Cookie',
			\urlencode($form->getKey() . '-form') . '=' .
			\urlencode(\base64_encode(\json_encode($dataset))));
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
				$data = \json_decode(\base64_decode($cookies[$form->getKey() . '-form']), true);

				foreach ($form->getFields() as $field) {
					if (\array_key_exists($field->getName(), $data)) {
						if (isset($data[$field->getName()]['value'])) {
							$field->setValue($data[$field->getName()]['value']);
						}
						$field->setErrors($data[$field->getName()]['errors']);
					}
				}
				if (isset($data['_errors'])) {
					$form->setErrors($data['_errors']);
				}
			} catch (\Exception $e) {
			} catch (\Throwable $e) {
			}
			return true;
		} else {
			return false;
		}
	}
}
