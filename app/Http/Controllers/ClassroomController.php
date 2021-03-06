<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Teacher;
use App\Student;
use App\Classroom;
use App\Registration;

class ClassroomController extends Controller
{
    
    public function classesWithTeacherLogged() {
        // $teacher = Auth::user();

        $classes = Classroom::where('teacher_id', Auth::user()->id)->with(['student'])->get();
        return view('classrooms.index-teacher', compact(['classes']));
    }
    
    public function loadJsonClassesWithTeacherLogged($id) {
        // $teacher = Auth::user();
        $classes = Classroom::where('teacher_id', $id)->get();
        return json_encode($classes);
        //return view('classrooms.index-teacher', compact(['classes']));
    }

    public function store(Request $request)
    {
        $teacher = Teacher::findOrFail($request->input('teacher_id'));

        if(isset($teacher)) {
            $solicitation = new Classroom();
            $solicitation->name = $request->input('name');
            $solicitation->teacher()->associate($teacher);
            $solicitation->save();

            return json_encode($solicitation);
        }
        return response('Professor nao encontrado, impossível cadastrar uma turma', 404);
    }

    public function show($id)
    {
        $classroom = Classroom::where(function ($query) use($id) {
                $query->where('id', '=', $id);
            })->with(['student'])->first();

        if(isset($classroom)) {
            return json_encode($classroom);
        }
        return response('Turma nao encontrada', 404);
    }

    public function update(Request $request, $id){
        $classroom = Classroom::where(
            function ($query) use($id) {
                $query->where('id', '=', $id);
            }
        )->with(['student'])->first();

        if(isset($classroom)) {
            $classroom->name = $request->input('name');
            foreach ($classroom->student as $student) {
                $regis = Registration::where(
                    function($query) use($student, $classroom) {
                        $query->where('student_id', '=', $student->id);
                        $query->where('classroom_id', '=', $classroom->id);
                    }
                );

                $regis->delete();
            }

            $students = $request->input('students');
            if(isset($students)) {
                foreach ($students as $student) {
                    $stu = Student::findOrFail($student);
    
                    if(isset($stu)) {
                        $registration = new Registration();
                        $registration->student()->associate($stu);
                        $registration->classroom()->associate($classroom);
                        $registration->save();
                    }
                }
            }

            $classroom->save();

            $classroomUpdated = Classroom::where(
                function ($query) use($id) {
                    $query->where('id', '=', $id);
                }
            )->with(['student'])->first();

            return json_encode($classroomUpdated);
        }
        return response('Turma nao encontrada', 404);
    }

    public function destroy($id)
    {
        $classroom = Classroom::findOrFail($id);
        if(isset($classroom)) {
            $classroom->delete();

            return json_encode($classroom);
        }

        return response('Turma nao encontrada', 404);
    }
}








