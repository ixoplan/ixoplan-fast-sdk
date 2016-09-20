<?php

namespace Ixolit\Dislo\CDE\WorkingObjects;

use Ixolit\Dislo\CDE\Exceptions\CDEFeatureNotSupportedException;
use Ixolit\Dislo\CDE\Exceptions\MailSendingFailedException;
use Ixolit\Dislo\CDE\Interfaces\MailAPI;

class CDEMailAPI implements MailAPI {

	/**
	 * {@inheritdoc}
	 */
	public function sendPlainText($from, $to, $subject, $plainText, $cc = [], $bcc = []) {
		if (!\function_exists('sendMail')) {
			throw new CDEFeatureNotSupportedException('sendMail');
		}
		if (!sendMail(
			$from,
			$to,
			$subject,
			$plainText,
			[
				'cc' => $cc,
				'bcc' => $bcc,
			]
		)) {
			throw new MailSendingFailedException();
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function sendMixed($from, $to, $subject, $plainText, $html, $cc = [], $bcc = []) {
		if (!\function_exists('sendMail')) {
			throw new CDEFeatureNotSupportedException('sendMail');
		}
		if (!sendMail(
			$from,
			$to,
			$subject,
			[
				'text' => $plainText,
				'html' => $html,
			],
			[
				'cc' => $cc,
				'bcc' => $bcc,
			]
		)) {
			throw new MailSendingFailedException();
		}
	}
}