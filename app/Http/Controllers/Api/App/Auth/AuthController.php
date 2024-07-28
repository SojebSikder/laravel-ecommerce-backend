<?php

namespace App\Http\Controllers\Api\App\Auth;

use App\Http\Controllers\Controller;
use App\Mail\RecoveryMail;
use App\Models\Auth\AuthProvider;
use App\Models\Ucode;
use App\Models\User;
use App\Notifications\VerifyEmail;
use App\Services\Newsletter\MailingListService;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['emailVerify', 'emailResend', 'sendMail', 'recover', 'login', 'register', 'redirectToSocialAuth', 'handleSocialAuthCallback']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function me()
    {
        try {
            $user = auth("api")->user();

            if ($user) {
                return response()->json([
                    'success' => true,
                    'data' => $user,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found',
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                // 'message' => $th->getMessage(),
                'message' => "Something went wrong :(",
            ]);
        }
    }

    public function updateUser(Request $request)
    {
        try {
            $request->validate([
                'fname' => 'string|max:255',
                'lname' => 'string|max:255',
                'email' => 'string|email|max:255',
            ]);
            $fname = $request->input('fname');
            $lname = $request->input('lname');
            $gender = $request->input('gender');
            $email = $request->input('email');
            $phone = $request->input('phone');
            // $birth = $request->input('birth');

            $user_id = auth("api")->user()->id;

            $user = User::where("id", $user_id)->first();
            if ($fname) {
                $user->fname = $fname;
            }
            if ($lname) {
                $user->lname = $lname;
            }
            if ($gender) {
                $user->gender = $gender;
            }
            if ($email) {
                $emailExist = User::where('id', "!=", $user_id)
                    ->where('email', $email)->exists();
                if ($emailExist) {
                    return response()->json([
                        'success' => false,
                        'message' => "The email has already been taken."
                    ]);
                } else {
                    $user->email = $email;
                }
            }
            if ($phone) {
                $user->phone = $phone;
            }
            // if ($birth) {
            //     $user->birth = $birth;
            // }
            // if ($gender) {
            //     $user->gender = $gender;
            // }
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Saved successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    // login with email
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);
            $credentials = $request->only('email', 'password');

            // check if email is exist or not
            $user_exist = User::where('email', $request->input('email'))->first();
            if (!$user_exist) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found',
                ], 401);
            }

            $token = Auth::guard('api')->attempt($credentials);
            if (!$token) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password not correct',
                ], 401);
            }

            $user = Auth::guard('api')->user();

            if ($user->status != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your account is not allowed',
                ], 401);
            }

            $user->last_login = now();
            $user->save();


            return response()->json([
                'success' => true,
                'message' => 'Logged in successfully',
                'data' => $user,
                'authorization' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    // register with email
    public function register(Request $request)
    {
        try {
            $customMessages = [
                'required' => 'The :attribute field is required.'
            ];
            $rules = [
                // 'name' => 'required|string|max:255',
                'fname' => 'required|string|max:255',
                'lname' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ];

            $this->validate($request, $rules, $customMessages);

            // request data
            $fname = $request->input('fname');
            $lname = $request->input('lname');
            $email = $request->input('email');
            $gender = $request->input('gender');
            $password = $request->input('password');
            // if true then add user email into mailinglist
            $mailing = $request->input('mailing');

            // insert into user
            $user = new User();
            $user->fname = $fname;
            $user->lname = $lname;
            $user->email = $email;
            $user->gender = $gender;
            $user->password = Hash::make($password);
            $user->save();

            // add into mailing list
            if ($mailing) {
                (new MailingListService())->store(email: $email, user_id: $user->id);
            }

            $token = Auth::guard('api')->login($user);

            // send email verification
            $user->notify(new VerifyEmail($user));
            // $user->sendEmailVerificationNotification();

            return response()->json([
                'success' => true,
                'action' => 'email_verify',
                'message' => 'Account created successfully. Please check your inbox to verify email',
                'user' => $user,
                'authorization' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function logout()
    {
        try {
            Auth::guard('api')->logout();
            return response()->json([
                'success' => true,
                'message' => 'Successfully logged out',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function refresh()
    {
        return response()->json([
            'success' => true,
            'data' => Auth::guard('api')->user(),
            'authorization' => [
                'token' => Auth::guard('api')->refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    /**
     * Check if user is logged
     */
    public function checkAuth(Request $request)
    {
        $user = Auth::guard('api')->user();
        if ($user) {
            if (!(auth("api")->user()->status == "allow")) {
                return response()->json(['logged_in' => false], 401);
            }
            return response()->json(['logged_in' => true], 200);
        }
        return response()->json(['logged_in' => false], 401);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    //----------------------- Password recovery by email -------------
    // send password recovery email
    public function sendMail(Request $request)
    {
        try {
            $code = uniqid("U", true);
            // $url = "http://" . $_SERVER["HTTP_HOST"] . "/forgot-password/$code";
            $url = env("CLIENT_APP_URL") . "/recover-password/$code";

            $email = $request->input('email');

            if ($email) {
                // Check if email is exist or not
                if (!User::where('email', $email)->exists()) {
                    return response()->json(['message' => 'Account not exist :(']);
                }

                // insert ucode to table
                $ucode = new Ucode();
                $ucode->token = $code;
                $ucode->email = $email;
                $ucode->for = 'password_recover';
                $ucode->save();
                //
                $data = new \stdClass();
                $data->token = $code;
                $data->url = $url;
                $data->sender = env("APP_NAME", 'Laravel');
                Mail::to($email)->send(new RecoveryMail($data));
                //
                return response()->json([
                    'success' => true,
                    'message' => 'Email sent. Please check your inbox in a minute'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Please enter Email'
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    // recover and change password
    public function recover(Request $request)
    {
        try {
            $code = $request->input('code');
            $password = $request->input('password');

            $request->validate([
                'password' => 'required|string|min:6',
            ]);

            // check if code exists
            if (Ucode::where('code', $code)->exists()) {
                if ($password) {
                    // save new password
                    $user = User::where('email', Ucode::where('code', $code)->first()->email)->first();
                    $user->password = bcrypt($password);
                    $user->save();
                    // delete code from database
                    $ucode = Ucode::where('code', $code)->first();
                    $ucode->delete();

                    return response()->json([
                        'success' => true,
                        'message' => 'Password changed successfully. Now you can login'
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Please enter your new password'
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'You\'re not able to proceed'
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
    // --------------------- end Password recovery by email --------

    //-------------------------Social auth----------------
    /**
     * Social auth:
     * google, facebook
     */
    public function redirectToSocialAuth($provider): JsonResponse
    {
        // $provider - google, facebook
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }

        return response()->json([
            'url' => Socialite::driver($provider)
                // ->with(['custom_redirect_url' => $custom_redirect_url])
                ->stateless()
                ->redirect()
                ->getTargetUrl(),
        ]);
    }

    public function handleSocialAuthCallback($provider): JsonResponse
    {
        // $provider - google, facebook
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }

        try {
            /** @var SocialiteUser $socialiteUser */
            $socialiteUser = Socialite::driver($provider)->stateless()->user();
        } catch (ClientException $e) {
            return response()->json(['error' => 'Invalid credentials provided.'], 422);
        }

        // $providerId = User::where('provider_uid', $socialiteUser->getId())->first();
        $providerId = User::whereRelation('auth_providers', 'provider_uid', "=", $socialiteUser->getId())->first();
        // $providerId = User::whereHas('auth_providers', function ($query) use ($socialiteUser) {
        //     return $query->where('provider_uid',  $socialiteUser->getId());
        // })->first();

        if ($providerId) {
            //// $updateUser = User::where('email', $socialiteUser->getEmail())->first();
            // $updateUser = User::where('provider_uid', $socialiteUser->getId())->first();
            $updateUser = User::whereRelation('auth_providers', 'provider_uid', "=", $socialiteUser->getId())->first();
            // $updateUser = User::whereHas('auth_providers', function ($query) use ($socialiteUser) {
            //     return $query->where('provider_uid',  $socialiteUser->getId());
            // })->first();
            //// $token = $updateUser->createToken('token')->plainTextToken;
            if ($updateUser) {
                if (!$updateUser->email_verified_at) {
                    $updateUser->email_verified_at = now();
                    $updateUser->save();
                }
            }
            $token = Auth::guard('api')->login($updateUser);
            $user = $updateUser;
        } else {

            if ($provider == "facebook") {
                $nameArr = explode(" ", $socialiteUser->getName());
                $fname = $nameArr[0];
                $lname = $nameArr[1];
            } else if ($provider == "microsoft" || $provider == "azure") {
                $nameArr = explode(" ", $socialiteUser->user['displayName']);
                $fname = $nameArr[0];
                $lname = $nameArr[1];
            } else if ($provider == "apple") {
                $nameArr = explode(" ", $socialiteUser->getName());
                $fname = $nameArr[0];
                $lname = $nameArr[1];
            } else if ($provider == "google") {
                $fname = $socialiteUser->user['given_name'];
                $lname = $socialiteUser->user['family_name'];
            }

            /** @var User $user */
            if ($socialiteUser->getEmail()) {
                //
                $user = User::where('email', $socialiteUser->getEmail())->first();
                if ($user) {
                    if (!$user->email_verified_at) {
                        $user->email_verified_at = now();
                        $user->save();
                    }
                } else {
                    // create new user
                    $user = new User();
                    $user->email = $socialiteUser->getEmail();
                    $user->email_verified_at = now();
                    $user->fname = $fname;
                    $user->lname = $lname;
                    $user->save();

                    $authProvider = new AuthProvider();
                    $authProvider->user_id = $user->id;
                    $authProvider->provider = $provider;
                    $authProvider->provider_uid = $socialiteUser->getId();
                    $authProvider->avatar = $socialiteUser->getAvatar();
                    $authProvider->save();

                    // add into mailing list
                    (new MailingListService())->store(email: $user->email, user_id: $user->id);
                }
                // $user = User::query()
                //     ->firstOrCreate(
                //         [
                //             'email' => $socialiteUser->getEmail(),
                //         ],
                //         [
                //             'email_verified_at' => now(),
                //             // 'name' => $socialiteUser->getName(),
                //             'fname' => $fname,
                //             'lname' => $lname,
                //             'provider' => $provider,
                //             'provider_uid' => $socialiteUser->getId(),
                //             'avatar' => $socialiteUser->getAvatar(),
                //         ]
                //     );
            } else {
                // create account without email
                $user = new User();
                $user->fname = $fname;
                $user->lname = $lname;
                $user->save();

                $authProvider = new AuthProvider();
                $authProvider->user_id = $user->id;
                $authProvider->provider = $provider;
                $authProvider->provider_uid = $socialiteUser->getId();
                $authProvider->avatar = $socialiteUser->getAvatar();
                $authProvider->save();
            }

            // $token = $user->createToken('token')->plainTextToken;
            $token = Auth::guard('api')->login($user);
        }

        if ($socialiteUser->getEmail()) {
            return response()->json([
                'success' => true,
                'message' => 'Logged in successfully',
                'data' => $user,
                'authorization' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
        } else {
            return response()->json([
                'success' => true,
                'action' => 'email_verify',
                'message' => 'Please verify email',
                'data' => $user,
                'authorization' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
        }
    }

    protected function validateProvider($provider)
    {
        if (!in_array($provider, ['facebook', 'google', 'microsoft', 'azure', 'apple'])) {
            return response()->json(['error' => 'Please login using facebook or google'], 422);
        }
    }
    //------------------------end Social auth ---------------


    // //----------------------- Phone verification ------------------
    // /**
    //  * Send phone verification otp sms
    //  */
    // public function send_phone_verification_code(Request $request)
    // {
    //     try {

    //         $data = $request->validate([
    //             // 'phone' => ['required', 'numeric', 'unique:users'],
    //             'phone' => ['required', 'numeric'],
    //             'dial_code' => ['required', 'string'],
    //         ]);
    //         /* Get credentials from .env */
    //         $token = getenv("TWILIO_AUTH_TOKEN");
    //         $twilio_sid = getenv("TWILIO_SID");
    //         $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
    //         $twilio = new Client($twilio_sid, $token);
    //         $twilio->verify->v2->services($twilio_verify_sid)
    //             ->verifications
    //             ->create($data['dial_code'] . $data['phone'], "sms");

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Sms sent with Otp.',
    //             'data' => [
    //                 'dial_code' => $data['dial_code'],
    //                 'phone' => $data['phone'],
    //             ],
    //         ]);
    //     } catch (\Throwable $th) {
    //         return response()->json([
    //             'success' => false,
    //             // 'message' => 'Please insert number with country code'
    //             'message' => $th->getMessage()
    //         ]);
    //     }
    // }

    // /**
    //  * Veify phone number
    //  */
    // public function verify_phone(Request $request)
    // {
    //     try {
    //         $data = $request->validate([
    //             'code' => ['required', 'numeric'],
    //             'phone' => ['required', 'string'],
    //             'dial_code' => ['required', 'string'],
    //         ]);
    //         /* Get credentials from .env */
    //         $token = getenv("TWILIO_AUTH_TOKEN");
    //         $twilio_sid = getenv("TWILIO_SID");
    //         $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
    //         $twilio = new Client($twilio_sid, $token);
    //         $verification = $twilio->verify->v2->services($twilio_verify_sid)
    //             ->verificationChecks
    //             ->create(['code' => $data['code'], 'to' => $data['dial_code'] . $data['phone']]);
    //         if ($verification->valid) {
    //             // $user = tap(User::where('phone', $data['phone']))->update(['phone_verified_at' => now()]);

    //             $user_id = auth("api")->user()->id;

    //             $user = User::where("id", $user_id)->first();
    //             $user->phone = $data['phone'];
    //             $user->dial_code = $data['dial_code'];
    //             $user->phone_verified_at = now();
    //             $user->save();
    //             // /* Authenticate user */
    //             // Auth::login($user->first());

    //             return response()->json([
    //                 'success' => true,
    //                 'data' => [
    //                     'email_verified_at' => $user->email_verified_at,
    //                 ],
    //                 'message' => 'Phone number verified',
    //             ]);
    //         }
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Invalid verification code entered!',
    //             'data' => [
    //                 'dial_code' => $data['dial_code'],
    //                 'phone' => $data['phone'],
    //             ],
    //         ]);
    //     } catch (\Throwable $th) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $th->getMessage(),
    //             'data' => [
    //                 'dial_code' => $data['dial_code'],
    //                 'phone' => $data['phone'],
    //             ],
    //         ]);
    //     }
    // }
    // //-----------------------end phone verification ----------------

    // ---------------------- email verification -------------------
    /**
     * Send phone verification otp sms
     */
    public function send_email_verification_code(Request $request)
    {
        try {

            $data = $request->validate([
                'email' => ['required'],
            ]);

            $token = uniqid('ex', true);

            // create token
            $ucode = new Ucode();
            $ucode->user_id = auth('api')->user()->id;
            $ucode->token = $token;
            $ucode->for = 'email_verify';
            $ucode->email = $data['email'];
            $ucode->save();

            // send to mail
            $dataObject = new \stdClass();
            $dataObject->email = $data['email'];
            $dataObject->user = auth('api')->user();
            $dataObject->token = $token;

            Notification::route('mail', $data['email'])
                ->notify(new VerifyEmail($dataObject));

            return response()->json([
                'success' => true,
                'message' => 'Email sent to your inbox. Please check.',
                'data' => [
                    'email' => $data['email'],
                ],
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                // 'message' => 'Please insert your email'
                'message' => $th->getMessage()
            ]);
        }
    }

    // public function emailVerify($id, $hash, Request $request)
    // {
    //     try {
    //         $email = Crypt::decryptString($request->input('email'));

    //         $validator = Validator::make(['email' => $email], [
    //             'email' => 'required|string|email|max:255|unique:users'
    //         ]);


    //         if (!$request->hasValidSignature()) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 "message" => "Invalid/Expired url provided."
    //             ], 401);
    //         }

    //         if (!$validator->fails()) {
    //             $user = User::findOrFail($id);
    //             $user->email = $email;
    //             $user->save();
    //             if (!$user->hasVerifiedEmail()) {
    //                 $user->markEmailAsVerified();
    //             }
    //             return redirect()->to(env('CLIENT_APP_URL') . '/thankyou?message=Email verified');
    //         } else {
    //             return redirect()->to(env('CLIENT_APP_URL') . '/thankyou?message=Email not verified');
    //         }
    //     } catch (\Throwable $th) {
    //         return redirect()->to(env('CLIENT_APP_URL') . '/thankyou?message=Something went wrong');
    //     }
    // }
    public function emailVerify($code, Request $request)
    {
        try {
            $ucode = Ucode::where('code', $code)->first();

            if ($ucode) {
                $user = User::findOrFail($ucode->user_id);
                $user->email =  $ucode->email;
                $user->save();
                if (!$user->hasVerifiedEmail()) {
                    $user->markEmailAsVerified();
                }
                $ucode->delete();
                return redirect()->to(env('CLIENT_APP_URL') . '/thankyou?message=Email verified');
            } else {
                return redirect()->to(env('CLIENT_APP_URL') . '/thankyou?message=Email not verified');
            }
        } catch (\Throwable $th) {
            return redirect()->to(env('CLIENT_APP_URL') . '/thankyou?message=Something went wrong');
        }
    }

    public function emailResend()
    {
        if (auth("api")->user()->hasVerifiedEmail()) {
            return response()->json([
                'success' => true,
                'message' => 'Email already verified.',
            ]);
        }
        auth("api")->user()->sendEmailVerificationNotification();

        return response()->json([
            'success' => true,
            'message' => 'Email verification link sent on your email id',
        ]);
    }
    // ---------------------- end email verification -------------------
}
