<?php

declare(strict_types=1);

namespace OCA\WikipediaSearch\Search;

use OCA\WikipediaSearch\AppInfo\Application;
use OCA\WikipediaSearch\Service\SearchService;
use OCA\WikipediaSearch\Service\WikipediaArticle;
use OCP\IL10N;
use OCP\IUser;
use OCP\Search\IProvider;
use OCP\Search\ISearchQuery;
use OCP\Search\SearchResult;
use OCP\Search\SearchResultEntry;
use function array_map;

class WikipediaSearchProvider implements IProvider {

	/** @var SearchService */
	private $searchService;

	/** @var IL10N */
	private $l10n;

	public function __construct(SearchService $searchService,
								IL10N $l10n) {
		$this->l10n = $l10n;
		$this->searchService = $searchService;
	}

	public function getId(): string {
		return Application::APP_ID;
	}

	public function getName(): string {
		return $this->l10n->t('Wikipedia');
	}

	public function getOrder(string $route, array $routeParameters): int {
		return 80; // Less important -> higher number
	}

	public function search(IUser $user, ISearchQuery $query): SearchResult {
		$cursor = $query->getCursor();
		if ($cursor !== null) {
			$cursor = (int) $cursor;
		}

		$result = $this->searchService->search($query->getTerm(), $cursor);
		$results = array_map(function(WikipediaArticle $article) {
			return new SearchResultEntry(
				'',
				$article->getTitle(),
				$this->l10n->t('Read more on Wikipedia'),
				$article->getUrl(),
				'icon-info'
			);
		}, $result->getArticles());

		return SearchResult::paginated(
			$this->getName(),
			$results,
			count($results) + $cursor
		);
	}
}
