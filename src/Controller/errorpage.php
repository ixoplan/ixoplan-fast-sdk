<?php

/**
 * @var Exception $e
 */
?><!DOCTYPE html>
<html>
	<head>
		<title>An error occurred</title>
	</head>
	<body>
		<h1>Uncaught <?=get_class($e)?> on <?=html($e->getFile() . ':' . $e->getLine())?></h1>
		<p><?=html($e->getMessage())?></p>
	</body>
</html>
