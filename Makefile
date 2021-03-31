##@ Utility commands

.PHONY: help
help: ## Displays this help command
	@echo "\033[33mMakefile\033[0m"
	@echo "\033[33m--------\033[0m"
	@awk 'BEGIN {FS = ":.*##"; } /^[a-zA-Z_-]+:.*?##/ { printf "  \033[32m%-18s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)
	@echo

##@ Global

.PHONY: up
up: composer-install ## Installs project

.PHONY: test
test: phpunit php-cs-fixer-diff phpstan ## Run all tests

##@ PHP

.PHONY: composer-install
composer-install: ## Run composer install
	@echo "--- [flow] Running composer install"
	@composer install

##@ Tests

.PHONY: phpunit
phpunit: ## PHPUnit tests
	@vendor/bin/simple-phpunit

.PHONY: coverage
coverage: ## Coverage tests
	@XDEBUG_MODE=coverage vendor/bin/simple-phpunit --coverage-html=build/coverage_html
	@echo Report in ./build/coverage_html/index.html


.PHONY: php-cs-fixer
php-cs-fixer: ## Runs PHP-CS-Fixer
	@echo "--- [flow] Running php-cs-fixer (fix mode)"
	@bin/php-cs-fixer fix --config .php_cs --diff

.PHONY: php-cs-fixer-diff
php-cs-fixer-diff: ## Runs PHP-CS-Fixer with dry-run mode
	@echo "--- [flow] Running php-cs-fixer (diff mode)"
	@bin/php-cs-fixer fix --config .php_cs --diff --dry-run

.PHONY: phpstan
phpstan: ## Runs phpstan
	@echo "--- [flow] Running phpstan"
	@bin/phpstan analyze --configuration phpstan.neon
