<?php
	require_once(__DIR__.'/../../components/Money.php');

	$determineBlock = false;
	if (!isset($block))
		$determineBlock = true;

	if (isset($transactions) && count($transactions) > 0) {
		if (!isset($noreverse))
			$transactions = array_reverse($transactions);
		foreach ($transactions as $transaction) {
			if ($determineBlock) {
				$block = $this->_storage->getBlockById($transaction['blockId']);
			}
			?>
			<table class="table transactions-table">
				<thead>
				<tr>
					<th colspan="3"><a href="?r=explorer/tx&hash=<?php echo $transaction['hash']; ?>"><?php echo $transaction['hash']; ?></a></th>
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
									<td><div class="green-bg"><?php echo $this->_storage->getHeight()-$block['height']+1; ?> confirmation</div></td>
								<?php
							} else {
								?>
									<td>Emitted contract</td>
									<td><?php echo $transaction['output']; ?></td>
									<td><?php echo base64_decode($transaction['input']) ?></td>
									<td><div class="green-bg"><?php echo $this->_storage->getHeight()-$block['height']+1; ?> confirmation</div></td>
								<?php
							}
						?>
					</tr>
				</tbody>
			</table>
			<?php
		}
	} else {
		?>
		<p>No transactions found.</p>
		<?php
	}
?>