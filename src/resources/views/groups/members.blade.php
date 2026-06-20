<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                メンバー管理 - {{ $group->name }}
            </h2>
            <a href="{{ route('groups.show', $group) }}"
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                グループに戻る
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- 成功メッセージ -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- エラーメッセージ -->
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <!-- メンバー統計 -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="text-3xl font-bold text-green-600">{{ $approvedMembers->count() }}</div>
                        <div class="text-sm text-gray-600">承認済みメンバー</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="text-3xl font-bold text-yellow-600">{{ $pendingMembers->count() }}</div>
                        <div class="text-sm text-gray-600">承認待ちメンバー</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="text-3xl font-bold text-blue-600">{{ $approvedMembers->count() + $pendingMembers->count() }}</div>
                        <div class="text-sm text-gray-600">総メンバー数</div>
                    </div>
                </div>
            </div>

            <!-- 承認待ちメンバー -->
            @if($pendingMembers->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4 text-yellow-600">
                            🕐 承認待ちメンバー ({{ $pendingMembers->count() }}名)
                        </h3>
                        <div class="space-y-4">
                            @foreach($pendingMembers as $member)
                                <div class="border border-yellow-200 rounded-lg p-4 bg-yellow-50">
                                    <div class="flex justify-between items-center">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3">
                                                <div>
                                                    <h4 class="font-medium text-gray-900">{{ $member->name }}</h4>
                                                    <p class="text-sm text-gray-600">{{ $member->email }}</p>
                                                    @if($member->grade)
                                                        <p class="text-xs text-gray-500">{{ $member->grade->label() }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-xs text-gray-500">
                                                申請日: {{ $member->pivot->created_at->format('Y/m/d') }}
                                            </span>
                                            <form method="POST" action="{{ route('groups.approve-member', [$group, $member]) }}" class="inline">
                                                @csrf
                                                <button type="submit"
                                                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-sm"
                                                        onclick="return confirm('{{ $member->name }}さんを承認しますか？')">
                                                    承認
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('groups.remove-member', [$group, $member]) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm"
                                                        onclick="return confirm('{{ $member->name }}さんの申請を拒否しますか？')">
                                                    拒否
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- 承認済みメンバー -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4 text-green-600">
                        ✅ 承認済みメンバー ({{ $approvedMembers->count() }}名)
                    </h3>

                    @if($approvedMembers->count() > 0)
                        <div class="space-y-4">
                            @foreach($approvedMembers as $member)
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex justify-between items-center">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3">
                                                <div>
                                                    <h4 class="font-medium text-gray-900">{{ $member->name }}</h4>
                                                    <p class="text-sm text-gray-600">{{ $member->email }}</p>
                                                    @if($member->grade)
                                                        <p class="text-xs text-gray-500">{{ $member->grade->label() }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <!-- 権限レベル表示 -->
                                            <span @class([
                                                'inline-flex items-center px-2 py-1 text-xs rounded-full',
                                                'bg-yellow-100 text-yellow-800' => $member->pivot->permission_level === \App\Enums\PermissionLevel::Pending,
                                                'bg-blue-100 text-blue-800' => $member->pivot->permission_level === \App\Enums\PermissionLevel::Member,
                                                'bg-red-100 text-red-800' => $member->pivot->permission_level === \App\Enums\PermissionLevel::Admin,
                                                'bg-purple-100 text-purple-800' => $member->pivot->permission_level === \App\Enums\PermissionLevel::Owner,
                                            ])>
                                                {{ $member->pivot->permission_level->shortLabel() }}
                                            </span>

                                            <span class="text-xs text-gray-500">
                                                参加日: {{ $member->pivot->created_at->format('Y/m/d') }}
                                            </span>

                                            <!-- オーナー以外は削除可能 -->
                                            @if(! $member->pivot->permission_level->isOwner())
                                                <!-- オーナーのみ昇格・降格ボタン表示 -->
                                                @if($currentUserPermission->isOwner())
                                                    <!-- レベル2メンバーは管理者に昇格可能 -->
                                                    @if($member->pivot->permission_level === \App\Enums\PermissionLevel::Member)
                                                        <form method="POST" action="{{ route('groups.promote-member', [$group, $member]) }}" class="inline mr-2">
                                                            @csrf
                                                            <button type="submit"
                                                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-sm"
                                                                    onclick="return confirm('{{ $member->name }}さんを管理者に昇格させますか？')">
                                                                管理者に昇格
                                                            </button>
                                                        </form>
                                                    @endif

                                                    <!-- レベル3管理者はメンバーに降格可能 -->
                                                    @if($member->pivot->permission_level === \App\Enums\PermissionLevel::Admin)
                                                        <form method="POST" action="{{ route('groups.demote-member', [$group, $member]) }}" class="inline mr-2">
                                                            @csrf
                                                            <button type="submit"
                                                                    class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-1 px-3 rounded text-sm"
                                                                    onclick="return confirm('{{ $member->name }}さんを一般メンバーに降格させますか？')">
                                                                メンバーに降格
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endif

                                                <!-- 除名権限チェック（権限の判定ロジックは PermissionLevel に集約） -->
                                                @php($canRemove = $currentUserPermission->canRemove($member->pivot->permission_level))

                                                @if($canRemove)
                                                    <form method="POST" action="{{ route('groups.remove-member', [$group, $member]) }}" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm"
                                                                onclick="return confirm('{{ $member->name }}さんをグループから除名しますか？この操作は元に戻せません。')">
                                                            除名
                                                        </button>
                                                    </form>
                                                @endif
                                            @else
                                                <span class="text-xs text-gray-400">（オーナー）</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <p>承認済みメンバーがいません</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- 権限について -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">権限レベルについて</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <h4 class="font-medium mb-2">権限レベル一覧</h4>
                            <ul class="space-y-1">
                                <li class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2 py-1 text-xs bg-purple-100 text-purple-800 rounded-full">オーナー</span>
                                    <span>すべての権限（グループ設定変更、削除、メンバー管理、昇格・降格）</span>
                                </li>
                                <li class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">管理者</span>
                                    <span>メンバー管理権限（承認・一般メンバー除名可能）</span>
                                </li>
                                <li class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">メンバー</span>
                                    <span>グループ参加済み（活動参加可能）</span>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-medium mb-2">権限管理</h4>
                            <ul class="space-y-1 text-gray-600">
                                <li>• オーナーはメンバーを管理者に昇格できます</li>
                                <li>• オーナーは管理者をメンバーに降格できます</li>
                                <li>• 管理者・オーナーは新規申請を承認・拒否できます</li>
                                <li>• 管理者は承認待ち・メンバーのみ除名できます</li>
                                <li>• オーナーは全レベルのメンバーを除名できます</li>
                                <li>• オーナー権限は変更できません</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
