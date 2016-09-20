<?php

namespace Ixolit\Dislo\CDE\Interfaces;

use Ixolit\Dislo\CDE\Exceptions\MailSendingFailedException;

/**
 * This API gives access to the CDE's mail facilities, enabling the simple sending of e-mails.
 */
interface MailAPI {
	/**
	 * Send a plain text only e-mail.
	 *
	 * @param string $from
	 * @param string $to
	 * @param string $subject
	 * @param string $plainText
	 * @param array $cc
	 * @param array $bcc
	 *
	 * @throws MailSendingFailedException
	 */
	public function sendPlainText($from, $to, $subject, $plainText, $cc = [], $bcc = []);

	/**
	 * Send a mixed-content e-mail (both HTML and plain text)
	 *
	 * @param string $from
	 * @param string $to
	 * @param string $subject
	 * @param string $plainText
	 * @param string $html
	 * @param array $cc
	 * @param array $bcc
	 *
	 * @throws MailSendingFailedException
	 */
	public function sendMixed($from, $to, $subject, $plainText, $html, $cc = [], $bcc = []);
}