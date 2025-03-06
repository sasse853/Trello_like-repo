<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use HasFactory;

    // Colonnes que l'on peut remplir via les requêtes
    protected $fillable = ['id', 'name', 'email','password','role'];

    /**
     * Un post appartient à un board.
     */
    public function users()
    {
        return $this->belongsTo(Users::class);
    }
}
