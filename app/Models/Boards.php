<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boards extends Model
{
    use HasFactory;

    // Colonnes que l'on peut remplir via les requêtes
    protected $fillable = [  
        'name',
        'description',
        'workspace_id',
        'member_id'];

    /**
     * Un post appartient à un board.
     */
   

    public function users()
    {
        return $this->belongsTo(Users::class,'member_id');
    }

    public function workspaces()
    {
        return $this->belongsTo(Workspace::class,'workspace_id');
    }

    public function getWorkspaceIdAttribute($value)
    {
        return Workspace::find($value);
    }

    public function members()
    {
        return $this->belongsToMany(Users::class, 'board_members', 'board_id', 'member_id');
    }

    public function lists()
    {
        return $this->hasMany(Lists::class, 'board_id');
    }

    public static function boot()
{
    parent::boot();

    static::deleting(function ($board) {
        $board->lists()->delete(); // Supprime d'abord les listes associées
    });
}


}
