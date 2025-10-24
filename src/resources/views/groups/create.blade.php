<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('新規グループ作成') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('groups.store') }}">
                        @csrf

                        <!-- Group Name -->
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('グループ名')" />
                            <x-text-input id="name" class="block mt-1 w-full"
                                        type="text"
                                        name="name"
                                        :value="old('name')"
                                        required
                                        autofocus
                                        autocomplete="name"
                                        placeholder="例: 釣り研究会2025"/>
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('groups.index') }}" class="underline text-sm text-gray-600 hover:text-gray-900 mr-4">
                                {{ __('キャンセル') }}
                            </a>

                            <x-primary-button class="ml-4">
                                {{ __('グループを作成') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
