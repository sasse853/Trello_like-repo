<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Boards;
use App\Models\Workspace;
use Illuminate\Support\Facades\Auth;
use App\Notifications\BoardInvitationNotification;
use App\Models\Users;
use App\Models\Lists;
use App\Services\NotificationService;

class BoardsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $boards = Boards::where('workspace_id',(int)$user->workspace->id)->where('member_id', $user->id)->get();
        return view('UserDashboard',['variable'=>$boards]);
    }

    public function store(Request $request)
{
    try {
        // Validation des données
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            
        ]);

        
        $workspace_id = Auth::user()->workspace->id;


        // Création du board
        $board = Boards::create([
            'name' => $request->name,
            'description' => $request->description,
            'workspace_id' => $request->workspace_id,
            'member_id' => Auth::id(),
        ]);

        return redirect('/Dashboard');

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Erreur lors de la création du board.',
            'message' => $e->getMessage()
        ], 500);
    }
}

    public function show($id ,Boards $board)
{
    $user = Auth::user();

    

    // Vérifier si le board appartient au workspace de l'utilisateur
    $board = Boards::where('id', $id)
                   ->where('member_id', $user->id)
                   ->first();

    if (!$board) {
        abort(403, "Accès interdit"); // Bloque l'accès si le board n'appartient pas à l'utilisateur
    }
    return view('boards.show',['board'=>$board]);
}

public function edit(Boards $board)
{
    if ($board->member_id !== Auth::id()) {
        abort(403, "Vous n'êtes pas autorisé à modifier ce tableau");
    }
    return view('boards.edit', compact('board'));
}

public function update(Request $request, Boards $board)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
    ]);

    $board->update([
        'name' => $request->name,
        'description' => $request->description,
    ]);

    return redirect('/Dashboard')->with('success', 'Tableau mis à jour avec succès.');
}

public function destroy(Boards $board)
{
    if ($board->member_id !== Auth::id()) {
        abort(403, "Vous n'êtes pas autorisé à supprimer ce tableau");
    }
    $board->delete();
    return redirect('/Dashboard')->with('success', 'Tableau supprimé avec succès.');
}


public function create(){
    $workspace = Auth::user()->workspace;
    return view('boards.create',compact('workspace'));
}



public function inviteMember(Request $request, Boards $board, NotificationService $notificationService)
{
    $request->validate([
        'email' => 'required|email|exists:members,email',
    ]);

    // Trouver le membre correspondant à l'email
    $member = Users::where('email', $request->email)->first();

    if (!$member) {
        return response()->json(['error' => 'Utilisateur introuvable.'], 404);
    }

    // Vérifier si l'utilisateur est bien le propriétaire du workspace du board
    $user_id = Auth::id();
    if (!$board->workspace_id || $board->workspace_id->member_id !== $user_id) {
        abort(403, "Vous n'avez pas la permission d'ajouter des collaborateurs à ce board.");
    }

    // Vérifier si l'utilisateur est déjà membre du board
    if ($board->members()->where('member_id', $member->id)->exists()) {
        return response()->json(['error' => 'Cet utilisateur est déjà membre du board.'], 400);
    }

    // Ajouter le membre au board
    $board->members()->attach($member->id);

    // Envoyer l'email de notification (existant)
    $member->notify(new BoardInvitationNotification(Auth::user(), $board));

    // NOUVEAU : Créer la notification pour le dashboard
    // Puisque Users utilise directement la table members, $member EST le member
    $notificationService->createBoardInvitation($member, $board);

    // Créer la liste personnelle pour le nouveau membre
    $existingList = Lists::where('board_id', $board->id)
        ->where('name', $member->name)
        ->first();

    if (!$existingList) {
        Lists::create([
            'name' => $member->name,
            'board_id' => $board->id,
        ]);
    }

    return redirect('/Dashboard')->with('success', 'Membre invité avec succès !');
}

public function removeMember(Boards $board, Users $member)
{
    $user_id=Auth::id();
    // Vérifier si l'utilisateur est bien autorisé à supprimer des membres
    if (!$board->workspace_id || $board->workspace_id->member_id !== $user_id) {
        abort(403, "Vous n'avez pas la permission de retirer des membres de ce board.");
    }

    // Vérifier si le membre fait bien partie du board
    if (!$board->members()->where('member_id', $member->id)->exists()) {
        return response()->json(['error' => 'Ce membre ne fait pas partie du board.'], 400);
    }

    // Supprimer le membre du board
    $board->members()->detach($member->id);

    $existingList = Lists::where('board_id', $board->id)
    ->where('name', $member->name)
    ->first();

    if ($existingList) {
        Lists::where('name', $member->name)
             ->where('board_id', $board->id)
             ->delete();
    }

    return redirect('/Dashboard');
}


public function invite_collabs_interface(Boards $board){ 

    return view('boards.invite_collabs',compact('board'));
}




public function show_single_board($id)
{
    $board = Boards::findOrFail($id); // Trouve le board ou renvoie une erreur 404 si non trouvé

    return view('boards.showoff', ['board' => $board]);
}




}
