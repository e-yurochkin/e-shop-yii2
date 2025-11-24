<?php

declare(strict_types=1);

namespace backend\controllers\api\v1;

use backend\requests\auth\LoginRequest;
use backend\requests\auth\PasswordResetLinkRequest;
use backend\requests\auth\RegisterSellerDataRequest;
use backend\requests\auth\ResendVerificationEmailRequest;
use backend\requests\auth\ResetPasswordRequest;
use backend\requests\auth\SignupRequest;
use backend\services\auth\AuthService;
use common\RateLimiter\IpRateLimiter;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Yii;
use yii\base\Module;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\Request;
use yii\web\Response;

/**
 * Auth controller
 */
class AuthController extends Controller
{
    private static int $rateLimit = 2;
    private static int $timePeriod = 1;

    public function __construct(
        string                       $id,
        Module                       $module,
        private readonly AuthService $authService,
        array                        $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->setDependency();
    }

    public static function setRateLimit(int $rateLimit): void
    {
        if (YII_ENV_TEST) {
            self::$rateLimit = $rateLimit;
        }
    }

    public static function setTimePeriod(int $timePeriod): void
    {
        if (YII_ENV_TEST) {
            self::$timePeriod = $timePeriod;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON
                ]
            ],
            'authenticator' => [
                'class' => HttpBearerAuth::class,
                'except' => [
                    'login',
                    'error',
                    'signup',
                    'register-seller-data',
                    'resend-verification-email',
                    'verify-email',
                    'request-password-reset',
                    'reset-password',
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'denyCallback' => function ($rule, $action) {
                    return $this->asJson(['error' => 'You are not allowed to access this page.']);
                },
                'rules' => [
                    [
                        'actions' => [
                            'login',
                            'error',
                            'signup',
                            'register-seller-data',
                            'resend-verification-email',
                            'verify-email',
                            'request-password-reset',
                            'reset-password',
                        ],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                    'login' => ['post'],
                    'signup' => ['post'],
                    'register-seller-data' => ['post'],
                    'resend-verification-email' => ['post'],
                    'verify-email' => ['get'],
                    'request-password-reset' => ['post'],
                    'reset-password' => ['post'],
                ],
            ],
            'rateLimiter' => [
                'class' => IpRateLimiter::class,
                'rateLimit' => self::$rateLimit,
                'timePeriod' => self::$timePeriod,
                'enableRateLimitHeaders' => false,
            ],
        ];
    }

    private function setDependency(): void
    {
        Yii::$container->set('backend\requests\auth\LoginRequest');
        Yii::$container->set('backend\requests\auth\SignupRequest');
        Yii::$container->set('backend\requests\auth\ResendVerificationEmailRequest');
        Yii::$container->set('backend\requests\auth\PasswordResetLinkRequest');
        Yii::$container->set('backend\requests\auth\ResetPasswordRequest');
        Yii::$container->set('backend\requests\auth\RegisterSellerDataRequest');
    }

    /**
     * Return current user.
     *
     * @return Response
     */
    public function actionIndex(): Response
    {
        AuthController::setRateLimit(100);
        AuthController::setTimePeriod(1);

        return $this->asJson([
            'rateLimit' => self::$rateLimit,
            'timePeriod' => self::$timePeriod,
        ]);

//        return $this->asJson(Yii::$app->user->identity);
    }

    /**
     * Login action.
     *
     * @param Request $request
     * @param LoginRequest $loginRequest
     * @return Response
     * @throws BadRequestHttpException
     * @throws Exception
     * @throws \yii\base\Exception
     */
    public function actionLogin(Request $request, LoginRequest $loginRequest): Response
    {
        if ($this->authService->isAuthByAuthorizationHeader($request)) {
            return $this->asJson(['message' => 'You are already logged in']);
        }

        $data = $this->authService->getPostData($request);

        return $this->asJson($loginRequest->authenticate($data));
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout(): Response
    {
        $user = Yii::$app->user->identity;
        $user->generateAuthKey();
        $user->save();
        Yii::$app->user->logout();

        return $this->asJson(['message' => 'You are logged out successfully']);
    }

    /**
     * Signs user up.
     *
     * @param Request $request
     * @param SignupRequest $signupRequest
     * @return Response
     * @throws BadRequestHttpException
     * @throws \yii\base\Exception
     */
    public function actionSignup(Request $request, SignupRequest $signupRequest): Response
    {
        $data = $this->authService->getPostData($request);

        return $this->asJson($signupRequest->signup($data));
    }

    /**
     * @throws \yii\base\Exception
     * @throws BadRequestHttpException
     */
    public function actionRegisterSellerData(
        Request                   $request,
        RegisterSellerDataRequest $registerSellerInfoRequest
    ): Response
    {
        $data = $this->authService->getPostData($request);

        return $this->asJson($registerSellerInfoRequest->register($data));
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @return yii\web\Response
     * @throws Exception
     * @throws BadRequestHttpException
     */
    public function actionVerifyEmail(string $token): Response
    {
        return $this->asJson($this->authService->verifyEmail($token));
    }

    /**
     * Resend verification email
     *
     * @param Request $request
     * @param ResendVerificationEmailRequest $resendVerificationEmailRequest
     * @return Response
     * @throws BadRequestHttpException
     * @throws \yii\base\Exception
     */
    public function actionResendVerificationEmail(
        Request                        $request,
        ResendVerificationEmailRequest $resendVerificationEmailRequest
    ): Response
    {
        $data = $this->authService->getPostData($request);

        return $this->asJson($resendVerificationEmailRequest->sendEmail($data));
    }

    /**
     * Requests password reset.
     *
     * @param Request $request
     * @param PasswordResetLinkRequest $requestPasswordResetRequest
     * @return Response
     * @throws BadRequestHttpException
     * @throws Exception
     * @throws InternalErrorException
     * @throws \yii\base\Exception
     */
    public function actionRequestPasswordReset(
        Request                  $request,
        PasswordResetLinkRequest $requestPasswordResetRequest
    ): Response
    {
        $data = $this->authService->getPostData($request);

        return $this->asJson($requestPasswordResetRequest->sendEmail($data));
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @param Request $request
     * @param ResetPasswordRequest $resetPasswordRequest
     * @return Response
     * @throws BadRequestHttpException
     * @throws Exception
     * @throws \yii\base\Exception
     */
    public function actionResetPassword(
        string               $token,
        Request              $request,
        ResetPasswordRequest $resetPasswordRequest
    ): Response
    {
        $data = $this->authService->getPostData($request);

        return $this->asJson($resetPasswordRequest->resetPassword($token, $data));
    }
}
