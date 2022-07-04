@props(['title', 'content' => '', 'spinner' => null])

<!-- Modal -->
<div {{$attributes->merge(['class' => 'modal backdrop-blur-[1px] fade fixed top-0 left-0 show flex w-full justify-center items-start h-full outline-none overflow-x-hidden overflow-y-auto'])}}
     id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  border-solid border-2	 border-orange-600  relative w-1/2 w-auto pointer-events-none">
        <div
                class="modal-content border-none shadow-2xl relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
            <div
                    class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 rounded-t-md">
                <h5 class="text-xl font-medium leading-normal text-gray-800" id="exampleModalLabel">{{$title}}</h5>
                <button type="button"
                        class="btn-close box-content w-4 h-4 p-1 text-black border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline"
                        data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body relative p-4">
                @if($content)
                    <div class="mb-2">{{$content}}</div>
                @endif
                {{$slot}}
            </div>
            <div
                    class="modal-footer flex flex-shrink-0 flex-wrap items-center justify-end p-4 border-t border-gray-200 rounded-b-md">
               @if(! $spinner)
                <button id="modal-close" type="button" class="px-6
          py-2.5
          bg-purple-600
          text-white
          font-medium
          text-xs
          leading-tight
          uppercase
          rounded
          shadow-md
          hover:bg-purple-700 hover:shadow-lg
          focus:bg-purple-700 focus:shadow-lg focus:outline-none focus:ring-0
          active:bg-purple-800 active:shadow-lg
          transition
          duration-150
          ease-in-out" data-bs-dismiss="modal">Close
                </button>
                <button type="button" id='modal-save-button' class="px-6
      py-2.5
      bg-blue-600
      text-white
      font-medium
      text-xs
      leading-tight
      uppercase
      rounded
      shadow-md
      hover:bg-blue-700 hover:shadow-lg
      focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0
      active:bg-blue-800 active:shadow-lg
      transition
      duration-150
      ease-in-out
      ml-1">Save changes
                </button>
      @endif
                @if($spinner)
                    {{$spinner}}
                @endif
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        $(function () {
            $('#modal-close').click(function () {
                $('#show-modal').toggleClass('hidden')
                $('input').val('');
                //$('#amount-errors').empty()
            })
        })
    </script>
@endpush
