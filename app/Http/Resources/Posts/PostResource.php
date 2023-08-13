<?php

namespace App\Http\Resources\Posts;

use App\Http\Resources\Admin\EmployeeResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'id' => $this->id,
            'content' => $this->content,
            'image' => $this->image,
            'comments_count' => $this->comments_count,
            'likes_count' => $this->likes_count,
            'shares_count' => $this->shares_count,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d') : null,
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'likes' => LikeResource::collection($this->whenLoaded('likes')),
            'shares' => ShareResource::collection($this->whenLoaded('shares')),
            'writer' => EmployeeResource::make($this->whenLoaded('user')),


        ];
    }
}
