<div id='table-wrapper--primary' class="bg-white rounded-lg shadow-lg py-6">
    <div id='table-wrapper' class="block overflow-x-auto mx-6">
        <table class="w-full text-left rounded-lg">
            <thead>
            <tr class="text-gray-800 text-center border border-b-0">
            {{$columns}}
            </tr>
            </thead>
            <tbody>
            <tr class="w-full font-light text-gray-700 text-center bg-gray-100 whitespace-no-wrap border border-b-0 hover:bg-gray-50">
                {{$rows}}
            </tr>
            </tbody>
        </table>
    </div>
</div>