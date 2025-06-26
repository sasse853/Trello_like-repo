<?php

namespace App\Http\Controllers;
use App\Models\Users;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Boards;
use App\Models\Notifications;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Mail\Mailable;
use App\Http\Controllers\ResetPasswordMail;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash; 

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register(){
        return view('UserRegister');
    }

    public function login(){
        return view('UserLogin');
    }


    public function ajout_membre(?Request $request){
        $request->validate([
            'name'=>'required|unique:members,name',
            'email'=>'required|unique:members,email',
            'password'=>'required|min:8',
            'confirm_password'=>'required|min:8',

            

            
        ]);
        if($request->password!=$request->confirm_password){
            return  back()->withErrors([
                'confirm_password'=>'Les mots de passe ne correspondent pas',

            ]);
        }
        $member=new Users();

        $member->name=$request->name;
        $member->email=$request->email;
        $member->password=Hash::make($request->password);

        $member->save();

        
        return redirect('login');



    }


   
    public function verify_user(Request $request){
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if (Auth::attempt([
            'email' => $validated['email'],
            'password' => $validated['password']
        ])){
            return redirect('Dashboard');

        }else{
            return back()->withErrors([
                'email' => 'Les identifiants sont incorrects.',
            ]);
        }
    

    }

    public function password_modification(){
        return view('modify_password');
    }


    public function updatePassword(Request $request)
{
    // Validation des champs
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:8|confirmed',
    ], [
        'current_password.required' => 'L\'ancien mot de passe est requis',
        'new_password.required' => 'Le nouveau mot de passe est requis',
        'new_password.min' => 'Le nouveau mot de passe doit contenir au moins 8 caractères',
        'new_password.confirmed' => 'La confirmation du nouveau mot de passe ne correspond pas',
    ]);

    // Vérifier que l'utilisateur est connecté
    if (!Auth::check()) {
        return redirect('/login')->with('error', 'Vous devez être connecté pour modifier votre mot de passe');
    }

    // Récupérer l'utilisateur connecté
    $user = Auth::user();
    
    // Vérifier que l'ancien mot de passe est correct
    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors([
            'current_password' => 'L\'ancien mot de passe est incorrect',
        ]);
    }

    // Mettre à jour le mot de passe en utilisant le modèle Users
    $userId = $user->id;
    $userModel = Users::where('id', $userId)->first();
    
    if ($userModel) {
        $userModel->password = Hash::make($request->new_password);
        $userModel->save();
        
        return redirect('/Dashboard')->with('success', 'Votre mot de passe a été modifié avec succès');
    } else {
        return back()->withErrors([
            'error' => 'Une erreur s\'est produite lors de la mise à jour du mot de passe',
        ]);
    }
}



public function showForgotPasswordForm()
{
    return view('forgot_password');
}

// Traiter la demande et envoyer le lien par email
public function sendResetLinkEmail(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:members,email',
    ], [
        'email.exists' => 'Aucun compte n\'est associé à cette adresse email.'
    ]);

    // Générer un token
    $token = Str::random(60);
    
    // Enregistrer le token dans la base de données
    DB::table('password_resets')->insert([
        'email' => $request->email,
        'token' => $token,
        'created_at' => Carbon::now()
    ]);
    
    // Créer le lien de réinitialisation
    $resetLink = url('/reset-password/' . $token . '?email=' . urlencode($request->email));
    
    // Envoi de l'email (version simplifiée - vous devriez créer un vrai modèle d'email)
    // Note: Ceci suppose que vous avez configuré l'envoi d'emails dans Laravel
    Mail::send('emails.remodify_password', ['token' => $token], function($message) use ($request) {
        $message->to($request->email);
        $message->subject('Réinitialisation de votre mot de passe');
    });
    
    return back()->with('status', 'Nous vous avons envoyé un lien de réinitialisation par email!');
}

// Afficher le formulaire de réinitialisation de mot de passe
public function showResetPasswordForm(Request $request, $token)
{
    return view('reset_password', ['token' => $token,'email' => $request->email]);
    
}

// Traiter la réinitialisation du mot de passe
public function resetPassword(Request $request)
{
   
    $request->validate([
        'email' => 'required|email|exists:members,email',
        'password' => 'required|min:8|confirmed',
        'token' => 'required'
    ]);

    // Vérifier si le token est valide
    $tokenData = DB::table('password_resets')
        ->where('token', $request->token)
        ->where('email', $request->email)
        ->first();

    if (!$tokenData) {
        return back()->withErrors(['error' => 'Token invalide ou expiré.']);
    }

    // Vérifier si le token n'est pas trop ancien (24h maximum par exemple)
    $createdAt = Carbon::parse($tokenData->created_at);
    if (Carbon::now()->diffInHours($createdAt) > 24) {
        return back()->withErrors(['error' => 'Le token a expiré. Veuillez faire une nouvelle demande.']);
    }

    // Mettre à jour le mot de passe
    $user = Users::where('email', $request->email)->first();
    $user->password = Hash::make($request->password);
    $user->save();

    // Supprimer le token utilisé
    DB::table('password_resets')->where('email', $request->email)->delete();

    return redirect('/login')->with('status', 'Votre mot de passe a été réinitialisé avec succès!');
}



public function logout(Request $request)
{
    Auth::logout(); // Déconnecter l'utilisateur

    $request->session()->invalidate(); // Invalider la session
    $request->session()->regenerateToken(); // Régénérer le token CSRF

    return redirect('/login')->with('success', 'Vous êtes déconnecté.');
}





public function user_dashboard()
{
   $user = Auth::user();

    $boards = Boards::all();

    return view('UserDashboard', compact('boards'));
    
}







    
}
