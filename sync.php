<?php

ini_set('display_errors', true);
error_reporting(E_ALL | E_NOTICE);

define('APP_ROOT', __DIR__);
define('WEB_ROOT', APP_ROOT.'/web');

require_once(__DIR__.'/RPC.php');
require_once(__DIR__.'/Storage.php');
require_once(__DIR__.'/components/ChartDataProvider.php');

$config = [];
$config['db'] = require_once(__DIR__.'/config/db.php');

$explorerFiller = new ExplorerFiller($config);
$explorerFiller->sync();

class ExplorerFiller {
	private $_xcoinRPC = null;
	private $_storage = null;
	private $_lastBlockHeight = 0;
	private $_newBlockHeight = 0;
	private $_params = [];



	/**
	 * Constructor.
	 * Connect to mysql server and make object for RPC server.
	 */
	public function __construct($config) {
		$this->_xcoinRPC = new JsonRpcClient('http://user:password@127.0.0.1:17786');

		$this->_storage = new Storage($config['db']);

		$this->_params = $this->parseParams();

		if (!isset($this->_params['blockhash'])) {
			die('Block hash not provided'."\n");
		}

		// file_put_contents(__DIR__.'/log.txt', file_get_contents(__DIR__.'/log.txt').$this->_params['blockhash']."\n");
	}



	/**
	 * Sync data about state.
	 */
	public function sync() {
		$this->makeLockFile();

		try {
			$this->_lastBlockHeight = $this->_storage->getHeight();

			if ($this->_lastBlockHeight == -1) {
				$this->addGenesisBlock();
			}

			$this->syncBlocks();
		} catch (Exception $e) {
			$this->handleException($e);
		}

		$this->removeLockFile();
	}



	/**
	 * Make lock file
	 */
	public function makeLockFile() {
		$lockFile = __DIR__.'/explorer.lock';
		if (file_exists($lockFile))
			die('Process already running');

		if (!file_put_contents($lockFile, getmypid()))
			die('Cant create lock file');
	}



	public function removeLockFile() {
		$lockFile = __DIR__.'/explorer.lock';
		if (file_exists($lockFile) && !unlink($lockFile))
			die('Cant delete lock file');
	}



	/**
	 * Handle RPC response.
	 *
	 * @param mixed $response
	 * @return array
	 */
	public function handleRPC($response) {
		if (!$response)
			throw new Exception('RPC error');

		return $response;
	}



	/**
	 * Sync blocks.
	 */
	public function syncBlocks() {
		$bestBlock = $this->handleRPC($this->_xcoinRPC->getblock($this->_params['blockhash']));
		$lastBlock = $this->_storage->getLastBlock();

		$rootBlock = $this->getRootBlock($bestBlock, $lastBlock);

		if ($rootBlock == null) {
			return;
		}

		if ($rootBlock['height'] == $lastBlock['height']) {
			$this->rewindBlocks($lastBlock['height'], $bestBlock['height']);
		} elseif ($rootBlock['height'] < $lastBlock['height']) {
			$this->_storage->deleteBlocksAfterId($rootBlock['id']);
			$this->rewindBlocks($rootBlock['height'], $bestBlock['height']);
		}
	}



	/**
	 * Rewind last blocks.
	 */
	public function rewindBlocks($begin, $end) {
		$dataProvider = new ChartDataProvider($this->_storage);
		
		for ($i = $begin+1; $i <= $end; $i++) {
			$blockhash = $this->handleRPC($this->_xcoinRPC->getblockhash($i));
			$block = $this->handleRPC($this->_xcoinRPC->getblock($blockhash));
			
			$this->_storage->beginTransaction();
			
			$this->addBlock($block);

			$this->_storage->commit();
			
			// Difficulty
			$dataProvider->generateDifficultyFile();
		}

		// Hashrate
		$hashrate = $this->handleRPC($this->_xcoinRPC->getmininginfo());
		$dataProvider->pushHashrateItem(['created'=>time()*1000, 'value'=>$hashrate['networkhashps']]);
	}



	/**
	 * Get root block to sync.
	 *
	 * @param string $bestBlock
	 * @param string $lastBlock
	 * @return array|null
	 */
	public function getRootBlock($bestBlock, $lastBlock) {
		if ($bestBlock['hash'] == $lastBlock['hash'] && $bestBlock['height'] == $lastBlock['height'])
			return $lastBlock;

		if ($bestBlock['height'] > $lastBlock['height']) {
			$height = (int)$lastBlock['height'];
			while ($height >= 0) {
				$candidateHash = $this->handleRPC($this->_xcoinRPC->getblockhash($height));
				$candidateBlock = $this->_storage->getBlockByHeight($height);
				if ($candidateHash == $candidateBlock['hash']) {
					return $candidateBlock;
				}
				$height--;
			}
			return null;
		}

		return $lastBlock;
	}



	/**
	 * Add genesis block.
	 */
	private function addGenesisBlock() {
		$this->_storage->beginTransaction();
		$blockhash = $this->handleRPC($this->_xcoinRPC->getblockhash(0));
		$block = $this->handleRPC($this->_xcoinRPC->getblock($blockhash));

		$blockData = [
			'hash' => $block['hash'],
			'prev'=>'0',
			'next'=>'0',
			'height' => $block['height'],
			'root' => $block['merkleroot'],
			'difficulty' => $block['difficulty'],
			'size' => $block['size'],
			'fee' => 0,
			'totalAmount' => 0,
			'createdAt' => $block['time'],
			'version' => $block['version'],
			'transactions' => count($block['tx'])
		];

		$result = $this->_storage->addBlock($blockData);
		$this->_storage->commit();
	}



	/**
	 * Add block.
	 *
	 * @param array $block
	 */
	public function addBlock($block) {
		$blockData = [
			'hash' => $block['hash'],
			'prev' => $block['previousblockhash'],
			'next' => '0',
			'height' => $block['height'],
			'root' => $block['merkleroot'],
			'difficulty' => $block['difficulty'],
			'size' => $block['size'],
			'fee' => 0,
			'totalAmount' => 0,
			'value' => 0,
			'createdAt' => $block['time'],
			'version' => $block['version'],
			'transactions' => count($block['tx'])
		];

		$result = $this->_storage->addBlock($blockData);

		$blockValueAndFee = $this->addTransactions($block);

		$blockData['totalAmount'] = $blockValueAndFee['value'];
		$blockData['fee'] = $blockValueAndFee['fee'];

		$this->_storage->updateBlock($blockData);
	}



	/**
	 * Add transactions.
	 *
	 * @param string
	 * @param array
	 * @return array
	 */
	private function addTransactions($block) {
		$value = 0;
		$fee = 0;
		
		if (count($block['tx']) == 0)
			return compact('value', 'fee');

		if (!is_array($block['tx']))
			$block['tx'] = [$block['tx']];

		$indx = 0;
		foreach ($block['tx'] as $txid) {
			$decodedTx = $this->handleRPC($this->_xcoinRPC->getrawtransaction($txid, true));

			$transaction = [
				'hash' => $decodedTx['hash'],
				'block' => $block['hash'],
				'amount' => 0,
				'fee'=> 0,
				'createdAt' => $block['time'],
				'indx' => $indx
			];
			
			$result = $this->_storage->addTransaction($transaction);
			
			$vinValue = $this->addVins($transaction['hash'], $decodedTx['vin']);
			$voutValue = $this->addVouts($transaction['hash'], $decodedTx['vout']);

			if ($vinValue == -1) $vinValue = $voutValue;

			$transaction['amount'] = $voutValue;
			$transaction['fee'] = round($vinValue - $voutValue, 8);

			$this->_storage->updateTransaction($transaction);

			$value += $voutValue;
			$fee += $transaction['fee'];
			$indx++;
		}

		return compact('value', 'fee');
	}



	/**
	 * Add vins.
	 *
	 * @param string
	 * @param array
	 * @return float
	 */
	private function addVins($txid, $vins) {
		if (count($vins) == 0)
			return 0;

		$value = 0;

		$vinsCount = count($vins);
		for ($i = 0; $i < $vinsCount; $i++) {
			$vin = $vins[$i];

			$vinData = [
				'txid' => $txid,
				'indx' => $i,
			];

			if (isset($vin['coinbase'])) {
				$vinData['amount'] = 250000000;
				$vinData['type'] = 'immature';
				$vinData['address'] = 'coinbase';
				
				$result = $this->_storage->addVin($vinData);
				$value = -1;
			} else {
				$voutTx = $this->handleRPC($this->_xcoinRPC->getrawtransaction($vin['txid'], true));
				if (!isset($voutTx['vout'][$vin['vout']])) {
					continue;
				}
				$vout = $voutTx['vout'][$vin['vout']];
				
				$vinData['amount'] = round($vout['value'] * 100000000);
				$vinData['type'] = 'send';
				$vinData['address'] = $vout['scriptPubKey']['addresses'][0];

				$value += round($vout['value'] * 100000000);
				$result = $this->_storage->addVin($vinData);
			}
		}

		return $value;
	}



	/**
	 * Add vouts.
	 *
	 * @param string
	 * @param string
	 * @param array
	 * @return float
	 */
	private function addVouts($txid, $vouts) {
		if (count($vouts) == 0)
			return 0;

		$value = 0;

		foreach ($vouts as $vout) {
			$value += round($vout['value'] * 100000000);

			$voutData = [
				'txid' => $txid,
				'indx' => $vout['n'],
				'amount' => round($vout['value'] * 100000000),
				'address' => $vout['scriptPubKey']['addresses'][0],
				'type' => 'spend'
			];

			$result = $this->_storage->addVout($voutData);

			$address = [
				'address' => $voutData['address'],
				'pubkey' => $vout['scriptPubKey']['hex'],
				'firstseen' => 0
			];

			$this->addAddress($address);
		}

		return $value;
	}



	/**
	 * Add address if not exists.
	 *
	 * @internal
	 * @param string $txid
	 * @param array $scriptPubKey
	 */
	private function addAddress($address) {
		$result = $this->_storage->addAddress($address);
	}



	private function handleException(Exception $e) {
		$this->removeLockFile();
		die($e->getMessage());
	}



	/**
	 * Get hash160 from line.
	 *
	 * @param string $text
	 * @return string
	 */
	private function getHash160($text) {
		$parts = explode(' ', $text);

		foreach ($parts as $part) {
			if (!preg_match("/^OP.*/", $part))
				return $part;
		}

		return '';
	}



	/**
	 * Parse params.
	 *
	 * @return array
	 */
	public function parseParams() {
		$argv = $GLOBALS['argv'];
		if (($count = count($argv)) < 2)
			return [];

		$params = [];
		for ($i = 1; $i < $count; $i++) {
			if (strpos($argv[$i], '=') === false) {
				continue;
			}

			$part = explode('=', $argv[$i]);

			$params[str_replace('-', '', $part[0])] = $part[1];
		}

		return $params;
	}
}
