<?php
/*$aTabList = [
    'netbank' => '网银',
    'quick' => '网银快捷',
    'qq' => 'QQ钱包',
    'weixin' => '微信',
    'alipay' => '支付宝',
    'baidu' => '百度钱包',
// 'alipay-to-netbank' => '转网银',
];*/
if ((date('H') < 9 || date('H') > 21)) unset($aTabList['pay']);
$aTabChildList = [
    'alipay' => ['get-alipay-qrcode'],
    'weixin' => ['confirmWeiXin'],
    'qq' => ['confirmQq'],
    'baidu' => ['confirmBaidu'],
];

// $picture_arr = json_decode($sAllBanksJs,true);


?>
{{--{{dd($picture_arr)}}--}}
@section ('styles')
    @parent
    <style type="text/css">
        .way-img-weixin {
            background: url('/assets/images/ucenter/way-6.png') no-repeat;
        }

        .way-img-banks {
            background: url('/assets/images/ucenter/way-2.png') no-repeat;
            background-position: 0px 1px;
        }

        .way-img-unionpay {
            background: url('/assets/images/ucenter/unionpay.png') no-repeat;
            background-position: 0px 1px;
        }
    </style>
@stop
<ul class="tab-title">
    @foreach($aTabList as $sTabKey => $sTabText)
        @if ($sTabKey == 'pay')
            <li @if($sTabKey == $current_tab)class="current"@endif>
                <span @if($sTabKey == $current_tab)class="top-bg"@endif></span>
                <a target="_blank" href="{{ route('user-recharges.'. $sTabKey) }}"><span><div
                                class="way-img way-img-{{$sTabKey}}"></div>{{{ $sTabText }}}</span></a>
            </li>
        @else
            <li @if($sTabKey == $current_tab)class="current"@endif>
                <span @if($sTabKey == $current_tab)class="top-bg"@endif ></span>
                <a href="{{ route('test.'. $sTabKey) }}"><span><div
                                class="way-img way-img-{{$sTabKey == 'alipay-to-netbank' ? 'alipay' : $sTabKey}}"></div>
                {{--<a href="{{ route('test.'. $sTabKey) }}"><span><div style="background-image: url("{{ $sTabKey }}")"
                                class="way-img way-img-{{$sTabKey == 'alipay-to-netbank' ? 'alipay' : $sTabKey}}"></div>--}}
                        @if(count($sTabText) > 0)
                            {{{ $sTabText }}}
                        @else
                            {{{ $sTabKey }}}
                        @endif
            </span></a>
            </li>
        @endif
    @endforeach
</ul>