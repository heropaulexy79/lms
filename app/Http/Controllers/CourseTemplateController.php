<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Str;

class CourseTemplateController extends Controller
{
    /**
     * Display a gallery of available course templates.
     */
    public function index(Request $request)
    {
        $organisation = $request->user()->organisation();

        // Prevent non-admins from seeing this
        if ($request->user()->cannot('update', $organisation)) {
             abort(403);
        }

        // Fetch all courses that are marked as templates
        $templates = Course::where('is_template', true)
            ->where('is_published', true)
            ->get();

        return Inertia::render('Organisation/Course/Templates/Index', [
            'templates' => $templates,
        ]);
    }

    /**
     * Display the specified course template in a read-only preview.
     */
    public function show(Request $request, Course $templateCourse)
    {
        $organisation = $request->user()->organisation();

        // Prevent non-admins from seeing this
        if ($request->user()->cannot('update', $organisation)) {
             abort(403);
        }

        // Ensure this is a real template
        if (!$templateCourse->is_template) {
            abort(404);
        }

        // Load the template course with all its lessons, questions, and options
        $templateCourse->load('lessons.questions.options');

        return Inertia::render('Organisation/Course/Templates/Show', [
            'template' => $templateCourse,
        ]);
    }

    /**
     * Create a new course for the user's organisation from a template.
     */
    public function store(Request $request, Course $templateCourse)
    {
        $organisation = $request->user()->organisation();
        $user = $request->user();

        // Ensure this is a real template
        if (!$templateCourse->is_template) {
            abort(404, 'Not a template.');
        }

        // Ensure user is admin of their org
        if ($user->cannot('update', $organisation)) {
            abort(403, 'Unauthorized');
        }

        $newCourse = null;

        DB::transaction(function () use ($templateCourse, $organisation, $user, &$newCourse) {
            // 1. Replicate the Course
            $newCourse = $templateCourse->replicate([
                'is_template', // Don't copy the template flag
                'organisation_id', // Will set this manually
                'slug', // Will regenerate this
            ]);

            $newCourse->organisation_id = $organisation->id;
            $newCourse->is_template = false; // It's a real course now
            $newCourse->is_published = false; // Draft by default
            $newCourse->title = $templateCourse->title; // "Employee Onboarding Template"
            // Regenerate a unique slug for the new org's course
            $newCourse->slug = Course::generateUniqueSlug($newCourse->title, $organisation->id);
            $newCourse->save();

            // 2. Replicate Lessons and their contents
            foreach ($templateCourse->lessons()->with(['questions.options'])->get() as $templateLesson) {
                
                $newLesson = $templateLesson->replicate([
                    'course_id', // Will set this manually
                    'slug', // Will regenerate this
                ]);

                $newLesson->course_id = $newCourse->id;
                // Regenerate a unique slug for the new lesson
                $newLesson->slug = Lesson::generateUniqueSlug($newLesson->title, $newCourse->id);
                $newLesson->save();

                // 3. Replicate Quiz Questions (if any)
                if ($templateLesson->type === Lesson::TYPE_QUIZ) {
                    foreach ($templateLesson->questions as $templateQuestion) {
                        
                        $newQuestion = $templateQuestion->replicate([
                            'lesson_id', // Will set this manually
                        ]);
                        $newQuestion->lesson_id = $newLesson->id;
                        $newQuestion->save();

                        // 4. Replicate Quiz Options
                        foreach ($templateQuestion->options as $templateOption) {
                            $newOption = $templateOption->replicate([
                                'quiz_question_id', // Will set this manually
                            ]);
                            $newOption->quiz_question_id = $newQuestion->id;
                            $newOption->save();
                        }
                    }
                }
            }
        });

        // Redirect to the new course's edit page
        if ($newCourse) {
            return redirect()->route('course.edit', $newCourse)
                ->with('success', 'Course created from template. You can now edit it.');
        }

        return redirect()->back()->with('error', 'Failed to create course from template.');
    }
}

