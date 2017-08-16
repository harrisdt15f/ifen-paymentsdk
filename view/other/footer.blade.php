@if (Session::get('is_client'))
@include('w.client.footer')
@else
@include('w.footer-v5')
@endif