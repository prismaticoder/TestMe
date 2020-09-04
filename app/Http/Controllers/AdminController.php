<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subject;
use App\Classes;
use App\Exam;
use App\Mark;
use App\Question;
use App\Option;
use App\User;
use Gate;
use DB;
use Auth;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use PDF;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admins');
    }

    public function dashboard() {
        // to authorize admin as the super admin

        $exams = [];

        if (Gate::denies('superAdminGate')){
            //Only return the subject of that user
            $subjects = Auth::user()->subjects()->with('classes','subject')->get();
            $classes = Classes::get();
            $all_started = Exam::whereIn('subject_id', Arr::pluck($subjects, 'subject_id'))->where('hasStarted', 1)->get();
        }

        else {
            $subjects = Subject::orderBy('subject_name')->get();
            $classes = Classes::get();
            //get all started exams
            $all_started = Exam::where('hasStarted', 1)->get();
        }

        if (count($all_started) > 0) {
            foreach ($all_started as $exam) {
                array_push($exams, ['id' => $exam->id, 'subject' => $exam->subject, 'class' => $exam->class]);
            }
        }

        foreach ($subjects as $subject) {
            foreach ($subject->classes as $class) {
                $class->hasPendingExamToday = $class->hasPendingExamToday($subject->id);
            }
        }

        $exams = json_encode($exams);

        return view('admin.dashboard',compact('subjects','classes', 'exams'));
    }

    public function getAllStudents() {
        $classes = Classes::with(['students' => function ($q) {
            $q->withTrashed()->orderBy('lastname');
          }])->get();

        return view('admin.class-students', compact('classes'));
    }

    public function updateStudent(Request $request,$id) {
        $student = User::find($id);

        $firstname = $request->firstname;
        $lastname = $request->lastname;

        $student->firstname = $firstname;
        $student->lastname = $lastname;
        $student->save();
        $message = "Details updated successfully";

        return compact('student','message');
    }

    public function disableStudent($id) {
        $student = User::find($id);
        $student->delete();
        $message = 'Student access disabled';

        return compact('student','message');
    }

    public function deleteStudent($id) {
        $student = User::find($id);
        $student->forceDelete();

        return response()->json(['message' => 'Student deleted successfully']);
    }

    public function restoreStudent($id) {
        $student = User::onlyTrashed()->where('id',$id)->first();
        $student->restore();
        $message = 'The selected student has succesfully been restored!';

        return compact('student','message');
    }

    public function generateStudentCode($class_id) {
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
        $code = mt_rand(5111, 9999) . $characters[rand(0, strlen($characters) - 1)] . $characters[rand(0, strlen($characters) - 1)];

        $check = User::where('class_id',$class_id)->where('code', $code)->first();

        //recursively check whether another student exists in that class with the same code
        if ($check) {
            return $this->generateStudentCode($class_id);
        }

        else {
            return $code;
        }
    }

    public function addStudent(Request $request) {
        $firstname = $request->firstname;
        $lastname = $request->lastname;
        $class_id = $request->class_id;

        $code = $this->generateStudentCode($class_id);

        $student = new User;
        $student->firstname = $firstname;
        $student->lastname = $lastname;
        $student->class_id = $class_id;
        $student->code = $code;
        $student->save();

        $message = "Student Added Successfully!";

        return compact('student','message');
    }

    public function getAllQuestions($subject,$class_id) {
        $subject = Subject::where('alias',$subject)->firstOrFail();
        $current_class = $subject->classes()->where('class_id', $class_id)->firstOrFail();
        $subject_id = $subject['id'];
        $classes = Classes::all();

        if (Gate::allows('view-subject-details', $subject)) {
            //Get the exam with the latest date, that is the current exam.
            $exams = Exam::where('subject_id',$subject->id)->where('class_id',$class_id)->orderBy('date','desc')->get();

            if (count($exams) > 0) {
                Session::put('exam_id', $exams[0]->id);
            }
            else {
                Session::forget('exam_id');
            }

            return view('admin.questions', compact('subject','class_id','current_class','classes','exams'));
        }

        return abort('404','Page does not exist');
    }

    public function addQuestion(Request $request) {
        $question = $request->question;
        $options = $request->options;
        $correctAnswer = $request->correct;

        // converts all special characters to utf-8
        $question = mb_convert_encoding($question, 'HTML-ENTITIES', 'UTF-8');
        $dom = new \domdocument('1.0', 'utf-8');

        libxml_use_internal_errors(true); //for the math tags
        $dom->loadHtml($question, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $images = $dom->getelementsbytagname('img');

        //loop over img elements, decode their base64 src and save them to public folder,
        //and then replace base64 src with stored image URL.
        foreach($images as $k => $img){
            $data = $img->getattribute('src');
            if (strpos($data, 'data:image')!==false) {
                list($type, $data) = explode(';', $data);
                list(, $data)      = explode(',', $data);

                $data = base64_decode($data);
                $image_name= time().$k.'.png';
                $path = public_path() .'/img/uploads/'. $image_name;

                file_put_contents($path, $data);

                $img->removeattribute('src');
                $img->setattribute('src', '/img/uploads/'.$image_name);
            }
        }

        $question = $dom->savehtml();

        $createdQuestion = new Question;
        DB::transaction(function() use($question,$options,$correctAnswer, &$createdQuestion) {

            $createdQuestion->exam_id = Session::get('exam_id');
            $createdQuestion->question = $question;
            $createdQuestion->save();

            foreach ($options as $key=>$option) {
                $option = mb_convert_encoding($option, 'HTML-ENTITIES', 'UTF-8');
                $dom = new \domdocument('1.0', 'utf-8');

                libxml_use_internal_errors(true); //for the math tags
                $dom->loadHtml($option, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
                libxml_clear_errors();

                $images = $dom->getelementsbytagname('img');


                foreach($images as $k => $img){
                    $data = $img->getattribute('src');
                    if (strpos($data, 'data:image')!==false) {
                        list($type, $data) = explode(';', $data);
                        list(, $data)      = explode(',', $data);

                        $data = base64_decode($data);
                        $image_name= $key.time().$k.'.png';
                        $path = public_path() .'/img/uploads/'. $image_name;

                        file_put_contents($path, $data);

                        $img->removeattribute('src');
                        $img->setattribute('src', '/img/uploads/'.$image_name);
                    }
                }

                $option = $dom->savehtml();

                $createdOption = new Option;
                $createdOption->body = $option;
                $createdOption->isCorrect = ($correctAnswer == $key)?1:0;

                $createdQuestion->options()->save($createdOption);
            }
        });

        $newQuestion = Question::where('id', $createdQuestion->id)->with('options')->first();
        return response()->json(['question'=>$newQuestion, 'message'=>'Question Added Successfully!']);
    }

    public function updateQuestion(Request $request, $id) {
        $question = $request->question;
        $options = $request->options;
        $correctAnswer = $request->correct;
        $questionDB = Question::where('id',$id)->with('options')->first();

        $question = mb_convert_encoding($question, 'HTML-ENTITIES', 'UTF-8');
        $dom = new \domdocument('1.0', 'utf-8');

        libxml_use_internal_errors(true); //for the math tags
        $dom->loadHtml($question, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $images = $dom->getelementsbytagname('img');

        //loop over img elements, decode their base64 src and save them to public folder,
        //and then replace base64 src with stored image URL.
        foreach($images as $k => $img){
            $data = $img->getattribute('src');
            if (strpos($data, 'data:image')!==false){
                list($type, $data) = explode(';', $data);
                list(, $data)      = explode(',', $data);

                $data = base64_decode($data);
                $image_name= time().$k.$id.'.png';
                $path = public_path() .'/img/uploads/'. $image_name;

                file_put_contents($path, $data);

                $img->removeattribute('src');
                $img->setattribute('src', asset('/img/uploads/'.$image_name));
            }
        }

        $question = $dom->savehtml();

        DB::transaction(function() use($question,$options,$correctAnswer,&$questionDB) {
            $questionDB->update([
                'question' => $question,
            ]);
            foreach ($options as $key => $option) {
                $optionFind = Option::find($option['id']);

                $option_body = $option['value'];
                $option_body = mb_convert_encoding($option_body, 'HTML-ENTITIES', 'UTF-8');
                $dom = new \domdocument('1.0', 'utf-8');

                libxml_use_internal_errors(true); //for the math tags
                $dom->loadHtml($option_body, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
                libxml_clear_errors();

                $images = $dom->getelementsbytagname('img');

                foreach($images as $k => $img){
                    $data = $img->getattribute('src');
                    if (strpos($data, 'data:image')!==false){
                        list($type, $data) = explode(';', $data);
                        list(, $data)      = explode(',', $data);

                        $data = base64_decode($data);
                        $image_name= $key.time().$k.'.png';
                        $path = public_path() .'/img/uploads/'. $image_name;

                        file_put_contents($path, $data);

                        $img->removeattribute('src');
                        $img->setattribute('src', '/img/uploads/'.$image_name);
                    }
                }

                $option_body = $dom->savehtml();

                $optionFind->body = $option_body;
                $optionFind->isCorrect = ($correctAnswer == $key)?1:0;
                $optionFind->save();
            }

            $questionDB->refresh();
        });

        return response()->json(['question'=>$questionDB, 'message'=>'Question Updated Successfully!']);

    }

    public function deleteQuestion($id) {
        $question = Question::find($id);
        $question->delete();

        return response()->json(['message'=>'Question Deleted Successfully!']);
    }

    public function findOneQuestion(Request $request, $id) {
        $question = Question::where('id',$id)->with('options')->get();

        return response()->json($question[0]);
    }

    public function getResults() {
        $classes = Classes::get();

        if(Gate::denies('superAdminGate')){
            //Only return the subject of that user
            $subjects = Subject::where('id', Auth::user()->subject_id)->get();
        }

        else {
            $subjects = Subject::all();
        }

        return view('admin.results',compact('subjects','classes'));
    }

    public function getSingleResult(Request $request,$subject,$class_id) {
        $subject = Subject::where('alias',$subject)->first();
        $current_class = Classes::find($class_id);

        if ($subject && $current_class) {
            if (Gate::allows('view-subject-details', $subject)) {

                $students = User::where('class_id',$class_id)->orderBy('lastname')->get();
                $exams = $current_class->getAllExams($subject->id);

                $selected_exam = null;
                if ($request->date && $request->id) {
                    $selected_exam = Exam::where('id', $request->id)->where('subject_id',$subject->id)->where('class_id',$class_id)->where('date', $request->date)->has('scores')->first();
                }

                foreach ($students as $student) {
                    $student->score = count($exams) > 0 ? $student->getScore($selected_exam ? $selected_exam->id : $exams[0]->id) : null;
                }

                $classes = Classes::all();

                return view('admin.main-result',compact('students','subject','current_class','classes','exams','selected_exam'));
            }

            return  abort('404');
        }

        return abort('404');
    }

    public function downloadResult(Request $request, $subject,$class_id,$exam_id) {
        $subject = Subject::where('alias',$subject)->first();
        $current_class = Classes::find($class_id);

        if ($subject && $current_class) {
            if (Gate::allows('view-subject-details', $subject)) {

                $students = User::where('class_id',$class_id)->orderBy('lastname')->get();
                $exam = Exam::where('id', $exam_id)->where('subject_id',$subject->id)->where('class_id',$class_id)->first();

                if ($exam) {
                    foreach ($students as $student) {
                        $student->score = $student->getScore($exam->id);
                    }

                    $data = compact('current_class','subject','students','exam');

                    $pdf = PDF::loadView('admin.pdf-result',$data);

                    return $pdf->download(strtolower($subject->alias).'_'.strtolower($current_class->class).'_results.pdf');
                }

                return abort('404');
            }

            return  abort('404');
        }

        return abort('404');
    }

    public function startExam(Request $request) {
        $subject_id = $request->subject_id;
        $class_id = $request->class_id;
        $today = date('Y-m-d');
        $exam = Exam::where('subject_id',$subject_id)->where('class_id',$class_id)->where('date', $today)->with('subject','class')->doesntHave('scores')->first();

        if ($exam) {
            $subject = Subject::find($subject_id);

            if (Gate::allows('view-subject-details', $subject)) {
                $exam->hasStarted = 1;
                $exam->save();

                return response()->json(['exam' => $exam]) ;
            }

            return abort('403');
        }

        return abort('404');
    }

    public function endExam(Request $request, $id) {
        $subject_id = $request->subject_id;
        $exam = Exam::find($id);


        if ($exam) {
            $subject = Subject::find($subject_id);

            if (Gate::allows('view-subject-details', $subject)) {
                $exam->hasStarted = 0;
                $exam->save();

                return response()->json(['exam' => $exam]) ;
            }

            return abort(403);
        }

        return abort('404');
    }

    public function createExam(Request $request) {
        $subject_id = $request->subject_id;
        $class_id = $request->class_id;
        $base_score = $request->base_score;
        $hours = $request->hours;
        $minutes = $request->minutes;
        $date = $request->date;

        $table = new Exam;
        $table->class_id = $class_id;
        $table->subject_id = $subject_id;
        $table->base_score = $base_score;
        $table->hours = $hours;
        $table->minutes = $minutes;
        $table->date = $date;
        $table->save();

        Session::put('exam_id', $table->id);

        return response()->json(['exam' => $table, 'message' => 'Exam created successfully']);
    }

    public function updateExam(Request $request, $id) {
        $base_score = $request->base_score;
        $hours = $request->hours;
        $minutes = $request->minutes;
        $date = $request->date;

        $table = Exam::find($id);
        $table->base_score = $base_score;
        $table->hours = $hours;
        $table->minutes = $minutes;
        $table->date = $date;
        $table->save();

        return response()->json(['exam' => $table, 'message' => 'Settings updated successfully']);
    }


    public function createExamFromTemplate(Request $request, $template_id) {

        $number = $request->number;

        DB::transaction(function() use($template_id,$number) {

            $questions = Question::where('exam_id', $template_id)->with('options')->inRandomOrder()->get();

            if ($number) $questions = $questions->take($number);

            foreach ($questions as $question) {
                $newQuestion = Question::find($question->id)->replicate();
                $newQuestion->exam_id = Session::get('exam_id');

                $newQuestion->save();

                foreach ($question->options as $option) {
                    $newOption = Option::find($option->id)->replicate();
                    $newOption->question_id = $newQuestion->id;

                    $newOption->save();
                }
            }
        });

        $exam = Exam::find(Session::get('exam_id'));

        return response()->json(['exam' => $exam, 'message' => 'Settings updated successfully']);
    }

}
