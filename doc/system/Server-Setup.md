<a name="sys"/>
## System Requirements
### Hardware Requirements
* Modern x64 multi-core processor
* Minimum 4 GB Ram
* Minimum 30 GB HDD space
    * Approximately 5 GB will be used by core packages
    * Approximately 20 GB will be available for the application and database

### Software Requirements
* Operating System: Ubuntu, latest LTS release
* Apache Web Server (latest stable version)
* MariaDB (latest stable version)
* PHP (5.6.25 or later)

<a name="apache"/>
## Apache Configuration
* The DocumentRoot directive must point to the '/src/public' directory of the project
* SSL must be enabled in order for Shibboleth to work

<a name="shib"/>
## Shibboleth Configuration
* In order to use RIT's SSO, the Shibboleth Service provider must be installed and configured correctly
    * Instructions from ITS can be found [here](https://wiki.rit.edu/pages/viewpage.action?spaceKey=ITSoperations&title=SSO+-+Shibboleth+Service+Provider)
    * Once the service provider is configured, ITS needs to be contacted to finish setup and provide an attribute map file
    * Here is the expected attribute map

