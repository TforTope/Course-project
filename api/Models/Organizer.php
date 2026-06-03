<?php
/**
 * Author: TOPE_OLUSEGU
 * Date: 6/2/2026
 * File: Organizer.php
 * Description: Defines the organizer model class
 */

namespace EventHubAPI\Models;

use Illuminate\Database\Eloquent\Model;

class Organizer extends Model {
    // The table associated with this model
    protected $table = 'organizers';

    // The primary key of the table
    protected $primaryKey = 'organizer_id';

    // The PK is numeric and auto-incrementing
    public $incrementing = true;

    // The primary key type
    protected $keyType = 'int';

    // If created_at and updated_at columns are not used
    public $timestamps = false;

    // Define one-to-many relationship between Organizer and Event
    public function events() {
        return $this->hasMany(Event::class, 'organizer_id');
    }

    // Retrieve all organizers
    public static function getOrganizers() {
        $organizers = self::with('events')->get();
        return $organizers;
    }

    // Retrieve a specific organizer
    public static function getOrganizerById(int $id) {
        $organizer = self::findOrFail($id);
        $organizer->load('events');
        return $organizer;
    }

    // View all events organized by an organizer
    public static function getEventsByOrganizer(int $id) {
        $events = self::findOrFail($id)->events;
        return $events;
    }
}