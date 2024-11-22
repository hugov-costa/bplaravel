<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\AttemptToAuthenticate;
use Laravel\Fortify\Actions\CanonicalizeUsername;
use Laravel\Fortify\Actions\EnsureLoginIsNotThrottled;
use Laravel\Fortify\Actions\PrepareAuthenticatedSession;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Contracts\PasswordUpdateResponse;
use Laravel\Fortify\Contracts\ProfileInformationUpdatedResponse;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Fortify;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class FortifyServiceProvider extends ServiceProvider
{
    private function loginResponse(): void
    {
        $this->app->instance(LoginResponse::class, new class implements LoginResponse
        {
            public function toResponse($request): Response
            {
                if (PersonalAccessToken::findToken($request->bearerToken())?->tokenable_id && $request->user()?->id) {
                    return response()->json([
                        'message' => 'You are already logged in.',
                    ], 409);
                }

                $user = User::where('email', $request->email)->first();

                return response()->json([
                    'message' => 'Successfully logged in.',
                    'token' => $user->createToken($request->email)->plainTextToken,
                ], 200);
            }
        });
    }

    private function logoutResponse(): void
    {
        $this->app->instance(LogoutResponse::class, new class implements LogoutResponse
        {
            public function toResponse($request): Response
            {
                $request->user()->currentAccessToken()->delete();

                return response()->json([
                    'message' => 'Successfully logged out.',
                ], 200);
            }
        });
    }

    private function registerResponse(): void
    {
        $this->app->instance(RegisterResponse::class, new class implements RegisterResponse
        {
            public function toResponse($request): Response
            {
                $user = User::where('email', $request->email)->first();

                return response()->json([
                    'message' => 'Registration successful. Please verify your email address.',
                    'token' => $user->createToken($request->email)->plainTextToken,
                ], 200);
            }
        });
    }

    private function profileInformationUpdatedResponse(): void
    {
        $this->app
            ->instance(ProfileInformationUpdatedResponse::class, new class implements ProfileInformationUpdatedResponse
            {
                public function toResponse($request): Response
                {
                    if (Hash::check($request->password, $request->user()?->password)) {
                        return response()->json([
                            'message' => 'Profile information successfully updated.',
                        ], 200);
                    }

                    return response()->json([
                        'message' => 'The provided password does not match your current password.',
                    ], 422);
                }
            });
    }

    private function updatePasswordResponse(): void
    {
        $this->app->instance(PasswordUpdateResponse::class, new class implements PasswordUpdateResponse
        {
            public function toResponse($request): Response
            {
                return response()->json([
                    'message' => 'Password successfully updated.',
                ], 200);
            }
        });
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->loginResponse();
        $this->logoutResponse();
        $this->profileInformationUpdatedResponse();
        $this->registerResponse();
        $this->updatePasswordResponse();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(6)
                ->by(Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip()))
                ->response(function () {
                    return response()->json([
                        'message' => 'Too many login attempts.',
                    ], 429);
                });
        });

        Fortify::authenticateThrough(function () {
            return array_filter([
                config('fortify.limiters.login') ? null : EnsureLoginIsNotThrottled::class,
                config('fortify.lowercase_usernames') ? CanonicalizeUsername::class : null,
                AttemptToAuthenticate::class,
                PrepareAuthenticatedSession::class,
            ]);
        });
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
    }
}
