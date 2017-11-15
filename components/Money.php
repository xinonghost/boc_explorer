<?php

/**
 * Created by PhpStorm.
 * User: vladyslav
 * Date: 06.07.17
 * Time: 14:08
 */
class Money
{
	private $_amount = 0;
	private $_currency = 'MNX';
	private $_decimals = 8;

	public function __construct($amount = 0)
	{
		$this->_amount = intval($amount);
	}



	/**
	 * Get presentation of amount with currency.
	 *
	 * @return string
	 */
	public function getFormatted() {
		return number_format($this->_amount / pow(10, $this->_decimals), $this->_decimals);
	}



	/**
	 * Get presentation of amount with currency.
	 *
	 * @return string
	 */
	public function getFormattedWithCurrency() {
		return $this->getFormatted().' '.$this->_currency;
	}



	/**
	 * Get amount.
	 *
	 * @return int
	 */
	public function getAmount() {
		return $this->_amount;
	}



	/**
	 * Get presentation of amount with currency.
	 *
	 * @return string
	 */
	public static function formateWithCurrency($amount = 0, $decimals = 8, $currency = 'MNX') {
		return number_format($amount / pow(10, $decimals), $decimals).' '.$currency;
	}
}