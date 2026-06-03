<?php
/**
 * Author: TOPE_OLUSEGU
 * Date: 6/2/2026
 * File: Registration.php
 * Description: Defines the registration model class
 */

namespace EventHubAPI\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model {
    // The table associated with this model
    protected $table = 'registrations';

    // The primary key of the table
    protected $primaryKey = 'registration_id';

    // The PK is numeric and auto-incrementing
    public $incrementing = true;

    // The primary key type
    protected $keyType = 'int';

    // If created_at and updated_at columns are not used
    public $timestamps = false;

    // Define many-to-one relationship between Registration and Event
    public function event() {
        return $this->belongsTo(Event::class, 'event_id');
    }

    // Define many-to-one relationship between Registration and Attendee
    public function attendee() {
        return $this->belongsTo(Attendee::class, 'attendee_id');
    }

    // Retrieve all registrations
    public static function getRegistrations() {
        $registrations = self::with('event', 'attendee')->get();
        return $registrations;
    }

    // Retrieve a specific registration
    public static function getRegistrationById(int $id) {
        $registration = self::findOrFail($id);
        $registration->load('event', 'attendee');
        return $registration;
    }

    // View event for a registration
    public static function getEventByRegistration(int $id) {
        $event = self::findOrFail($id)->event;
        return $event;
    }

    // View attendee for a registration
    public static function getAttendeeByRegistration(int $id) {
        $attendee = self::findOrFail($id)->attendee;
        return $attendee;
    }
}