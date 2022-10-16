# Required for source commands in Makefile
SHELL := /bin/bash

##@ Utility commands

.PHONY: help
help: ## Displays this help command
	@echo "\033[33mMakefile\033[0m"
	@echo "\033[33m--------\033[0m"
	@awk 'BEGIN {FS = ":.*##"; } /^[a-zA-Z_-]+:.*?##/ { printf "  \033[32m%-18s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)
	@echo

.PHONY: test
test: php-cs-fixer phpstan phpunit

##@ Documentation

.PHONY: doc
doc: venv pip-install render-images ## Generates the documentation
	@source venv/bin/activate && mkdocs

.PHONY: pip-install
pip-install: ## Installs python dependencies
	@source venv/bin/activate && pip install -q -r requirements.txt

venv:
	@python3 -m venv venv

render-images: vendor ## Render the images in the documentation
	php bin/render-images.php

##@ Tests

.PHONY: phpunit
phpunit: vendor ## Runs PHPUnit tests and coverage test
	@XDEBUG_MODE=coverage vendor/bin/phpunit --coverage-clover build/coverage_clover.xml
	@php bin/coverage-checker.php build/coverage_clover.xml

.PHONY: php-cs-fixer-diff
php-cs-fixer: ## Runs PHP-CS-Fixer tests
	@bin/php-cs-fixer fix --config .php-cs-fixer.dist.php --dry-run --diff src tests

.PHONY: php-cs-fixer-diff
php-cs-fixer-fix: ## Runs PHP-CS-Fixer (and fix)
	@bin/php-cs-fixer fix --config .php-cs-fixer.dist.php --diff src tests

.PHONY: phpstan
phpstan: vendor ## Runs PHPStan
	@bin/phpstan analyse src src_dev tests

vendor:
	@composer install
