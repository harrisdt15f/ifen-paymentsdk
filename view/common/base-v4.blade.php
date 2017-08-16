@if(Session::get('is_client'))
    @include('pamentsdkonly.common.base')
@else
<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge"  />
        <meta name="description" content="@yield('description')" />
        <meta name="keywords" content="@yield('keywords')" />
        <meta name="renderer" content="webkit|ie-comp|ie-stand">
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
        <title>
            @section('title')
            -博猫游戏
            @show
        </title>
        @section ('styles')
            <style type="text/css">
                @font-face {
                font-family: 'Bomao';
                src: url('/assets/images/global/Bomao.eot');
                src: local('?'), url('/assets/images/global/Bomao.woff') format('/assets/images/global/woff'), url('/assets/images/global/Bomao.ttf') format('truetype'), url('/assets/images/global/Bomao.svg') format('svg');
                }
                @font-face{
                    font-family: dyAvenir;
                    src:url('/assets/images/global/dyAvenir.ttf');
                }

   /*             @font-face{
                    font-family:dyFzzhs;
                    src:url('/assets/images/global/dyFzzhs.ttf');
                }*/
                @font-face{
                    font-family:dyBebas;
                    src:url('/assets/images/global/dyBebas.ttf');
                }

                @font-face{
                    font-family:FZLanTingHei-R-GBK;
                    src:url('/assets/images/global/FZLanTingHei-R-GBK.ttf');
                }
            </style>
            {{ style('global-v4')}}

            {{ style('animate') }}
            {{ style('font-awesome')}}
        @show
    </head>

    @section('body')
    <body>
        @section('start')
        <input type="hidden" name="_token" id="J-global-token-value" value="" />
        @show

        @yield('container')

        

        


        @section('scripts')
            {{ script('base-all') }}
            {{ script('jquery.lavalamp') }}


        @show

        @include('pamentsdkonly.common.notification')

        @section('end')

        @show

    </body>
    @show
</html>
@endif