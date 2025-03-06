<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    use HasFactory;

    // Colonnes que l'on peut remplir via les requêtes
    protected $fillable = ['id', 'user_id', 'card_id','content'];

    /**
     * Un post appartient à un board.
     */
    public function comments()
    {
        return $this->belongsTo(Comments::class);
    }

    public function users()
    {
        return $this->belongsTo(Users::class);
    }

    public function cards()
    {
        return $this->belongsTo(Cards::class);
    }
}
