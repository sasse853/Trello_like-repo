<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workspaces extends Model
{
    use HasFactory;

    // Colonnes que l'on peut remplir via les requêtes
    protected $fillable = ['id', 'user_id', 'name'];

    /**
     * Un post appartient à un board.
     */
    public function workspaces()
    {
        return $this->belongsTo(Workspaces::class);
    }

    public function users()
    {
        return $this->belongsTo(Users::class);
    }
}
