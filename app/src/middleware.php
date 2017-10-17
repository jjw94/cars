<?php
// Application middleware
require_once("autoload-cars.php");

$auth = function($request, $response, $next) use ($app) {
    //Check for authentication type
    $authType = $this['settings']['authType'];

    //If userid isn't set in session, try to get it
    if(!isset($_SESSION['cars-user'])) {
        //For local auth, redirect to login if user is not set in session
        if($authType == 'dev') {
            $uri = $request->getUri()->withPath('/login');
            return $response = $response->withRedirect($uri, 403);
        //For shib login, get user out of the server array and store in the session
        } else if($authType == 'shib') {
            if(isset($_SERVER['uid'])) {
                $_SESSION['cars-user'] = $_SERVER['uid'];
            } else {

            }
        //Anything other auth type should throw an exception
        } else {
            throw new \Cars\CarsException("Undefined authentication type '$authType' in app/src/settings.php", $this);
        }
    }

    //If the user's role isn't set, try to get it
    if(!isset($_SESSION['cars-role'])) {
        if(isset($_SESSION['cars-user'])) {
            //Get the user's role from our database
            $biz = new \Cars\Business\Selection($this);
            $role = $biz->getUserRole($_SESSION['cars-user']);
            //Ensure a roll was returned
            if(!is_null($role)) {
                $_SESSION['cars-role'] = $role;
            }
        }
    }

    //If by now the user and role are not set, this user is not authenticated
    if(isset($_SESSION['cars-user']) and isset($_SESSION['cars-role'])) {
        if($_SESSION['cars-role'] != "Adjunct") {
            $_SESSION['auth'] = true;
        } else {
            //Stop the request
            return $this->renderer->render($response->withStatus(401), 'not_authorized.php');
        }
    } else {
        //Stop the request
        return $this->renderer->render($response->withStatus(401), 'not_authorized.php');
    }
    $response = $next($request, $response);
    return $response;

};
