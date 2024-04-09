default: test package

test:
	phpunit tests/Test*

phpcs:
	src/private/libraries/bin/phpcs

phpcbf:
	src/private/libraries/bin/phpcbf src/private/app/

package:
	rm -rf pkg
	mkdir -p pkg
	./buildinfo.sh > buildinfo.txt
	zip -r pkg/Sicroc.zip buildinfo.txt
	zip -r pkg/Sicroc.zip src/public.html
	zip -r pkg/Sicroc.zip src/public.json
	zip -r pkg/Sicroc.zip src/private

container-image-base:
	podman rmi jamesread/sicroc-base || true
	buildah bud -t localhost/sicroc/sicroc-base -f Dockerfile.base

container-image:
	podman rmi jamesread/sicroc || true
	buildah bud -t localhost/sicroc/sicroc -f Dockerfile.app

container-instance:
	podman kill sicroc || true
	podman rm sicroc || true
	podman create --name sicroc -p 1350:8080 -v /etc/Sicroc/:/etc/Sicroc/ localhost/sicroc/sicroc
	podman start sicroc


docker-container-image-base:
	docker build -t localhost/sicroc/sicroc-base -f Dockerfile.base .

docker-container-image:
	docker build -t localhost/sicroc/sicroc:latest -f Dockerfile.app .

phpstan:
	./src/private/libraries/bin/phpstan analyse -c phpstan.neon

.PHONY: default test
