<?php

declare(strict_types=1);

namespace OCA\WikipediaSearch\Service;

use Exception;
use OCP\Http\Client\IClientService;
use Psr\Log\LoggerInterface;
use function array_map;
use function json_decode;
use function urlencode;

class SearchService {

	/** @var IClientService */
	private $clientService;

	/** @var LoggerInterface */
	private $logger;

	public function __construct(IClientService $clientService,
								LoggerInterface $logger) {
		$this->clientService = $clientService;
		$this->logger = $logger;
	}

	public function search(?string $term = null, ?int $offset = null): WikipediaSearchResult {
		$this->logger->debug('finding the latest cat facts');

		$url = "https://en.wikipedia.org/w/api.php?action=query&list=search&srsearch=" . urlencode($term) . "&format=json";
		if ($offset !== null) {
			$url .= "&sroffset=$offset";
		}
		$client = $this->clientService->newClient();

		try {
			$response = $client->get($url);
		} catch (Exception $e) {
			$this->logger->error("Could not search wikipedia articles: " . $e->getMessage());

			throw $e;
		}

		$body = $response->getBody();
		$parsed = json_decode($body, true);

		return new WikipediaSearchResult(
			array_map(function (array $result) {
				return new WikipediaArticle(
					$result['title'],
					"http://en.wikipedia.org/?curid=" . $result['pageid']
				);
			}, $parsed['query']['search']),
			$parsed['continue']['sroffset'] ?? null
		);
	}

}
