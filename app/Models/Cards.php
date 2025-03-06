<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cards extends Model
{
    use HasFactory;

    // Colonnes que l'on peut remplir via les requêtes
    protected $fillable = ['id', 'list_id','title','description','due_date'];

    /**
     * Un post appartient à un board.
     */
    public function cards()
    {
        return $this->belongsTo(Cards::class);
    }

    public function lists()
    {
        return $this->belongsTo(Lists::class);
    }
}
