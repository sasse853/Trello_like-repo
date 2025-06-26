<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workspace extends Model
{
    use HasFactory;

    // Colonnes que l'on peut remplir via les requêtes
    protected $fillable = [ 'member_id', 'name'];

    /**
     * Un post appartient à un board.
     */

    public function user()
    {
        return $this->belongsTo(Users::class,'member_id');
    }

    public function boards()
    {
        return $this->hasMany(Boards::class,'workspace_id');
    }




}
