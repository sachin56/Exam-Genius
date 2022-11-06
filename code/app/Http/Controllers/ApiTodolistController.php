<?php

namespace App\Http\Controllers;
use App\Models\TodoList;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ApiTodolistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        $result = TodoList::all();

        return response()->json($result);
    }

    public function create(){
        $result = TodoList::all();

        return response()->json($result);
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'name'=>'required',
            'description' => 'required',
            'end_date'=>'required'
        ]);

        if($validator->fails()){
            return response()->json(['validation_error' => $validator->errors()->all()]);
        }else{
            try{
                $todolist = new TodoList;
                $todolist->name = $request->name;
                $todolist->priority = $request->priority;
                $todolist->end_date = $request->end_date;
                $todolist->description = $request->description;


                $todolist->save();

                DB::commit();
                return response()->json(['db_success' => 'Added New Todo']);

            }catch(\Throwable $th){
                DB::rollback();
                throw $th;
                return response()->json(['db_error' =>'Database Error'.$th]);
            }

        }
    }

    public function show($id){

        $result = TodoList::find($id);

        return response()->json($result);

    }

    public function update(Request $request ){

        $validator = Validator::make($request->all(), [
            'name'=>'required',
            'description' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['validation_error' => $validator->errors()->all()]);
        }else{
            try{
                DB::beginTransaction();

                $todolist = TodoList::find($request->id);
                $todolist->name = $request->name;
                $todolist->description = $request->description;
                $todolist->priority = $request->priority;
                $todolist->end_date = $request->end_date;

                $todolist->save();

                DB::commit();
                return response()->json(['db_success' => 'Todo Updated']);

            }catch(\Throwable $th){
                DB::rollback();
                throw $th;
                return response()->json(['db_error' =>'Database Error'.$th]);
            }

        }

    }
    public function destroy($id){

        $result = TodoList::destroy($id);

        return response()->json($result);

    }
}
