<?php

namespace common\models;

use backend\models\Role;
use backend\models\Seller;
use common\RateLimiter\IpRateLimitInterface;
use Yii;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property integer $role_id
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property string $expires_at
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface, IpRateLimitInterface
{
    const int STATUS_DELETED = 0;
    const int STATUS_INACTIVE = 9;
    const int STATUS_ACTIVE = 10;

    /**
     * @var string IP of the user
     */
    private string $ip;

    /**
     * @var integer maximum number of allowed requests
     */
    private int $rateLimit;

    /**
     * @var integer time period for the rates to apply to
     */
    private int $timePeriod;


    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id): User|IdentityInterface|null
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null): ?IdentityInterface
    {
        $user = User::findOne(['auth_key' => $token]);

        if ($user && $user->getAuthKeyExpireTimestamp() < time()) {
            return null;
        }

        return $user;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername(string $username): ?User
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken(string $token): ?User
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken(string $token): ?User
    {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid(string $token): bool
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey(): ?string
    {
        return $this->auth_key;
    }

    public function getAuthKeyExpireTimestamp(): ?string
    {
        return $this->expires_at;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     * @throws Exception
     */
    public function setPassword(string $password): void
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     * @throws Exception
     */
    public function generateAuthKey(): void
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
        $this->expires_at = time() + Yii::$app->params['user.authTokenExpire'];
    }

    /**
     * Generates new password reset token
     * @throws Exception
     */
    public function generatePasswordResetToken(): void
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     * @throws Exception
     */
    public function generateEmailVerificationToken(): void
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken(): void
    {
        $this->password_reset_token = null;
    }

    /**
     * @inheritdoc
     */
    public static function findByIp(string $ip, int $rateLimit, int $timePeriod): static
    {
        $user = new static();

        $user->ip = $ip;
        $user->rateLimit = $rateLimit;
        $user->timePeriod = $timePeriod;

        return $user;
    }

    public function setRateLimit(int $rateLimit, int $timePeriod): User
    {
        $this->rateLimit = $rateLimit > 0 ? $rateLimit : 1;
        $this->timePeriod = $timePeriod > 0 ? $timePeriod : 1;
        $this->ip = '';

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getRateLimit($request, $action): array
    {
        return [$this->rateLimit, $this->timePeriod];
    }

    /**
     * @inheritdoc
     */
    public function loadAllowance($request, $action): array
    {
        $cache = Yii::$app->getCache();

        return [
            $cache->get('user.ratelimit.ip.allowance.' . $this->ip),
            $cache->get('user.ratelimit.ip.allowance_updated_at.' . $this->ip),
        ];
    }

    /**
     * @inheritdoc
     */
    public function saveAllowance($request, $action, $allowance, $timestamp): void
    {
        $cache = Yii::$app->getCache();

        $cache->set('user.ratelimit.ip.allowance.' . $this->ip, $allowance);
        $cache->set('user.ratelimit.ip.allowance_updated_at.' . $this->ip, $timestamp);
    }

    public function getSeller(): ActiveQuery
    {
        return $this->hasOne(Seller::class, ['user_id' => 'id']);
    }

    public function getRole(): ActiveQuery
    {
        return $this->hasOne(Role::class, ['id' => 'role_id']);
    }
}
