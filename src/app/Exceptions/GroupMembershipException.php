<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * グループ所属・権限に関する業務ルール違反を表す例外。
 *
 * コントローラは getMessage() をそのままユーザー向けエラーとして表示できる。
 */
final class GroupMembershipException extends RuntimeException {}
