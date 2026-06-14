<?php
/**
 * Author: TOPE_OLUSEGU
 * Date: 6/12/2026
 * File: OAuth2Authenticator.php
 * Description: Defines the OAuth2Authenticator class.
 */

namespace EventHubAPI\Authentication;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use EventHubAPI\Models\User;

class OAuth2Authenticator {
    public function __invoke(Request $request, RequestHandler $handler) : Response {
        //If the header named "Authorization" does not exist, returns an error
        if(!$request->hasHeader('Authorization')) {
            $results = ['Status' => 'Authorization header not available'];
            return AuthenticationHelper::withJson($results, 401);

        }

        //Retrieve the header and the token
        $auth = $request->getHeader('Authorization');
        list(, $token) = explode(" ", $auth[0], 2);

        //Validate the token. If it is invalid, return an error
        if(!User::validateOauth2($token)) {
            $results = ['Status' => 'Authentication failed.'];
            return AuthenticationHelper::withJson($results, 403);
        }

        // Authentication succeeded
        return $handler->handle($request);
    }
}

