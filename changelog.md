# Changelog for Laravel Bets Api

## 2.0.0 (2021-04-02)
- Laravel 8 support (too late, I know)

## 1.0.9 (2020-03-31)
- Laravel 7 support

## 1.0.8 (2018-10-21)
- Added support for the /event/view endpoint

## 1.0.7 (2018-08-25)
- Added support for the /event/merge_history endpoint

## 1.0.6 (2018-08-18)
- Added support for the /events/inplay endpoint

## 1.0.5 (2018-08-18)
- Bug fix: env variables which holds integer no longer fail the int validation

## 1.0.4 (2018-08-15)
- Bug fix: Used wrong variable when catching a failed request

## 1.0.3 (2018-08-11)
- Add support for requests to both v1 and v2 endpoints
- Retry failed requests few times before throwing an exception. That's configurable via the config file's failed_calls key
- Dispatch RequestFailed and ResponseReceived events

## 1.0.2 (2018-07-21)
- The odds call now returns the bookmaker, alongside the odds

## 1.0.1 (2018-07-13)
- Allow ended games to be filtered by date. Available only for v2 of the betsapi.com API

## 1.0.0 (2018-06-12)
- A new package was born
