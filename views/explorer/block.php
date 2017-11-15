<?php

require_once(__DIR__.'/../../components/Money.php');

?>

<!DOCTYPE html>
<html lang="ru">
	<?php $this->render('../common/_head'); ?>
	<body>
		<!-- Header -->
		<?php $this->render('../common/_header'); ?>

		<!-- Page Title -->
		<section class="page-title-panel">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="page-title">
							<h1>Block #<?php echo $block['height']; ?></h1>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- Main -->
		<main id="main">
			<div class="container">
				<div class="row mb-30">
					<div class="col-md-4">
						<table class="table info-table">
							<tbody>
								<tr>
									<td>Height</td>
									<td><?php echo $block['height']; ?></td>
								</tr>
								<tr>
									<td>Version</td>
									<td><?php echo $block['version']; ?></td>
								</tr>
								<tr>
									<td>Block time</td>
									<td><?php echo date('Y-m-d H:i:s', $block['createdAt']); ?></td>
								</tr>
								<tr>
									<td>Confirmations</td>
									<td><?php echo $this->_storage->getHeight()-$block['height']+1; ?></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-md-8">
						<table class="table info-table">
							<tbody>
								<tr>
									<td>Hash</td>
									<td><?php echo $block['hash']; ?></td>
								</tr>
								<tr>
									<td>Next block hash</td>
									<td><?php echo $nextBlock ? '<a class="hash-value" href="?r=explorer/block&hash='.$nextBlock['hash'].'">'.$nextBlock['hash'].'</a>' : '-'; ?></td>
								</tr>
								<tr>
									<td>Prev block hash</td>
									<td><?php echo $block['prev'] != '0' ? '<a class="hash-value" href="?r=explorer/block&hash='.$block['prev'].'">'.$block['prev'].'</a>' : '-'; ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="block-title">
							<?php $noreverse = true; ?>
							<?php $this->render('_transactions', compact('transactions', 'block', 'noreverse')); ?>
						</div>
					</div>
				</div>
			</div>
		</main>

		<!-- Footer -->
		<?php $this->render('../common/_footer'); ?>
	</body>
</html>