

all:
	@mkdir -p $(HOME)/data/wordpress
	@mkdir -p $(HOME)/data/mariadb
	@docker-compose -f srcs/docker-compose.yml up

clean:
	@docker stop $$(docker ps -qa)
	@docker-compose -f srcs/docker-compose.yml down

fclean : clean
	sudo rm -rf $(HOME)/data/wordpress
	sudo rm -rf $(HOME)/data/mariadb
	docker rmi $(docker ps -qa)
	docker volume rm $(docker volume ls -q)
	docker network rm $(docker network ls -q)

re: fclean all