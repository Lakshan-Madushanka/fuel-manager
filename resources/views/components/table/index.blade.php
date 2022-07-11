<div id='table-wrapper--primary' class="bg-white rounded-lg shadow-lg py-6">
    <div id='table-wrapper' class="block overflow-x-auto mx-6">
        <table {{$attributes->merge(['class' => 'w-full text-left rounded-lg'])}}>
            <thead>
            <tr class="text-gray-800 text-center border border-b-0">
            {{$columns}}
            </tr>
            </thead>
            <tbody>
                {{$rows}}
            </tbody>
        </table>
    </div>
</div>