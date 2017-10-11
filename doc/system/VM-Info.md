<a name="urls"/>
## URLs
* Production Site
    * [http://white.ist.rit.edu](http://white.ist.rit.edu)
* Admin and Dev Pages
    * [Adminer](http://white.ist.rit.edu/tswift/db)

<a name="ci"/>
## CI setup
* We are using a CI service called [Buildkite]()
    * Runs an agent to pull from GitHub and deploy to correct location

<a name="conf"/>
## Configuration notes
* Application located in /var/www/cars
    * Apache conf in default location
    * Using self-signed SSL certificate
* Adminer in /car/www/tswift/db
    * MySQL users for each team member

