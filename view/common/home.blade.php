@extends('pamentsdkonly.common.base-v4')


@section ('styles')
@parent
    {{ style('font-awesome')}}
    {{ style('ucenter') }}
    {{ style('proxy') }}
    {{--{{ style('proxy-global') }}--}}
@stop


@section ('container')
        <div class="page-content">
            <div class="container main clearfix">
                <div class="main-content">
                    @section ('main')
                    @show
                </div>
            </div>
        </div>
@stop

@section('end')
@parent

@stop
