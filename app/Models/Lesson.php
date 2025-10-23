<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
// *** ADD THIS ***
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\QuizQuestion;
// *** END ADDITION ***

class Lesson extends Model
{
    use HasFactory;

    const TYPE_DEFAULT = 'DEFAULT';
    const TYPE_QUIZ = 'QUIZ';

    protected $fillable = [
        'title', 'slug', 'content', 'course_id', 'position', 'is_published', 'type', 'content_json'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'content_json' => 'array',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    // *** ADD THIS FUNCTION ***
    /**
     * Get the quiz questions for the lesson.
     */
    public function questions(): HasMany
    {
        // This creates the $lesson->questions relationship
        return $this->hasMany(QuizQuestion::class, 'lesson_id', 'id')->orderBy('position');
    }
    // *** END ADDITION ***
    
    public function quizWithoutCorrectAnswer()
    {
        $quiz = $this->content_json;

        if (is_null($quiz)) {
            return [];
        }

        return array_map(function ($question) {
            if (isset($question['correct_option'])) {
                unset($question['correct_option']);
            }
            if (isset($question['options'])) {
                $question['options'] = array_map(function ($option) {
                    unset($option['is_correct']);
                    return $option;
                }, $question['options']);
            }
            return $question;
        }, $quiz);
    }
}