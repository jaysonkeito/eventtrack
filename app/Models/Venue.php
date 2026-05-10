<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model {
    protected $fillable = ['name', 'address', 'city', 'capacity'];
    public function events() { return $this->hasMany(Event::class); }
}
