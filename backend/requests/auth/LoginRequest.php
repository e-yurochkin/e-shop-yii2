<?php

namespace backend\requests\auth;

use common\models\User;
use Yii;
use yii\base\Model;
use yii\db\Exception;

/**
 * Login request
 */
class LoginRequest extends Model
{
    public ?string $username = null;
    public ?string $password = null;
    private ?User $_user = null;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     */
    public function validatePassword(string $attribute): void
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if ((!$this->hasErrors() && !$user) || ($user && !$user->validatePassword($this->password))) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login(): bool
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser());
        }

        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser(): ?User
    {
        if ($this->_user !== null) {
            return $this->_user;
        }

        $user = User::findByUsername($this->username);

        if (!$user) {
            $inactiveUser = User::findOne(['username' => $this->username]);

            if ($inactiveUser && ($inactiveUser->status === User::STATUS_INACTIVE)) {
                $this->addError('email', 'Your should verify your email before logging in.');
            }

            return null;
        }

        $this->_user = $user;

        return $this->_user;
    }

    /**
     * @throws Exception
     * @throws \yii\base\Exception
     */
    public function authenticate(array $data): array
    {
        if ($this->load($data, '') && $this->login()) {
            $user = $this->getUser();
            $user->generateAuthKey();
            $user->save();

            return [
                'message' => 'Successful login',
                'access token data' => [
                    'access_token' => $user->getAuthKey(),
                    'token_type' => 'Bearer',
                    'expires_at' => date('d-m-Y H:m', $user->getAuthKeyExpireTimestamp()),
                ]
            ];
        }

        return ['errors' => $this->errors];
    }
}
