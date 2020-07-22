<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Item;

use App\Http\Resources\ItemResource;
use App\Http\Resources\CommentResource;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ItemResource::collection(Item::paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Returns all comments assossiated with an item.
     * @param  int $parentId item id
     * @return [type]     [description]
     */
    public function listComments($parentId)
    {
        $comments = Item::where('parend_id', $parentId);

        return CommentResource::collection($comments);
    }

    public function storeComment(Request $request, $parentId)
    {
        $comment = Item::create([
            'user_id' => $request -> user_id,
            'parent_id' => $parentId,
            'type' => Item::TYPE_COMMENT,
            'title' => User::find($request -> user_id) -> name,
            'description' => $request -> text,
            'hidden' => false,
        ]);

        return response(
            new CommentResource($comment),
            Response::HTTP_CREATED
        );
    }
}
