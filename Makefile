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

container-image:
	podman rmi jamesread/sicroc || true
	buildah bud -t jamesread/sicroc .

container-instance:
	podman kill sicroc || true
	podman rm sicroc || true
	podman create --name sicroc -p 1340:8080 -v /var/www/html/Sicroc/test:/etc/Sicroc jamesread/sicroc
	podman start sicroc

phpstan:
	./src/private/libraries/bin/phpstan analyse -c phpstan.neon

.PHONY: default test
