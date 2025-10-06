<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Notifications extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'member_id',
        'notifiable_type',
        'notifiable_id',
        'data',
        'read_at'
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relation avec le membre (Users utilise la table members)
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Users::class, 'member_id');
    }

    /**
     * Relation polymorphe avec l'entité notifiable
     */
    public function notifiable()
    {
        return $this->morphTo();
    }

    /**
     * Vérifier si la notification est lue
     */
    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    /**
     * Marquer comme lue
     */
    public function markAsRead(): void
    {
        $this->update(['read_at' => now()]);
    }

    /**
     * Obtenir le message formaté
     */
    public function getMessageAttribute(): string
    {
        switch ($this->type) {
            case 'board_invitation':
                return "Vous avez été invité au projet : " . $this->data['board_name'];
            case 'list_updated':
                return "La liste '{$this->data['list_name']}' a été mise à jour";
            case 'task_assigned':
                return "Une nouvelle tâche vous a été assignée : " . $this->data['task_title'];
            case 'project_update':
                return "Le projet '{$this->data['project_name']}' a été mis à jour";
            case 'deadline_reminder':
                return "Rappel : échéance proche pour " . $this->data['item_name'];
            default:
                return "Nouvelle notification";
        }
    }

    /**
     * Obtenir l'icône selon le type
     */
    public function getIconAttribute(): string
    {
        switch ($this->type) {
            case 'board_invitation':
                return 'fas fa-user-plus';
            case 'list_updated':
                return 'fas fa-edit';
            case 'task_assigned':
                return 'fas fa-tasks';
            case 'project_update':
                return 'fas fa-project-diagram';
            case 'deadline_reminder':
                return 'fas fa-clock';
            default:
                return 'fas fa-bell';
        }
    }

    /**
     * Obtenir la couleur selon le type
     */
    public function getColorAttribute(): string
    {
        switch ($this->type) {
            case 'board_invitation':
                return 'text-success';
            case 'list_updated':
                return 'text-primary';
            case 'task_assigned':
                return 'text-warning';
            case 'project_update':
                return 'text-info';
            case 'deadline_reminder':
                return 'text-danger';
            default:
                return 'text-secondary';
        }
    }

    /**
     * Obtenir le temps écoulé formaté
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Scope pour les notifications non lues
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope pour les notifications récentes
     */
    public function scopeRecent($query, $limit = 5)
    {
        return $query->latest()->limit($limit);
    }
}