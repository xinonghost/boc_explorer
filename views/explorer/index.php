<!DOCTYPE html>
<html lang="ru">
<?php $this->render('../common/_head'); ?>
	<body>
		<!-- Header -->
		<?php $this->render('../common/_main_header'); ?>
		
		<!-- Main -->
		<main id="main">
			<div class="container">
				<div class="row">
					<div class="col-md-6">
						<div class="block-title">
							<h2>Latest Blocks</h2>
						</div>
						<table class="table main-table">
							<thead>
								<tr>
									<th>Height</th>
									<th>Created</th>
									<th>Transactions</th>
								</tr>
							</thead>
							<tbody>
								<?php
									if (isset($blocks) && count($blocks) > 0) {
										foreach ($blocks as $block) {
											?>
												<tr>
													<td><a href="?r=explorer/block&hash=<?php echo $block['hash']; ?>"><?php echo $block['height']; ?></a></td>
													<td><div class="value-mnx"><?php echo date('d.m.y H:i:s', $block['createdAt']); ?></div></td>
													<td><?php echo $block['version']; ?></td>
												</tr>
											<?php
										}
									}
								?>
							</tbody>
						</table>
					</div>
					<div class="col-md-6">
						<div class="block-title">
							<h2>Latest Transactions</h2>
						</div>
						<table class="table main-table">
							<thead>
								<tr>
									<th>Hash</th>
									<th>Created</th>
								</tr>
							</thead>
							<tbody>
								<?php
									if (isset($transactions) && count($transactions)) {
										foreach ($transactions as $transaction) {
											?>
												<tr>
													<td><a href="?r=explorer/tx&hash=<?php echo $transaction['hash']; ?>" class="hash-value"><?php echo substr($transaction['hash'], 0, 40); ?>...</a></td>
													<td>
														<div class="value-mnx"><?php echo date('d.m.Y H:i', $transaction['createdAt']); ?></div>
													</td>
												</tr>
											<?php
										}
									}
								?>
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