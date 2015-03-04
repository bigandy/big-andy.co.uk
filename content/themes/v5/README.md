# WordPress Coding Standards

First you need to install [WordPress Coding Standards](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards).

Then run `phpcs -s -v -p --standard=./code.ruleset.xml ./**/*.php *.php` to check whether the php is up to the WordPress coding standards.

## How to install
1. install composer
<pre>
brew update
brew tap homebrew/dupes
brew tap homebrew/php
brew install composer
</pre>
2. add composer to PATH
in ~/.bash_profile: add the line : `export PATH=~/.composer/vendor/bin:$PATH`
3. install phpcs
`composer global require "squizlabs/php_codesniffer=*"`
4. install wpcs
`composer create-project wp-coding-standards/wpcs:dev-master --no-dev`
5. Add its path to PHP_CodeSniffer configuration:
`phpcs --config-set installed_paths /path/to/wpcs`
6. test it works
`phpcs -s -v -p --standard=./code.ruleset.xml ./**/*.php *.php`

# Sass Linting
Install scss-lint with `[sudo] gem install scss-lint`