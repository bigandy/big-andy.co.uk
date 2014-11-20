# WordPress Coding Standards

First you need to install [WordPress Coding Standards](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards).

Then run `phpcs -s -v -p --standard=./code.ruleset.xml ./**/*.php *.php` to check whether the php is up to the WordPress coding standards.

## How to install
1. install composer
`brew update
brew tap homebrew/dupes
brew tap homebrew/php
brew install composer`
2. add composer to PATH
in ~/.bash_profile: add the line : `export PATH=~/.composer/vendor/bin:$PATH`
3. install phpcs
`composer global require "squizlabs/php_codesniffer=*"`
4. install wpcs
`composer create-project wp-coding-standards/wpcs:dev-master --no-dev`
5. test it works
`phpcs -s -v -p --standard=./code.ruleset.xml ./**/*.php *.php`