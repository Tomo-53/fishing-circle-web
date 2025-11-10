<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            @if($currentUserGroup->permission_level >= 4)
                <!-- 編集可能なグループ名（レベル4のみ） -->
                <div class="group relative">
                    <div id="groupNameContainer">
                        <h2 id="groupName"
                            class="font-semibold text-xl text-gray-800 leading-tight cursor-pointer hover:bg-gray-100 px-2 py-1 rounded transition-colors"
                            ondblclick="enableEdit()">
                            {{ $group->name }}
                        </h2>
                        <p class="text-xs text-gray-500 mt-1 px-2">
                            ↑ダブルクリックでグループ名変更可能
                        </p>
                    </div>
                    <form id="editForm" method="POST" action="{{ route('groups.update', $group) }}" class="hidden">
                        @csrf
                        @method('PUT')
                        <input type="text"
                               id="nameInput"
                               name="name"
                               value="{{ $group->name }}"
                               maxlength="50"
                               class="font-semibold text-xl text-gray-800 leading-tight border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500"
                               onblur="cancelEdit()"
                               onkeydown="handleKeyDown(event)">
                        <div class="text-xs text-gray-500 mt-1">最大50文字まで</div>
                    </form>
                </div>
            @else
                <!-- 読み取り専用のグループ名 -->
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $group->name }}
                </h2>
            @endif
            <div class="flex space-x-2">
                @if($currentUserGroup->permission_level >= 3)
                    <a href="{{ route('groups.members', $group) }}"
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        メンバー管理
                    </a>
                @endif
                @if($currentUserGroup->permission_level >= 4)
                    <button onclick="openDeleteModal()"
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        グループ削除
                    </button>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- エラーメッセージ -->
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <strong>入力エラーがあります:</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

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

    <!-- 削除確認モーダル -->
    @if($currentUserGroup->permission_level >= 4)
        <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">グループ削除の確認</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">
                            本当に「{{ $group->name }}」を削除しますか？<br>
                            <strong class="text-red-600">この操作は元に戻せません。</strong><br>
                            全てのメンバー情報も削除されます。
                        </p>
                    </div>
                    <div class="items-center px-4 py-3">
                        <form method="POST" action="{{ route('groups.destroy', $group) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">
                                削除
                            </button>
                        </form>
                        <button onclick="closeDeleteModal()"
                                class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            キャンセル
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        // グループ名編集機能(ダブルクリックした際の処理)
        function enableEdit() {
            const nameContainerElement = document.getElementById('groupNameContainer');
            const formElement = document.getElementById('editForm');
            const inputElement = document.getElementById('nameInput');

            nameContainerElement.classList.add('hidden');
            formElement.classList.remove('hidden');
            inputElement.focus();
            inputElement.select();
        }

        // グループ名編集機能キャンセル時(ダブルクリックした際の処理)
        function cancelEdit() {
            setTimeout(() => {
                const nameContainerElement = document.getElementById('groupNameContainer');
                const formElement = document.getElementById('editForm');
                const inputElement = document.getElementById('nameInput');

                // 元の値に戻す
                inputElement.value = "{{ $group->name }}";

                nameContainerElement.classList.remove('hidden');
                formElement.classList.add('hidden');
            }, 100);
        }

        function handleKeyDown(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                const form = document.getElementById('editForm');
                form.submit();
            } else if (event.key === 'Escape') {
                event.preventDefault();
                cancelEdit();
            }
        }

        // グループ削除モーダル機能
        function openDeleteModal() {
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Escキーでモーダルを閉じる
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDeleteModal();
            }
        });
    </script>
</x-app-layout>
