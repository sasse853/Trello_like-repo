<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boards extends Model
{
    use HasFactory;

    // Colonnes que l'on peut remplir via les requêtes
    protected $fillable = ['id', 'workspace_id', 'name'];

    /**
     * Un post appartient à un board.
     */
    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    public function workspaces()
    {
        return $this->belongsTo(Workspaces::class);
    }
}
