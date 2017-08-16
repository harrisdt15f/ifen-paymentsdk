@if (Session::get('is_client'))
@include('pamentsdkonly.client.header')
@else
@include('pamentsdkonly.other.header-v4')
@endif