<?php
/**
 * Author: TOPE_OLUSEGU
 * Date: 6/2/2026
 * File: Event.php
 * Description: Defines the event model class
 */

namespace EventHubAPI\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model {
    // The table associated with this model
    protected $table = 'events';

    // The primary key of the table
    protected $primaryKey = 'event_id';

    // The PK is numeric and auto-incrementing
    public $incrementing = true;

    // The primary key type
    protected $keyType = 'int';

    // If created_at and updated_at columns are not used
    public $timestamps = false;

    // Define many-to-one relationship between Event and Organizer
    public function organizer() {
        return $this->belongsTo(Organizer::class, 'organizer_id');
    }

    // Define many-to-one relationship between Event and Venue
    public function venue() {
        return $this->belongsTo(Venue::class, 'venue_id');
    }

    // Define one-to-many relationship between Event and Registration
    public function registrations() {
        return $this->hasMany(Registration::class, 'event_id');
    }

    // Define many-to-many relationship between Event and Attendee through registrations
    public function attendees() {
        return $this->belongsToMany(
            Attendee::class,
            'registrations',
            'event_id',
            'attendee_id'
        );
    }

    // Retrieve all events
    public static function getEvents() {
        $events = self::with('organizer', 'venue', 'registrations')->get();
        return $events;
    }

    // Retrieve a specific event
    public static function getEventById(int $id) {
        $event = self::findOrFail($id);
        $event->load('organizer', 'venue', 'registrations');
        return $event;
    }

    // View organizer of an event
    public static function getOrganizerByEvent(int $id) {
        $organizer = self::findOrFail($id)->organizer;
        return $organizer;
    }

    // View venue of an event
    public static function getVenueByEvent(int $id) {
        $venue = self::findOrFail($id)->venue;
        return $venue;
    }

    // View all registrations for an event
    public static function getRegistrationsByEvent(int $id) {
        $registrations = self::findOrFail($id)->registrations;
        return $registrations;
    }

    // View all attendees for an event
    public static function getAttendeesByEvent(int $id) {
        $attendees = self::findOrFail($id)->attendees;
        return $attendees;
    }
}