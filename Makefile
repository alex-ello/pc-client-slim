
build: 
	docker build -t php-client-slim:dev .

run:
	docker run -it --rm -p 8080:8080 php-client-slim:dev
