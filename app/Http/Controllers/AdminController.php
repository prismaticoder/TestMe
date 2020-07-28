<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subject;
use App\Classes;
use App\Mark;
use App\Question;
use App\Option;
use App\User;
use Gate;
use DB;
use Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

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

        $classes = Classes::get();
        $exams = [];

        if(Gate::denies('superAdminGate')){
            //Only return the subject of that user
            $subjects = Subject::where('id', Auth::user()->subject_id)->get();
            $all_started = Mark::where('subject_id', Auth::user()->subject_id)->where('hasStarted', 1)->get();
        }

        else {
            $subjects = Subject::orderBy('subject_name')->get();
            //get all started exams
            $all_started = Mark::where('hasStarted', 1)->get();
        }

        if (count($all_started) > 0) {
            foreach ($all_started as $exam) {
                array_push($exams, ['id' => $exam->id, 'subject' => $exam->subject, 'class' => $exam->class]);
            }
        }

        foreach ($classes as $class) {
            $params_array = [];
            foreach ($subjects as $subject) {
                $params_array[$subject->subject_name] = $class->checkParams($subject->id);
            }
            $class->examsWithParamsSet = $params_array;
        }

        $exams = json_encode($exams);

        return view('admin.dashboard',compact('subjects','classes', 'exams'));
    }

    public function getClassStudents($class) {
        $class = Classes::where('class',$class)->first();

        if ($class) {
            $students = $class->students()->withTrashed()->get();

            return view('admin.class-students', compact('students','class'));
        }

        return abort('404','Page does not exist');
    }

    public function updateStudent(Request $request,$id) {
        $student = User::find($id);

        $firstname = $request->firstname;
        $lastname = $request->lastname;

        $student->firstname = $firstname;
        $student->lastname = $lastname;
        $student->save();

        return response()->json('Details Saved Successfully');

    }

    public function deleteStudent($id) {
        $student = User::find($id);
        $student->delete();

        return response()->json('Deletion Successful!');
    }

    public function restoreStudent($id) {
        $student = User::onlyTrashed()->where('id',$id);
        $student->restore();

        return response()->json('The selected student has succesfully been restored!');
    }

    public function addStudent(Request $request) {
        $firstname = $request->firstname;
        $lastname = $request->lastname;
        $class_id = $request->class_id;

        $check = User::where('firstname',$firstname)->where('lastname',$lastname)->where('class_id',$class_id)->first();

        if (empty($check)) {
            $student = new User;
            $student->firstname = $firstname;
            $student->lastname = $lastname;
            $student->class_id = $class_id;
            $student->code = rand(10000,50000);
            $student->save();
            $res = "Student Added Successfully!";
        }
        else {
            $res = "This Student Already Exists!";
        }

        return response()->json($res);
    }

    public function getAllQuestions($subject,$class_id) {
        $subject = Subject::where('alias',$subject)->first();
        $subject_id = $subject['id'];
        $classes = Classes::all();

        if ($subject) {

            if (Gate::allows('view-subject-details', $subject)) {
                $mark = Mark::where('subject_id',$subject->id)->where('class_id',$class_id)->get();

                $questions = Question::where('class_id',$class_id)->where('subject_id',$subject_id)->with('options')->get();
                // $options = Question::where('class_id',$class_id)->where('subject_id',$subject_id)->options;
                Session::put('subject_id', $subject_id);
                Session::put('class_id', $class_id);

                return view('admin.questions', compact('questions','subject','class_id','classes','mark'));
            }

            return abort('404','Page does not exist');
        }

        return abort('404','Page does not exist');
    }

    public function addQuestion(Request $request) {
        $subject_id = $request->subject_id;
        $class_id = $request->class_id;
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
        DB::transaction(function() use($class_id,$subject_id,$question,$options,$correctAnswer, &$createdQuestion) {

            $createdQuestion->subject_id = $subject_id;
            $createdQuestion->class_id = $class_id;
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

    public function getSingleResult($subject,$class_id) {
        $subject = Subject::where('alias',$subject)->first();

        if ($subject) {
            if (Gate::allows('view-subject-details', $subject)) {
                $students = User::where('class_id',$class_id)->get();
                $selected_class = Classes::find($class_id);
                $mark = (Mark::where('subject_id',$subject->id)->where('class_id',$class_id)->first())?Mark::where('subject_id',$subject->id)->where('class_id',$class_id)->first()->mark:50;
                $classes = Classes::get();
                return view('admin.main-result',compact('students','subject','selected_class','classes','mark'));
            }

            return  abort('404');
        }

        return abort('404');
    }

    public function hostExam($subject) {
        $subject = Subject::where('alias',$subject)->first();
        $marks = Mark::where('subject_id',$subject->id)->orderBy('class_id')->get();


        if ($subject) {
            $subject->isHosted = 1;
            $subject->save();

            return view('admin.host-exam',compact('subject','marks'));
        }

        return abort('404');
    }

    public function startExam(Request $request) {
        $subject_id = $request->subject_id;
        $class_id = $request->class_id;

        $mark = Mark::where('subject_id',$subject_id)->where('class_id', $class_id)->first();


        if ($mark) {
            $subject = Subject::where('id',$subject_id)->first();

            if (Gate::allows('view-subject-details', $subject)) {
                $mark->hasStarted = 1;
                $mark->save();

                return response()->json(['exam' => $mark]) ;
            }

            return abort('403');
        }

        return abort('404');
    }

    public function endExam(Request $request) {
        $subject_id = $request->subject_id;
        $class_id = $request->class_id;

        $mark = Mark::where('subject_id',$subject_id)->where('class_id', $class_id)->first();


        if ($mark) {
            $subject = Subject::where('id',$subject_id)->first();

            if (Gate::allows('view-subject-details', $subject)) {
                $mark->hasStarted = 0;
                $mark->save();

                return response()->json(['exam' => $mark]) ;
            }

            return abort(403);
        }

        return abort('404');
    }

    public function setMark(Request $request) {
        $subject_id = $request->subject_id;
        $class_id = $request->class_id;
        $mark = $request->mark;
        $hours = $request->hours;
        $minutes = $request->minutes;

        $table = new Mark;
        $table->class_id = $class_id;
        $table->subject_id = $subject_id;
        $table->mark = $mark;
        $table->hours = $hours;
        $table->minutes = $minutes;
        $table->save();

        return response()->json(['params' => $table, 'message' => 'Details added successfully']);
    }

    public function updateMark(Request $request, $id) {
        // $id = $request->id;
        $mark = $request->mark;
        $hours = $request->hours;
        $minutes = $request->minutes;

        $table = Mark::find($id);
        $table->mark = $mark;
        $table->hours = $hours;
        $table->minutes = $minutes;
        $table->save();

        return response()->json(['params' => $table, 'message' => 'Update successful']);
    }

}
