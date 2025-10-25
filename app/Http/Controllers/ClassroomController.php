<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Lesson;
use App\Models\UserLesson;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClassroomController extends Controller
{
    // Only allow if user is enrolled

    public function showCourse(Request $request, Course $course)
    {
        return redirect(route('classroom.lesson.index', ['course' => $course->slug]));
    }

    public function showLessons(Request $request, Course $course)
    {

        $user = $request->user();

        $lastCompletedLesson = UserLesson::where('user_id', $user->id)
            ->where('completed', 1)
            ->join('lessons', 'user_lessons.lesson_id', '=', 'lessons.id')
            ->select('user_lessons.*', 'lessons.course_id', 'lessons.is_published', 'lessons.position', 'lessons.slug')
            ->where('lessons.is_published', 1)
            ->where('lessons.course_id', $course->id)
            ->orderBy('lessons.position', 'desc')
            ->first();

        // Find the next lesson slug
        $redirectLessonSlug = null;
        if ($lastCompletedLesson) {
            // Find the *next* lesson after the last completed one
            $nextLesson = $course->lessons()
                ->where('is_published', true)
                ->where('position', '>', $lastCompletedLesson->position)
                ->orderBy('position', 'asc')
                ->first();
            
            $redirectLessonSlug = $nextLesson ? $nextLesson->slug : $lastCompletedLesson->slug; // Stay on last lesson if it's the final one
        } else {
            // No lessons completed, redirect to the very first lesson
            $firstLesson = $course->lessons()
                ->where('is_published', true)
                ->orderBy('position', 'asc')
                ->first();
            
            if ($firstLesson) {
                $redirectLessonSlug = $firstLesson->slug;
            } else {
                // No published lessons in this course, redirect to dashboard
                return redirect(route('dashboard'))->with('message', [
                    'status' => 'error',
                    'message' => 'This course has no published lessons.'
                ]);
            }
        }

        return redirect(route('classroom.lesson.show', ['course' => $course->slug, 'lesson' => $redirectLessonSlug]));
    }


    public function showLesson(Request $request, Course $course, Lesson $lesson)
    {

        $user = $request->user();

        $temp_content_json = $lesson->content_json;
        $hasAiQuestions = false; // Initialize checker variable

        if ($lesson->type === Lesson::TYPE_QUIZ) {
            
            // *** QUIZ FIX: Directly fetch questions to bypass stale model state ***
            $aiGeneratedQuestions = $lesson->quizQuestions()->with('options')->get();

            // Prioritize AI-generated quizzes from quiz_questions table
            if ($aiGeneratedQuestions->isNotEmpty()) {
                $hasAiQuestions = true; // Mark that we found AI questions
                // Convert AI-generated questions to the format expected by frontend
                $lesson->content_json = $this->formatAiGeneratedQuestions($aiGeneratedQuestions);
            } else {
                // Fallback to old content_json format
                $lesson->content_json = $lesson->quizWithoutCorrectAnswer();
            }
        }

        $lessons = $course->lessons()
            ->where('is_published', true)
            ->orderBy('position')
            ->with(['userLessons' => function ($query) use ($user) { // <-- FIX: Changed user_lesson to userLessons
                $query->where('user_id', $user->id);
            }])
            ->get(['title', 'position', 'type', 'id', 'slug',]);
        
        $total_completed = 0;

        foreach ($lessons as $l) {
            $l->completed = $l->userLessons->first()?->completed === 1; // <-- FIX: Changed user_lesson to userLessons


            if ($l->completed) {
                $total_completed++;
            }
        };

        if (count($lessons) > 0 && $total_completed === count($lessons)) {
            $enrollment = CourseEnrollment::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->first();

            if ($enrollment) {
                $enrollment->is_completed = true;
                $enrollment->save();
            }
        }

        $user_lesson_data = $lessons->filter(function ($item) use ($lesson) {
            return $item['id'] === $lesson->id;  // Strict comparison with ===
        })->first();

        // <-- FIX: Changed user_lesson to userLessons
        $user_lesson = $user_lesson_data ? $user_lesson_data->userLessons->first() : null;


        $lesson->completed = $user_lesson?->completed === 1;
        $lesson->answers = $user_lesson?->answers ?? null;

        // *** QUIZ FIX: Use our new variable instead of the stale hasAiGeneratedQuestions() method ***
        if ($lesson->completed && !$hasAiQuestions) {
            $lesson->content_json = $temp_content_json;
        }

        $lessons->makeHidden('userLessons'); // <-- FIX: Changed user_lesson to userLessons

        return Inertia::render('Classroom/Lesson', [
            'course' => $course,
            'lessons' => $lessons,
            'lesson' => $lesson,
        ]);
    }

    public function showCompleted(Request $request, Course $course)
    {

        $user = $request->user();
        $lessons = $course->lessons()
            ->where('is_published', true)
            ->orderBy('position')
            ->with(['userLessons' => function ($query) use ($user) { // <-- FIX: Changed user_lesson to userLessons
                $query->where('user_id', $user->id);
            }])
            ->get(['title', 'position', 'type', 'id', 'slug',]);
        
        $total_completed = 0;

        foreach ($lessons as $l) {
            $l->completed = $l->userLessons->first()?->completed === 1; // <-- FIX: Changed user_lesson to userLessons
            if ($l->completed) {
                $total_completed++;
            }
        };

        $enrollment = CourseEnrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        $lesson_count = count($lessons);

        if ($lesson_count > 0 && $total_completed === $lesson_count) {

            if ($enrollment) {
                $enrollment->is_completed = true;
                $enrollment->save();
            }
        }

        $lessons->makeHidden('userLessons'); // <-- FIX: Changed user_lesson to userLessons


        $userScores = $user->lessons()
            ->with('lesson')
            ->whereHas('lesson', function ($query) use ($course) {
                $query->where('course_id', $course->id);
            })
            ->select('user_lessons.score') // Select specific columns
            ->get();

        return Inertia::render('Classroom/Completed', [
            'course' => $course,
            'lessons' => $lessons,
            'enrollment' => $enrollment,
            'progress' => ($lesson_count > 0 ? ($total_completed / $lesson_count) * 100 : 0),
            'completed_lessons' => $total_completed,
            'total_score' => $userScores->sum('score'),
        ]);
    }



    public function markLessonComplete(Request $request, Course $course, Lesson $lesson)
    {

        $user = $request->user();

        UserLesson::upsert(
            [['user_id' => $user->id, 'lesson_id' => $lesson->id, 'completed' => true],],
            uniqueBy: ['user_id', 'lesson_id'],
            update: ['completed']
        );

        $next_lesson_id = $request->query('next') ?? $lesson->slug;

        return redirect(route('classroom.lesson.show', ['course' => $course->slug, 'lesson' => $next_lesson_id]));
    }


    public function answerQuiz(Request $request, Course $course, Lesson $lesson)
    {
        $user = $request->user();

        $request->validate([
            'answers' => 'required|array|min:0',
            'answers.*.question_id' => 'required',
            'answers.*.selected_option' => 'nullable',
        ]);

        $score = 0.0;
        $total = 0.0;

        // *** QUIZ FIX: Directly fetch questions to ensure we have them ***
        $aiGeneratedQuestions = $lesson->quizQuestions()->with('options')->get();

        // Check if this is an AI-generated quiz
        if ($aiGeneratedQuestions->isNotEmpty()) {
            // Handle AI-generated quiz scoring
            $answers = $request->input('answers');
            
            foreach ($answers as $answer) {
                $question = $aiGeneratedQuestions->find($answer['question_id']);
                if (!$question) continue;
                
                $total++;
                
                if ($this->isAnswerCorrect($question, $answer['selected_option'])) {
                    $score++;
                }
            }
        } else {
            // Fallback to old quiz scoring system
            $quiz = $lesson->content_json;
            $answers = $request->input('answers');

            if (is_null($quiz)) { 
                return redirect()->back()->with('message', [
                    'status' => 'error',
                    'message' => 'Quiz content is missing.',
                ]);
            }

            foreach ($answers as $key => $value) {
                $v = array_search($value['question_id'],  array_column($quiz, 'id'));
                if ($v === false) {
                    continue;
                }
                $question = $quiz[$v];

                if (!$question) {
                    continue;
                }

                if ($question['type'] === 'single_choice') {
                    $total++;

                    if ($question['correct_option'] === $value['selected_option']) {
                        $score++;
                    }
                }
            }
        }


        $scoreInPercent = ($total > 0) ? (($score / $total) * 100) : 0;

        UserLesson::upsert(
            [[
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
                'completed' => true,
                'answers' => json_encode($answers),
                'score' => $scoreInPercent
            ]],
            uniqueBy: ['user_id', 'lesson_id'],
            update: ['completed', 'score', 'answers']
        );

        return redirect()->back()->with('message', [
            'status' => 'success',
            'message' => 'You scored ' . $score . ' out of ' . $total,
            'score' => $scoreInPercent
        ]);
    }

    private function formatAiGeneratedQuestions($questions)
    {
        return $questions->map(function ($question) {
            $formatted = [
                'id' => (string) $question->id,
                'text' => $question->question,
                'type' => $this->mapQuestionType($question->type),
                'options' => []
            ];

            if ($question->options->isNotEmpty()) {
                foreach ($question->options as $option) {
                    $formatted['options'][] = [
                        'id' => (string) $option->id,
                        'text' => $option->option_text,
                        'is_correct' => $option->is_correct
                    ];
                }
            }

            if ($question->type === 'MULTIPLE_CHOICE' && $question->options->isNotEmpty()) {
                $correctOption = $question->options->where('is_correct', true)->first();
                if ($correctOption) {
                    $formatted['correct_option'] = (string) $correctOption->id;
                }
            }

            return $formatted;
        })->toArray();
    }

    private function mapQuestionType($aiType)
    {
        $typeMap = [
            'MULTIPLE_CHOICE' => 'single_choice',
            'MULTIPLE_SELECT' => 'multiple_select',
            'TRUE_FALSE' => 'true_false',
            'TYPE_ANSWER' => 'type_answer',
            'PUZZLE' => 'puzzle'
        ];

        return $typeMap[$aiType] ?? 'single_choice';
    }

    private function isAnswerCorrect($question, $selectedOption)
    {
        switch ($question->type) {
            case 'MULTIPLE_CHOICE':
            case 'TRUE_FALSE':
                $correctOption = $question->options->where('is_correct', true)->first();
                return $correctOption && $correctOption->id == $selectedOption;
                
            case 'MULTIPLE_SELECT':
                $selected = is_array($selectedOption) ? $selectedOption : (is_null($selectedOption) ? [] : [$selectedOption]);
                $selected = array_filter($selected, fn($v) => !is_null($v) && $v !== '');

                $correctIds = $question->options->where('is_correct', true)->pluck('id')->map(fn($id) => (string)$id)->values()->toArray();
                sort($correctIds);
                $selectedStr = array_map(fn($id) => (string)$id, $selected);
                sort($selectedStr);
                return $selectedStr === $correctIds;
                
            case 'TYPE_ANSWER':
            case 'PUZZLE':
                $correctAnswer = $question->metadata['correct_answer'] ?? null;
                if (is_null($correctAnswer)) {
                    $correctOption = $question->options->where('is_correct', true)->first();
                    $correctAnswer = $correctOption ? $correctOption->option_text : '';
                }
                return is_string($selectedOption) && strtolower(trim($selectedOption)) === strtolower(trim($correctAnswer));
                
            default:
                return false;
        }
    }
}

