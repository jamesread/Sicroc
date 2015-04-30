default:
	rm -rf pkg
	mkdir -p pkg
	zip -r pkg/foo.zip src/public.html
	zip -r pkg/foo.zip src/public.json
	zip -r pkg/foo.zip src/private
