<x-app-layout>
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 py-12">
        <div id='main-wrapper' class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <x-card class="w-full">
            <x-slot:title class="text-red-600">
                Account haven't been approved
            </x-slot:title>
            <x-slot:body-content>
                Your account still under processing we will inform you once finished
            </x-slot:body-content>
            <x-slot:footer>
                please contact admin if your account..
            </x-slot:footer>
        </x-card>
    </div>
    </div>
</x-app-layout>
