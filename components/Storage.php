<?php

/**
 * Storage class
 */

class Storage {
	private $_mysqli = null;

	public $blockTable = 'block';
	public $transactionTable = 'transaction';
	public $keyTable = 'wallet_key';

	public function __construct($params) {
		$this->_mysqli = new mysqli($params['host'], $params['user'], $params['password'], $params['dbname']);
		
		if ($this->_mysqli->connect_errno)
			throw new Exception('Failed to connect to MySQL: ('.$this->_mysqli->connect_errno.') '.$this->_mysqli->connect_error);

		$this->query('set innodb_lock_wait_timeout=200');
	}

	/**
	 * Begin transaction.
	 */
	public function beginTransaction() {
		$this->_mysqli->begin_transaction();
	}

	/**
	 * Commit transaction.
	 */
	public function commit() {
		$this->_mysqli->commit();
	}

	/**
	 * Rollback transaction.
	 */
	public function rollback() {
		$this->_mysqli->rollback();
	}

	/**
	 * Make mysqli query.
	 *
	 * @param string $query
	 * @return object|void
	 */
	public function query($query) {
		$result = $this->_mysqli->query($query);
		if (!$result) throw new Exception('Mysqli error: '.$this->_mysqli->error);

		return $result;
	}

	/**
	 * Get height.
	 *
	 * @return int
	 */
	public function getHeight() {
		$result = $this->query('SELECT height FROM '.$this->blockTable.' ORDER BY height DESC LIMIT 1');

		if (!$result || $result->num_rows < 1)
			return -1;

		$height = $result->fetch_assoc();
		
		return $height['height'];
	}

	/**
	 * Get block.
	 */
	public function getBlockByHash($hash) {
		$result = $this->query('SELECT * FROM '.$this->blockTable.' where hash = \''.$hash.'\'');

		if (!$result || $result->num_rows < 1)
			return null;

		$block = $result->fetch_assoc();
		return $block ? : null;
	}

	/**
	 * Get block.
	 */
	public function getBlockById($id) {
		$result = $this->query('SELECT * FROM '.$this->blockTable.' where id = '.$id);

		if (!$result || $result->num_rows < 1)
			return null;

		$block = $result->fetch_assoc();
		return $block ? : null;
	}

	/**
	 * Get next block.
	 */
	public function getNextBlockByHash($hash) {
		$result = $this->query('SELECT * FROM '.$this->blockTable.' where prev = \''.$hash.'\'');

		if (!$result || $result->num_rows < 1)
			return null;

		$block = $result->fetch_assoc();
		return $block ? : null;
	}

	/**
	 * Get block by height.
	 */
	public function getBlockByHeight($height) {
		$result = $this->query('SELECT * FROM '.$this->blockTable.' where height = \''.$height.'\'');

		if (!$result || $result->num_rows < 1)
			return null;

		$block = $result->fetch_assoc();
		return $block ? : null;
	}

	/**
	 * Get last blocks.
	 */
	public function getLastBlocks($limit = 7) {
		$result = $this->query('SELECT * FROM '.$this->blockTable.' ORDER BY height DESC LIMIT '.$limit);

		if (!$result || $result->num_rows < 1)
			return [];

		$blocks = [];
		while ($block = $result->fetch_assoc())
			$blocks[] = $block;

		return $blocks;
	}

	/**
	 * Get last blocks.
	 *
	 * @param int $shift
	 */
	public function getLastBlock($shift = 0) {
		$result = $this->query('SELECT * FROM '.$this->blockTable.' ORDER BY height DESC LIMIT '.$shift.', 1');

		if (!$result || $result->num_rows < 1)
			return [];

		return $result->fetch_assoc();
	}

	/**
	 * Get last transactions.
	 */
	public function getLastTransactions($limit = 7) {
		$result = $this->query('SELECT * FROM '.$this->transactionTable.' ORDER BY id DESC LIMIT '.$limit);

		if (!$result || $result->num_rows < 1)
			return [];

		$transactions = [];
		while ($transaction = $result->fetch_assoc())
			$transactions[] = $transaction;

		return $transactions;
	}

	/**
	 * Get transactions of block.
	 */
	public function getTransactionsForBlockId($blockId) {
		$result = $this->query('SELECT * FROM '.$this->transactionTable.' WHERE blockId = \''.$blockId.'\' ORDER BY indx ASC');

		if (!$result || $result->num_rows < 1)
			return [];

		$transactions = [];
		while ($transaction = $result->fetch_assoc())
			$transactions[] = $transaction;

		return $transactions;
	}

	/**
	 * Get transaction by hash.
	 *
	 * @param string
	 * @return array|null
	 */
	public function getTransactionByHash($hash) {
		$result = $this->query('SELECT * FROM '.$this->transactionTable.' WHERE hash = \''.$hash.'\'');

		if (!$result && $result->num_rows < 1)
			return null;

		return $result->fetch_assoc();
	}

	/**
	 * Get address.
	 *
	 * @param string
	 * @return boolean
	 */
	public function isAddressAvailable($address) {
		$result = $this->query('SELECT count(*) as cnt FROM transaction WHERE input = \''.$address.'\' or output = \''.$address.'\'');

		if (!$result || $result->num_rows < 1)
			return '';

		$address = $result->fetch_assoc();
		return $address['cnt'] > 0;
	}

	/**
	 * Get transactions for address.
	 *
	 * @param string
	 * @return array
	 */
	public function getTransactionsForAddress($address, $start = null, $limit = null) {
		$txs = [];

		$chunk = $limit !== null ? ' LIMIT '.$start.', '.$limit : '';

		$txResult = $this->query('SELECT * FROM '.$this->transactionTable.' WHERE (input = \''.$address.'\' and type = 0) or output = \''.$address.'\' ORDER BY createdAt DESC, id DESC'.$chunk);

		if (!$txResult || $txResult->num_rows < 1)
			return [];

		$trasnactions = [];

		while ($transaction = $txResult->fetch_assoc())
			$transactions[] = $transaction;

		return $transactions;
	}



	/**
	 * Get transactions for address.
	 *
	 * @param string
	 * @return array
	 */
	public function getCountTransactionsForAddress($address) {
		$txs = [];

		$txResult = $this->query('SELECT count(*) as count FROM '.$this->transactionTable.' WHERE (input = \''.$address.'\' and type = 0) or output = \''.$address.'\'');

		if (!$txResult || $txResult->num_rows < 1)
			return 0;

		$count = $txResult->fetch_assoc();

		return $count['count'];
	}



	/**
	 * Set setting.
	 *
	 * @param string $key
	 * @param string $value
	 * @return boolean
	 */
	public function setSetting($key, $value) {
		$result = $this->query('SELECT * FROM setting WHERE k = \''.$key.'\'');
		if ($result && $result->num_rows > 0) {
			$result = $this->query('UPDATE setting SET v = \''.$value.'\' WHERE k = \''.$key.'\'');
			if ($this->_mysqli->errno)
				return false;
			return true;
		} else {
			$result = $this->query('INSERT INTO setting (k, v, created) VALUES (\''.$key.'\', \''.$value.'\', '.time());
			if ($this->_mysqli->errno)
				return false;
			return true;
		}
	}



	/**
	 * Get setting.
	 *
	 * @param string $key
	 * @return string|null
	 */
	public function getSetting($key) {
		$result = $this->query('SELECT * FROM setting WHERE k = \''.$key.'\'');

		if ($result && $result->num_rows > 0) {
			$value = $result->fetch_assoc();
			return $value['v'];
		}

		return null;
	}
}
