#!/usr/bin/make -f
include .env
export
BRANCH := $(shell git name-rev --name-only HEAD)

.PHONY: build update-d update-f gulp-watch

all: env-build start build init-db init-data

build: update-d update-f

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
	@echo ">>> Start: Visit http://localhost:9487 ...."

stop:
	docker-compose stop

update-d:
	composer install

update-f:
	npm install

gulp-watch:
	gulp watch

# Environment Related
env-build: docker-build

cp_conf:
	cp _conf/default.conf /tmp

docker-build:
	docker-compose build --parallel

docker-destroy:
	docker-compose down --remove-orphans

destroy: docker-destroy
	rm -rf vendor && rm -rf node_modules

rebuild: destroy all

# DB init
init-db:
	docker exec -it dev_phpfpm php artisan migrate

# Data Init
init-data:
	cp resources/json/condition.sample.json resources/json/condition.json

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


