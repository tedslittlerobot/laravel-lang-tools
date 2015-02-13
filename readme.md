Laravel Language Utils
======================

> A language export and import command for Laravel 4. (yes, laravel 5 is coming...)

When dealing with agencies and companies who translate programming projects and websites into other languages, they often request it in certain formats.

- Currently works with csv only

### Installation

Run the following to install the library.

`composer require tlr/laravel-lang-tools "1.x"`

Then, add `Tlr\LaravelLangTools\LaravelLangToolsServiceProvider` to the `providers` array in `config/app.php`.

### Usage

The package provides two CLI commands:

#### artisan lang:export

Running `php artisan lang:export` will display the current lang tokens, and all of their translations in a table in the terminal - in a similar manner to viewing laravel's routes.

Running `php artisan lang:export --format csv > translations.csv` will save a csv of all of the translations, and their keys, to a csv file. This can be sent to translators.

#### artisan lang:import

Running `php artisan lang:import translations.csv` will convert a csv file (in the same format as the one outputted) into a laravel-style lang directory structure, with lang.php files in. At the moment, the path to the provided file must be relative to the project directory.

By default, the output is saved to a directory called `lang` in your project's root directory. You can pass a relative path as a third argument to change this. The target directory must not already exist.

### Notes for translators

You may want to give translators some notes from [laravel's localization docs page](http://laravel.com/docs/5.0/localization) - especially regarding variables, and pluralisation.
