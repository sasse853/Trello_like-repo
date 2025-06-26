<?php

namespace App\Models;

use App\Models\Boards;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Events\ListUpdated;
use Illuminate\Database\Eloquent\Model;

class Lists extends Model
{
    use HasFactory;


    // Colonnes que l'on peut remplir via les requêtes
    protected $fillable = [ 'board_id', 'name'];


    protected $dispatchesEvents = [
        'updated' => ListUpdated::class,
    ];

    /**
     * Un post appartient à un board.
     */
    public function lists()
    {
        return $this->belongsTo(Lists::class);
    }

    public function board()
    {
        return $this->belongsTo(Boards::class);
    }

    public function items()
{
    return $this->hasMany(ListItem::class, 'list_id');
}

}
