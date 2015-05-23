default: test package

test:
	phpunit tests/Test*

package:
	rm -rf pkg
	mkdir -p pkg
	./buildinfo.sh > buildinfo.txt
	zip -r pkg/Sicroc.zip buildinfo.txt
	zip -r pkg/Sicroc.zip src/public.html
	zip -r pkg/Sicroc.zip src/public.json
	zip -r pkg/Sicroc.zip src/private

.PHONY: default test
