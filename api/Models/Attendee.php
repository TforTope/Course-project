<?php
/**
 * Author: TOPE_OLUSEGU
 * Date: 6/2/2026
 * File: Attendee.php
 * Description: Defines the attendee model class
 */

namespace EventHubAPI\Models;

use Illuminate\Database\Eloquent\Model;

class Attendee extends Model {
    // The table associated with this model
    protected $table = 'attendees';

    // The primary key of the table
    protected $primaryKey = 'attendee_id';

    // The PK is numeric and auto-incrementing
    public $incrementing = true;

    // The primary key type
    protected $keyType = 'int';

    // If created_at and updated_at columns are not used
    public $timestamps = false;

    // Define one-to-many relationship between Attendee and Registration
    public function registrations() {
        return $this->hasMany(Registration::class, 'attendee_id');
    }

    // Define many-to-many relationship between Attendee and Event through registrations
    public function events() {
        return $this->belongsToMany(
            Event::class,
            'registrations',
            'attendee_id',
            'event_id'
        );
    }

    // Retrieve all attendees
    public static function getAttendees() {
        $attendees = self::with('registrations')->get();
        return $attendees;
    }

    // Retrieve a specific attendee
    public static function getAttendeeById(int $id) {
        $attendee = self::findOrFail($id);
        $attendee->load('registrations');
        return $attendee;
    }

    // View all registrations for an attendee
    public static function getRegistrationsByAttendee(int $id) {
        $registrations = self::findOrFail($id)->registrations;
        return $registrations;
    }

    // View all events for an attendee
    public static function getEventsByAttendee(int $id) {
        $events = self::findOrFail($id)->events;
        return $events;
    }
}