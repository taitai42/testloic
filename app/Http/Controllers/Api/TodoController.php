<?php

namespace App\Http\Controllers\Api;

use App\Todo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

/**
 * Class TodoController
 * @package App\Http\Controllers\Api
 */
class TodoController extends Controller
{
    /**
     * 404 error message
     */
    const ERROR_404_MESSAGE = 'this resource doesn\'t exist';

    /**
     * Get a todo by status
     * @param $status
     *
     * @return \Illuminate\Http\JsonResponse
     * @return an array of todo or an empty array
     *
     */
    public function getByStatus($status)
    {
        $todos = Todo::where('done', $status)->get();

        return response()->json($todos);
    }

    /**
     * get a todo by id
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse of Todo or empty array
     */
    public function show($id)
    {
        $todo = Todo::find($id);

        return response()->json($todo);
    }

    /**
     * create a todo ressource and insert it in database
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse of the created todo
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'done' => 'required|in:0,1,true,false'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $todo = Todo::create($request->all());

        return response()->json($todo, 201);
    }

    /**
     * update a todo instance by it's id
     * @param         $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse of updated todo
     */
    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'done' => 'in:0,1,true,false'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $todo = Todo::find($id);

        if (!$todo) {
            return response()->json(self::ERROR_404_MESSAGE, 404);
        }

        $todo->fill($request->all());
        $todo->save();

        return response()->json($todo, 200);
    }

    /**
     * delete a record of todo in the database by it's id
     * @param         $id
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response 204 response if it worked
     */
    public function delete($id, Request $request)
    {
        $todo = Todo::find($id);

        if (!$todo) {
            return response()->json(self::ERROR_404_MESSAGE, 404);
        }

        $todo->delete();

        return response('', 204);
    }


}
