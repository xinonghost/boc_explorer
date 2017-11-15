<?php

/**
 * Class Pagination
 * @package app\components
 */
class Pagination
{
	public $pages = 0;
	public $perPage = 20;
	public $items = 0;
	public $page = 1;
	public $start = 0;
	public $width = 3;

	/***/
	public function __construct($params = null)
	{
		if (isset($params['perPage'])) {
			$this->perPage = $params['perPage'];
		}

		if (isset($params['items'])) {
			$this->items = $params['items'];
		}

		$this->calculate();
	}

	/***/
	public function calculate()
	{
		if ($this->perPage == 0)
			return false;

		$this->pages = ceil($this->items / $this->perPage);

		if (isset($_REQUEST['page'])) {
			$this->page = intval($_REQUEST['page']);
			if ($this->page > $this->pages) {
				$this->page = $this->pages;
			} elseif ($this->page < 1) {
				$this->page = 1;
			}
		}

		return true;
	}

	/***/
	public function getPaginator($url)
	{
		// Start of posts to page
		$this->start = ($this->page-1)*$this->perPage;
		if ($this->start < 0) $this->start = 0;
		
		// Main loop
		$paginator = '';
		if ($this->pages > 1) {
			$f = false;
			if ($this->page-$this->width > 1) {
				$f = true;
				$paginator .= '<a class="page_item" href="'.$url.'&page=1">&laquo;</a>';
			}
			for ($i = $this->page-$this->width; $i <= $this->page+$this->width && $i <= $this->pages; $i++) {
				// Before
				if ($i < $this->page && $i > 0) {
					if (!$f) {
						$paginator .= '<a class="page_item" href="'.$url.'&page='.$i.'">'.$i.'</a>';
						$f = true;
					}
					elseif ($this->page+$this->width >= $this->pages && $this->pages == $i) {
						$paginator .= '<a class="page_item" href="'.$url.'&page='.$i.'">'.$i.'</a>';
					}
					else $paginator .= '<a class="page_item" href="'.$url.'&page='.$i.'">'.$i.'</a>';
				}
				// Current
				elseif ($i == $this->page) {
					if (!$f) {
						$paginator .= '<a class="current_page_item" href="'.$url.'&page='.$i.'">'.$i.'</a>';
						$f = true;
					}
					elseif ($this->page+$this->width >= $this->pages && $this->pages == $i) {
						$paginator .= '<a class="current_page_item" href="'.$url.'&page='.$i.'">'.$i.'</a>';
					}
					else $paginator .= '<a class="current_page_item" href="'.$url.'&page='.$i.'">'.$i.'</a>';
				}
				// After
				elseif ($i > $this->page && $i <= $this->pages) {
					if ($this->page+$this->width >= $this->pages && $this->pages == $i) {
						$paginator .= '<a class="page_item" href="'.$url.'&page='.$i.'">'.$i.'</a>';
						}
						else $paginator .= '<a class="page_item" href="'.$url.'&page='.$i.'">'.$i.'</a>';
					}
			}
			if ($this->page+$this->width < $this->pages) {
				$paginator .= '<a class="page_item" href="'.$url.'&page='.$this->pages.'">&raquo;</a>';
			}

			$style = '
				<style>
					.page_item {
						margin: 5px;
						background-color:#e0e0ff;
						padding: 3px 6px;
						border-radius: 4px;
					}

					.current_page_item {
						margin: 5px;
						padding: 3px 6px;
					}
				</style>
			';

			$paginator = $style.'<div id="pagination">'.$paginator.'</div>';
		}
		return $paginator;
	}
}