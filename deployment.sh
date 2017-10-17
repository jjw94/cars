#!/bin/sh

#===============GET COMPOSER=====================
echo "Downloading Composer...."
EXPECTED_SIGNATURE=$(wget -q -O - https://composer.github.io/installer.sig)
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
ACTUAL_SIGNATURE=$(php -r "echo hash_file('SHA384', 'composer-setup.php');")

if [ "$EXPECTED_SIGNATURE" != "$ACTUAL_SIGNATURE" ]
then
    echo 'ERROR: Invalid installer signature'
    rm composer-setup.php
    exit 1
fi

# ==============INSTALL COMPOSER===========================
echo "Installing Composer...."
php composer-setup.php --quiet
RESULT=$?
if [ $RESULT -eq 0 ]; then
    rm composer-setup.php
    echo "Composer installed successfully!"
else
    echo "Composer Failed to install, aborting..."
    exit 1
fi

#============RUN COMPOSER TO INSTALL DEPENDENCIES=============
echo "Installing dependencies from Composer...."
cd app/
php ../composer.phar install --no-dev
cd ../db
RESULT=$?
if [ $RESULT -eq 0 ]; then
    echo "Dependencies installed successfully"
else
    echo "Error installing project dependencies, aborting..."
    exit 1
fi

# #==============CREATE DATABASE==============
echo "Connecting to MySQL to create CARS database..."
echo -n "MySQL Username: "
read user
mysql -e "source cars.sql" -u $user -p
RESULT=$?
if [ $RESULT -eq 0 ]; then
    echo "Database creation successful!"
else
    echo "Error creating database, aborting..."
    exit 1
fi

#==============CREATE SYSTEM ADMINSTRATOR==============
echo "Creating initial system administrator user in database..."
echo -n "Enter system administrator user id (RIT id): "
read initUser
echo -n "Enter system administrator's first name: "
read initFirst
echo -n "Enter system administrator's last name: "
read initLast
echo -n "Enter system administrator's email: "
read initEmail
echo "Connecting to MySQL with user '$user'"
mysql -e "use cars; insert into users(rit_id,first_name,last_name,email,role_id) values('$initUser','$initFirst','$initLast','$initEmail',1);" -u $user -p
RESULT=$?
if [ $RESULT -eq 0 ]; then
    echo "User creation successful!"
else
    echo "Error creating initial user, aborting..."
    exit 1
fi

echo "CARS installed successfully!"
exit
