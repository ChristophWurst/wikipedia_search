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
use function mb_strpos;
use function mb_substr;
use function strlen;

class WikipediaSearchProvider implements IProvider {

	/**
	 * @var IL10N
	 */
	private $l10n;

	/**
	 * @var SearchService
	 */
	private $searchService;

	public function __construct(IL10N $l10n,
								SearchService $searchService) {
		$this->l10n = $l10n;
		$this->searchService = $searchService;
	}

	public function getId(): string {
		return Application::APP_ID;
	}

	public function getName(): string {
		return $this->l10n->t('Wikipedia articles');
	}

	public function getOrder(string $route, array $routeParameters): int {
		return 80;
	}

	public function search(IUser $user, ISearchQuery $query): SearchResult {
		if (mb_strpos($query->getTerm(), "wiki ") !== 0) {
			return SearchResult::complete(
				$this->getName(),
				[]
			);
		}

		$term = mb_substr($query->getTerm(), strlen("wiki "));
		$offset = $query->getCursor();
		if ($offset !== null) {
			$offset = (int) $offset;
		}

		$result = $this->searchService->search(
			$term,
			$offset
		);

		return SearchResult::paginated(
			$this->getName(),
			array_map(function(WikipediaArticle $article) {
				return new SearchResultEntry(
					'',
					$article->getTitle(),
					$this->l10n->t('Find more on Wikipedia'),
					$article->getUrl(),
					'icon-info'
				);
			}, $result->getArticles()),
			$result->getOffset()
		);
	}
}
