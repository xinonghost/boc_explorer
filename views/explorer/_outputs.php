<?php

$outputs = $this->_storage->getOutputsByTxid($transaction['hash']);
if (count($outputs) > 0) {
	foreach ($outputs as $output) {
		if ($output['address'] == $ignoredAddress) {
			?>
				<p>MinexBank reserve</p>
			<?php
		} else {
			?>
				<a class="code" href="?r=explorer/address&hash=<?php echo $output['address']; ?>"  ><?php echo $output['address']; ?></a>
			<?php
		}
	}
}

?>