<?php
/**
 * Author:
 * Date: 6/4/2026
 * File: Validator.php
 * Description: Defines the validator class for EventHub API.
 */

namespace EventHubAPI\Validation;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class Validator {
    private static array $errors = [];

    // A generic validation method. It returns true on success or false on failed validation.
    public static function validate($request, array $rules) : bool {
        // Reset errors before every validation
        self::$errors = [];

        foreach ($rules as $field => $rule) {
            // Retrieve parameters from URL or the request body
            $body = $request->getParsedBody();

            $param = $request->getAttribute($field);

            if ($param === null && is_array($body) && array_key_exists($field, $body)) {
                $param = $body[$field];
            }

            try {
                $rule->setName($field)->assert($param);
            } catch (NestedValidationException $ex) {
                self::$errors[$field] = $ex->getFullMessage();
            }
        }

        // Return true or false; false means a failed validation.
        return empty(self::$errors);
    }

    // Validate attendee data.
    public static function validateAttendee($request) : bool {
        // Define all the validation rules
        $rules = [
                'name' => v::notEmpty()->alpha(' ')->length(2, 100),
                'email' => v::notEmpty()->email()->length(5, 100),
                'phone' => v::notEmpty()->digit()->length(10, 20),
                'date_of_birth' => v::notEmpty()->date('Y-m-d')
        ];

        return self::validate($request, $rules);
    }

    // Return the errors in an array
    public static function getErrors() : array {
        return self::$errors;
    }
}