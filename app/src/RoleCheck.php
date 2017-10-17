<?php

class RoleCheck
{
    private $roleList;
    private $app;

    public function __construct($app, $roleList)
    {
        $this->roleList = $roleList;
        $this->app = $app;
    }

    public function __invoke($request, $response, $next)
    {
        if(in_array($_SESSION['cars-role'], $this->roleList)) {
            $response = $next($request, $response);
            return $response;
        } else {
            throw new Exception("Unauthorized");
        }
    }
}

?>
