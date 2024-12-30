<?php

namespace app\models\enums;

enum PostStatusEnum: int
{
    case ACTIVE = 1;
    case INACTIVE = 0;

    /**
     * @return array
     */
    public static function getList(): array
    {
        return [
            self::ACTIVE->value => 'Активен',
            self::INACTIVE->value => 'Неактивен',
        ];
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::ACTIVE => 'Активен',
            self::INACTIVE => 'Неактивен',
        };
    }

    /**
     * @return array
     */
    public static function values(): array
    {
        return array_map(fn(self $status) => $status->value, self::cases());
    }
}