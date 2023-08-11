<?php

namespace App\Services;

use App\Exceptions\CustomValidationException;
use App\Models\User;
use App\Repositories\AuthorRepositoryInterface;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

class AuthorService implements AuthorServiceInterface
{
    private AuthorRepositoryInterface $authorRepository;

    public function __construct(AuthorRepositoryInterface $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    /**
     * @param array|null $data
     * @return mixed
     */
    public function list(array $data = null): mixed
    {
        return $this->authorRepository->findAll([]);
    }

    /**
     * @param $data
     * @return mixed
     * @throws CustomValidationException
     */
    public function store($data): mixed
    {
        $password = $data['password'];
        $data['password'] = Hash::make($data['password']);
        $author = $this->authorRepository->create($data);

        //Generate token
        $tokens = $this->generateToken($author->email, $password);
        $author['access_token'] = $tokens->access_token;
        $author['refresh_token'] = $tokens->refresh_token;
        return $author;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getOneBy($id): mixed
    {

        return $this->authorRepository->findOneBy(['id' => $id]);
    }

    /**
     * @param array $data
     * @return User
     */
    public function update(array $data): User
    {
        $item = $this->authorRepository->findOneBy(['id' => $data['id']]);
        $this->authorRepository->update($item, $data);
        return $item;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id): mixed
    {
        return $this->authorRepository->delete($id);
    }

    /**
     * @param $data
     * @return mixed
     * @throws CustomValidationException
     * @throws AuthenticationException
     */
    public function login($data): mixed
    {
        $password = $data['password'];
        if(Auth::attempt(['email' => $data['email'], 'password' => $data['password']])){
            $user = $this->authorRepository->findOneBy(['email' => $data['email']]);
            //Generate token
            $tokens = $this->generateToken($user->email, $password);
            $user['access_token'] = $tokens->access_token;
            $user['refresh_token'] = $tokens->refresh_token;
            return $user;
        }
        else{
            throw new AuthenticationException('Unauthenticated credentials');
        }
    }

    /**
     * @param $phoneNumber
     * @param $password
     * @return mixed
     * @throws CustomValidationException
     */
    private function generateToken($email, $password): mixed
    {
        request()->request->add([
            'grant_type' => 'password',
            'client_id' => env('PASSPORT_GRANT_PASSWORD_CLIENT_ID'),
            'client_secret' => env('PASSPORT_GRANT_PASSWORD_CLIENT_SECRET'),
            'username' => $email,
            'password' => $password,
            'scope' => '*',
        ]);
        $tokenRequest = request()->create('oauth/token', 'POST', request()->all());
        $tokens = json_decode(Route::dispatch($tokenRequest)->getContent());
        if (!isset($tokens->access_token)) {
            throw new CustomValidationException('Unauthenticated Client credentials');
        }
        return $tokens;
    }
}
