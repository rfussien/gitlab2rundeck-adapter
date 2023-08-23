gitlab2rundeck-adapter
======================

[![Latest Stable Version](https://poser.pugx.org/rfussien/gitlab2rundeck-adapter/v/stable)](https://packagist.org/packages/rfussien/gitlab2rundeck-adapter)
[![License](https://poser.pugx.org/rfussien/gitlab2rundeck-adapter/license)](https://packagist.org/packages/rfussien/gitlab2rundeck-adapter)
[![Build Status](https://travis-ci.org/rfussien/gitlab2rundeck-adapter.svg?branch=master)](https://travis-ci.org/rfussien/gitlab2rundeck-adapter)
[![Dependency Status](https://www.versioneye.com/user/projects/563779a136d0ab0016002151/badge.svg?style=flat)](https://www.versioneye.com/user/projects/563779a136d0ab0016002151)
[![Code Coverage](https://scrutinizer-ci.com/g/rfussien/gitlab2rundeck-adapter/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/rfussien/gitlab2rundeck-adapter/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/rfussien/gitlab2rundeck-adapter/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/rfussien/gitlab2rundeck-adapter/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/02cd6dc2-07ad-4418-9be8-6795211ea211/mini.png)](https://insight.sensiolabs.com/projects/02cd6dc2-07ad-4418-9be8-6795211ea211)

This package allows to run a rundeck job after a gitlab(ci) event is hooked.

## Requirements:
- works with PHP 5.6 or above
- rundeck api 12 and above
- gitlab(ci) 8.5 and above

> note:
    it works with the webhook of gitlab AND gitlabCI. However, when using the
    webhook of gitlab, the build_status is considered as failed. So in that case
    it would be useful to specify "runOnFail: true" in the project config.

## Install

Via Composer

``` bash
$ composer require rfussien/gitlab2rundeck-adapter
```

## Usage

### Configuration

Create two yaml configuration files. One for rundeck and another one for the gitlab's projects

#### Rundeck configuration

```yaml
# rundeck api configuration
# http{$ssl}://{$host}:{$port}/api/{api_version}/{jobID}/(run|executions)
host: rundeck.local                     # REQUIRED
token: CmBl3gDr8ua6uMXQS0pLSmGUDvHjdOl7 # REQUIRED
port: 4440                              # OPTIONAL (Default 4440)
ssl: true                               # OPTIONAL (Default false)
api_version: 13                         # OPTIONAL (Default 13. Has to be >= 12)
log_level: DEBUG                        # OPTIONAL ('DEBUG','VERBOSE','INFO','WARN','ERROR')
```

#### Gitlab projects configuration

```yaml
# gitlab(ci) configuration
gitlab_url: http://gitlab
projects:
- project:
    name: repos/app1                             # REQUIRED project name (w/o the base_url)
    jobId: 558d3c76-7768-4056-a10c-0842ecae0ca9 # REQUIRED Rundeck Job UUID
    ref: master                                 # OPTIONAL default: master. Project branch
    runOnFail: true                             # OPTIONAL default: false. Run the job even if the tests failed
    runOnTagOnly: true                          # OPTIONAL default: false. Run the job only when a tag is done (useful for release deployment)
    jobArgs: { arg1: foo, arg2: bar }           # OPTIONAL Rundeck Job arguments
    runJobAs: foo                               # OPTIONAL Run the job as the given user
- project:
    name: repos/app2
    jobId: 558d3c76-7768-4056-a10c-0842ecae0ca8
    ref: master
    runOnFail: false
[...]
```

### Run the adapter

```php
$adapter = G2R\Adapter::factory(
    __DIR__ . '/rundeck.yml',
    __DIR__ . '/projects.yml',
    // Eventually, give it a logger that implements psr3 (Psr\Log\LoggerInterface)
    (new Monolog\Logger('g2r'))->pushHandler(new Monolog\Handler\StreamHandler('./g2r.log'))
);

$adapter->run();
```

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email :author_email instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
