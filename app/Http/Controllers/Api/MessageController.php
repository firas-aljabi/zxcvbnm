<?php

namespace App\Http\Controllers\Api;

use App\ApiHelper\ApiResponseHelper;
use App\ApiHelper\Result;
use App\Events\PrivateMessageEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AdminResource;
use App\Http\Resources\PaginationResource;
use App\Models\Message;
use App\Models\User;
use App\Services\Admin\AdminService;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function __construct(private AdminService $adminService)
    {
    }

    public function getHrsList()
    {
        $data = $this->adminService->getHrsList();

        $returnData = AdminResource::collection($data);

        $pagination = PaginationResource::make($data);
        return ApiResponseHelper::sendResponseWithPagination(
            new Result($returnData, $pagination, "DONE")
        );
    }

    public function privateMessages(User $user)
    {
        $privateCommunication = Message::with('user')
            ->where(['user_id' => auth()->id(), 'receiver_id' => $user->id])
            ->orWhere(function ($query) use ($user) {
                $query->where(['user_id' => $user->id, 'receiver_id' => auth()->id()]);
            })
            ->get();

        // return $privateCommunication;
        return response(['messages' => $privateCommunication]);
    }

    public function sendPrivateMessage(Request $request, User $user)
    {
        $input = $request->all();
        $input['receiver_id'] = $user->id;
        $message = auth()->user()->messages()->create($input);

        broadcast(new PrivateMessageEvent($message->load('user')))->toOthers();

        return response(['status' => 'Message private sent successfully', 'message' => $message]);
    }
}
