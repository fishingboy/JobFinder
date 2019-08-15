#!/usr/bin/make -f
include .env
export
BRANCH := $(shell git name-rev --name-only HEAD)

.PHONY: build update-d update-f gulp-watch

all: build env-build start

build: update-d update-f gulp-watch

# Git Related
pull:
	@echo ">>> Pull Code on Current branch [$(BRANCH)]"
	git pull origin $(BRANCH) --rebase

push:
	@echo ">>> Current branch [$(BRANCH)] Pushing Code"
	git push origin $(BRANCH)

# Use the System

start: cp_conf
	docker-compose up -d --no-recreate
	chmod 777 resources

stop:
	docker-compose stop


update-d:
	composer install

update-f:
	npm install

gulp-watch:
	gulp watch

# Environment Related
env-build: docker-build init-db

cp_conf:
	cp _conf/default.conf /tmp

docker-build:
	docker-compose build --parallel

docker-destroy:
	docker-compose down --remove-orphans

# DB init
init-db:
	docker exec -it dev_phpfpm php artisan migrate


# Behavior

update-104-jobs:
	php artisan update:jobs 104

update-companies:
	php artisan update:companies

# Behavior w/Docker
dk-update-jobs:
	docker exec -it dev_phpfpm php artisan update:jobs 104

dk-update-companies:
	docker exec -it dev_phpfpm php artisan update:companies

refresh: dk-update-jobs dk-update-companies


