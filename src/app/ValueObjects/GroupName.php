<?php

namespace App\ValueObjects;

use InvalidArgumentException;
use Stringable;

/**
 * グループ名を表す値オブジェクト。
 *
 * コンストラクタで不変条件（1〜50文字）を検証することで、
 * 不正な名前を持つインスタンスが生成されることを根本から防ぐ（完全コンストラクタ）。
 */
final class GroupName implements Stringable
{
    /** グループ名の最大文字数 */
    public const MAX_LENGTH = 50;

    public readonly string $value;

    public function __construct(string $value)
    {
        $trimmed = trim($value);
        $length = mb_strlen($trimmed);

        if ($length < 1) {
            throw new InvalidArgumentException('グループ名を入力してください。');
        }

        if ($length > self::MAX_LENGTH) {
            throw new InvalidArgumentException('グループ名は'.self::MAX_LENGTH.'文字以内で入力してください。');
        }

        $this->value = $trimmed;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
