<?php

namespace backend\requests\auth;

use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use common\models\User;

/**
 * Password reset request form
 */
class PasswordResetLinkRequest extends Model
{
    public ?string $email = null;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'There is no user with this email address.'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return array whether the email was send
     * @throws \yii\db\Exception
     * @throws InternalErrorException
     * @throws Exception
     */
    public function sendEmail(array $data): array
    {
        if (!$this->load($data, '') || !$this->validate()) {
            return ['errors' => $this->errors];
        }

        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        $user->generatePasswordResetToken();
        $user->save();

        $status = Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Password reset for ' . Yii::$app->name)
            ->send();

        if (!$status) {
            throw new InternalErrorException('Unable to send email. Internal server error.', 500);
        }

        return ['message' => 'Password reset link was sent successfully.'];
    }
}
