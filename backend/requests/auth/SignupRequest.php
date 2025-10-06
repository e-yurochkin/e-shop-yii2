<?php

namespace backend\requests\auth;

use common\models\User;
use Yii;
use yii\base\Exception;
use yii\base\Model;

/**
 * Signup request
 */
class SignupRequest extends Model
{
    public ?string $username = null;
    public int $role = 3;
    public ?string $email = null;
    public ?string $password = null;


    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['role', 'in', 'range' => [2, 3]],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
        ];
    }

    /**
     * Signs user up.
     *
     * @return array whether the creating new account was successful and email was sent
     * @throws Exception
     * @throws \Exception
     */
    public function signup(array $data): array
    {
        if (!$this->load($data, '') || !$this->validate()) {
            return ['errors' => $this->errors];
        }

        $auth = Yii::$app->authManager;
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->save();
        $auth->assign($auth->getRole($this->role === 3 ? 'customer' : 'seller'), $user->id);
        $this->sendEmail($user);

        return [
            'message' => 'Successful signed up, check your email for further instructions.',
            'access token data' => [
                'access_token' => $user->getAuthKey(),
                'token_type' => 'Bearer',
                'expires_at' => date('d-m-Y H:m', $user->getAuthKeyExpireTimestamp()),
            ]
        ];
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be sent
     * @return bool whether the email was sent
     */
    protected function sendEmail(User $user): bool
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}
