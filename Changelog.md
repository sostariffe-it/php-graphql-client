# Change Log

The change log describes what is "Added", "Removed", "Changed" or "Fixed" between each release.


## 1.1: 2019-04-26

### Added

- Mutation class for running mutation operations
- Examples in the README for running
    - Mutation operation
    - Raw string queries
- New badges to README to show downloads count, license, and latest version
- Changelog

### Changed

- `Client::runQuery` method now accepts `QueryBuilderInterface` as well as `Query`


## 1.0: 2019-04-19

### Removed

- Moved all schema generation logic to a separate repository


## 0.6: 2019-04-09

### Added

- Generate ArgumentsObject classes for all argument lists on fields
- Ability to override default schema writing director with a custom one
- Ability to generate classes with custom namespaces
- Pushed code coverage to 100%

### Changed

- Refactored schema class generation mechanism to traverse the API schema from the root queryType
- Refactored QueryObject class to accommodate changes in the schema generation
    - Removed arguments from QueryObject classes and moved them to ArgumentsObjects
    - Modified how generation works to accommodate ArgumentsObjects nested within QueryObjects
    - Added ArgumentsObject argument to all field selector methods
- Refactored Query class
    - Added the ability to set Query object name to 'query'



## 0.5: 2019-03-25

### Added

- Query builder functionality

### Changed

- Minimum PHP version form 7.2 to 7.1

### Fixed

- Throw QueryError on 400 response
- Throw QueryError in missing scenario (specifying result as array)

### Removed

- composer.lock from version control


## 0.4: 2019-03-25

### Added

- Support for Travis CI
- Installation section to README
- Codacy support for analyzing code
- Raised code coverage
- Generator script to composer file to add it composer bin

### Changed

- Upgraded to PHP7 (7.2 minimum version)


## 0.3.4: 2019-03-02

### Fixed

- Issue in README examples


## 0.3.3: 2019-03-02

### Fixed:

- Issue in package root directory name


## 0.3.2: 2019-03-02

### Fixed:

- Autoload issue in schema classes generation


## 0.3.1: 2019-03-02

### Fixed:

- Issue in input object generation


## 0.3: 2019-03-02

### Added:

- Generation of filters and arguments depending on type
- Input object setters for input object arguments


## 0.2: 2019-02-16

### Added

- Auto schema generation using GraphQL inspection ability
- Throw QueryError if syntax errors are detected by the GraphQL server
- Unit tests to cover basic functionality


## 0.1.4: 2019-01-18

### Added

- Ability to run raw string queries
- Set default content type

### Fixed

- String values do not get wrapped in quotations in the arguments construction


## 0.1.3: 2018-10-10

### Fixed

- Fixed issue in generating query when no arguments are provided


## 0.1.2: 2018-10-07

### Fixed

- Typo in namespace declaration

## 0.1.1: 2018-10-07 


### Changed

- Upgrade Guzzle from 5.x to 6.x


## 0.1: 2018-10-07

- First release
