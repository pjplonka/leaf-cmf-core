## Assumptions
Interfaces:
- ConfigurationProvider
- Elements (Element Repository)
- EventDispatcher

### Packages
- [Symfony validator](https://symfony.com/doc/current/components/validator.html) is required and can not be replaced.   
All validation rules must be provided from [Symfony Validator Constraints list](https://symfony.com/doc/current/validation.html#basic-constraints).
- Uuid Interface is provided by [symfony/uid package](https://symfony.com/doc/current/components/uid.html)
- Only [DateTimeImmutable](https://www.php.net/manual/en/class.datetimeimmutable.php) objects are used

## Makefile

### Examples:  

#### Composer
Install composer packages:  
`make composer:install`

Require composer package:  
`make composer:require p=symfony/validator`

#### Tests
`make tests:run`  
or with filter:  
`make tests:run f=CreateElementHandlerTest`

#### Stan
`make stan`

## TODO:  
- Add assertions to every field type (e.g. StringType must contain StringValue which checks base validation rules
like `is_string` or `max_length=200`) and for element (e.g. field name cannot be duplicated)
- configuration validator + ConfigurationIsInvalidException 
- encryption for field on demand (in configuration)
- element `name` property should be changed to something like group, band or something
- every field should have own constraints instead of holding it in configuration array (and configuration can hold additional constraints)
- add some TestField to use it in tests to not use real field
- what about read model? do we need any bus for it?
- write more about makefile in readme
- fields should contain basic constraints (eg. string must be string and max length is 255)
- FieldsDtoValidator: rename this class + validate configuration field type (type can be invalid e.g. not exist)
- FieldsDtoValidator should be a default one for usage
- transactional manager interface?
- add assertion class
- validation failed exception could be used form symfony package
- test for empty constraints (allowMissingFields) is set to true
- when tests are run in docker it creates container and it is not reusable (every time make run:tests is run, new container is created)