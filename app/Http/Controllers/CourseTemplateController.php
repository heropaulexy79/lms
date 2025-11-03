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
     * Display the course template gallery.
     */
    public function index(Request $request)
    {
        $organisation = $request->user()->organisation();

        // Prevent non-admins from seeing this
        if ($request->user()->cannot('update', $organisation)) {
             abort(403);
        }

        $templates = Course::where('is_template', true)
                            ->where('is_published', true)
                            ->get();

        return Inertia::render('Organisation/Course/Templates/Index', [
            'templates' => $templates,
        ]);
    }

    /**
     * Create a new course for the user's organisation from a template.
     */
    public function store(Request $request, Course $templateCourse)
    {
        $user = $request->user();
        $organisation = $user->organisation();

        // Ensure this is a real template
        if (!$templateCourse->is_template) {
            abort(404);
        }

        // Authorize
        if ($user->cannot('update', $organisation)) {
             abort(403);
        }

        $newCourse = null;

        try {
            DB::transaction(function () use ($templateCourse, $organisation, &$newCourse) {
                
                // 1. Replicate the Course
                $newCourse = $templateCourse->replicate(['slug']); // Don't copy slug
                $newCourse->organisation_id = $organisation->id;
                $newCourse->is_template = false;
                $newCourse->is_published = false; // Start as a draft
                $newCourse->slug = Str::slug($newCourse->title) . '-' . uniqid();
                $newCourse->save();

                // 2. Replicate Lessons and their contents
                foreach ($templateCourse->lessons()->with('questions.options')->get() as $templateLesson) {
                    
                    $newLesson = $templateLesson->replicate(['slug']);
                    $newLesson->course_id = $newCourse->id;
                    $newLesson->slug = Str::slug($newLesson->title) . '-' . uniqid();
                    $newLesson->save();

                    // 3. Replicate Quiz Questions (if any)
                    if ($templateLesson->questions->isNotEmpty()) {
                        foreach ($templateLesson->questions as $templateQuestion) {
                            
                            $newQuestion = $templateQuestion->replicate();
                            $newQuestion->lesson_id = $newLesson->id;
                            $newQuestion->save();

                            // 4. Replicate Quiz Options
                            if ($templateQuestion->options->isNotEmpty()) {
                                $newOptions = $templateQuestion->options->map(function ($templateOption) {
                                    return $templateOption->replicate();
                                });
                                $newQuestion->options()->saveMany($newOptions);
                            }
                        }
                    }
                }
            });

        } catch (\Exception $e) {
            // Log the error
            \Log::error('Failed to duplicate course template: ' . $e->getMessage());
            return redirect()->back()->with('message', [
                'status' => 'error',
                'message' => 'Could not create course from template. Please try again.'
            ]);
        }

        // Success! Redirect them to edit their new course
        return redirect(route('course.edit', $newCourse->id))->with('message', [
            'status' => 'success',
            'message' => 'Course created from template! You can now edit it.'
        ]);
    }
}
