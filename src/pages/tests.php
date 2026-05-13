<?php
/**
 * @var array $parts
 * @var string $includesDir
 * @var string $cssDir
 * @var string $jsDir
 */

$alertSamples = [
	'success' => 'Success: changes saved.',
	'error' => 'Error: could not save changes.',
	'warning' => 'Warning: please double-check your entries.',
	'info' => 'Info: this is a sample alert.',
	'dev' => 'Debug: sample dev alert. APP_DEBUG must be true.',
];

$debugEnabled = ($_ENV['APP_DEBUG'] ?? 'false') === 'true';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$action = $_POST['action'] ?? 'single';

	if ($action === 'all') {
		foreach ($alertSamples as $type => $message) {
			switch ($type) {
				case 'success':
					Alert::success($message);
					break;
				case 'error':
					Alert::error($message);
					break;
				case 'warning':
					Alert::warning($message);
					break;
				case 'info':
					Alert::info($message);
					break;
				case 'dev':
					Alert::dev($message);
					break;
			}
		}
	} else {
		$type = $_POST['alert_type'] ?? 'info';
		if (!array_key_exists($type, $alertSamples)) {
			$type = 'info';
		}

		$message = trim($_POST['message'] ?? '');
		if ($message === '') {
			$message = $alertSamples[$type];
		}

		switch ($type) {
			case 'success':
				Alert::success($message);
				break;
			case 'error':
				Alert::error($message);
				break;
			case 'warning':
				Alert::warning($message);
				break;
			case 'info':
				Alert::info($message);
				break;
			case 'dev':
				Alert::dev($message);
				break;
			default:
				Alert::info($alertSamples['info']);
				break;
		}
	}

	header('Location: /tests');
	exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, interactive-widget=resizes-content, viewport-fit=cover">
	<title>Alert Tests | Innerspace</title>
	<link rel="stylesheet" href="<?= $cssDir ?>">
	<link rel="shortcut icon" href="/assets/images/favicon.png" type="image/png">
	<script src="<?= $jsDir ?>" defer></script>
	<link rel="manifest" href="/manifest.json">
	<meta name="theme-color" content="#0f3460">

	<style>
		.alert-test-header {
			margin-bottom: 2rem;
		}

		.alert-test-header p {
			color: rgba(163, 196, 243, 0.75);
			font-size: 18px;
		}

		.alert-test-meta {
			margin-top: 0.5rem;
			display: flex;
			align-items: center;
			gap: 0.75rem;
		}

		.alert-test-badge {
			display: inline-flex;
			align-items: center;
			padding: 0.2rem 0.6rem;
			border: 1px solid rgba(163, 196, 243, 0.4);
			background: rgba(163, 196, 243, 0.1);
			font-size: 16px;
			text-transform: uppercase;
			letter-spacing: 1px;
		}

		.alert-test-badge.is-on {
			border-color: #2d5a3d;
			background: rgba(45, 90, 61, 0.2);
			color: #7edaa0;
		}

		.alert-test-badge.is-off {
			border-color: #5a2d2d;
			background: rgba(90, 45, 45, 0.2);
			color: #e87878;
		}

		.alert-test-grid {
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
			gap: 1.5rem;
		}

		.alert-test-card {
			background: #16213e;
			border: 2px solid rgba(163, 196, 243, 0.25);
			box-shadow: 4px 4px 0 #0a0a1a;
			padding: 1.25rem;
			display: flex;
			flex-direction: column;
			gap: 0.75rem;
		}

		.alert-test-note {
			color: rgba(163, 196, 243, 0.7);
			font-size: 16px;
		}

		.alert-test-actions {
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(110px, 1fr));
			gap: 0.75rem;
		}

		.alert-test-form {
			display: flex;
			flex-direction: column;
			gap: 0.75rem;
		}

		.alert-test-form label {
			font-size: 18px;
		}

		.alert-test-form input[type="text"],
		.alert-test-form select,
		.alert-test-form textarea {
			width: 100%;
			padding: 0.5rem;
			border: 1px solid rgba(163, 196, 243, 0.35);
			background: rgba(163, 196, 243, 0.08);
			color: #e9e7ff;
			font-family: "VT323", monospace;
			font-size: 18px;
		}

		.alert-test-button {
			cursor: pointer;
			padding: 0.5rem 0.75rem;
			border: 2px solid rgba(163, 196, 243, 0.6);
			background: rgba(163, 196, 243, 0.12);
			color: #e9e7ff;
			font-family: "VT323", monospace;
			font-size: 20px;
			box-shadow: 3px 3px 0 #0a0a1a;
			transition: transform 0.08s ease, box-shadow 0.08s ease;
		}

		.alert-test-button:hover {
			transform: translate(-2px, -2px);
			box-shadow: 5px 5px 0 #0a0a1a;
		}

		.alert-test-success {
			border-color: #2d5a3d;
			background: rgba(45, 90, 61, 0.2);
			color: #7edaa0;
		}

		.alert-test-error {
			border-color: #5a2d2d;
			background: rgba(90, 45, 45, 0.2);
			color: #e87878;
		}

		.alert-test-warning {
			border-color: #5a4d2d;
			background: rgba(90, 77, 45, 0.2);
			color: #e8c778;
		}

		.alert-test-info {
			border-color: #2d425a;
			background: rgba(45, 66, 90, 0.2);
			color: #78c4e8;
		}

		.alert-test-dev {
			border-color: #4d2d5a;
			background: rgba(77, 45, 90, 0.2);
			color: #c478e8;
		}

		.alert-test-all {
			border-color: rgba(163, 196, 243, 0.75);
			background: rgba(163, 196, 243, 0.2);
		}
	</style>
</head>

<body>
	<div class="page">
		<div class="pixel-scanlines"></div>
		<div class="content">
			<?php include $includesDir . '/navbar.php'; ?>
			<div class="alerts-wrapper">
				<?php include $includesDir . '/alerts.php'; ?>
			</div>

			<div class="main">
				<div class="alert-test-header">
					<h1>Alert Test Lab</h1>
					<p>Trigger each alert type and confirm the visual style. Alerts auto-hide after 3 seconds.</p>
					<div class="alert-test-meta">
						<span>APP_DEBUG</span>
						<span class="alert-test-badge <?= $debugEnabled ? 'is-on' : 'is-off' ?>">
							<?= $debugEnabled ? 'enabled' : 'disabled' ?>
						</span>
					</div>
				</div>

				<div class="alert-test-grid">
					<section class="alert-test-card">
						<h2>Quick triggers</h2>
						<p class="alert-test-note">Click any button to fire a sample alert.</p>
						<form method="post" class="alert-test-actions">
							<input type="hidden" name="csrf_token" value="<?= htmlspecialchars(Csrf::token()) ?>">
							<button type="submit" name="alert_type" value="success" class="alert-test-button alert-test-success">Success</button>
							<button type="submit" name="alert_type" value="error" class="alert-test-button alert-test-error">Error</button>
							<button type="submit" name="alert_type" value="warning" class="alert-test-button alert-test-warning">Warning</button>
							<button type="submit" name="alert_type" value="info" class="alert-test-button alert-test-info">Info</button>
							<button type="submit" name="alert_type" value="dev" class="alert-test-button alert-test-dev">Dev</button>
						</form>
					</section>

					<section class="alert-test-card">
						<h2>Custom message</h2>
						<form method="post" class="alert-test-form">
							<input type="hidden" name="csrf_token" value="<?= htmlspecialchars(Csrf::token()) ?>">
							<label for="alert_type">Alert type</label>
							<select id="alert_type" name="alert_type">
								<option value="success">Success</option>
								<option value="error">Error</option>
								<option value="warning">Warning</option>
								<option value="info" selected>Info</option>
								<option value="dev">Dev</option>
							</select>
							<label for="alert_message">Message</label>
							<textarea id="alert_message" name="message" rows="4" placeholder="Write a custom alert message..."></textarea>
							<button type="submit" class="alert-test-button">Send alert</button>
						</form>
					</section>

					<section class="alert-test-card">
						<h2>Full stack</h2>
						<p class="alert-test-note">Fire every alert type at once.</p>
						<form method="post">
							<input type="hidden" name="csrf_token" value="<?= htmlspecialchars(Csrf::token()) ?>">
							<input type="hidden" name="action" value="all">
							<button type="submit" class="alert-test-button alert-test-all">Trigger all alerts</button>
						</form>
					</section>
				</div>
			</div>

			<?php include $includesDir . '/footer.php'; ?>
		</div>
	</div>
</body>

</html>
