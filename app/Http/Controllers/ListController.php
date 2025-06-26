<?php

namespace App\Http\Controllers;
use App\Models\Lists;
use App\Models\Boards;
use Illuminate\Http\Request;
use App\Events\ListUpdated;
use App\Models\Users;
use App\Notifications\ListUpdatedNotification;

class ListController extends Controller
{
    public function index_lists($board_id)
    {
        $board = Boards::findOrFail($board_id);
        $lists = Lists::where('board_id', $board_id)->get();
        return view('listes.index', compact('board', 'lists'));
    }

    public function store_lists(Request $request, $board_id)
    {
        
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $list = Lists::create([
            'name' => $request->name,
            'board_id' => $board_id,
        ]);

        $user = Users::where('name', $list->name)->first();

        if ($user && $user->email) {
            // Déclencher l'événement pour signaler la mise à jour
            event(new ListUpdated($list));

            // Envoyer la notification par mail
            $user->notify(new ListUpdatedNotification($list));
        }

        return redirect()->route('listes.index', $board_id);

       

    }
}
