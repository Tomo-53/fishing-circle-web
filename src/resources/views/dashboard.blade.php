<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('釣りサークル ダッシュボード') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- ユーザー情報カード -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">ユーザー情報</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">名前</p>
                            <p class="font-medium">{{ Auth::user()->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">メールアドレス</p>
                            <p class="font-medium">{{ Auth::user()->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">学年</p>
                            <p class="font-medium">
                                @if(Auth::user()->grade)
                                    @switch(Auth::user()->grade)
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
                                @else
                                    未設定
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">アカウント作成日</p>
                            <p class="font-medium">{{ Auth::user()->created_at->format('Y年m月d日') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- クイックアクション -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">クイックアクション</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('groups.myGroups') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded text-center transition duration-200">
                            📊 グループ管理
                        </a>
                        <a href="{{ route('profile.edit') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-3 px-4 rounded text-center transition duration-200">
                            ⚙️ プロフィール編集
                        </a>
                    </div>
                </div>
            </div>


            <!-- グループ参加状況 -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">グループ参加状況</h3>
                    @if(Auth::user()->groups && Auth::user()->groups->count() > 0)
                        <div class="space-y-3">
                            @foreach(Auth::user()->groups as $group)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-medium">{{ $group->name }}</h4>
                                            <p class="text-sm text-gray-600">{{ $group->description }}</p>
                                        </div>
                                        <div class="text-right">
                                            <span @class([
                                                'inline-flex items-center px-2 py-1 text-xs rounded-full',
                                                'bg-yellow-100 text-yellow-800' => $group->pivot->permission_level == 1,
                                                'bg-blue-100 text-blue-800' => $group->pivot->permission_level == 2,
                                                'bg-red-100 text-red-800' => $group->pivot->permission_level == 3,
                                                'bg-green-100 text-green-800' => $group->pivot->permission_level == 4,
                                            ])>
                                                @switch($group->pivot->permission_level)
                                                    @case(4) オーナー @break
                                                    @case(3) 管理者 @break
                                                    @case(2) メンバー @break
                                                    @default 承認待ち
                                                @endswitch
                                            </span>
                                            @if(!$group->pivot->is_approved)
                                                <p class="text-xs text-orange-600 mt-1">承認待ち</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <p class="mb-4">まだグループに参加していません</p>
                            <p class="text-sm">新しいグループを作成するか、既存のグループに参加してみましょう！</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
