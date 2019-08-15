#!/usr/bin/make -f
include .env
export
BRANCH := $(shell git name-rev --name-only HEAD)

.PHONY: build update-d update-f gulp-watch

all: build docker-build start

build: update-d update-f gulp-watch

update-d:
	composer install

update-f:
	npm install

gulp-watch:
	gulp watch

# Git Related

pull:
	@echo ">>> Pull Code on Current branch [$(BRANCH)]"
	git pull origin $(BRANCH) --rebase

push:
	@echo ">>> Current branch [$(BRANCH)] Pushing Code"
	git push origin $(BRANCH)

# Environment Related
cp_conf:
	cp _conf/default.conf /tmp

docker-build:
	docker-compose build --parallel

start: cp_conf
	docker-compose up -d --no-recreate
	chmod 777 resources

stop:
	docker-compose stop

docker-destroy:
	docker-compose down --remove-orphans

# Behavior

update-104-jobs:
	php artisan update:jobs 104

xupdate-104-jobs:
	docker exec -it dev_phpfpm php artisan update:jobs 104

update-companies:
	php artisan update:companies

init-db:
	docker exec -it dev_phpfpm php artisan migrate
