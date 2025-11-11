<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('グループを探す') }}
            </h2>
            <a href="{{ route('groups.create') }}"
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                新しいグループを作成
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- 検索フォーム -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('groups.all') }}" class="flex gap-4">
                        <div class="flex-1">
                            <input type="text"
                                   name="search"
                                   value="{{ $search }}"
                                   placeholder="グループ名で検索..."
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            検索
                        </button>
                        @if($search)
                            <a href="{{ route('groups.all') }}"
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                クリア
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            <!-- グループ一覧 -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($allGroups->count() > 0)
                        <div class="grid gap-6">
                            @foreach($allGroups as $group)
                                <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                                {{ $group->name }}
                                            </h3>
                                            <div class="text-sm text-gray-600 space-y-1">
                                                <p>
                                                    <span class="font-medium">オーナー:</span>
                                                    {{ $group->masterUser->name }}
                                                </p>
                                                <p>
                                                    <span class="font-medium">メンバー数:</span>
                                                    {{ $group->approved_users_count }}人
                                                </p>
                                                <p>
                                                    <span class="font-medium">作成日:</span>
                                                    {{ $group->created_at->format('Y年m月d日') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="ml-4">
                                            @if(in_array($group->id, $userGroupIds))
                                                <!-- 既に参加済み/申請済み -->
                                                @php
                                                    $userGroup = Auth::user()->groups()->where('group_id', $group->id)->first();
                                                @endphp
                                                @if($userGroup && $userGroup->pivot->is_approved)
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                        参加済み
                                                    </span>
                                                    <a href="{{ route('groups.show', $group) }}"
                                                       class="ml-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                                        グループを見る
                                                    </a>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                                        申請中
                                                    </span>
                                                @endif
                                            @else
                                                <!-- 参加申請可能 -->
                                                <form method="POST" action="{{ route('groups.join', $group) }}" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm"
                                                            onclick="return confirm('このグループに参加申請しますか？')">
                                                        参加申請
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- ページネーション -->
                        <div class="mt-6">
                            {{ $allGroups->appends(['search' => $search])->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-gray-500 text-lg">
                                @if($search)
                                    「{{ $search }}」に一致するグループが見つかりませんでした。
                                @else
                                    まだグループが作成されていません。
                                @endif
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('groups.create') }}"
                                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    最初のグループを作成する
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
