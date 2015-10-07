# gitlab2rundeck-adapter

This small package able you to run a rundeck job after a gitlab(ci) event is hooked.

## Requirements:
- works with PHP 5.4 or above
- rundeck api 12 and above
- gitlab(ci) 7 and above

note: 
    it works with the webhook of gitlab AND gitlabCI. However, when using the
    webhook of gitlab, the build_status is considered as failed. So in that case
    it would be useful to specify "runOnFail: true" in the project config.

## Configuration

Create two configuration files. One for rundeck and another one for the gitlab's projects

### Rundeck configuration

```yaml
# rundeck api configuration
# http{$ssl}://{$host}:{$port}/api/{api_version}/{jobID}/(run|executions)
host: rundeck.local                     # REQUIRED
token: CmBl3gDr8ua6uMXQS0pLSmGUDvHjdOl7 # REQUIRED
port: 4440                              # OPTIONAL (Default 4440)
ssl: true                               # OPTIONAL (Default false)
api_version: 13                         # OPTIONAL (Default 14. Has to be >= 13)
log_level: DEBUG                        # OPTIONAL ('DEBUG','VERBOSE','INFO','WARN','ERROR')
```

### Gitlab projects configuration

```yaml
# gitlab(ci) configuration
gitlab_url: http://gitlab
projects:
- project:
    url: repos/app1                             # REQUIRED project url (w/o the base_url)
    jobId: 558d3c76-7768-4056-a10c-0842ecae0ca9 # REQUIRED Rundeck Job UUID
    ref: master                                 # OPTIONAL default: master. Project branch
    runOnFail: true                             # OPTIONAL default: false. Run the job even if the tests failed
    runOnTagOnly: true                          # OPTIONAL default: false. Run the job only when a tag is done (useful for release deployment)
    jobArgs: { arg1: foo, arg2: bar }           # OPTIONAL Rundeck Job arguments
    runJobAs: foo                               # OPTIONAL Run the job as the given user
- project:
    url: repos/app2
    jobId: 558d3c76-7768-4056-a10c-0842ecae0ca8
    ref: master
    runOnFail: false
[...]
```

## Run the adapter

```php
$adapter = G2R\Adapter::factory(
    __DIR__ . '/rundeck.yml',
    __DIR__ . '/projects.yml',
    // Eventually, give it a logger that implements psr3 (Psr\Log\LoggerInterface)
    (new Monolog\Logger('g2r'))->pushHandler(new Monolog\Handler\StreamHandler('./g2r.log'))
);

$adapter->run();
```
