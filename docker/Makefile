#!make

start:
	docker-compose up -d

stop:
	docker-compose stop

queue_work:
	docker-compose exec $(d) newsio_phpfpm php artisan queue:work

queue_restart:
	docker-compose exec $(d) newsio_phpfpm php artisan queue:restart

init:
	docker-compose exec newsio_phpfpm composer install
	docker-compose exec newsio_phpfpm php artisan key:generate
	docker-compose exec newsio_phpfpm php artisan migrate --seed
	docker-compose exec newsio_phpfpm chgrp -R www-data storage bootstrap/cache && chmod -R ug+rwx storage bootstrap/cache
	docker-compose exec newsio_phpfpm mkdir -p logs
	docker-compose exec newsio_phpfpm chmod -R 755 logs/

refresh:
	docker-compose exec newsio_phpfpm chmod -R 755 logs/

update:
	docker-compose exec newsio_phpfpm chmod -R 755 logs/

supervisor_start:
	docker-compose exec newsio_queue service supervisor stop
	docker-compose exec newsio_queue service supervisor start
	docker-compose exec newsio_queue supervisorctl reread
	docker-compose exec newsio_queue supervisorctl update
	docker-compose exec newsio_queue supervisorctl start laravel-worker:*