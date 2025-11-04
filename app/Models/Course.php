<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'teacher_id',
        'is_published',
        'banner_image',
        'organisation_id', 
        'is_template',    
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_template' => 'boolean', // Add this cast
    ];

    /**
     * Get the lessons for the course.
     */
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('position');
    }

    /**
     * Get the organisation that owns the course.
     */
    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    /**
     * Get the teacher (User) who created the course.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Get the enrollments for the course.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    public static function generateUniqueSlug(string $title, ?int $organisationId): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        // *** FIX: Check for the slug globally, not just within the organisation ***
        // This matches your database's global unique constraint.
        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }
}

