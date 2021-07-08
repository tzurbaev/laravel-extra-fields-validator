# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) 
and this project adheres to [Semantic Versioning](http://semver.org/).

## [1.0.4] - 2021-07-08

### Fixed

- Allow PHP 8.0.

## [1.0.3] - 2020-01-23

### Fixed

- Fixed the `Array to string conversion` error from `array_diff` function (`ExtraFieldsDetector`). This also
fixes validation cases with empty arrays.

## [1.0.2] - 2020-01-23

### Added

- Added `data_source` configuration that allows to change validation data source.

## [1.0.1] - 2020-01-23

### Added

- Added trait `Laravel\ExtraFieldsValidator\ProvidesExtraFieldsValidator`, so requests that must be extended from
another classes still can have extra fields validation.

## [1.0.0] - 2020-01-22

Initial release

[1.0.4]: https://github.com/tzurbaev/laravel-extra-fields-validator/compare/1.0.3...1.0.4
[1.0.3]: https://github.com/tzurbaev/laravel-extra-fields-validator/compare/1.0.2...1.0.3
[1.0.2]: https://github.com/tzurbaev/laravel-extra-fields-validator/compare/1.0.1...1.0.2
[1.0.1]: https://github.com/tzurbaev/laravel-extra-fields-validator/compare/1.0.0...1.0.1
[1.0.0]: https://github.com/tzurbaev/laravel-extra-fields-validator/releases/tag/1.0.0
