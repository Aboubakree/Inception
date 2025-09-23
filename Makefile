DOCKER = docker compose

all:
	@mkdir -p $(HOME)/data/wordpress
	@mkdir -p $(HOME)/data/mariadb
	@$(DOCKER) -f srcs/docker-compose.yml up

clean:
	@docker stop $$(docker ps -qa)
	@$(DOCKER) -f srcs/docker-compose.yml down

fclean :
	@$(DOCKER) -f srcs/docker-compose.yml down
	@sudo rm -rf $(HOME)/data/wordpress
	@sudo rm -rf $(HOME)/data/mariadb
	@docker rmi -f $$(docker images -qa)
	@docker volume rm -f $$(docker volume ls -q)
	@docker network rm -f $$(docker network ls -q) 2> /dev/null || echo -n

re: fclean all
