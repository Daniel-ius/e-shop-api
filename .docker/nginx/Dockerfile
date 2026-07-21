FROM nginx:alpine

COPY ssl/localhost.pem /etc/nginx/ssl/
COPY ssl/localhost-key.pem /etc/nginx/ssl/

COPY . /var/www/html
