symfony_task
============
###Test task according to given requirements###

**HOW TO USE**:

With default filename:
```php app/console app:import:csv```

With default filename without persisting data to DB:
```php app/console app:import:csv --testMode```

With custom filename and including first line option (e.g. if is not description)
```php app/console app:import:csv c:/data/stock.csv --includeFL```

With custom filename, without persisting data to DB, including first line option
```php app/console app:import:csv c:/data/stock.csv --testMode --includeFL```

**TO RUN TESTS**:
```phpunit -c app src/AppBundle/Tests/Utils/ParserTest.php```

*NOTICES*:
File name argument is optional (default: symfony_task/app/data/stock.csv)
All settings are optional