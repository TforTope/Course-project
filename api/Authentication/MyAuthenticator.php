<?php
/**
 * Author: TOPE_OLUSEGU
 * Date: 6/11/2026
 * File: MyAuthenticator.php
 * Description: Defines rhe Authentication class.
 */

namespace EventHubAPI\Authentication;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use EventHubAPI\Models\User;

class MyAuthenticator {
    /*
    * Use the __invoke method so the object can be used as a callable.
    * This method gets called automatically when the object is treated
    * as a callable.
    */
    public function __invoke(Request $request, RequestHandler $handler) : Response {
        //Username and password are stored in a header called "MyCollegeAPI-Authorization".
        if(!$request->hasHeader('MyCollegeAPI-Authorization')) {
            $results = ['Status' => 'MyCollegeAPI-Authorization header not found.'];
            return AuthenticationHelper::withJson($results, 401);
        }

        //Retrieve the header.
        $auth = $request->getHeader('MyCollegeAPI-Authorization');

        $apikey = $auth[0];
        list($username, $password) = explode(':', $apikey);

        //Retrieve the header and then the username and password
        $auth = $request->getHeader('MyCollegeAPI-Authorization');
        list($username, $password) = explode(':', $auth[0]);

        //Validate the username and password
        if(!User::authenticateUser($username, $password)) {
            $results = ['Status' => 'Authentication failed.'];
            return AuthenticationHelper::withJson($results, 403);
        }
        //A user has been authenticated
        return $handler->handle($request);

    }
}