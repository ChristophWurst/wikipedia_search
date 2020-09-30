<?php

declare(strict_types=1);

namespace OCA\WikipediaSearch\Service;

class WikipediaSearchResult {

	/** @var WikipediaArticle[] */
	private $articles;

	/** @var int|null */
	private $offset;

	/**
	 * @param WikipediaArticle[] $articles
	 * @param int|null $offset
	 */
	public function __construct(array $articles,
								int $offset) {
		$this->articles = $articles;
		$this->offset = $offset;
	}

	/**
	 * @return WikipediaArticle[]
	 */
	public function getArticles(): array {
		return $this->articles;
	}

	public function getOffset(): ?int {
		return $this->offset;
	}

}
