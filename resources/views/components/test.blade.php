<div class="test"> teeeeeeeeeeeee</div>

@if(2>1)
@push('script')
<script>
    $(function () {
        console.log('aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa')
        $('.test').click(function () {


        })
    })
</script>
@endpush
@endif