<?php
// Routes

//Route to Home
$app->group('', function() use ($app) {
    $app->get('/', function ($request, $response, $args) {
        // Render index view
        $biz = new \Cars\Business\Common($this);
        $terms = $biz->getTerms();
        return $this->renderer->render($response, 'index.php', array("terms" => $terms));
    });

    //Route to Reports
    $app->get('/reports', function ($request, $response, $args) {
         // Render index view
        $biz = new \Cars\Business\Common($this);
        return $this->renderer->render($response, 'reports.php', array("terms" => $biz->getTerms(), "rooms" => $biz->getAllRoomsByBuilding(), "departments"=> $biz->getAllDepartments()));
    });
    $app->group('/course',function () use ($app){
        $app->get('/assignment', function ($request, $response, $args) {
        // Render course Assignment view
        $biz = new \Cars\Business\Assignment($this);
            //Get a list of users that can be assigned
            $depts = $biz->getUserDepartments($_SESSION['cars-user']);
            $users = array();
            foreach($depts as $dept) {
                $usrList = $biz->getUsersInDept($dept['department_id']);
                foreach($usrList as $usr) {
                    array_push($users, $usr);
                }
            }

            $args['assignmentTerms'] = json_encode($biz->getTermByStatus("Open for Assignment"));
            $args['programs'] = json_encode($biz->getAssignmentStatus(0, $_SESSION['cars-user']));
            $args['users'] = json_encode($users);
            return $this->renderer->render($response, 'course_assignment.php', $args);
        })->add(new RoleCheck($app,["Department Administrator"]));

        //Route to Course Management
        $app->get('/management', function ($request, $response, $args) {
            // Render Course management view
            $biz = new \Cars\Business\Common($this);
            $terms = array();
            {
                $rawTerms = array($biz->getTermByStatus("Under Development"),
                    $biz->getTermByStatus("Open for Requests"),
                    $biz->getTermByStatus("Open for Assignment"));

                foreach ($rawTerms as $termType) {
                    foreach ($termType as $aTerm) {
                        $terms[] = $aTerm;
                    }
                }

            }
            $data = array();
            //$data['developingTerms'] = json_encode($biz->getTermByStatus("Under Development"));
            $data['developingTerms'] = $terms;
            $data['allTerms'] = json_encode($biz->getTerms());
            $data['programs'] = json_encode($biz->getCoursesByProgramByLevel($_SESSION['cars-user']));
            $data['rooms'] = $biz->getAllRoomsByBuilding();
            return $this->renderer->render($response, 'course_management.php', $data);
        })->add(new RoleCheck($app,["Department Administrator", "Department Manager"]));

        //Route to Course Selection
        $app->get('/selection', function ($request, $response, $args) {
            // Render course selection view
            $biz = new \Cars\Business\Common($this);
            $args['requestTerms'] = json_encode($biz->getTermByStatus("Open for Requests"));
            $args['programs'] = json_encode($biz->getCoursesByProgramByLevel($_SESSION['cars-user']));
            $args['numCourses'] = $this['settings']['numCourses'];
            return $this->renderer->render($response, 'course_selection.php', $args);
        });
    });
    //Route to Course Assignment
    $app->group('/department',function () use ($app){
         //Route to Admin add user to department
        $app->get('/users', function ($request, $response, $args) {
            // Render admin add user view
            $biz = new \Cars\Business\Common($this);
            $data = array('users' => $biz->getUsersFormatted(), "departments"=> $biz->getUserDepartments($_SESSION['cars-user']),"roles" => $biz->getLesserRoles("Department Manager") );
            return $this->renderer->render($response, 'department_users.php', $data);
        });
    })->add(new RoleCheck($app,["Department Administrator", "Department Manager"]));

    $app->group('/admin',function () use ($app){
         //Route to Admin add user to department
        $app->get('/users', function ($request, $response, $args) {
            // Render admin add user view
            $biz = new \Cars\Business\Common($this);
            $data = array('users' => $biz->getUsersFormatted(), "departments"=> $biz->getAllDepartments(),"roles" => $biz->getAllRoles() );
            return $this->renderer->render($response, 'admin_users.php', $data);
        });

        //Route to Application Admin, Manage Department
        $app->get('/application', function ($request, $response, $args) {
            // Render Admin manage Department view
            $biz =new \Cars\Business\Common($this);
            $data = array("departments"=>$biz->getAllDepartments(), "terms"=>$biz->getAllTerms(), "status"=>$biz->getTermStatuses());
            return $this->renderer->render($response, 'admin_application.php', $data);
        });

        //Route to Admin
        $app->get('/', function ($request, $response, $args) {
            // Render Admin manage Department view
            return $this->renderer->render($response, 'admin.php', $args);
        });

        //Add trailing slash to doc request
        $app->get('/docs', function ($request, $response, $args) {
            return $response->withStatus(302)->withHeader('Location', '/admin/docs/');
        });

        //Route to view admin docs
        $app->get('/docs/[{page}]', function ($request, $response, $args) {
            //Get page out of the args
            $page = '';
            if(isset($args['page'])) {
                $page = Cars\Constants::sanitizeString($args['page']);
            } else {
                $page = "Home";
            }

            //Parse the markdown to html
            $md = file_get_contents("../../doc/system/$page.md");
            $sb = file_get_contents("../../doc/system/_Sidebar.md");

            //Render the parsedown as file
            $parsedown = new Parsedown();
            $body = $parsedown->text($md);
            $sidebar = $parsedown->text($sb);
            return $this->renderer->render($response, 'docs.php', array(
                'title' => $page,
                'body' => $body,
                'sidebar' => $sidebar
            ));
        });

        //Route for documentation images
        $app->get('/docs/images/{fileName}', function ($request, $response, $args) {
            $file = Cars\Constants::sanitizeString($args['fileName']);
            $image = file_get_contents("../../doc/system/images/$file");
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $response = $response->withHeader('Content-Type', 'content-type: ' . $finfo->buffer($image));
            $response->getBody()->write($image);
            return $response;
        });
    })->add(new RoleCheck($app,["System Administrator"]));

    //Add trailing slash to help request
    $app->get('/help', function ($request, $response, $args) {
        return $response->withStatus(302)->withHeader('Location', '/help/');
    });

    //Route to view admin docs
    $app->get('/help/[{page}]', function ($request, $response, $args) {
        //Get page out of the args
        $page = '';
        if(isset($args['page'])) {
            $page = Cars\Constants::sanitizeString($args['page']);
        } else {
            $page = "User-Help";
        }

        //Parse the markdown to html
        $md = file_get_contents("../../doc/user/$page.md");
        $sb = file_get_contents("../../doc/user/_Sidebar.md");

        //Render the parsedown as file
        $parsedown = new Parsedown();
        $body = $parsedown->text($md);
        $sidebar = $parsedown->text($sb);
        return $this->renderer->render($response, 'docs.php', array(
            'title' => $page,
            'body' => $body,
            'sidebar' => $sidebar
        ));
    });

    //Route for documentation images
    $app->get('/help/images/{fileName}', function ($request, $response, $args) {
        $file = Cars\Constants::sanitizeString($args['fileName']);
        $image = file_get_contents("../../doc/user/images/$file");
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $response = $response->withHeader('Content-Type', 'content-type: ' . $finfo->buffer($image));
        $response->getBody()->write($image);
        return $response;
    });

})->add($auth);

//Route to temporary login
$app->get('/login', function ($request, $response, $args) {
    if($this['settings']['authType'] == 'dev') {
        // Render index view
        return $this->renderer->render($response, 'login.php', $args);
    } else {
        return $response->withStatus(302)->withHeader('Location', '/');
    }
});
//Route to temporary logout
$app->get('/logout', function ($request, $response, $args) {
    session_unset();
    session_destroy();
    if($this['settings']['authType'] == 'dev') {
        // Render index view
        return $this->renderer->render($response, 'logout.php', $args);
    } else {
        return $response->withStatus(302)->withHeader('Location', '/Shibboleth.sso/Logout?return=https://shibboleth.main.ad.rit.edu/logout.html?logout=1');
    }
});

$app->get('/MattLake', function ($request,$response,$args){

       return $this->renderer->render($response->withStatus(301), 'courses.php', $args);
});
