<?php

namespace App\Http\Controllers\Teacher;

use App\Classes;
use App\Exam;
use App\Http\Controllers\Controller;
use App\Question;
use App\Subject;
use DOMDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class QuestionsController extends Controller
{
    private ?DOMDocument $domDocument;

    public function __construct()
    {
        $this->domDocument = new \domdocument('1.0', 'utf-8');
    }

    /**
     * Index Page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Subject $subject, Classes $class)
    {
        abort_if(! Gate::allows('access-class-subject', [$class->id, $subject->id]), 404, 'Page not found');

        $exams = Exam::belongsToClassSubject($class->id, $subject->id)->with('subject', 'class')->latest('updated_at')->get();
        $classes = auth()->user()->isAdmin()
                        ? $subject->classes->sortBy('id')
                        : $subject->teacherSubjects()->where('teacher_id', auth()->id())->first()->classes->sortBy('id');

        ($exams->isNotEmpty()) ? session()->put('exam_id', $exams[0]->id) : session()->forget('exam_id');

        return view('teacher.questions', compact('subject', 'class', 'classes', 'exams'));
    }

    /**
     * Create a new question for an upcoming exam.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->merge(
            [
                'correct_option' => strtolower($request->input('correct_option')),
            ]
        );

        $request->validate([
            'question' => ['required', 'string'],
            'options' => ['required', 'array', 'size:4'],
            'options.*' => ['required', 'string'],
            'correct_option' => ['required', 'string', 'in:'.implode(',', array_keys($this->getValidOptionKeys()))],
        ]);

        $question = $this->loadAsHtml($request->question)->storeImages()->save();

        DB::beginTransaction();

        try {
            $createdQuestion = Question::create([
                'exam_id' => session()->get('exam_id'),
                'body' => $question,
            ]);

            $options = collect($request->options)->map(function ($option, $index) use ($request) {
                return [
                    'body' => $this->loadAsHtml($option)->storeImages()->save(),
                    'is_correct' => $this->getValidOptionKeys()[$request->correct_option] === $index,
                ];
            });

            $createdQuestion->options()->createMany($options);

            DB::commit();

            $createdQuestion->load('options');

            return $this->sendSuccessResponse('Question added successfully', $createdQuestion, 201);
        } catch (\Throwable $e) {
            DB::rollback();

            return $this->sendErrorResponse("An error was encountered submitting this question: {$e->getMessage()}");
        }
    }

    /**
     * Update a question.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question)
    {
        $request->merge(
            [
                'correct_option' => strtolower($request->input('correct_option')),
            ]
        );

        $request->validate([
            'question' => ['required', 'string'],
            'options' => ['required', 'array', 'size:4'],
            'options.*' => ['required', 'string'],
            'correct_option' => ['required', 'string', 'in:'.implode(',', array_keys($this->getValidOptionKeys()))],
        ]);

        $questionBody = $this->loadAsHtml($request->question)->storeImages()->save();

        DB::beginTransaction();

        try {
            $question->update([
                'body' => $questionBody,
            ]);

            foreach ($request->options as $key => $option) {
                $option = $this->loadAsHtml($option)->storeImages()->save();

                $question->options[$key]->body = $option;
                $question->options[$key]->is_correct = $this->getValidOptionKeys()[$request->correct_option] === $key;

                $question->push();
            }

            DB::commit();

            $question->refresh();
            $question->load('options');

            return $this->sendSuccessResponse('Question updated successfully', $question);
        } catch (\Throwable $e) {
            DB::rollback();

            return $this->sendErrorResponse("An error was encountered updating this question: {$e->getMessage()}");
        }
    }

    /**
     * Delete a question.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        $question->delete();

        return $this->sendSuccessResponse('Question deleted successfully', null, 200);
    }

    private function getValidOptionKeys(): array
    {
        return array_flip(range('a', 'd'));
    }

    /**
     * Loads a string as an HTML DOM document.
     *
     * @param string $text
     *
     * @return self
     */
    private function loadAsHtml(string $text): self
    {
        $parsedHtml = mb_convert_encoding($text, 'HTML-ENTITIES', 'UTF-8');

        libxml_use_internal_errors(true); //for the math tags

        $this->domDocument->loadHtml($parsedHtml, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        libxml_clear_errors();

        return $this;
    }

    /**
     * Decodes each base64 image string in the document and stores it on the server.
     *
     * @return self
     */
    private function storeImages(): self
    {
        $images = $this->domDocument->getElementsByTagName('img');

        foreach ($images as $image) {
            $this->upload($image);
        }

        return $this;
    }

    private function upload($image): void
    {
        $base64Data = $image->getAttribute('src');

        if (strpos($base64Data, 'data:image') !== false) { //check if it's a base64 image
            [$type, $base64Data] = explode(';', $base64Data);
            [$format, $base64String] = explode(',', $base64Data);

            $imageData = base64_decode($base64String);

            $fileName = Storage::putFile('question-uploads', $imageData);

            $image->removeattribute('src');
            $image->setattribute('src', asset("storage/app/public/{$fileName}"));
        }
    }

    private function save(): string
    {
        return $this->domDocument->saveHTML();
    }
}
