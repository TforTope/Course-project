<?php
/**
 * Author: TOPE_OLUSEGU
 * Date: 6/12/2026
 * File: JWTAuthenticator.php
 * Description: Defines the JWTAuthenticator class.
 */

namespace EventHubAPI\Authentication;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use EventHubAPI\Models\User;

class JWTAuthenticator {
    public function __invoke(Request $request, RequestHandler $handler) : Response {
        //If the header named "Authorization" does not exist, returns an error
        if(!$request->hasHeader('Authorization')) {
            $results = ['Status' => 'Authorization header not available'];
            return AuthenticationHelper::withJson($results, 401);
        }

        //Retrieve the header and the token
        $auth = $request->getHeader('Authorization');
        list(, $token) = explode(" ", $auth[0], 2);

        //Validate the token
        if(!User::validateJWT($token)) {
            $results = ['Status' => 'Authentication failed.'];
            return AuthenticationHelper::withJson($results, 403);
        }

        //Authentication succeeded
        return $handler->handle($request);
    }
}
