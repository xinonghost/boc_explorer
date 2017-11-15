<?php

require_once(__DIR__.'/Controller.php');
require_once(__DIR__.'/../components/Money.php');

class ExplorerController extends Controller {
	public function actionIndex() {
		$this->pageTitle = 'BOC Explorer';
		$blocks = $this->_storage->getLastBlocks();

		if (count($blocks) > 0) {
			for ($i = 0; $i < count($blocks); $i++) {
				$blocks[$i]['version'] = count($this->_storage->getTransactionsForBlockId($blocks[$i]['id']));
			}
		}

		$transactions = $this->_storage->getLastTransactions();
		return $this->render('index', compact('blocks','transactions'));
	}

	public function actionSearch() {
		if (!isset($_GET['search']))
			throw new Exception('Search request ton supplied');

		$request = $_GET['search'];

		if (preg_match("/[^A-Za-z0-9]/", $request))
			throw new Exception('Wrong request');

		if (strlen($request) == 64) {
			$block = $this->_storage->getBlockByHash($request);
			if ($block) {
				return $this->redirect('?r=explorer/block&hash='.$block['hash']);
			}
			
			$tx = $this->_storage->getTransactionByHash($request);
			if ($tx) {
				return $this->redirect('?r=explorer/tx&hash='.$tx['hash']);
			}
		}

		if (strlen($request) > 24 && strlen($request) < 35 && substr($request, 0, 1) == 'X') {
			return $this->redirect('?r=explorer/address&hash='.$request);
		}

		if (strlen($request) < 7 && is_numeric($request) && ($height = intval($request)) >= 0) {
			$block = $this->_storage->getBlockByHeight($height);

			if ($block) {
				return $this->redirect('?r=explorer/block&hash='.$block['hash']);
			}
		}
		
		return $this->render('notfound');
	}



	/**
	 * View block.
	 */
	public function actionBlock() {
		$this->pageTitle = 'Block';

		if (!isset($_GET['hash']) || !$_GET['hash'])
			throw new Exception('Block hash not provided');

		if (preg_match("/[^A-Za-z0-9]/", $_GET['hash']))
			throw new Exception('Wrong request');

		$block = $this->_storage->getBlockByHash($_GET['hash']);
		if (!$block)
			throw new Exception('Block not found');

		$nextBlock = $this->_storage->getNextBlockByHash($block['hash']);

		$transactions = $this->_storage->getTransactionsForBlockId($block['id']);

		return $this->render('block', compact('block','transactions', 'nextBlock'));
	}



	/**
	 * View transaction.
	 */
	public function actionTx() {
		$this->pageTitle = 'Transaction';

		if (!isset($_GET['hash']) || !$_GET['hash'])
			throw new Exception('Trasnaction hash not provided');

		if (preg_match("/[^A-Za-z0-9]/", $_GET['hash']))
			throw new Exception('Wrong request');

		$transaction = $this->_storage->getTransactionByHash($_GET['hash']);

		if (!$transaction)
			throw new Exception('Block not found');

		$block = $this->_storage->getBlockById($transaction['blockId']);

		return $this->render('transaction', compact('block','transaction'));
	}



	/**
	 * View address
	 */
	public function actionAddress() {
		$this->pageTitle = 'Address';

		require_once(__DIR__.'/../components/Pagination.php');

		if (!isset($_GET['hash']) || !$_GET['hash'])
			throw new Exception('Address not provided');

		if (preg_match("/[^A-Za-z0-9]/", $_GET['hash']))
			throw new Exception('Wrong request');

		$address = $this->_storage->getAddress($_GET['hash']);

		if (!$address) {
			$address = [
				'id'=>0,
				'address'=>$_GET['hash'],
				'hash160'=>'',
				'created'=>time()
			];
			$inputs = [];
			$outputs = [];
			$transactions = [];
			
			return $this->render('address', compact('address','inputs','outputs','transactions','ignoredAddress'));
		}

		if ($ignoredAddress == $address['address'])
			return $this->render('system_address');

		$inputs = $this->_storage->getInputsForAddress($address['address']);
		$outputs = $this->_storage->getOutputsForAddress($address['address']);
		
		$items = $this->_storage->getCountTransactionsForAddress($address['address'], $inputs, $outputs);
		$pagination = new Pagination(['items'=>$items]);
		$paginator = $pagination->getPaginator('?r=explorer/address&hash='.(isset($_GET['hash']) ? $_GET['hash'] : ''));
		
		$transactions = $this->_storage->getTransactionsForAddress($address['address'], $inputs, $outputs, $pagination->start, $pagination->perPage);

		return $this->render('address', compact('address','inputs','outputs','transactions','ignoredAddress', 'paginator', 'items'));
	}

	/**
	 * Total supply.
	 */
	public function actionTotalSupply()
	{
		echo '19000000.00000000';
	}

	/**
	 * Current supply.
	 */
	public function actionCurrentSupply()
	{
		$premine = 5500000;

		$height = $this->_storage->getHeight();

		echo number_format($premine + ($height * 2.5), 8, '.', '');
	}
}
