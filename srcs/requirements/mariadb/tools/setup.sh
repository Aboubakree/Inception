#!/bin/bash
service mariadb start

mariadb -u root << EOF
CREATE DATABASE IF NOT EXISTS $MARIADB_DATABASE;
CREATE USER IF NOT EXISTS '$MARIADB_USER'@'%' IDENTIFIED BY '$MARIADB_PASSWORD';
GRANT ALL PRIVILEGES ON $MARIADB_DATABASE.* TO '$MARIADB_USER'@'%' IDENTIFIED BY '$(cat /run/secrets/mariadb_password)';
SET PASSWORD FOR 'root'@'localhost' = PASSWORD('$(cat /run/secrets/mariadb_root_password)');
EOF

sleep 5

service mariadb stop

exec $@ 