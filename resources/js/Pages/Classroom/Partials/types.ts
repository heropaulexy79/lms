import { Lesson } from "@/types";

export type QuestionOption = {
    id: string;
    text: string;
    is_correct?: boolean;
};

export type BaseQuestion = {
    id: string;
    text: string;
    options: Array<QuestionOption>;
    metadata: {
        hint?: string;
        explanation?: string;
    };
};

export type SingleChoice = BaseQuestion & {
    type: "single_choice";
    correct_option: string;
};

export type MultipleChoice = BaseQuestion & {
    type: "multiple_choice";
    correct_option: string[];
};

// *** ADDED NEW TYPES FROM YOUR PROJECT ***
export type TrueFalse = BaseQuestion & {
    type: "true_false";
    correct_option: string; // "true" or "false"
};

export type MultipleSelect = BaseQuestion & {
    type: "multiple_select";
    correct_option: string[];
};

export type TypeAnswer = BaseQuestion & {
    type: "type_answer";
    correct_option: string;
};

export type Puzzle = BaseQuestion & {
    type: "puzzle";
    correct_option: string; // Or appropriate type
};
// *** END ADDITION ***

// *** FIX: Added all question types to the main Question union type ***
// This will fix the TS2367 "no overlap" errors in QuizRenderer.vue
export type Question =
    | SingleChoice
    | MultipleChoice
    | TrueFalse
    | MultipleSelect
    | TypeAnswer
    | Puzzle;

// *** ADDED NEW TYPES FROM YOUR PROMPT ***
export type Answer = {
    question_id: string;
    selected_option: string | string[];
};

export type WithUserLesson<T> = T & {
    completed: boolean;
    answers: Answer[];
    // *** ADDED SCORE TO FIX BUILD ERROR ***
    score?: number; 
};


