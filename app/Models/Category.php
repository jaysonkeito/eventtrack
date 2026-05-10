<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Category extends Model {
    protected $fillable = ['name', 'description', 'color_hex'];
    public function events() { return $this->hasMany(Event::class); }
}
