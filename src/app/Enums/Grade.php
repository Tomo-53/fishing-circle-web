<?php

namespace App\Enums;

/**
 * 所属学年を表す値オブジェクト。
 *
 * 許可値の定義をこのクラス1か所に集約することで、登録/更新バリデーション・
 * 表示ラベルの二重管理をなくし、定義外の値の混入を防ぐ。
 */
enum Grade: string
{
    case B1 = 'B1';
    case B2 = 'B2';
    case B3 = 'B3';
    case B4 = 'B4';
    case M1 = 'M1';
    case M2 = 'M2';
    case D1 = 'D1';
    case D2 = 'D2';
    case D3 = 'D3';
    case Other = 'OTHER';

    /**
     * 学年の表示ラベル
     */
    public function label(): string
    {
        return match ($this) {
            self::B1 => '学部1年',
            self::B2 => '学部2年',
            self::B3 => '学部3年',
            self::B4 => '学部4年',
            self::M1 => '修士1年',
            self::M2 => '修士2年',
            self::D1 => '博士1年',
            self::D2 => '博士2年',
            self::D3 => '博士3年',
            self::Other => 'その他',
        };
    }
}
