<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    use HasFactory;

    // Colonnes que l'on peut remplir via les requêtes
    protected $fillable = [ 'type', 'notifiable_id', 'notifiable_type', 'data', 'read_at'];

    /**
     * Un post appartient à un board.
     */
    public function notifications()
    {
        return $this->belongsTo(Notifications::class);
    }

    public function notifiable()
    {
        return $this->morphTo();
    }

    public function users()
    {
        return $this->belongsTo(Users::class,'member_id');
    }
}
