# Javascript/CSS Compressor Makefile - By Benjamin "balupton" Lupton (MIT Licenced)

MAKEFLAGS = --no-print-directory --always-make
MAKE = make $(MAKEFLAGS)

BUILDDIR = ./.build

CLOSUREURL = http://closure-compiler.googlecode.com/files/compiler-latest.zip
CLOSUREDIR = $(BUILDDIR)/closure
CLOSUREFILE = $(CLOSUREDIR)/compiler.jar
YUIURL = http://yui.zenfs.com/releases/yuicompressor/yuicompressor-2.4.7.zip
YUIDIR = $(BUILDDIR)/yui
YUIFILE = $(YUIDIR)/yuicompressor-2.4.7/build/yuicompressor-2.4.7.jar


all:
	$(MAKE) build;
	$(MAKE) add;


demo:
	open ./demo/index.html

add:
	git add .gitignore CHECKLIST.* COPYING.* demo Makefile README.* prettify scripts styles

push:
	git push --all ; git push --tags ;

edithooks:
	mate .git/hooks/pre-commit


refresh:
	wget -q http://balupton.github.com/jquery-sparkle/scripts/resources/core.console.js -O scripts/resources/core.console.js ;
	wget -q http://balupton.github.com/jquery-sparkle/scripts/resources/jquery.appendscriptstyle.js -O scripts/resources/jquery.appendscriptstyle.js ;

pack:
	cat \
		./scripts/resources/core.console.js \
		./scripts/resources/jquery.appendscriptstyle.js \
		./scripts/resources/jquery.syntaxhighlighter.js \
		> ./scripts/jquery.syntaxhighlighter.js;

compress:
	java -jar $(CLOSUREFILE) --create_source_map ./scripts/closure.map --js_output_file=./scripts/jquery.syntaxhighlighter.min.js --js=./scripts/jquery.syntaxhighlighter.js;
	java -jar $(YUIFILE) ./styles/style.css -o ./styles/style.min.css
	java -jar $(YUIFILE) ./styles/theme-balupton.css -o ./styles/theme-balupton.min.css
	java -jar $(YUIFILE) ./styles/theme-google.css -o ./styles/theme-google.min.css
	java -jar $(YUIFILE) ./styles/ie.css -o ./styles/ie.min.css

	java -jar $(CLOSUREFILE) --create_source_map ./prettify/closure.map --js_output_file=./prettify/prettify.min.js --js=./prettify/prettify.js;
	java -jar $(YUIFILE) ./prettify/prettify.css -o ./prettify/prettify.min.css

build:
	$(MAKE) pack;
	$(MAKE) compress;

build-update:
	$(MAKE) clean;
	mkdir $(BUILDDIR) $(CLOSUREDIR) $(YUIDIR);
	cd $(CLOSUREDIR); wget $(CLOSUREURL) -O file.zip; tar -xf file.zip;
	cd $(YUIDIR); wget $(YUIURL) -O file.zip; tar -xf file.zip;

clean:
	rm -Rf $(BUILDDIR);

