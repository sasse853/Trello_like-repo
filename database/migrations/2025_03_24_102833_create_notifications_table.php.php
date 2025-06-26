<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // Type de notification (ex: "BoardInvitationNotification")
            $table->foreignId('member_id')->nullable()->constrained('members')->onDelete('cascade'); // Clé étrangère vers users
            $table->morphs('notifiable'); // Permet aux notifications d'être associées à différents modèles
            $table->text('data'); // Stocke les données de la notification sous format JSON
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
