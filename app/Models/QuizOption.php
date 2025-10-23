<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_question_id',
        'option_text',
        'is_correct',
        'position',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    // *** START FIX ***
    /**
     * Appends the 'text' attribute to array/JSON versions of the model.
     */
    protected $appends = ['text'];

    /**
     * Accessor to map the 'option_text' column to a 'text' attribute.
     * This is what the frontend expects.
     */
    public function getTextAttribute()
    {
        return $this->option_text;
    }
    // *** END FIX ***

    public function question(): BelongsTo
    {
        return $this->belongsTo(QuizQuestion::class, 'quiz_question_id');
    }
}