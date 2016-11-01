<?php

namespace App\Http\Pagination;

use Illuminate\Pagination\BootstrapThreeNextPreviousButtonRendererTrait;
use Illuminate\Pagination\BootstrapThreePresenter;
use Illuminate\Pagination\UrlWindow;
use Illuminate\Pagination\UrlWindowPresenterTrait;
use Illuminate\Support\HtmlString;
use Illuminate\Contracts\Pagination\Paginator as PaginatorContract;

class CustomerPresenter extends BootstrapThreePresenter {
	use BootstrapThreeNextPreviousButtonRendererTrait, UrlWindowPresenterTrait;
	/**
	 * The paginator implementation.
	 *
	 * @var \Illuminate\Contracts\Pagination\Paginator
	 */
	protected $paginator;

	/**
	 * The URL window data structure.
	 *
	 * @var array
	 */
	protected $window;

	/**
	 * Create a new Bootstrap presenter instance.
	 *
	 * @param  \Illuminate\Contracts\Pagination\Paginator $paginator
	 * @param  \Illuminate\Pagination\UrlWindow|null $window
	 * @return void
	 */
	public function __construct(PaginatorContract $paginator, UrlWindow $window = null) {
		$this->paginator = $paginator;
		$this->window = is_null($window) ? UrlWindow::make($paginator) : $window->get();
	}

	/**
	 * Determine if the underlying paginator being presented has pages to show.
	 *
	 * @return bool
	 */
	public function hasPages() {
		return $this->paginator->hasPages();
	}

	/**
	 * Convert the URL window into Bootstrap HTML.
	 *
	 * @return \Illuminate\Support\HtmlString
	 */
	public function render() {
		if($this->hasPages()) {
			return new HtmlString(sprintf(
				'<div class="page_nav">%s %s %s %s %s</div>',
				$this->getFirstButton('首页'),
				$this->getPreviousButton('上一页'),
				$this->getLinks(),
				$this->getNextButton('下一页'),
				$this->getLastButton('末页')
			));
		}

		return '';
	}

	/**
	 * 得到最后一页
	 * @param $text
	 * @return string
	 */
	protected function getLastButton($text) {
		if(!$this->paginator->hasMorePages()) {
			return $this->getDisabledTextWrapper($text);
		}
		$url = $this->paginator->url($this->paginator->lastPage());

		return $this->getPageLinkWrapper($url, $text, 'last');
	}

	/**
	 * 得到第一页
	 * @param $text
	 * @return string
	 */
	protected function getFirstButton($text) {
		if($this->paginator->currentPage() == 1) {
			return $this->getDisabledTextWrapper($text);
		}
		$url = $this->paginator->url(1);

		return $this->getPageLinkWrapper($url, $text, 'first');
	}

	/**
	 * Get HTML wrapper for an available page link.
	 *
	 * @param  string $url
	 * @param  int $page
	 * @param  string|null $rel
	 * @return string
	 */
	protected function getAvailablePageWrapper($url, $page, $rel = null) {
		$rel = is_null($rel) ? '' : ' rel="' . $rel . '"';

		return '<a class="num" href="' . htmlentities($url) . '"' . $rel . '>' . $page . '</a>';
	}

	/**
	 * Get HTML wrapper for disabled text.
	 *
	 * @param  string $text
	 * @return string
	 */
	protected function getDisabledTextWrapper($text) {
		return '<span>' . $text . '</span>';
	}

	/**
	 * Get HTML wrapper for active text.
	 *
	 * @param  string $text
	 * @return string
	 */
	protected function getActivePageWrapper($text) {
		return '<span class="current">' . $text . '</span>';
	}

	/**
	 * Get a pagination "dot" element.
	 *
	 * @return string
	 */
	protected function getDots() {
		return $this->getDisabledTextWrapper('...');
	}

	/**
	 * Get the current page from the paginator.
	 *
	 * @return int
	 */
	protected function currentPage() {
		return $this->paginator->currentPage();
	}

	/**
	 * Get the last page from the paginator.
	 *
	 * @return int
	 */
	protected function lastPage() {
		return $this->paginator->lastPage();
	}
}
