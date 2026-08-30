<?php

namespace App\Casts;

use App\ValueObjects\GroupName;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * groups.name カラムを GroupName 値オブジェクトに変換する Eloquent キャスト。
 *
 * 取得時は GroupName を返し、設定時は GroupName のコンストラクタで
 * 妥当性を検証したうえで文字列として保存する。
 *
 * @implements CastsAttributes<GroupName, GroupName|string>
 */
class GroupNameCast implements CastsAttributes
{
    /**
     * DB の値を GroupName 値オブジェクトへ変換する。
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?GroupName
    {
        if ($value === null) {
            return null;
        }

        return new GroupName((string) $value);
    }

    /**
     * GroupName または文字列を受け取り、検証済みの文字列として保存する。
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): array
    {
        if ($value === null) {
            return [$key => null];
        }

        $groupName = $value instanceof GroupName ? $value : new GroupName((string) $value);

        return [$key => $groupName->value];
    }
}
