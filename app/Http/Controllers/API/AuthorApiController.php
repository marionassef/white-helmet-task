<?php

namespace App\Http\Controllers\API;

use App\Exceptions\CustomQueryException;
use App\Exceptions\CustomValidationException;
use App\Http\Requests\Author\CreateAuthorRequest;
use App\Http\Requests\Author\DetailsAuthorRequest;
use App\Http\Requests\Author\LoginRequest;
use App\Http\Requests\Author\UpdateAuthorRequest;
use App\Http\Requests\Author\DeleteAuthorRequest;
use App\Http\Resources\AuthorResource;
use App\Helpers\CustomResponse;
use App\Http\Controllers\Controller;
use App\Services\AuthorServiceInterface;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;

/**
 * Class AuthorApiController
 * @package App\Http\Controllers\API
 */
class AuthorApiController extends Controller
{
    private object $authorService;

    public function __construct(AuthorServiceInterface $authorService)
    {
        $this->authorService = $authorService;
    }

    /**
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        return CustomResponse::successResponse(__('success'), AuthorResource::collection($this->authorService->list()));
    }

    /**
     * @param CreateAuthorRequest $request
     * @return JsonResponse
     */
    public function store(CreateAuthorRequest $request): JsonResponse
    {
        return CustomResponse::successResponse(__('success'), new AuthorResource($this->authorService->store($request->validated())));
    }

    /**
     * @param DetailsAuthorRequest $request
     * @return JsonResponse
     */
    public function details(DetailsAuthorRequest $request): JsonResponse
    {
        return CustomResponse::successResponse(__('success'), new AuthorResource($this->authorService->getOneBy($request->validated()['id'])));
    }

    /**
     * @param UpdateAuthorRequest $request
     * @return JsonResponse
     */
    public function update(UpdateAuthorRequest $request): JsonResponse
    {
        return CustomResponse::successResponse(__('success'), new AuthorResource($this->authorService->update($request->validated())));
    }

    /**
     * @param DeleteAuthorRequest $request
     * @return JsonResponse
     */
    public function delete(DeleteAuthorRequest $request): JsonResponse
    {
        $this->authorService->delete($request->validated()['id']);
        return CustomResponse::successResponse(__('success'));
    }

    /**
     * @throws AuthenticationException
     * @throws CustomValidationException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        return CustomResponse::successResponse(__('success'),
            new AuthorResource($this->authorService->login($request->validated())));
    }

}
