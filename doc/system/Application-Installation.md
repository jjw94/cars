<a name="build"/>
## Building from Source
_All commands should be run from the root of the project unless noted_
### Production environments (Linux)
* Make sure the deployment script is executable and run it
```bash
chmod +x deployment.sh
./ deployment.sh
```
The script will perform the following functions:
* Download and install Composer
* Install project dependencies
* Create the CARS database
* Create an initial system administrator user

### Development environments
[See developer notes](#)

<a name="conf"/>
## Application Configuration
* To make changes to the default configuration, make a copy of `app/src/settings-default.php` and place it in `app/src/settings.php`

### Production Environments 
1. Set `displayErrorDetails` to `false` to suppress technical data in errors
1. Change the log level to `ERROR` to reduce log size and only contain error information
1. Change DB connection user and password
1. Make sure `authType` is set to `shib`
    * _Note: Setting this to `dev` will disable any password authentication - **Never do this in production!**_

_Example production `settings.php` file_
```php
return [
    'settings' => [
        'displayErrorDetails' => false, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::ERROR,
        ],

        // DB Connection Info
        'db' => [
            'host' => 'localhost',
            'dbname' => 'cars',
            'user' => 'mySqlUsername',
            'pass' => 'mySqlPassword'
        ],

        //Authentication Mode (shib/dev)
        //NEVER SET TO DEV IN PRODUCTION!!!
        'authType' => 'shib'
    ],
];

```

### Development Environments
[See developer notes](#)
