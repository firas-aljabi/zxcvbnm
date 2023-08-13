<?php

namespace App\Http\Controllers\Api;

use App\ApiHelper\ApiResponseHelper;
use App\ApiHelper\ErrorResult;
use App\ApiHelper\Result;
use App\ApiHelper\SuccessResult;
use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\CreateCommentRequest;
use App\Http\Requests\Posts\CreateLikeCommentRequest;
use App\Http\Requests\Posts\CreateLikeRequest;
use App\Http\Requests\Posts\CreatePostRequest;
use App\Http\Requests\Posts\CreateSharePostRequest;
use App\Http\Resources\PaginationResource;
use App\Http\Resources\Posts\CommentResource;
use App\Http\Resources\Posts\LikeResource;
use App\Http\Resources\Posts\PostResource;
use App\Http\Resources\Posts\ShareResource;
use App\Services\Posts\PostService;

class PostController extends Controller
{
    public function __construct(private PostService $postService)
    {
    }


    public function store(CreatePostRequest $request)
    {
        $createdData =  $this->postService->create_post($request->validated());

        $returnData = PostResource::make($createdData);

        return ApiResponseHelper::sendResponse(
            new Result($returnData, "Done")
        );
    }



    public function getPostsList()
    {
        $data = $this->postService->getPosts();

        $returnData = PostResource::collection($data);


        $pagination = PaginationResource::make($data);
        return ApiResponseHelper::sendResponseWithPagination(
            new Result($returnData, $pagination, "DONE")
        );
    }

    public function getMyPosts()
    {
        $data = $this->postService->getMyPosts();

        $returnData = PostResource::collection($data);

        $pagination = PaginationResource::make($data);
        return ApiResponseHelper::sendResponseWithPagination(
            new Result($returnData, $pagination, "DONE")
        );
    }

    public function addComment(CreateCommentRequest $request)
    {

        $createdData =  $this->postService->create_comment($request->validated());

        $returnData = CommentResource::make($createdData);

        return ApiResponseHelper::sendResponse(
            new Result($returnData, "Done")
        );
    }


    public function addLike(CreateLikeRequest $request)
    {
        $createdData =  $this->postService->add_like($request->validated());

        if ($createdData == null) {
            return response()->json(["message" => "Like Deleted..!"]);
        } else {
            $returnData = LikeResource::make($createdData);
        }

        return ApiResponseHelper::sendResponse(
            new Result($returnData, "Done")
        );
    }

    public function add_like_comment(CreateLikeCommentRequest $request)
    {
        $createdData =  $this->postService->add_like_comment($request->validated());

        if ($createdData == null) {
            return response()->json(["message" => "Like Deleted..!"]);
        } else {
            $returnData = LikeResource::make($createdData);
        }

        return ApiResponseHelper::sendResponse(
            new Result($returnData, "Done")
        );
    }

    public function sharePost(CreateSharePostRequest $request)
    {
        $createdData =  $this->postService->share_post($request->validated());

        if ($createdData == null) {
            return response()->json(["message" => "Share Deleted..!"]);
        } else {
            $returnData = ShareResource::make($createdData);
        }


        return ApiResponseHelper::sendResponse(
            new Result($returnData, "Done")
        );
    }

    public function destroyPost($id)
    {
        $deletionResult = $this->postService->DeletePost($id);

        if (is_string($deletionResult)) {
            return ApiResponseHelper::sendErrorResponse(
                new ErrorResult($deletionResult)
            );
        }

        return ApiResponseHelper::sendSuccessResponse(
            new SuccessResult("Done", $deletionResult)
        );
    }

    public function destroyComment($id)
    {
        $deletionResult = $this->postService->deleteComment($id);

        if (is_string($deletionResult)) {
            return ApiResponseHelper::sendErrorResponse(
                new ErrorResult($deletionResult)
            );
        }

        return ApiResponseHelper::sendSuccessResponse(
            new SuccessResult("Done", $deletionResult)
        );
    }
}
