<?php

namespace App\Http\Controllers;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Client as PassportClient;
use App\Models\User;
use Hash,Auth;

class CustomAuthController extends Controller
{
    private $passportClient;

    public function __construct(){
        $this->passportClient = PassportClient::find(2);
    }

    /**
     * Login and get access token
     * @param Request {$request}
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request) {
        $user = User::where(['email' => $request->email, 'is_deleted' => 0])->first();

        if ($user && Hash::check($request->password, $user->password) && $user->role == 'instructor') {
            return response()->json('fail', 401);
        }

        $data = [
            'grant_type' => 'password',
            'client_id' => $this->passportClient->id,
            'client_secret' => $this->passportClient->secret,
            'username' => $request->email,
            'password' => $request->password,
            'scope' => '*',
        ];
        $request = app('request')->create('/oauth/token', 'POST', $data);
        $response = app('router')->prepareResponse($request, app()->handle($request));
        return $response;
        $response = json_decode($response->getContent(), true);
        if(isset($response['access_token'])){
            return response()->json($response, 200);
        } else {
            return response()->json($response, 500);
        }
    }

    /**
     * Allow to register a user
     */
    public function register(RegisterRequest $request){

        $user = new User();
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        //$user->avatar_url = config('constant.default_files.avatar');
        $user->role = config('constant.roles.student');
        $user->company_id = $request->company;
        $user->save();


        $data = [
            'grant_type' => 'password',
            'client_id' => $this->passportClient->id,
            'client_secret' => $this->passportClient->secret,
            'username' => $request->email,
            'password' => $request->password,
            'scope' => '*',
        ];
        $request = app('request')->create('/oauth/token', 'POST', $data);
        $response = app('router')->prepareResponse($request, app()->handle($request));
        $token_data = json_decode($response->getContent(), true);
        return response()->json(['user'=>$user,'token_data'=>$token_data]);
    }
}
