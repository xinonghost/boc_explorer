<?php

$inputs = $this->_storage->getInputsByTxid($transaction['hash']);
if (count($inputs) > 0) {
	foreach ($inputs as $input) {
		if ($input['type'] == 'immature') {
			?>
				<p>Newly generated coins</p>
			<?php
		} else {
			if ($input['address'] == $ignoredAddress) {
				?>
					<p>MinexBank reserve</p>
				<?php
			} else {
				?>
					<a class="code" href="?r=explorer/address&hash=<?php echo $input['address']; ?>"  ><?php echo $input['address']; ?></a>
				<?php
			}
		}
	}
}

?>