#!make

start:
	docker-compose up -d

stop:
	docker-compose stop

queue_work:
	docker-compose exec $(d) phpfpm_newsio php artisan queue:work

queue_restart:
	docker-compose exec $(d) phpfpm_newsio php artisan queue:restart

init:
	docker-compose exec phpfpm_newsio mkdir -p logs
	docker-compose exec phpfpm_newsio chmod -R 755 logs/

refresh:
	docker-compose exec phpfpm_newsio chmod -R 755 logs/

update:
	docker-compose exec phpfpm_newsio chmod -R 755 logs/
