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

		<!-- Main -->
		<main id="main">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<table class="table transactions-table">
							<thead>
								<tr>
									<th colspan="3"> Transaction: <a class="hash-value" href="?r=explorer/tx&hash=<?php echo $transaction['hash']; ?>"><?php echo $transaction['hash']; ?></a></th>
									<th>
										<div class="date-time"><?php echo date('Y-m-d H:i:s', $transaction['createdAt']); ?></div>
									</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<div class="td-title">Inputs</div>
									</td>
									<td>
										<div class="td-title">Outputs</div>
									</td>
									<td>
										<div class="td-title">Contract</div>
									</td>
									<td></td>
								</tr>
								<tr>
									<?php
										if ($transaction['type'] == 0) {
											?>
												<td><a href="?explorer/tx&hash="<?php echo $transaction['input']; ?>"><?php echo $transaction['input']; ?></a></td>
												<td><a href="?r=explorer/address&hash=<?php echo $transaction['output']; ?>"><?php echo $transaction['output']; ?></a></td>
												<td>Will be soon</td>
												<td><div class="green-bg"><?php echo $transaction['blockId'] == 0 ? '0' : $this->_storage->getHeight()-$block['height']+1; ?> confirmation</div></td>
											<?php
											} else {
											?>
												<td>Emitted contract</td>
												<td><a href="?r=explorer/address&hash=<?php echo $transaction['output']; ?>"><?php echo $transaction['output']; ?></a></td>
												<td><?php echo base64_decode($transaction['input']) ?></td>
												<td><div class="green-bg"><?php echo $transaction['blockId'] == 0 ? '0' : $this->_storage->getHeight()-$block['height']+1; ?> confirmation</div></td>
											<?php
										}
									?>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</main>

		<!-- Footer -->
		<?php $this->render('../common/_footer'); ?>
	</body>
</html>