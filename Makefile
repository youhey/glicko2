.PHONY: all
all: build install test

.PHONY: build
build:
	@docker build -t glicko2/php docker/php

.PHONY: install
install:
	@docker run --rm -it -v "${PWD}:/work" glicko2/php composer install

.PHONY: test
test:
	@docker run --rm -it -v "${PWD}:/work" glicko2/php vendor/bin/phpunit
