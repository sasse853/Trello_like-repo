<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lists extends Model
{
    use HasFactory;

    // Colonnes que l'on peut remplir via les requêtes
    protected $fillable = ['id', 'board_id', 'name'];

    /**
     * Un post appartient à un board.
     */
    public function lists()
    {
        return $this->belongsTo(Lists::class);
    }

    public function board()
    {
        return $this->belongsTo(Board::class);
    }

}
