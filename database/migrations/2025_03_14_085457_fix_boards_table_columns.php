<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('boards_temp', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->foreignId('workspace_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
        
        // Copier les données de l'ancienne table vers la nouvelle sans la colonne user_id
        DB::statement('INSERT INTO boards_temp (id, name, member_id, description, workspace_id, created_at, updated_at) 
                      SELECT id, name, member_id, description, workspace_id, created_at, updated_at FROM boards');
        
        // Supprimer l'ancienne table
        Schema::dropIfExists('boards');
        
        // Renommer la table temporaire
        Schema::rename('boards_temp', 'boards');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('boards', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(); // Ajout de user_id en nullable au cas où
        });
    }
};
