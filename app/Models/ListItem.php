<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ListItem extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'list_id','is_completed'];

    public function list()
    {
        return $this->belongsTo(Lists::class);
    }
    public function toggleCompletion($id)
{
    $item = ListItem::findOrFail($id);
    $item->is_completed = !$item->is_completed; // Inverse l'état
    $item->save();

    return response()->json(['success' => true, 'is_completed' => $item->is_completed]);
}
}
