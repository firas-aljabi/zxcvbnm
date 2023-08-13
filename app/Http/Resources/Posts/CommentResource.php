<?php

namespace App\Http\Resources\Posts;


use App\Http\Resources\Admin\EmployeeResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'content' => $this->content,
            'likes_count' => $this->likes_count,
            'user' => $this->whenLoaded('user', function () {
                return [
                    'name' => $this->user->name,
                    'image' => $this->user->image,
                ];
            }),

            'likes' => LikeResource::collection($this->whenLoaded('likes')),
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d') : null
        ];
    }
}
