<!DOCTYPE html>
<html lang="ru">
	<?php $this->render('../common/_head'); ?>
	<body>
		<!-- Preloader -->
		<div id="preloader">
			<div class="cssload-whirlpool"></div>
		</div>

		<!-- Header -->
		<?php $this->render('../common/_header'); ?>

		<!-- Page Title -->
		<section class="page-title-panel with-descr">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="page-title">
							<h1>Minexcoin address</h1>
							<p>Addresses are identifiers that you use to send minexcoins to another person.</p>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- Main -->
		<main id="main">
			<div class="container">
				<div class="row mb-30">
					<div class="col-md-2">
						<div class="qr-code"><img src="https://chart.googleapis.com/chart?cht=qr&chs=200&chl=<?php echo $ignoredAddress != $address['address'] ? $address['address'] : ''; ?>"></div>
					</div>
					<div class="col-md-5">
						<table class="table info-table address-table">
							<tbody>
								<tr>
									<td>General info</td>
									<td> </td>
								</tr>
								<tr>
									<td>Address</td>
									<td>
										<div class="hash-value"><?php echo $address['address']; ?></div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-md-5">
						<table class="table info-table address-table">
							<tbody>
								<tr>
									<td>Transactions</td>
									<td> </td>
								</tr>
								<tr>
									<td>Number of transactions</td>
									<td><?php echo $items; ?></td>
								</tr>
								<tr>
									<td>Total received</td>
									<td>
									<?php
										$received = 0;
										if (count($outputs) > 0) {
											foreach ($outputs as $items) {
												foreach ($items as $output)
													$received = round($received + (float)$output['amount'], 8);
											}
										}

										$sent = 0;

										if (count($inputs) > 0) {
											foreach ($inputs as $items) {
												foreach ($items as $input)
													$sent = round($sent + (float)$input['amount'], 8);
										}
									}

									echo Money::formateWithCurrency($received);
									?>
									</td>
								</tr>
								<tr>
									<td>Total balance</td>
									<td><?php echo Money::formateWithCurrency($received-$sent); ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="block-title">
							<h2>Transactions</h2>
						</div>
						<?php
							$noreverse = true;
							$this->render('_transactions', compact('transactions','ignoredAddress', 'noreverse'));
							echo $paginator;
						?>
					</div>
				</div>
			</div>
		</main>

		<!-- Footer -->
		<?php $this->render('../common/_footer'); ?>
	</body>
</html>
