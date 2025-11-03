<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Added this import
use App\Models\QuizQuestion;
use App\Models\UserLesson;
use Illuminate\Support\Str; // Added this import

class Lesson extends Model
{
    use HasFactory;

    public const TYPE_DEFAULT = 'DEFAULT';
    public const TYPE_QUIZ = 'QUIZ';

    protected $fillable = [
        'title', 'slug', 'content', 'course_id', 'position', 'is_published', 'type', 'content_json'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'content_json' => 'array',
    ];

    /**
     * Get the course that owns the lesson.
     */
    public function course(): BelongsTo // Added return type
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * Get the quiz questions for the lesson.
     */
    public function questions(): HasMany
    {
        // This creates the $lesson->questions relationship
        return $this->hasMany(QuizQuestion::class, 'lesson_id', 'id')->orderBy('position');
    }

    /**
     * Get the user-specific data for this lesson.
     */
    public function userLessons(): HasMany
    {
        // This creates the $lesson->userLessons relationship
        return $this->hasMany(UserLesson::class, 'lesson_id', 'id');
    }

    /**
     * Generate a unique slug for a new lesson.
     * We pass $courseId to keep the signature, but we check globally.
     */
    public static function generateUniqueSlug(string $title, int $courseId): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        // *** FIX: Check for the slug globally, not just within the course ***
        // This matches your database's global unique constraint.
        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }

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

