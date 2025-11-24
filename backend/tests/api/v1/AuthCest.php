<?php

namespace backend\tests\api\v1;

use backend\controllers\api\v1\AuthController;
use backend\tests\ApiTester;
use Codeception\Exception\ModuleException;
use common\fixtures\UserFixture;
use common\models\User;
use Yii;
use yii\db\Exception;

/**
 * Class LoginCest
 */
class AuthCest
{
    public function _before(): void
    {
        AuthController::setRateLimit(100);
        AuthController::setTimePeriod(1);
    }

    /**
     * Load fixtures before db transaction begin
     * Called in _before()
     * @return array
     * @see \Codeception\Module\Yii2::loadFixtures()
     * @see \Codeception\Module\Yii2::_before()
     */
    public function _fixtures(): array
    {
        return [
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user_data.php'
            ],
        ];
    }

    private function getExpectedSuccessLoginResponseJson(User $user): array
    {
        return [
            'message' => 'Successful login',
            'access token data' => [
                'access_token' => $user->getAuthKey(),
                'token_type' => 'Bearer',
                'expires_at' => date('d-m-Y H:m', $user->getAuthKeyExpireTimestamp()),
            ]
        ];
    }

    private function getExpectedFailureLoginResponseJson(): array
    {
        return [
            'errors' => [
                'password' => [
                    'Incorrect username or password.'
                ]
            ]
        ];
    }

    private function getExpectedResponseJsonWhenPostRequestHasEmptyBody(): array
    {
        return [
            'name' => 'Bad Request',
            'message' => 'Request body can not be empty',
            'code' => 0,
            'status' => 400,
            'type' => 'yii\\web\\BadRequestHttpException'
        ];
    }

    private function getExpectedSuccessLogoutResponseJson(): array
    {
        return ['message' => 'You are logged out successfully'];
    }

    private function getExpectedUnauthorizedResponseJson(): array
    {
        return [
            'name' => 'Unauthorized',
            'message' => 'Your request was made with invalid credentials.',
            'code' => 0,
            'status' => 401,
            'type' => 'yii\\web\\UnauthorizedHttpException'
        ];
    }

    private function getExpectedSuccessSignupResponseJson(): array
    {
        return ['message' => 'Successful signed up, check your email for further instructions.'];
    }

    private function getExpectedFailureSignupResponseJson(bool $username, bool $email, bool $password): array
    {
        $result = ['errors' => []];

        if (!$username) {
            $result['errors']['username'][] = 'Username cannot be blank.';
        }

        if (!$email) {
            $result['errors']['email'][] = 'Email cannot be blank.';
        }

        if (!$password) {
            $result['errors']['password'][] = 'Password cannot be blank.';
        }

        return $result;
    }

    private function getExpectedSuccessRegisterSellerDataResponseJson(): array
    {
        return ['message' => 'Successful registered seller data.'];
    }

    private function getExpectedFailureRegisterSellerDataResponseJson(
        bool $username,
        bool $shopName,
        bool $legalAddress,
        bool $physicalAddress,
        bool $inn,
        bool $ogrn,
    ): array
    {
        $result = ['errors' => []];

        if (!$username) {
            $result['errors']['username'][] = 'Username cannot be blank.';
        }

        if (!$shopName) {
            $result['errors']['shop_name'][] = 'Shop Name cannot be blank.';
        }

        if (!$legalAddress) {
            $result['errors']['legal_address'][] = 'Legal Address cannot be blank.';
        }

        if (!$physicalAddress) {
            $result['errors']['physical_address'][] = 'Physical Address cannot be blank.';
        }

        if (!$inn) {
            $result['errors']['inn'][] = 'Inn cannot be blank.';
        }

        if (!$ogrn) {
            $result['errors']['ogrn'][] = 'Ogrn cannot be blank.';
        }

        return $result;
    }

    private function getExpectedRegisterSellerAlreadyExistsDataResponseJson(): array
    {
        return [
            'errors' => [
                'shop_name' => ['This shop name has already been taken.'],
                'legal_address' => ['This legal address has already been taken.'],
                'physical_address' => ['This physical address has already been taken.'],
                'inn' => ['This inn has already been taken.'],
                'ogrn' => ['This inn has already been taken.']
            ]
        ];
    }

    private function getExpectedSuccessVerifyEmailResponseJson(): array
    {
        return ['message' => 'Your email has been verified successfully.'];
    }

    private function getExpectedFailureVerifyEmailResponseJson(): array
    {
        return [
            'name' => 'Bad Request',
            'message' => 'Wrong verify email token.',
            'code' => 0,
            'status' => 400,
            'type' => 'yii\\web\\BadRequestHttpException'
        ];
    }

    private function getExpectedAlreadyVerifyEmailResponseJson(): array
    {
        return ['message' => 'Your email is already verified.'];
    }

    private function getExpectedVerifyEmailResponseJsonIfSellerDataAreMissing(): array
    {
        return ['message' => 'Your should register detail seller data before email verification.'];
    }

    private function getExpectedSuccessSendVerificationEmailLinkResponseJson(): array
    {
        return ['message' => 'Verification link sent to your email address.'];
    }

    private function getExpectedSendVerificationEmailLinkResponseJsonIfInvalidEmail(): array
    {
        return [
            'errors' => [
                'email' => ['There is no user with this email address.']
            ]
        ];
    }

    private function getExpectedSendVerificationEmailLinkResponseJsonIfAlreadyVerified(): array
    {
        return ['message' => 'Your email already verified.'];
    }

    private function getExpectedSendVerificationEmailLinkResponseJsonIfAccountWasDeleted(): array
    {
        return ['message' => 'Your account has been deleted.'];
    }

    private function getExpectedSuccessPasswordResetLinkEmailResponseJson(): array
    {
        return ['message' => 'Password reset link was sent successfully.'];
    }

    private function getExpectedFailurePasswordResetLinkEmailResponseJson(): array
    {
        return [
            'errors' => [
                'email' => ['There is no user with this email address.']
            ]
        ];
    }

    private function getExpectedSuccessPasswordResetResponseJson(): array
    {
        return ['message' => 'Password successfully changed.'];
    }

    private function getExpectedFailurePasswordResetResponseJson(): array
    {
        return [
            'name' => 'Bad Request',
            'message' => 'Wrong password reset token.',
            'code' => 0,
            'status' => 400,
            'type' => 'yii\\web\\BadRequestHttpException'
        ];
    }

    private function getExpectedExcessRateLimitResponseJson(): array
    {
        return [
            'name' => 'Too Many Requests',
            'message' => 'Rate limit exceeded.',
            'code' => 0,
            'status' => 429,
            'type' => 'yii\\web\\TooManyRequestsHttpException'
        ];
    }

    public function userCanLoginWithValidCredentials(ApiTester $I): void
    {
        $user = User::findOne(1);
        $I->sendPost('/api/v1/login', ['username' => $user->username, 'password' => 'password_0']);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $user = Yii::$app->user->identity;
        $I->seeResponseContainsJson($this->getExpectedSuccessLoginResponseJson($user));
        $I->assertEquals(false, Yii::$app->user->isGuest);

    }

    public function userCanNotLoginWithInvalidCredentials(ApiTester $I): void
    {
        $user = User::findOne(1);
        $I->sendPost('/api/v1/login', ['username' => $user->username, 'password' => 'wrong_password']);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($this->getExpectedFailureLoginResponseJson());
        $I->assertEquals(true, Yii::$app->user->isGuest);
    }

    public function userCanNotLoginWhenRequestHasEmptyBody(ApiTester $I): void
    {
        $I->sendPost('/api/v1/login');
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($this->getExpectedResponseJsonWhenPostRequestHasEmptyBody());
        $I->assertEquals(true, Yii::$app->user->isGuest);
    }

    public function authorizedUserCanLogout(ApiTester $I): void
    {
        $user = User::findOne(1);
        $I->amBearerAuthenticated($user->getAuthKey());
        $I->sendPost('/api/v1/logout');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->assertEquals(true, Yii::$app->user->isGuest);
        $I->seeResponseContainsJson($this->getExpectedSuccessLogoutResponseJson());
    }

    public function unauthorizedUserCanNotLogout(ApiTester $I): void
    {
        $I->sendPost('/api/v1/logout');
        $I->seeResponseCodeIs(401);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($this->getExpectedUnauthorizedResponseJson());
    }

    /**
     * @throws ModuleException
     */
    public function userCanSignupWithValidData(ApiTester $I): void
    {
        $I->sendPost('/api/v1/signup', [
            'username' => 'new_testuser',
            'password' => 'password_0',
            'email' => 'new_testuser@gmail.com',
        ]);
        $I->seeResponseCodeIs(201);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($this->getExpectedSuccessSignupResponseJson());
        $I->seeEmailIsSent(1);
    }

    public function userCanNotSignupWithInvalidData(ApiTester $I): void
    {
        $I->sendPost('/api/v1/signup', [
            'username' => 'new_testuser',
        ]);
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($this->getExpectedFailureSignupResponseJson(true, false, false));

        $I->sendPost('/api/v1/signup', [
            'password' => 'password_0',
            'email' => 'new_testuser@gmail.com',
        ]);
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($this->getExpectedFailureSignupResponseJson(false, true, true));
    }

    public function userCanNotSignupWhenRequestHasEmptyBody(ApiTester $I): void
    {
        $I->sendPost('/api/v1/signup');
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($this->getExpectedResponseJsonWhenPostRequestHasEmptyBody());
    }

    public function userCanRegisterSellerValidData(ApiTester $I): void
    {
        $user = User::findOne(2);
        $I->sendPost('api/v1/auth/register-seller-data', [
            'username' => $user->username,
            'shop_name' => 'test shop',
            'legal_address' => 'test legal address',
            'physical_address' => 'test physical address',
            'inn' => '74686110',
            'ogrn' => '445144792',
        ]);
        $I->seeResponseCodeIs(201);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($this->getExpectedSuccessRegisterSellerDataResponseJson());
    }

    public function userCanNotRegisterSellerAlreadyExistsData(ApiTester $I): void
    {
        $user = User::findOne(2);

        $I->sendPost('api/v1/auth/register-seller-data', [
            'username' => $user->username,
            'shop_name' => 'test shop',
            'legal_address' => 'test legal address',
            'physical_address' => 'test physical address',
            'inn' => '74686110',
            'ogrn' => '445144792',
        ]);

        $I->sendPost('api/v1/auth/register-seller-data', [
            'username' => $user->username,
            'shop_name' => 'test shop',
            'legal_address' => 'test legal address',
            'physical_address' => 'test physical address',
            'inn' => '74686110',
            'ogrn' => '445144792',
        ]);
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($this->getExpectedRegisterSellerAlreadyExistsDataResponseJson());
    }

    public function userCanNotRegisterSellerInvalidData(ApiTester $I): void
    {
        $user = User::findOne(2);

        $I->sendPost('api/v1/auth/register-seller-data', [
            'shop_name' => 'test shop',
            'legal_address' => 'test legal address',
            'physical_address' => 'test physical address',
            'inn' => '74686110',
            'ogrn' => '445144792',
        ]);
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($this->getExpectedFailureRegisterSellerDataResponseJson(
            false,
            true,
            true,
            true,
            true,
            true,
        ));

        $I->sendPost('api/v1/auth/register-seller-data', [
            'username' => $user->username,
        ]);
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($this->getExpectedFailureRegisterSellerDataResponseJson(
            true,
            false,
            false,
            false,
            false,
            false,
        ));
    }

    public function userCanNotRegisterSellerDataWhenRequestHasEmptyBody(ApiTester $I): void
    {
        $I->sendPost('api/v1/auth/register-seller-data');
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($this->getExpectedResponseJsonWhenPostRequestHasEmptyBody());
    }

    /**
     * @throws Exception
     */
    public function userCanVerifyEmailWithValidToken(ApiTester $I): void
    {
        $user = User::findOne(1);
        $user->status = User::STATUS_INACTIVE;
        $user->save();

        $url = 'api/v1/auth/verify-email?token=' . $user->verification_token;
        $I->sendGet($url);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($this->getExpectedSuccessVerifyEmailResponseJson());
        $user->refresh();
        $I->assertEquals(User::STATUS_ACTIVE, $user->status);
    }

    /**
     * @throws Exception
     */
    public function userCanNotVerifyEmailWithInvalidToken(ApiTester $I): void
    {
        $user = User::findOne(1);
        $user->status = User::STATUS_INACTIVE;
        $user->save();

        $I->sendGet('api/v1/auth/verify-email?token=invalid_token');
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($this->getExpectedFailureVerifyEmailResponseJson());
        $user->refresh();
        $I->assertEquals(User::STATUS_INACTIVE, $user->status);
    }

    public function userCanNotVerifyAlreadyVerifiedEmail(ApiTester $I): void
    {
        $user = User::findOne(1);
        $I->assertEquals(User::STATUS_ACTIVE, $user->status);
        $url = 'api/v1/auth/verify-email?token=' . $user->verification_token;
        $I->sendGet($url);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($this->getExpectedAlreadyVerifyEmailResponseJson());
    }

    public function sellerCanNotVerifyEmailIfSellerDataAreMissing(ApiTester $I): void
    {
        $user = User::findOne(2);
        $url = 'api/v1/auth/verify-email?token=' . $user->verification_token;
        $I->sendGet($url);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($this->getExpectedVerifyEmailResponseJsonIfSellerDataAreMissing());
        $user->refresh();
        $I->assertEquals(User::STATUS_INACTIVE, $user->status);
    }

    public function sellerCanVerifyEmailIfSellerDataAreRegistered(ApiTester $I): void
    {
        $user = User::findOne(2);
        $I->sendPost('api/v1/auth/register-seller-data', [
            'username' => $user->username,
            'shop_name' => 'test shop',
            'legal_address' => 'test legal address',
            'physical_address' => 'test physical address',
            'inn' => '74686110',
            'ogrn' => '445144792',
        ]);

        $url = 'api/v1/auth/verify-email?token=' . $user->verification_token;
        $I->sendGet($url);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($this->getExpectedSuccessVerifyEmailResponseJson());
        $user->refresh();
        $I->assertEquals(User::STATUS_ACTIVE, $user->status);
    }

    /**
     * @throws Exception
     * @throws ModuleException
     */
    public function userCanGetVerificationEmailWithValidEmail(ApiTester $I): void
    {
        $user = User::findOne(1);
        $currentToken = $user->verification_token;
        $user->status = User::STATUS_INACTIVE;
        $user->save();

        $I->sendPost('api/v1/resend-verification-email', ['email' => $user->email]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($this->getExpectedSuccessSendVerificationEmailLinkResponseJson());
        $I->seeEmailIsSent(1);
        $user->refresh();
        $I->assertNotEquals($currentToken, $user->verification_token);
    }

    /**
     * @throws ModuleException
     */
    public function userCanNotGetVerificationEmailWithInvalidEmail(ApiTester $I): void
    {
        $I->sendPost('api/v1/resend-verification-email', ['email' => 'invalid-email@gmail.com']);
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($this->getExpectedSendVerificationEmailLinkResponseJsonIfInvalidEmail());
        $I->seeEmailIsSent(0);
    }

    /**
     * @throws ModuleException
     */
    public function userCanNotGetVerificationEmailIfAlreadyVerified(ApiTester $I): void
    {
        $user = User::findOne(1);
        $I->sendPost('api/v1/resend-verification-email', ['email' => $user->email]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($this->getExpectedSendVerificationEmailLinkResponseJsonIfAlreadyVerified());
        $I->seeEmailIsSent(0);
    }

    /**
     * @throws Exception
     * @throws ModuleException
     */
    public function userCanNotGetVerificationEmailIfAccountWasDeleted(ApiTester $I): void
    {
        $user = User::findOne(1);
        $user->status = User::STATUS_DELETED;
        $user->save();

        $I->sendPost('api/v1/resend-verification-email', ['email' => $user->email]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($this->getExpectedSendVerificationEmailLinkResponseJsonIfAccountWasDeleted());
        $I->seeEmailIsSent(0);
    }

    /**
     * @throws ModuleException
     */
    public function userCanNotGetVerificationEmailWhenRequestHasEmptyBody(ApiTester $I): void
    {
        $I->sendPost('api/v1/resend-verification-email');
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($this->getExpectedResponseJsonWhenPostRequestHasEmptyBody());
        $I->seeEmailIsSent(0);
    }

    /**
     * @throws ModuleException
     */
    public function userCanGetPasswordResetLinkEmailWithValidEmail(ApiTester $I): void
    {
        $user = User::findOne(1);
        $I->sendPost('api/v1/request-password-reset', ['email' => $user->email]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($this->getExpectedSuccessPasswordResetLinkEmailResponseJson());
        $I->seeEmailIsSent(1);
    }

    /**
     * @throws ModuleException
     */
    public function userCanNotGetPasswordResetLinkEmailWithInvalidEmail(ApiTester $I): void
    {
        $I->sendPost('api/v1/request-password-reset', ['email' => 'invalid-email@gmail.com']);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($this->getExpectedFailurePasswordResetLinkEmailResponseJson());
        $I->seeEmailIsSent(0);
    }

    /**
     * @throws ModuleException
     */
    public function userCanNotGetPasswordResetLinkEmailWhenRequestHasEmptyBody(ApiTester $I): void
    {
        $I->sendPost('api/v1/request-password-reset');
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($this->getExpectedResponseJsonWhenPostRequestHasEmptyBody());
        $I->seeEmailIsSent(0);
    }

    /**
     * @throws Exception
     * @throws \yii\base\Exception
     */
    public function userCanResetPasswordWithValidToken(ApiTester $I): void
    {
        $newPassword = 'new-password';
        $user = User::findOne(1);
        $user->generatePasswordResetToken();
        $user->save();

        $url = 'api/v1/reset-password?token=' . $user->password_reset_token;
        $I->sendPost($url, ['password' => $newPassword]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($this->getExpectedSuccessPasswordResetResponseJson());
        $user->refresh();
        $I->assertEquals(true, $user->validatePassword($newPassword));
    }

    public function userCanNotResetPasswordWithInvalidToken(ApiTester $I): void
    {
        $I->sendPost('api/v1/reset-password?token=invalid_token', ['password' => 'new-password']);
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($this->getExpectedFailurePasswordResetResponseJson());
    }

    /**
     * @throws Exception
     * @throws \yii\base\Exception
     */
    public function userCanNotResetPasswordWhenRequestHasEmptyBody(ApiTester $I): void
    {
        $newPassword = 'new-password';
        $user = User::findOne(1);
        $user->generatePasswordResetToken();
        $user->save();

        $I->sendPost('api/v1/reset-password?token=' . $user->password_reset_token);
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($this->getExpectedResponseJsonWhenPostRequestHasEmptyBody());
        $user->refresh();
        $I->assertEquals(false, $user->validatePassword($newPassword));
    }

    public function rateLimiterIsWorkCorrectly(ApiTester $I): void
    {
        AuthController::setRateLimit(5);

        for ($i = 0; $i < 10; $i++) {
            $I->sendGet('api/v1/auth/verify-email');
        }

        $I->seeResponseCodeIs(429);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($this->getExpectedExcessRateLimitResponseJson());
    }
}
