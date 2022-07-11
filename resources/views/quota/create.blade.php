<x-app-layout>

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
                            <x-dropdown.item value="{{$case->value}}" select="{{old('basis') ?? 2}}">
                                {{$case->name}}
                            </x-dropdown.item>
                        @endforeach
                    </x-dropdown.index>
                    <x-jet-input-error class="mt-2" for="basis"/>
                </div>

                <div class="mt-4">
                    <x-jet-label for="regular_amount" value="{{ __('Regular Amount') }}"/>
                    <x-jet-input id="regular_amount" class="block mt-4 w-full" type="number" name="regular_amount"
                                 value="{{old('regular_amount')}}" required/>
                    <x-jet-input-error class="mt-2" for="regular_amount"/>

                </div>

                <div class="mt-4">
                    <x-jet-label for="special_amount" value="{{ __('Special Amount') }}"/>
                    <x-jet-input id="special_amount" class="block mt-4 w-full" type="number" name="special_amount"
                                 value="{{old('special_amount')}}" required/>
                    <x-jet-input-error class="mt-2" for="special_amount"/>
                </div>

                <div class="mt-4">
                    <x-jet-label for="current-plan-checkbox-1" class="font-bold text-lg"
                                 value="{{ __('Is this the current plan') }}"/>
                    <div class="mt-2">
                        <div class="form-check">
                            <x-radio.input name="is_current_plan" value="1" id="current-plan-checkbox-1"/>
                            <x-jet-label for='current-plan-checkbox-1' value="{{ __('Yes') }}"/>

                        </div>
                        <div class="form-check">
                            <x-radio.input name="is_current_plan" value='0' id="current-plan-checkbox-2"
                                           check="{{old('is_current_plan') === '0' ? true : false}}"
                            />
                            <x-jet-label for='current-plan-checkbox-2' value="{{ __('No') }}"/>
                        </div>
                        <x-jet-input-error class="mt-2" for="is_current_plan"/>
                    </div>
                </div>

                    <div class="flex items-center justify-center mt-6">
                        <x-jet-button class="flex-1 text-center !inline">
                            {{ __('Create') }}
                        </x-jet-button>
                    </div>
            </form>
        </x-form-card>

    </div>


</x-app-layout>

