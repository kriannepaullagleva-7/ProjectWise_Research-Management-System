<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavedItem extends Model
{
    protected $fillable = [
        'user_id',
        'research_project_id',
    ];

    protected $table = 'saved_items';

    /**
     * Get the user who saved this item
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the research project
     */
    public function researchProject(): BelongsTo
    {
        return $this->belongsTo(ResearchProject::class);
    }
}
