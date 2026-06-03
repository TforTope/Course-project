<?php
/**
 * Author: TOPE_OLUSEGU
 * Date: 6/2/2026
 * File: Venue.php
 * Description: Defines the venue model class
 */

namespace EventHubAPI\Models;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model {
    // The table associated with this model
    protected $table = 'venues';

    // The primary key of the table
    protected $primaryKey = 'venue_id';

    // The PK is numeric and auto-incrementing
    public $incrementing = true;

    // The primary key type
    protected $keyType = 'int';

    // If created_at and updated_at columns are not used
    public $timestamps = false;

    // Define one-to-many relationship between Venue and Event
    public function events() {
        return $this->hasMany(Event::class, 'venue_id');
    }

    // Retrieve all venues
    public static function getVenues() {
        $venues = self::with('events')->get();
        return $venues;
    }

    // Retrieve a specific venue
    public static function getVenueById(int $id) {
        $venue = self::findOrFail($id);
        $venue->load('events');
        return $venue;
    }

    // View all events hosted at a venue
    public static function getEventsByVenue(int $id) {
        $events = self::findOrFail($id)->events;
        return $events;
    }
}