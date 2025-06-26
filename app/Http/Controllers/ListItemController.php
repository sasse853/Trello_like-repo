<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListItem;
use App\Models\Lists;

class ListItemController extends Controller
{
    public function store(Request $request, $list_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        ListItem::create([
            'name' => $request->name,
            'list_id' => $list_id,
        ]);

        return redirect()->route('listes.index', Lists::findOrFail($list_id)->board_id);
    } 
    public function toggleCompletion($id)
    {
        $item = ListItem::findOrFail($id); // Trouve la tâche
        $item->is_completed = !$item->is_completed; // Inverse l'état
        $item->save(); // Sauvegarde

        return response()->json(['success' => true, 'is_completed' => $item->is_completed]);
    }
    public function destroy($id)
    {
        $item = ListItem::findOrFail($id);
        $item->delete();

        return back()->with('success', 'Élément supprimé avec succès.');
    }
}
