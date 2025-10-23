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
                        <a href="{{ route('groups.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded text-center transition duration-200">
                            📊 グループ管理
                        </a>
                        <a href="{{ route('groups.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-4 rounded text-center transition duration-200">
                            ➕ 新規グループ作成
                        </a>
                        <a href="{{ route('profile.edit') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-3 px-4 rounded text-center transition duration-200">
                            ⚙️ プロフィール編集
                        </a>
                    </div>
                </div>
            </div>

            <!-- 機能説明カード -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">🎣 釣りサークルへようこそ！</h3>
                    <p class="mb-4">このシステムでは以下の機能をご利用いただけます：</p>
                    <ul class="list-disc list-inside space-y-2 text-gray-700">
                        <li><strong>グループ管理</strong>: 釣行グループの作成・参加・管理</li>
                        <li><strong>権限システム</strong>:
                            <span class="inline-flex items-center px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">一般メンバー</span>
                            <span class="inline-flex items-center px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">副管理者</span>
                            <span class="inline-flex items-center px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">管理者</span>
                        </li>
                        <li><strong>釣行計画</strong>: 釣行の企画・参加者管理</li>
                        <li><strong>学年管理</strong>: 学年による階層的な組織運営</li>
                    </ul>
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
                                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-full
                                                @if($group->pivot->permission_level === 'admin') bg-red-100 text-red-800
                                                @elseif($group->pivot->permission_level === 'moderator') bg-green-100 text-green-800
                                                @else bg-blue-100 text-blue-800 @endif">
                                                @switch($group->pivot->permission_level)
                                                    @case('admin') 管理者 @break
                                                    @case('moderator') 副管理者 @break
                                                    @default 一般メンバー
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
