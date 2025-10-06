<?php

namespace backend\requests\auth;

use common\models\User;
use Yii;
use yii\base\Exception;
use yii\base\InvalidArgumentException;
use yii\base\Model;

/**
 * Password reset form
 */
class ResetPasswordRequest extends Model
{
    public ?string $password = null;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
        ];
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @param array $data
     * @return array
     * @throws Exception
     * @throws \yii\db\Exception
     */
    public function resetPassword(string $token, array $data): array
    {
        if (!$this->load($data, '') || !$this->validate()) {
            return ['errors' => $this->errors];
        }

        if (!$token) {
            throw new InvalidArgumentException('Password reset token cannot be blank.');
        }

        $user = User::findByPasswordResetToken($token);

        if (!$user) {
            throw new InvalidArgumentException('Wrong password reset token.');
        }

        $user->setPassword($this->password);
        $user->removePasswordResetToken();
        $user->generateAuthKey();
        $user->save(false);

        return ['message' => 'Password successfully changed.'];
    }
}
