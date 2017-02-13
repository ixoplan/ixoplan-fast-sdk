<!DOCTYPE html>
<html>
<?php
/**
 * @var Exception $e
 */
?>
<head>
	<title>An error occurred</title>

	<style type="text/css">
		* {
			box-sizing: border-box;
		}
		body {
			background: #eee;
			font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
			font-size: 14px;
			margin: 0;
			padding: 0;
			-webkit-font-smoothing: antialiased;
			-moz-osx-font-smoothing: grayscale;
		}
		.holder {
			padding: 10px 15px;
			background: #fff;
			border-radius: 10px;
			border: 1px solid #ddd;
			margin: 20px auto;
			max-width: 1100px;
		}
		.holder-header {
			margin: -10px -15px 10px;
			padding: 10px 15px;
			background: #ddd;
			border-radius: 8px 8px 0 0;
		}
		h1 {
			font-weight: normal;
			margin: 0;
			font-size: 22px;
		}
		h2 {
			font-weight: normal;
			margin: 0;
			font-size: 22px;
		}
		.underline {
			text-decoration: underline;
		}
		ol li {
			margin-bottom: 5px;
			padding-left: 5px;
		}
		.text-mute {
			color: #999;
		}
	</style>
</head>
<body>
<div class="holder">
	<h1>Sorry, an error occurred.</h1>
</div>

<div class="holder">
	<div class="holder-header">
		<h2><b><?=get_class($e)?></b> in <b><?=html($e->getFile() . ':' . $e->getLine())?></b></h2>
	</div>

	<h4><?=html($e->getMessage())?></h4>

	<ol>
		<?php foreach($e->getTrace() as $trace): ?>
			<li><span class="underline"><?= $trace['class'] ?></span>-><?= $trace['function'] ?>(<?= implode(', ', $trace['args']) ?>) <span class="text-mute">in <span class="underline"><?= $trace['file'] ?></span>:<?= $trace['line'] ?></span></li>
		<?php endforeach; ?>
	</ol>
</div>
</body>
</html>