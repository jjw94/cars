<?php
require_once("autoload-cars.php");
//Routes for the API group - matches all URLs starting with /api
$app->group('/api', function() use ($app) {

    $app->group('/users',function() use ($app){
        $app->get('/lastName/{in}',function($request,$response,$args){
            $biz = new \Cars\Business\Common($this);
            return $response->withJson($biz->getUsersLastNameLike($args["in"]));
        });
        $app->get('/department/{in}',function($request,$response,$args){
            $biz = new \Cars\Business\Common($this);
            return $response->withJson($biz->getUsersInDept($args["in"]));
        });
    });

    $app->group('/rooms',function() use ($app){
        $app->get('/getAll',function($request,$response,$args){
            $biz = new \Cars\Business\Common($this);
            return $response->withJson($biz->getAllRoomsByBuilding());
        });
        $app->get('/getOpen/{days}/{start}/{end}/{term}',function($request,$response,$args){
            $biz = new \Cars\Business\Common($this);
            return $response->withJson($biz->getOpenRoomsByBuilding($args["days"], $args["start"], $args["end"], $args["term"]));
        });
    });

    $app->group('/classes',function() use ($app){
        $app->get('/byCourseId/{id}',function($request,$response,$args){
            $biz = new \Cars\Business\Selection($this);
            return $response->withJson($biz->getCourseById($args['id'],$_SESSION['cars-user']));
        });

        $app->get('/sectionsFor/{program}/{code}/{term}/{archived}',function($request,$response,$args){
            $biz = new \Cars\Business\Selection($this);
            return $response->withJson($biz->getSectionsFor($args['program'],$args['code'],$args['term'],$args['archived'],$_SESSION['cars-user']));
        });
        $app->get('/sectionsForFormatted/{program}/{courseId}/{term}/{archived}',function($request,$response,$args){
            $biz = new \Cars\Business\Selection($this);
            return $response->withJson($biz->getSectionsForFormatted($args['program'],$args['courseId'],$args['term'],$args['archived'],$_SESSION['cars-user']));
        });
        $app->get('/section/room/{id}',function($request,$response,$args){
            $biz = new \Cars\Business\Common($this);
            return $response->withJson($biz->getRoomByID($args["id"]));
        });
        $app->get('/byLevel/{program}/{level}/{archived}',function($request,$response,$args){
            $biz = new \Cars\Business\Selection($this);
            return $response->withJson($biz->getCoursesByLevel($args['program'],$args['level'],$args['archived'],$_SESSION['cars-user']));
        });
        $app->get('/byProgramCode/{program}/{archived}',function($request,$response,$args){
            $biz = new \Cars\Business\Selection($this);
            return $response->withJson($biz->getCoursesByProgram($args['program'],$args['archived'],$_SESSION['cars-user']));
        });
        $app->get('/byCourseCode/{program}/{number}/{section}/{archived}',function($request,$response,$args){
            $biz = new \Cars\Business\Selection($this);
            return $response->withJson($biz->getCourseByCourseCode($args['program'],$args['number'],$args['section'],$args['archived'],$_SESSION['cars-user']));
        });
        $app->get('/byProgram/byLevel/{user}',function($request,$response,$args){
            $biz = new \Cars\Business\Selection($this);
            return $response->withJson($biz->getCoursesByProgramByLevel($args['user']));
        });
        $app->get('/{department}/{archived}',function($request,$response,$args){
            $biz = new \Cars\Business\Selection($this);
            return $response->withJson($biz->getCoursesByDepartment($args['department'],  $args['archived'],$_SESSION['cars-user']));
        });
    });
    $app->group('/bucket', function() use ($app){
        $app->get('/getBucket/{term}',function($request,$response,$args){
            $biz = new \Cars\Business\Selection($this);
            return $response->withJson($biz->getBucket($_SESSION['cars-user'], $args['term']));
        });
    //remove from bucket
        $app->get("/removeFromBucket/{section_id}/{bucket}/{requestorder}",function($request,$response,$args){
            $biz = new \Cars\Business\Selection($this);
            return $response->withJson($biz->removeFromBucket($args['section_id'],$args['bucket'],$args['requestorder'],$_SESSION['cars-user']));
        });
        $app->get('/addToBucket/{section_id}/{bucket}/{requestOrder}',function($request,$response,$args){
            $biz = new \Cars\Business\Selection($this);
            return $response->withJson($biz->addToBucket($args['section_id'],$args['bucket'],$args['requestOrder'],$_SESSION['cars-user']));
        });
    });
   $app->group('/terms', function() use($app){
        $app->get('/{status}',function($request,$response,$args){
            $biz = new \Cars\Business\Common($this);
            return $response->withJson($biz->getTermByStatus($args['status']));
        });
        $app->get('/term/{num}',function($request,$response,$args){
            $biz = new \Cars\Business\Common($this);
            return $response->withJson($biz->getTerm($args['num']));
        });
       $app->get('/',function($request,$response,$args){
            $biz = new \Cars\Business\Common($this);
            return $response->withJson($biz->getTerms());
        });
   });

   $app->group('/assignment', function() use($app) {
       $app->get('/courses/{term}',function($request,$response,$args){
           $args = \Cars\Constants::sanitizeArr($args);
           $biz = new \Cars\Business\Assignment($this);
           return $response->withJson($biz->getAssignmentStatus($args['term'],$_SESSION['cars-user']));
       });
       $app->get('/assigned/{user}',function($request,$response,$args){
           $args = \Cars\Constants::sanitizeArr($args);
           $biz = new \Cars\Business\Assignment($this);
           return $response->withJson($biz->getAssignmentsForUser($args['user']));
       });
       $app->get('/requests/forUser/{userId}',function($request,$response,$args){
           $args = \Cars\Constants::sanitizeArr($args);
           $biz = new \Cars\Business\Assignment($this);
           return $response->withJson($biz->getRequestsForUser($args['userId']));
       });
       $app->get('/requests/{sectionId}',function($request,$response,$args){
           $args = \Cars\Constants::sanitizeArr($args);
           $biz = new \Cars\Business\Assignment($this);
           return $response->withJson($biz->getRequests($args['sectionId']));
       });
       $app->get('/requests/{program}/{courseId}/{term}',function($request,$response,$args){
           $args = \Cars\Constants::sanitizeArr($args);
           $biz = new \Cars\Business\Assignment($this);
           return $response->withJson($biz->getRequestsByCourse($args['program'],$args['courseId'],$args['term'],$_SESSION['cars-user']));
       });
       $app->get('/checkConflict/{user}/{sectionId}',function($request,$response,$args){
           $args = \Cars\Constants::sanitizeArr($args);
           $biz = new \Cars\Business\Assignment($this);
           return $response->withJson($biz->checkConflicts($args['user'],$args['sectionId']));
       });
       $app->get('/add/{user}/{sectionId}',function($request,$response,$args){
           $args = \Cars\Constants::sanitizeArr($args);
           $biz = new \Cars\Business\Assignment($this);
           return $response->withJson($biz->assignSection($args['user'], $args['sectionId']));
       });
       $app->get('/remove/{assignmentId}',function($request,$response,$args){
           $args = \Cars\Constants::sanitizeArr($args);
           $biz = new \Cars\Business\Assignment($this);
           return $response->withJson($biz->unassignSection($args['assignmentId']));
       });
   })->add(new RoleCheck($app,["Department Administrator"]));

    $app->get('/UserInfo/',function($request,$response,$args){
        $biz = new \Cars\Business\Common($this);
        return $response->withJson($biz->getUserData($_SESSION['cars-user']));
    });


    $app->group('/department', function() use($app) {
        $app->get('/get/all',function($request,$response,$args){
            $biz = new \Cars\Business\Common($this);
            return $response->withJson($biz->getAllDepartments());
        });
        $app->get('/{user}',function($request,$response,$args){
            $biz = new \Cars\Business\Common($this);
            return $response->withJson($biz->getUserDepartments($args['user']));
        });
        $app->get('/get/{abbrev}',function($request,$response,$args){
            $biz = new \Cars\Business\Common($this);
            return $response->withJson($biz->getDepartment($args["abbrev"]));
        });
    });
    //Add trailing slash to doc request
    $app->get('', function ($request, $response, $args) {
        return $response->withStatus(302)->withHeader('Location', '/api/');
    });

    $app->get('/', function ($request, $response, $args) {
        //Parse the markdown to html
        $md = file_get_contents("../../doc/api/api.md");

        //Render the parsedown as file
        $parsedown = new Parsedown();
        $body = $parsedown->text($md);
        return $this->renderer->render($response, 'docs.php', array(
            'title' => 'API Documentation | CARS',
            'body' => $body
        ));
    });


/** ADMIN ******************* NEEDS HIGHER AUTH *****************/
    $app->group('/admin',function() use ($app){
        $app->get('/add/course/{dept}/{name}/{number}/{desc}/{credits}/{classhrs}/{labhrs}',function($request,$response,$args){
            $biz = new \Cars\Business\Admin($this);
            return $response->withJson($biz->addCourse($args['dept'],$args['name'],$args['number'],$args['desc'],$args['credits'],$args['classhrs'],$args['labhrs']));
        });

        $app->get('/add/user/{fname}/{lname}/{email}/{ritid}',function($request,$response,$args){
            $biz = new \Cars\Business\Admin($this);
            return $response->withJson($biz->createUser($args["fname"],$args["lname"],$args["email"],$args["ritid"]));
        });

        $app->get('/add/user/inDept/{fname}/{lname}/{email}/{ritid}/{dept}',function($request,$response,$args){
            $biz = new \Cars\Business\Admin($this);
            return $response->withJson($biz->createUserInDept($args["fname"],$args["lname"],$args["email"],$args["ritid"],$args["dept"]));
        });

        $app->get('/remove/user/dept/{ritid}/{dept}',function($request,$response,$args){
            $biz = new \Cars\Business\Admin($this);
            return $response->withJson($biz->removeUserFromDept($args["ritid"],$args["dept"]));
        });

        $app->get('/add/course/section/{courseID}/{sectionNum}/{term}/{online}/{days}/{start}/{end}/{room}',function($request,$response,$args){
            $biz = new \Cars\Business\Admin($this);
            return $response->withJson($biz->addSection($args['courseID'],$args['sectionNum'],$args['term'],$args['online'],$args['days'],$args['start'],$args['end'],$args['room']));
        });
        $app->get('/addUserDepartment/{user}/{department}',function($request,$response,$args){
            $biz = new \Cars\Business\Admin($this);
            return $response->withJson($biz->addUserToDepartment($args["user"],$args["department"]));
        });
        $app->get('/setRole/{user}/{role}',function($request,$response,$args){
            $biz = new \Cars\Business\Admin($this);
            return $response->withJson($biz->setRole($args["user"],$args["role"]));
        });
        $app->get('/UserInfo/{user}',function($request,$response,$args){
            $biz = new \Cars\Business\Common($this);
            return $response->withJson($biz->getUserDataWithDepartments($args["user"]));
        });
    })->add(new RoleCheck($app,["System Administrator", "Department Administrator", "Department Manager"]));

    $app->group('/admin',function() use ($app){

         $app->get('/add/department/{name}/{abbrev}',function($request,$response,$args){
            $biz = new \Cars\Business\Admin($this);
            return $response->withJson($biz->newDepartment($args["name"],$args["abbrev"]));
        });
        $app->get('/add/program/{name}/{code}/{degree}/{deptID}',function($request,$response,$args){
            $biz = new \Cars\Business\Admin($this);
            return $response->withJson($biz->newProgram($args["name"],$args["code"], $args["degree"],$args["deptID"]));
        });
        $app->get('/term/setStatus/{num}/{statusID}',function($request,$response,$args){
            $biz = new \Cars\Business\Admin($this);
            return $response->withJson($biz->setTermStatus($args["num"],$args["statusID"]));
        });
        $app->get('/term/new/{num}/{desc}/{statusID}',function($request,$response,$args){
            $biz = new \Cars\Business\Admin($this);
            return $response->withJson($biz->createTerm($args["num"],$args["desc"],$args["statusID"]));
        });
    })->add(new RoleCheck($app,["System Administrator", "Department Administrator", "Department Manager"]));

    $app->group('/reports',function() use ($app){
      $app->get('/getRoomSchedule/{bld}/{room}/{term}',function($request,$response,$args){
          $biz = new \Cars\Business\Reports($this);
          return $response->withJson($biz->getRoomSchedule($args["bld"],$args["room"],$args["term"]));
      });
      $app->get('/getUserSchedule/{user}/{term}',function($request,$response,$args){
          $biz = new \Cars\Business\Reports($this);
          return $response->withJson($biz->getUserSchedule($args["user"],$args["term"]));
      });
    });
})->add($auth);
