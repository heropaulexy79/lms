<?php

namespace App\Http\Controllers;

use App\Http\Requests\Lesson\StoreLessonRequest;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB; // <-- ADDED

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, Course $course)
    {
        //
        $user = $request->user();
        $organisation = $user->organisation();

        if ($user->cannot('update', $organisation)) {
            return abort(404);
        }

        return Inertia::render('Organisation/Course/Lesson/Create', [
            // 'organisation' => $organisation,
            'course' => $course,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLessonRequest $request, Course $course)
    {
        //
        $lesson = new Lesson;

        $lesson->title = $request->input('title');
        $lesson->course_id = $course->id;
        $lesson->slug = $request->input('slug');
        $lesson->is_published = $request->input('is_published', false);

        if ($request->input('type', 'DEFAULT')) {
            $lesson->type = $request->type;
            if ($lesson->type === Lesson::TYPE_QUIZ) {
                $lesson->content_json = $request->quiz;
            } else {
                $lesson->content = $request->content;
            }
        }

        $lesson->save();

        return redirect(route('course.show', [
            'course' => $course->id,
            // 'organisation' => $organisation->id
        ]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Course $course, Lesson $lesson)
    {
        //
        $user = $request->user();
        $organisation = $user->organisation();

        if ($user->cannot('view', $user->organisation) || $organisation->id !== $course->organisation_id) {
            return abort(404); // <-- SYNTAX ERROR FIXED
        }

        // $lesson->content_json = json_decode($lesson->content_json);

        return Inertia::render('Organisation/Course/Lesson/View', [
            // 'organisation' => $user->organisation,
            // 'course' => $course,
            'lesson' => $lesson,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Course $course, Lesson $lesson)
    {
        //
        $user = $request->user();
        $organisation = $user->organisation();

        // dd($request->user()->cannot('view', $user->organisation),)

        if ($user->cannot('view', $organisation) || $organisation->id !== $lesson->course->organisation_id) {
            return abort(404);
        }

        // $lesson->content_json = json_decode($lesson->content_json);
        // $lesson->content_json = Arr::except($lesson->content_json, ['correct_option']);
        // $lesson->content_json = $lesson->quizWithoutCorrectAnswer();

        // *** THIS IS THE FIX ***
        // Load AI questions if it's a QUIZ lesson
        if ($lesson->type === Lesson::TYPE_QUIZ) {
            $lesson->load('questions.options');
        }
        // *** END FIX ***

        return Inertia::render('Organisation/Course/Lesson/Edit', [
            // 'organisation' => $user->organisation,
            'course' => $course,
            'lesson' => $lesson, // This now includes the 'questions' array
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreLessonRequest $request, Course $course, Lesson $lesson)
    {

        $lesson->title = $request->input('title');
        $lesson->is_published = $request->input('is_published');
        $lesson->slug = $request->input('slug');

        if ($request->input('type', 'DEFAULT')) {
            $lesson->type = $request->type;
            if ($request->has('content') || $request->has('quiz')) {
                if ($lesson->type === Lesson::TYPE_QUIZ) {
                    $lesson->content_json = $request->quiz;
                } else {
                    $lesson->content = $request->content;
                }
            }
        }

        $lesson->save();

        return redirect()->back()->with('global:message', [
            'status' => 'success',
            'message' => 'Changes have been saved!',
        ]);
    }
    public function updatePosition(Request $request, Course $course, Lesson $lesson)
    {


        $user = $request->user();
        $organisation = $user->organisation();

        if ($user->cannot('view', $organisation) || $organisation->id !== $course->organisation_id) {
            return abort(404);
        }

        $request->validate(['position' => 'numeric']);

        $lesson->position = $request->input('position');

        $lesson->save();

        if ($request->acceptsJson()) {
            return response()->json([
                "message" => [
                    "status" => "success",
                    "message" => "Order has been updated",
                ]
            ]);
        }

        return redirect()->back()->with('global:message', [
            'status' => 'success',
            'message' => 'Changes have been saved!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Course $course, Lesson $lesson) // <-- UPDATED
    {
        $user = $request->user();
        $organisation = $user->organisation();

        // Authorize the user (using 'update' policy as a proxy for managing content)
        if ($user->cannot('update', $organisation)) {
            return abort(403);
        }

        // Ensure the lesson belongs to the course and the course to the org
        if ($course->organisation_id !== $organisation->id || $lesson->course_id !== $course->id) {
             return abort(403);
        }

        try {
            DB::beginTransaction();

            // 1. Delete all related student progress (UserLesson records)
            $lesson->userLessons()->delete();

            // 2. Delete all related quiz questions (QuizQuestion records)
            // This assumes the QuizQuestion model will handle deleting its own options
            // via an observer or a foreign key cascade delete.
            $lesson->questions()->delete();

            // 3. Delete the lesson itself
            $lesson->delete();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            // Log the error for debugging
            \Log::error('Failed to delete lesson ' . $lesson->id . ': ' . $e->getMessage());
            
            // Return an error message to the user
            return redirect()->back()->with('message', [
                'status' => 'error',
                'message' => 'Failed to delete the lesson. Please try again.'
            ]);
        }

        // Redirect back to the lesson list page (Organisation/Course/View)
        return redirect()->back()->with('message', [
             'status' => 'success',
             'message' => 'Lesson deleted successfully.'
        ]);
    }
}

