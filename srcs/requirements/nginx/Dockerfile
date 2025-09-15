FROM debian:bullseye

EXPOSE 443

RUN apt update && apt install -y --no-install-recommends --no-install-suggests \
    ngnix \
    openssl && \
    rm -rf  /var/lib/apt/lists/*

ARG cert_folder certificate key country state locality organisation unit common_name

RUN mkdir -p ${cert_folder} && \
    openssl req -newkey rsa:4096 -x509 -sha256 -days 365 -nodes \
    -out ${certificate} \
    -keyout ${key} \
    -subj "/C=${country}/ST=${state}/L=${locality}/O=${organisation}/OU=${unit}/CN=${common_name}"

COPY conf/default.conf /etc/nginx/conf.d/

RUN        echo "\tserver name ${common_name};\n\
            \tssl_certificate ${certificate};\n\
            \tssl_certificate_key ${key};\n\
            }" >> /etc/nginx/conf.d/default.conf

RUN        mkdir -p /var/www/html
RUN        chown -R www-data:www-data /var/www/html

CMD        ["nginx", "-g", "daemon off;"]