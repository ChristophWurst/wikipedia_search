<?php

declare(strict_types=1);

namespace OCA\WikipediaSearch\Service;

class WikipediaArticle {

	/** @var string */
	private $title;

	/** @var string */
	private $url;

	public function __construct(string $title,
								string $url) {
		$this->title = $title;
		$this->url = $url;
	}

	public function getTitle(): string {
		return $this->title;
	}

	public function getUrl(): string {
		return $this->url;
	}

	public function __toString() {
		return $this->getTitle() . ": " . $this->getUrl();
	}

}
