<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Leader extends Model
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use UsesUuid;

    protected $fillable = [
        'church_id',
        'full_name',
        'position',
        'level',
        'term_start',
        'term_end',
        'phone',
        'email',
        'status',
    ];

    protected $casts = [
        'term_start' => 'date',
        'term_end' => 'date',
    ];

    public function church(): BelongsTo
    {
        return $this->belongsTo(Church::class);
    }

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    public function actionItems(): HasMany
    {
        return $this->hasMany(ActionItem::class, 'responsible_leader_id');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
