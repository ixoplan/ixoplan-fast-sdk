<style>
	.cdeToolbar {
		font-family:sans-serif;
		font-size:14px !important;
		line-height:50px !important;
		height:50px !important;
		width:100% !important;
		position:fixed !important;
		bottom:0 !important;
		left:0 !important;
		right:0 !important;
		border-top:1px solid #ccc !important;
		background:#f0f0f0 !important;
		color:#333 !important;
		overflow-y:hidden !important;
		z-index: 99999 !important;
		box-shadow: 0 0 10px 0 rgba(0,0,0,0.3) !important;
	}

	.cdeToolbar.small {
		position:fixed !important;
		bottom:0 !important;
		left:0 !important;
		right:auto !important;
		width:auto !important;
	}

	.cdeToolbar.small .cdeToolbarItem {
		display:none !important;
	}
	.cdeToolbar.small .cdeToolbarItem.cdeToggleToolbar {
		display:block !important;
	}

	.cdeToolbar .cdeToolbarItem {
		float:left !important;
		display:inline-block !important;
		border-right:1px solid #ccc !important;
	}

	.cdeToolbar .cdeToolbarItem.cdeToggleToolbar img {
		margin:5px;
	}

	.cdeToolbar .cdeToolbarItem>a,.cdeToolbar .cdeToolbarItem>span {
		display:inline-block !important;
		padding-left:10px !important;
		padding-right:10px !important;
	}

	.cdeToolbar a.cdeToolbarItem,
	.cdeToolbar a.cdeToolbarItem:hover,
	.cdeToolbar a.cdeToolbarItem:active,
	.cdeToolbar .cdeToolbarItem a,
	.cdeToolbar .cdeToolbarItem a:hover,
	.cdeToolbar .cdeToolbarItem a:active {
		text-decoration: none !important;
	}

	.cdeToolbar a.cdeToolbarItem,
	.cdeToolbar .cdeToolbarItem a {
		color:#6699CC !important;
		cursor:pointer !important;
	}

	.cdeToolbar a.cdeToolbarItem:hover,
	.cdeToolbar .cdeToolbarItem a:hover{
		background-color:#6699CC !important;
		color:#fff !important;
	}

	.cdeToolbar .cdeUnitTestList {
		border:1px solid #ccc !important;
		background-color:#f0f0f0 !important;
		position:fixed !important;
		bottom:50px !important;
		margin-left:-1px !important;
		box-shadow: 0 0 10px 0 rgba(0,0,0,0.3) !important;
		display:none !important;
	}

	.cdeToolbar .cdeToolbarItem.active a {
		background-color:#6699CC !important;
		color:#fff !important;
	}

	.cdeToolbar .cdeToolbarItem.active .cdeUnitTestList {
		display:block !important;
	}

	.cdeToolbar .cdeUnitTestList .cdeUnitTestClass {
		border-top:1px solid #ccc;
		position:relative;
	}
	.cdeToolbar .cdeUnitTestList .cdeUnitTestClass:last-child {
		border-bottom:0;
	}
	.cdeToolbar .cdeUnitTestList .cdeUnitTestClass label.cdeUnitTestClassSelector,
	.cdeToolbar .cdeUnitTestList .cdeRunUnitTestsButton {
		height:50px;
		line-height:50px;
		padding-left:10px;
		padding-right:120px;
		display:block;
		cursor:pointer;
		overflow-y:hidden;
	}

	.cdeToolbar .cdeUnitTestList .cdeUnitTestClass label.cdeUnitTestClassSelector:hover,
	.cdeToolbar .cdeUnitTestList .cdeRunUnitTestsButton:hover {
		background-color:#6699CC !important;
		color:#fff !important;
	}

	.cdeToolbar .cdeUnitTestList .cdeRunUnitTestsButton {
		font-weight:bold;
	}

	.cdeTestStatus {
		position:absolute;
		top:0;
		right:10px;
		width:100px;
		margin-right:10px;
	}

	.cdeTestStatus .cdeProgressBar {
		display:none;
		position:relative;
		height:20px;
		width:100px;
		background:#fff;
		margin-top:15px;
		margin-bottom:15px;
		margin-left:15px;
	}

	.cdeTestStatus.active .cdeProgressBar {
		display: inline-block;
	}

	.cdeTestStatus .cdeProgressBar .cdeProgressBarContent {
		float:left;
		display:inline-block;
		height:100%;
	}
	.cdeTestStatus .cdeProgressBar .cdeProgressBarContentSuccess {
		background-color:#66CC99 !important;
	}
	.cdeTestStatus .cdeProgressBar .cdeProgressBarContentFail {
		background-color:#CC6666 !important;
	}

	.cdeProgressBarContentText {
		position:absolute;
		top:0;
		left:0;
		right:0;
		bottom:0;
		line-height:20px;
		text-align:center;
		font-size:10px;
		color:#fff;
	}
</style>
<div class="cdeToolbar small" id="cdeToolbar">
	<a href="#cdeToolbar" class="cdeToolbarItem cdeToggleToolbar" id="cdeToggleToolbar" title="Toggle CDE toolbar">
		<img
			alt="Dislo"
			title="Show/hide Dislo toolbar"
			height="40"
			width="47"
			src="data:image/png;base64,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"
		/>
	</a>
	<?php if (isset($previewVersion) && $previewVersion) : ?>
		<div class="cdeToolbarItem">
			<span>Preview version: <?=$previewVersion->format('Y-m-d H:i:s')?> UTC</span>
		</div><?php
	endif;
	if (isset($previewUrl) && $previewUrl) :
		?><a href="<?=html($previewUrl)?>" class="cdeToolbarItem">
			<span>Leave preview session</span>
		</a><?php
	endif;
	?><div class="cdeToolbarItem">
		<a href="#cdeUnitTestList" id="cdeUnitTestButton">
			Unit tests ▲
		</a>
		<div class="cdeUnitTestList" id="cdeUnitTestList">
			<div class="cdeRunUnitTestsButton" id="cdeRunUnitTestsButton">
				Run selected tests...
			</div>
			<?php foreach ($unitTests as $testClass => $testMethods) :?>
				<div class="cdeUnitTestClass" id="cdeUnitTestClass_<?=html(str_replace('\\','',$testClass))?>">
					<label for="cdeUnitTestClassSelector_<?=html(str_replace('\\','',$testClass))?>"
						   class="cdeUnitTestClassSelector">
						<input type="checkbox" name="cdeUnitTestClasses[]"
						   id="cdeUnitTestClassSelector_<?=html(str_replace('\\','',$testClass))?>"
						   checked="checked" value="<?=html($testClass)?>" class="cdeUnitTestClassSelectorCheckbox" />
						<?=html($testClass)?>

						<span class="cdeTestStatus" title="Check browser developer console for details.">
							<span class="cdeProgressBar">
								<span class="cdeProgressBarContent cdeProgressBarContentSuccess" style="width:0"></span>
								<span class="cdeProgressBarContent cdeProgressBarContentFail" style="width:0"></span>
								<span class="cdeProgressBarContentText"></span>
							</span>
						</span>
					</label>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
<script>
	(function() {
		var unitTests = <?=\js($unitTests)?>;

		var toggleClass = function(element, className) {
			var classes = element.className.split(' ');
			var i = classes.indexOf(className);
			if (i !== -1) {
				classes.splice(i);
			} else {
				classes.push(className);
			}
			element.className = classes.join(' ');
		};

		var addClass = function(element, className) {
			var classes = element.className.split(' ');
			var i = classes.indexOf(className);
			if (i === -1) {
				classes.push(className);
			}
			element.className = classes.join(' ');
		};

		var removeClass = function(element, className) {
			var classes = element.className.split(' ');
			var i = classes.indexOf(className);
			if (i !== -1) {
				classes.splice(i);
			}
			element.className = classes.join(' ');
		};


		document.getElementById('cdeToggleToolbar').addEventListener('click', function (e) {
			e.stopPropagation();
			e.preventDefault();

			toggleClass(document.getElementById('cdeToolbar'), 'small');
		});

		document.getElementById('cdeUnitTestButton').addEventListener('click', function (e) {
			e.stopPropagation();
			e.preventDefault();

			toggleClass(this.parentElement, 'active');
		});

		document.getElementById('cdeRunUnitTestsButton').addEventListener('click', function (e) {
			var checkboxes = document.getElementsByClassName('cdeUnitTestClassSelectorCheckbox');
			var selectedTests = [];
			var testResults = {};
			var totalTestCounts = {};
			for (var i in checkboxes) {
				if (checkboxes.hasOwnProperty(i) && checkboxes[i].checked) {
					testResults[checkboxes[i].value] = {};
					totalTestCounts[checkboxes[i].value] = 0;
					for (var j in unitTests[checkboxes[i].value]) {
						if (unitTests[checkboxes[i].value].hasOwnProperty(j)) {
							selectedTests.push({
								'className':checkboxes[i].value,
								'methodName':unitTests[checkboxes[i].value][j]});
							totalTestCounts[checkboxes[i].value] += 1;
						}
					}
				}
			}

			var processTestQueue = function () {
				if (selectedTests.length > 0) {
					var selectedTest = selectedTests.shift();
					var xhr = new XMLHttpRequest();
					xhr.onreadystatechange = function () {
						if (this.readyState == 4) {
							var success = false;
							var response = JSON.parse(this.responseText);
							if (this.status == 200) {
								if (response == 'pass') {
									success = true;
								}
								testResults[selectedTest.className][selectedTest.methodName] = response;
								console.log(selectedTest.className + '::' + selectedTest.methodName + '(): ' + response)
							} else {
								testResults[selectedTest.className][selectedTest.methodName] = 'fail';
								console.log(selectedTest.className + '::' + selectedTest.methodName +
									'(): fail (HTTP status code ' + this.status + ' returned)')
							}

							var element =
								document.getElementById('cdeUnitTestClass_' +
									selectedTest.className.replace(/\\/g, ''));

							var statusElement = element.getElementsByClassName('cdeTestStatus')[0];

							addClass(statusElement, 'active');

							statusElement.getElementsByClassName('cdeProgressBarContent' +
								(success?'Success':'Fail'))[0].style.width =
								(Math.round(
									100 * Object.keys(testResults[selectedTest.className]).length /
									totalTestCounts[selectedTest.className])) + '%';

							var passed = 0;
							var failed = 0;
							for (var i in testResults[selectedTest.className]) {
								if (testResults[selectedTest.className].hasOwnProperty(i)) {
									if (testResults[selectedTest.className][i] == 'pass') {
										passed++;
									} else {
										failed++;
									}
								}
							}

							statusElement.getElementsByClassName('cdeProgressBarContentText')[0].innerText =
								passed + ' passed, ' + failed + ' failed';

							processTestQueue();
						}
					};
					xhr.open('GET', '?__cde_toolbar_action=unittest_execute&' +
						'__cde_toolbar_unittest_class=' + encodeURIComponent(selectedTest.className) + '&' +
						'__cde_toolbar_unittest_method=' + encodeURIComponent(selectedTest.methodName),
						true);
					xhr.send();
				}
			};

			processTestQueue();
		});
	})();
</script>