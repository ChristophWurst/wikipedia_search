<?php

declare(strict_types=1);

namespace OCA\WikipediaSearch\AppInfo;

use OCA\WikipediaSearch\Search\WikipediaSearchProvider;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;

class Application extends App implements IBootstrap {

	public const APP_ID = 'wikipedia_search';

	public function __construct() {
		parent::__construct(self::APP_ID);
	}

	public function register(IRegistrationContext $context): void {
		$context->registerSearchProvider(WikipediaSearchProvider::class);
	}

	public function boot(IBootContext $context): void {
	}
}
