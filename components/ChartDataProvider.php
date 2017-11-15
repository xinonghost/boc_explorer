<?php

class ChartDataProvider
{
	/***/
	private $_storage;

	/***/
	public $hashrateItemsCount = 1000;

	/***/
	public $difficultyItemsCount = 100;

	/***/
	public $hashrateDataFile = 'hashrate.json';

	/***/
	public $difficultyDataFile = 'difficulty.json';

	/***/
	public $filePath = WEB_ROOT;

	/***/
	public function __construct($storage, $config = null)
	{
		$this->setStorage($storage);
	}

	/***/
	public function pushHashrateItem($item)
	{
		if (!file_exists($this->filePath.'/'.$this->hashrateDataFile)) {
			file_put_contents($this->filePath.'/'.$this->hashrateDataFile, '[]');
		}

		$data = @json_decode(file_get_contents($this->filePath.'/'.$this->hashrateDataFile), true);

		if (!$data) {
			$data = [];
			$data[] = [$item['created'], $item['value']];
		} else {
			$data[] = [$item['created'], $item['value']];

			while (count($data) > $this->hashrateItemsCount) {
				array_shift($data);
			}
		}

		file_put_contents($this->filePath.'/'.$this->hashrateDataFile, json_encode($data));
	}

	/***/
	public function generateDifficultyFile()
	{
		$values = $this->getStorage()->getLastDifficultyValues($this->difficultyItemsCount);

		if (!file_exists($this->filePath.'/'.$this->difficultyDataFile)) {
			file_put_contents($this->filePath.'/'.$this->difficultyDataFile, '[]');
		}

		file_put_contents($this->filePath.'/'.$this->difficultyDataFile, json_encode($values));
	}

	/**
	 * Get instance of storage class.
	 *
	 * @return object
	 */
	public function getStorage()
	{
		return $this->_storage;
	}

	/**
	 * Set storage instance.
	 *
	 * @param object $storage
	 */
	public function setStorage($storage)
	{
		$this->_storage = $storage;
	}
}