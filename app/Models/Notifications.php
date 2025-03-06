<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    use HasFactory;

    // Colonnes que l'on peut remplir via les requêtes
    protected $fillable = ['id', 'user_id', 'message','read_status'];

    /**
     * Un post appartient à un board.
     */
    public function notifications()
    {
        return $this->belongsTo(Notifications::class);
    }

    public function users()
    {
        return $this->belongsTo(Users::class);
    }
}
