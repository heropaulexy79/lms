import { ref } from "vue";
import { generateId } from "./utils";
import { Question } from "@/types";

export function useQuizManager(initialQuestions?: Question[]) {
    const questions = ref<Array<Question>>(initialQuestions ?? []);

    const addQuestion = () => {
        questions.value.push({
            id: generateId(),
            text: "",
            type: "single_choice",
            options: [
                { id: generateId(), text: "", is_correct: false },
                { id: generateId(), text: "", is_correct: false },
            ],
        } as Question);
    };

    const deleteQuestion = (index: number) => {
        questions.value.splice(index, 1);
    };

    const addOption = (questionIndex: number) => {
        // Include is_correct by default (fixes TS error where is_correct required)
        const question = questions.value[questionIndex];

        // Check if both question and question.options exist
        if (question?.options) {
            question.options.push({
                id: generateId(),
                text: "",
                is_correct: false,
            } as any);
        }
    };

    const deleteOption = (questionIndex: number, optionIndex: number) => {
        const question = questions.value[questionIndex];
        
        if (question?.options) {
            question.options.splice(optionIndex, 1);
        }
    };

    return {
        questions,
        addQuestion,
        deleteQuestion,
        addOption,
        deleteOption,
    };
}

