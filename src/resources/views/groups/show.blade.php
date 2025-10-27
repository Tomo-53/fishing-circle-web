<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $group->name }}
            </h2>
            <div class="flex space-x-2">
                @if($currentUserGroup->permission_level >= 3)
                    <a href="{{ route('groups.members', $group) }}" 
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        メンバー管理
                    </a>
                @endif
                @if($currentUserGroup->permission_level >= 4)
                    <a href="{{ route('groups.edit', $group) }}" 
                       class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                        グループ編集
                    </a>
                @endif
            </div>
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

            <!-- グループ情報 -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">グループ情報</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">グループ名</p>
                            <p class="font-medium">{{ $group->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">オーナー</p>
                            <p class="font-medium">{{ $group->masterUser->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">作成日</p>
                            <p class="font-medium">{{ $group->created_at->format('Y年m月d日') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">メンバー数</p>
                            <p class="font-medium">{{ $members->count() }}人</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 現在のユーザーの権限 -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">あなたの権限</h3>
                    <div class="flex items-center space-x-4">
                        <span @class([
                            'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium',
                            'bg-yellow-100 text-yellow-800' => $currentUserGroup->permission_level == 1,
                            'bg-blue-100 text-blue-800' => $currentUserGroup->permission_level == 2,
                            'bg-red-100 text-red-800' => $currentUserGroup->permission_level == 3,
                            'bg-purple-100 text-purple-800' => $currentUserGroup->permission_level == 4,
                        ])>
                            @switch($currentUserGroup->permission_level)
                                @case(4) グループオーナー @break
                                @case(3) 管理者・幹部 @break
                                @case(2) 一般メンバー @break
                                @default 認証待機
                            @endswitch
                        </span>
                        @if(!$currentUserGroup->is_approved)
                            <span class="text-orange-600 text-sm">（承認待ち）</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- メンバー一覧 -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">メンバー一覧</h3>
                        @if($pendingCount > 0 && $currentUserGroup->permission_level >= 3)
                            <div class="text-sm text-orange-600">
                                {{ $pendingCount }}件の承認待ち申請があります
                            </div>
                        @endif
                    </div>

                    @if($members->count() > 0)
                        <div class="space-y-3">
                            @foreach($members as $member)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h4 class="font-medium">{{ $member->name }}</h4>
                                            <p class="text-sm text-gray-600">{{ $member->email }}</p>
                                            @if($member->grade)
                                                <p class="text-xs text-gray-500">
                                                    @switch($member->grade)
                                                        @case('B1') 学部1年 @break
                                                        @case('B2') 学部2年 @break
                                                        @case('B3') 学部3年 @break
                                                        @case('B4') 学部4年 @break
                                                        @case('M1') 修士1年 @break
                                                        @case('M2') 修士2年 @break
                                                        @case('D1') 博士1年 @break
                                                        @case('D2') 博士2年 @break
                                                        @case('D3') 博士3年 @break
                                                        @default その他
                                                    @endswitch
                                                </p>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <span @class([
                                                'inline-flex items-center px-2 py-1 text-xs rounded-full',
                                                'bg-yellow-100 text-yellow-800' => $member->pivot->permission_level == 1,
                                                'bg-blue-100 text-blue-800' => $member->pivot->permission_level == 2,
                                                'bg-red-100 text-red-800' => $member->pivot->permission_level == 3,
                                                'bg-purple-100 text-purple-800' => $member->pivot->permission_level == 4,
                                            ])>
                                                @switch($member->pivot->permission_level)
                                                    @case(4) オーナー @break
                                                    @case(3) 管理者 @break
                                                    @case(2) メンバー @break
                                                    @default 承認待ち
                                                @endswitch
                                            </span>
                                            <p class="text-xs text-gray-500 mt-1">
                                                参加日: {{ $member->pivot->created_at->format('Y/m/d') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <p>まだメンバーがいません</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>