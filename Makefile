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

