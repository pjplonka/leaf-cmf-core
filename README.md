## Assumptions

### Packages
- [Symfony validator](https://symfony.com/doc/current/components/validator.html) is required and can not be replaced.   
All validation rules must be provided from [Symfony Validator Constraints list](https://symfony.com/doc/current/validation.html#basic-constraints).
- Uuid Interface is provided by [ramsey/uuid package](https://github.com/ramsey/uuid)
- All DateTime objects are wrapped by [Carbon](https://github.com/briannesbitt/Carbon)  
[CarbonImmutable](https://github.com/briannesbitt/Carbon/blob/master/src/Carbon/CarbonImmutable.php) is used each time.

## Makefile

### Examples:  
Require composer package:  
`make composer:require p=symfony/validator`

## TODO:  
- Add assertions to every field type (e.g. StringType must contain StringValue which checks base validation rules
like `is_string` or `max_length=200`) and for element (e.g. field name cannot be duplicated)
- configuration validator + ConfigurationIsInvalidException 
- encryption for field on demand (in configuration)
- event dispatcher interface
- element `name` property should be changed to something like group, band or something
- every field should have own constraints instead of holding it in configuration array (and configuration can hold additional constraints)
- add some TestField to use it in tests to not use real field
- add command bus and use it in tests
- what about read model? do we need any bus for it?
- write more about makefile in readme
- fields should contain basic constraints (eg. string must be string and max length is 255)
- FieldsDtoValidator: rename this class + validate configuration field type (type can be invalid e.g. not exist)
- 