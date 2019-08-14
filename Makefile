#!/usr/bin/make -f
include .env
export
BRANCH := $(shell git name-rev --name-only HEAD)

.PHONY: build update-d update-f gulp-watch

build: update-d update-f gulp-watch

update-d:
	composer install

update-f:
	npm install

gulp-watch:
	gulp watch

pull:
	@echo ">>> Pull Code on Current branch [$(BRANCH)]"
	git pull origin $(BRANCH) --rebase

push:
	@echo ">>> Current branch [$(BRANCH)] Pushing Code"
	git push origin $(BRANCH)

update-104-jobs:
	php artisan update:jobs 104

update-companies:
	php artisan update:companies