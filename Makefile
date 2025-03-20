up:
	docker compose up -d

down:
	docker compose down

update:
	docker exec -it game-station-backend sh -c "cd /var/www/html && composer update"

migrate:
	docker exec -it game-station-backend sh -c "php artisan migrate"

migrate-refresh:
	clear && \
	docker exec -it game-station-backend sh -c "php artisan migrate:refresh" 

install:
	docker run -it -v ./start-app:/var/www/html diovanegabriel/php8.3-laravel:latest /bin/bash -c "composer create-project laravel/laravel . && php artisan install:api" && \
	sudo chmod -R 777 . && \
	mv ./start-app/* . && \
	mv ./start-app/.editorconfig . && \
	mv ./start-app/.env . && \
	mv ./start-app/.env.example . && \
	mv ./start-app/.gitattributes . && \
	mv ./start-app/.gitignore . && \
	rm -rf ./start-app/ && \
	docker container prune -f && \
	docker compose up -d && \
	make update