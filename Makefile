all:
	@make down
	@make build
	@make up
be:
	docker stop giangthe_site
	docker-compose build site
	docker-compose up -d site
worker:
	docker stop giangthe_worker
	docker-compose build worker
	docker-compose up -d worker
nginx:
	docker stop giangthe_nginx
	docker-compose build nginx
	docker-compose up -d nginx
redis:
	docker stop giangthe_redis
	docker-compose build redis
	docker-compose up -d redis
build:
	docker-compose build
down:
	docker-compose down
stop:
	docker-compose stop
up:
	docker-compose up -d

