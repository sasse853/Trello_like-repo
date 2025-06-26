<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Workspace;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;


class Users extends Authenticatable
{
    use HasFactory,Notifiable;

    // Colonnes que l'on peut remplir via les requêtes
    protected $fillable = ['name', 'email','password'];
    protected $table='members';

    /**
     * Un post appartient à un board.
     */
    public function users()
    {
        return $this->belongsTo(Users::class);
    }


    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            // Créer un workspace automatiquement pour le nouvel utilisateur
            Workspace::create([
                'member_id' => $user->id,
                'name' => 'Workspace de ' . $user->name,
            ]);
        });
    }


    public static function rules()
    {
        return [
            'name' => 'required|unique:members,name',
            'email' => 'required|email|unique:members,email',
            'password' => 'required|min:8',
        ];
    }


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function boards()
{
    return $this->hasMany(Boards::class,'member_id');
}


public function workspace()
{
    return $this->hasOne(Workspace::class, 'member_id');
}


public function sharedBoards()
{
    return $this->belongsToMany(Boards::class, 'board_members', 'member_id', 'board_id');
}

public function notifications()
{
    return $this->hasMany(Notifications::class);
}




}
