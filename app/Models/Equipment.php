<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    protected $fillable = ['name', 'description', 'image_path', 'city', 'quantity'];

    public function rentals()
    {
        return $this->hasMany(EquipmentRental::class);
    }
}
