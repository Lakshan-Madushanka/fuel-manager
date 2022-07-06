<x-app-layout>

    <div id="alert-wrapper"></div>
    <x-slot name="header" class="flex">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Quota Plans') }}
            </h2>

        </div>
    </x-slot>

    <div class="">
        <x-form-card title="Create new plan">
            <form method="POST" action="{{ route('admin.quotas.store') }}">
                @csrf

                <div class="mt-4">
                    <x-jet-label for="basis" value="{{ __('Basis') }}"/>
                    <x-dropdown.index class="mt-4" id="basis" name="basis">
                        @foreach(\App\Enums\Quota\Basis::cases() as $case)
                            <x-dropdown.item value="{{$case->value}}" select="2">
                                {{$case->name}}
                            </x-dropdown.item>
                        @endforeach
                    </x-dropdown.index>
                    <x-jet-input-error class="mt-2" for="basis"/>
                </div>

                <div class="mt-4">
                    <x-jet-label for="regular_amount" value="{{ __('Regular Amount') }}"/>
                    <x-jet-input id="regular_amount" class="block mt-4 w-full" type="number" name="regular_amount"
                                 required/>
                    <x-jet-input-error class="mt-2" for="regular_amount"/>

                </div>

                <div class="mt-4">
                    <x-jet-label for="special_amount" value="{{ __('Special Amount') }}"/>
                    <x-jet-input id="special_amount" class="block mt-4 w-full" type="number" name="special_amount" required/>
                    <x-jet-input-error class="mt-2" for="special_amount"/>
                </div>

                <div class="flex items-center justify-center mt-4">
                    <x-jet-button class="flex-1 text-center inline">
                        {{ __('Create') }}
                    </x-jet-button>
                </div>
            </form>
        </x-form-card>

    </div>


</x-app-layout>

