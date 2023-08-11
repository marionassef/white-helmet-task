<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\Comment\GetCommentsRequest;
use App\Http\Resources\CommentResource;
use App\Http\Requests\Comment\CreateCommentRequest;
use App\Http\Requests\Comment\DetailsCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Http\Requests\Comment\DeleteCommentRequest;
use App\Helpers\CustomResponse;
use App\Http\Controllers\Controller;
use App\Exceptions\CustomQueryException;
use App\Services\CommentServiceInterface;
use Illuminate\Http\JsonResponse;

/**
 * Class CommentApiController
 * @package App\Http\Controllers\API
 */
class CommentApiController extends Controller
{
    /**
     * @var CommentServiceInterface
     */
    private CommentServiceInterface $commentService;

    public function __construct(CommentServiceInterface $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * @return JsonResponse
     * @throws CustomQueryException
     */
    public function list(GetCommentsRequest $request): JsonResponse
    {
        return CustomResponse::successResponse(__('success'), CommentResource::collection($this->commentService->list($request->validated())));
    }

    /**
     * @param CreateCommentRequest $request
     * @return JsonResponse
     * @throws CustomQueryException
     */
    public function store(CreateCommentRequest $request): JsonResponse
    {
       return CustomResponse::successResponse(__('success'), $this->commentService->store($request->validated()));
    }

    /**
     * @param DetailsCommentRequest $request
     * @return JsonResponse
     * @throws CustomQueryException
     */
    public function details(DetailsCommentRequest $request): JsonResponse
    {
        return CustomResponse::successResponse(__('success'), new CommentResource($this->commentService->getOneBy($request->validated())));
    }

    /**
     * @param UpdateCommentRequest $request
     * @return JsonResponse
     * @throws CustomQueryException
     */
    public function update(UpdateCommentRequest $request): JsonResponse
    {
        return CustomResponse::successResponse(__('success'), new CommentResource($this->commentService->update($request->validated())));
    }

    /**
     * @param DeleteCommentRequest $request
     * @return JsonResponse
     * @throws CustomQueryException
     */
    public function delete(DeleteCommentRequest $request): JsonResponse
    {
        $this->commentService->delete($request->validated());
        return CustomResponse::successResponse(__('success'));
    }
}
