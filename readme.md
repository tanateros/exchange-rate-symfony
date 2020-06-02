Require
=
- PHP 7.3+ and extensions 
- composer

Install
=
Download there files. Install Symfony 4.1+. After run in console:
```
composer install
bin/console do:da:cr
bin/console do:mi:mi
```

For parsing run in console:
```
bin/console app:parse-rates
```

Run server
=
```
symfony server:start
```
Service will allow in `http://127.0.0.1:8000`;

For change source server need rewrite in config/services.yaml `parameters.source_default`

API
=
Get rate currencies:
```http request
/rate/get
```
Request:
- from (required, string, 3 symbols) - from currency
- to (required, string, 3 symbols) - to currency
- amount (optional, float or integer, any symbols) - amount for change (default 1)

Response (JSON):
- rate (float)

Exceptions: if bad request may be logic exceptions.

TODO
=
- api docs in Swagger
- tests: integration, unit. Test docs
- consider use Form/SearchRateType
- add logs when parsing
