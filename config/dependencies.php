<?php
/**
 * Author:
 * Date: 6/2/2026
 * File: dependencies.php
 * Description: Define dependencies - instances of controller class. They are
 * passed to routing functions as the callback routines.
 */

use DI\Container;

use EventHubAPI\Controllers\AttendeeController;
use EventHubAPI\Controllers\EventController;
use EventHubAPI\Controllers\OrganizerController;
use EventHubAPI\Controllers\RegistrationController;
use EventHubAPI\Controllers\VenueController;
use EventHubAPI\Controllers\UserController;

return function(Container $container) {
    // Set a dependency called "Attendee"
    $container->set('Attendee', function() {
        return new AttendeeController();
    });

    // Set a dependency called "Event"
    $container->set('Event', function() {
        return new EventController();
    });

    // Set a dependency called "Organizer"
    $container->set('Organizer', function() {
        return new OrganizerController();
    });

    // Set a dependency called "Registration"
    $container->set('Registration', function() {
        return new RegistrationController();
    });

    // Set a dependency called "Venue"
    $container->set('Venue', function() {
        return new VenueController();
    });

    // Set a dependency called "User"
    $container->set('User', function() {
        return new UserController();
    });
};


