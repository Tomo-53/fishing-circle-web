<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('グループ管理') }}
            </h2>
            <a href="{{ route('groups.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                新規グループ作成
            </a>
             <a href="{{ route('groups.all') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-4 rounded text-center transition duration-200">
                グループを探す
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($groups->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($groups as $group)
                                <div class="bg-gray-50 rounded-lg p-6 shadow">
                                    <h3 class="text-lg font-semibold mb-2">{{ $group->name }}</h3>
                                    <p class="text-sm text-gray-600 mb-4">
                                        オーナー: {{ $group->masterUser->name ?? '不明' }}
                                    </p>

                                    @php
                                        $userGroup = $group->users()->where('user_id', auth()->id())->first();
                                        $permissionLevel = $userGroup ? $userGroup->pivot->permission_level : null;
                                        $isApproved = $userGroup ? $userGroup->pivot->is_approved : false;
                                    @endphp

                                    @if($permissionLevel)
                                        <div class="mb-4">
                                            <span @class([
                                                'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                                'bg-yellow-100 text-yellow-800' => $permissionLevel == 1,
                                                'bg-blue-100 text-blue-800' => $permissionLevel == 2,
                                                'bg-green-100 text-green-800' => $permissionLevel == 3,
                                                'bg-purple-100 text-purple-800' => $permissionLevel == 4,
                                            ])>
                                                @switch($permissionLevel)
                                                    @case(4) オーナー @break
                                                    @case(3) 管理者 @break
                                                    @case(2) メンバー @break
                                                    @default 承認待ち @break
                                                @endswitch
                                            </span>
                                            @if(!$isApproved)
                                                <span class="ml-2 text-yellow-600 text-xs">（承認待ち）</span>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="flex space-x-2">
                                        <a href="{{ route('groups.show', $group) }}"
                                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
                                            詳細
                                        </a>
                                        @if($permissionLevel >= 3)
                                            <a href="{{ route('groups.edit', $group) }}"
                                               class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-3 rounded text-sm">
                                                編集
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 mb-4">まだグループに参加していません。</p>
                            <a href="{{ route('groups.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                最初のグループを作成
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
