SHELL := /bin/bash

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

prepare: network

network:
	docker network create environment_docker-fresh || true

database: prepare
	docker-compose -f docker-compose/db.yml -p docker-fresh-db up -d --build

fpm: prepare
	docker-compose -f docker-compose/fpm.yml -p docker-fresh-fpm up -d --build

nginx: prepare
	docker-compose -f docker-compose/nginx.yml -p docker-fresh-nginx up -d --build

redis: prepare
	docker-compose -f docker-compose/redis.yml -p docker-fresh-redis up -d --build

up: prepare database fpm nginx

down:
	docker stop $$(docker ps -aqf "name=docker-fresh-") || true

remove:
	docker rm -f $$(docker ps -aqf "name=docker-fresh-") || true
