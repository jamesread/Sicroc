default:
	rm -rf pkg
	mkdir -p pkg
	./id.sh > buildinfo.txt
	zip -r pkg/Sicroc.zip buildinfo.txt
	zip -r pkg/Sicroc.zip src/public.html
	zip -r pkg/Sicroc.zip src/public.json
	zip -r pkg/Sicroc.zip src/private

