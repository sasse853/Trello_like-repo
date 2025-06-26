<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if(!Schema::hasColumn('boards', 'workspace_id')){
            Schema::table('boards', function (Blueprint $table) {
                // Ajout de la colonne avec une valeur par défaut temporaire
                $table->foreignId('workspace_id')
                    ->nullable() // TEMPORAIRE : on commence avec NULL
                    ->constrained()
                    ->onDelete('cascade');
            });

        }
       

        // Mettre à jour toutes les lignes existantes avec un workspace_id valide
        DB::table('boards')->update(['workspace_id' => 1]); // Remplace 1 par un ID existant dans la table workspaces

        // Modifier la colonne pour qu'elle ne soit plus NULL après la mise à jour des valeurs
        Schema::table('boards', function (Blueprint $table) {
            $table->foreignId('workspace_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('boards', function (Blueprint $table) {
            $table->dropForeign(['workspace_id']);
            $table->dropColumn('workspace_id');
        });
    }
};
