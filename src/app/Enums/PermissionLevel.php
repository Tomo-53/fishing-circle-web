<?php

namespace App\Enums;

/**
 * グループ内の権限レベルを表す値オブジェクト（4段階ACL）。
 *
 * 「ただの int」ではなく型として表現することで、定義外の値（0 や 99 など）の
 * 混入を防ぎ、レベルの比較・ラベル化ロジックをこのクラス1か所に凝集させる。
 */
enum PermissionLevel: int
{
    case Pending = 1;   // 認証待機（申請中）
    case Member = 2;    // 一般メンバー
    case Admin = 3;     // 幹部・管理者
    case Owner = 4;     // グループオーナー

    /**
     * 正式名称ラベル（権限の説明やエラーメッセージで使用）
     */
    public function label(): string
    {
        return match ($this) {
            self::Pending => '認証待機',
            self::Member => '一般メンバー',
            self::Admin => '管理者・幹部',
            self::Owner => 'グループオーナー',
        };
    }

    /**
     * 一覧表示などで使う短いラベル
     */
    public function shortLabel(): string
    {
        return match ($this) {
            self::Pending => '承認待ち',
            self::Member => 'メンバー',
            self::Admin => '管理者',
            self::Owner => 'オーナー',
        };
    }

    /**
     * 指定したレベル以上の権限を持つか
     */
    public function atLeast(self $other): bool
    {
        return $this->value >= $other->value;
    }

    /**
     * オーナーか
     */
    public function isOwner(): bool
    {
        return $this === self::Owner;
    }

    /**
     * 管理者（レベル3ちょうど）か
     */
    public function isAdmin(): bool
    {
        return $this === self::Admin;
    }

    /**
     * 管理者以上（メンバー管理権限を持つ）か
     */
    public function hasAdminPermission(): bool
    {
        return $this->atLeast(self::Admin);
    }

    /**
     * この権限の保持者が $target を除名できるか。
     * オーナーは全員除名可能、管理者は一般メンバー以下のみ除名可能。
     */
    public function canRemove(self $target): bool
    {
        if ($this->isOwner()) {
            return true;
        }

        return $this->isAdmin() && ! $target->atLeast(self::Admin);
    }
}
