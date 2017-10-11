## Important URLs
<a name="env"/>
## Setting up the Dev Environment

<a name="install"/>
### Install and configure

<a name="conf"/>
### Local authentication

<a name="app-structure"/>
## Application Structure

<a name="slim"/>
### Slim framework

<a name="class"/>
### Class structure

<a name="db"/>
### Getting a Database Connection
* A PDO database connection exists in the project dependencies
    * Connection parameters must be configured in [[settings.php|application-installation#settings]]
* Calling `$this->db` will return a PDO connection object

<a name="logger"/>
### Using the Logger
* A logger also exists in the dependencies and can be accessed with `$this->logger`
* An info log message would look something like this
```php
 $this->logger->info("Something interesting happened");
```
