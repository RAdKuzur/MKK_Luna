<?php

namespace App\Http\Controllers;

use App\Repositories\UserTokenRepository;
use App\Services\UserTokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TokenController extends Controller
{
    private UserTokenRepository $userTokenRepository;
    private UserTokenService $userTokenService;
    public function __construct(
        UserTokenRepository $userTokenRepository,
        UserTokenService $userTokenService
    ){
        $this->userTokenRepository = $userTokenRepository;
        $this->userTokenService = $userTokenService;
    }
    public function index(){
        return view('token/index');
    }
    public function create(Request $request){
        $username = $request->get('username');
        $password = $request->get('password');
        $userToken = $this->userTokenRepository->getByUsername($username);
        if ($userToken) {
            if (!Hash::check($password, $userToken->password)) {
                return response()->json([
                    'token' => 'Ошибка ввода пароля'
                ]);
            }
            return response()->json([
                'token' => $userToken->token
            ]);
        }
        else {
            $token = $this->userTokenService->createUserToken();
            $this->userTokenRepository->create($username,
                Hash::make($password),
                $token
            );
        }
        return response()->json([
            'token' => $token
        ]);
    }
}
