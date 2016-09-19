<?php

namespace Ixolit\Dislo\CDE\Interfaces;

interface MailAPI {
	public function sendPlainText($from, $to, $subject, $plainText, $cc = [], $bcc = []);

	public function sendHTML($from, $to, $subject, $html, $cc = [], $bcc = []);

	public function sendMixed($from, $to, $subject, $plainText, $html, $cc = [], $bcc = []);
}