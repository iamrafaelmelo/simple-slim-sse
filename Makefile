up:
	docker compose up || docker-compose up
down:
	docker compose down || docker-compose down
container:
	docker exec -it server-side-events-app sh
