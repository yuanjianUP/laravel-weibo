<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TopicRequest;
use App\Http\Resources\TopicResource;
use Illuminate\Http\Request;
use App\Models\Topic;

class TopicsController extends Controller
{
    public function store(TopicRequest $request,Topic $topic){
        $topic->fill($request->all());
        $topic->user_id = $request->user()->id;
        $topic->save();
        return new TopicResource($topic);
    }

    public function update(TopicRequest $request,Topic $topic){
        $this->authorize('update',$topic);
        $topic->update($request->all());
        return new TopicResource($topic);
    }

    public function destroy(Topic $topic){
        $this->authorize('destroy',$topic);
        $topic->delete();
        return response(null,204);
    }

    public function index(Request $request,Topic $topic){
        $query =  $topic->query();
        $order = !empty($request->order) ? $request->order : "id";
        if($categoryId = $request->category_id){
            $query->where('category_id',$categoryId);
        }
        $topics = $query
            ->with(['user','category'])
            ->orderByDesc($order)
            ->paginate();
        return TopicResource::collection($topics);
    }
}
