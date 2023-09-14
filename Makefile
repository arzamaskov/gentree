BUILD_ARGS:= --build-arg USERID=$(shell id -u) \
		   --build-arg GROUPID=$(shell id -g) \
		   --build-arg USERNAME=$(shell id -un) \
		   --build-arg GROUPNAME=$(shell id -gn)

build:
	docker build ${BUILD_ARGS} -t app .

install:
	docker run -it -v $(PWD):/app app bash -c "composer install"

run:
	docker run -it -v $(PWD):/app app bash
