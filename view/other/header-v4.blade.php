
{{--@include('pamentsdkonly.top')--}}



<div class="header-v5">
    <div class="container clearfix" id="J-header-container">
        <div class="left">
            <a href="{{ route('home') }}" class="logo"></a>
        </div>
        {{--
        <div class="left year-logo">
            <a href="{{route('wishwall.index')}}" class="year-logo-canvas"></a>
        </div>
        --}}
        <div class="right">
            <ul class="menu">
                <li class="it"><a class="mu-big" href="{{ route('home') }}">首页</a></li>
                <li class="it it-lottery">
                    <a class="mu-big" href="#">彩票<span class="sj"></span></a>
                    <div class="panel-menu">
                        <span class="p-sj"></span>
                        <div class="row">
                            <div class="menu-table">
                                <div class="menu-row">
                                    <div class="menu-cell">
                                        <div class="sprite sprite-bmzx"></div>
                                    </div>
                                    <div class="menu-cell">
                                        <ul class="j-list clearfix">
                                            <li><a href="{{ route('bets.bet', 23) }}">博猫1分彩 <i class="ico ico-hot"></i></a></li>
                                            <li><a href="{{ route('bets.bet', 11) }}">博猫2分彩 <i class="ico ico-hot"></i></a></li>
                                            <li><a href="{{ route('bets.bet', 24) }}">博猫5分彩</a></li>
                                            <li><a href="{{ route('bets.bet', 12) }}">博猫11选5</a></li>
                                            <li><a href="{{ route('bets.bet', 20) }}">博猫快3 <i class="ico ico-new"></i></a></li>
                                            <li><a href="{{ route('bets.bet', 60) }}">博猫六合彩<i class="ico ico-new"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="menu-row">
                                    <div class="menu-cell">
                                        <div class="sprite sprite-ssc"></div>
                                    </div>
                                    <div class="menu-cell">
                                        <ul class="j-list clearfix">
                                            <li><a href="{{ route('bets.bet', 1) }}">重庆时时彩 <i class="ico ico-hot"></i></a></li>
                                            <li><a href="{{ route('bets.bet', 3) }}">黑龙江时时彩</a></li>
                                            <li><a href="{{ route('bets.bet', 6) }}">新疆时时彩 <i class="ico ico-hot"></i></a></li>
                                            <li><a href="{{ route('bets.bet', 7) }}">天津时时彩</a></li>
                                            <li><a href="{{ route('bets.bet', 72) }}">夺金60秒<i class="ico ico-new"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="menu-row">
                                    <div class="menu-cell">
                                        <div class="sprite sprite-11x5"></div>
                                    </div>
                                    <div class="menu-cell">
                                        <ul class="j-list clearfix">
                                            <li><a href="{{ route('bets.bet', 2) }}">山东11选5</a></li>
                                            <li><a href="{{ route('bets.bet', 8) }}">江西11选5</a></li>
                                            <li><a href="{{ route('bets.bet', 9) }}">广东11选5</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="menu-row">
                                    <div class="menu-cell">
                                        <div class="sprite sprite-k3"></div>
                                    </div>
                                    <div class="menu-cell">
                                        <ul class="j-list clearfix">
                                            <li><a href="{{ route('bets.bet', 21) }}">江苏快3 <i class="ico ico-hot"></i></a></li>
                                            <li><a href="{{ route('bets.bet', 22) }}">安徽快3</a></li>
                                            <li><a href="{{ route('bets.bet', 71) }}">江苏骰宝 <i class="ico ico-new"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="menu-row">
                                    <div class="menu-cell">
                                        <div class="sprite sprite-dpc"></div>
                                    </div>
                                    <div class="menu-cell">
                                        <ul class="j-list clearfix">
                                            <li><a href="{{ route('bets.bet', 13) }}">福彩3D</a></li>
                                            <li><a href="{{ route('bets.bet', 14) }}">体彩P3/5</a></li>
                                            <li><a href="{{ route('bets.bet', 61) }}">香港六合彩<i class="ico ico-new"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="menu-row">
                                    <div class="menu-cell">
                                        <div class="sprite sprite-klc"></div>
                                    </div>
                                    <div class="menu-cell">
                                        <ul class="j-list clearfix">
                                            <li><a href="{{ route('bets.bet', 53) }}">北京PK10 <i class="ico ico-new"></i></a></li>
                                            <li><a href="{{ route('bets.bets', 20) }}">幸运28&nbsp;<i class="ico ico-new"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="it it-sport">
                    <a class="mu-big" href="/jc/football">竞彩足球 <span class="sj"></span></a>
                    <div class="panel-menu">
                        <span class="p-sj"></span>
                        <div class="row">
                            <a href="{{ route('jc.groupbuy', 'football') }}" class="hemai"><img src="/assets/images/global-v4/nav-buy.jpg" alt=""></a>
                            <ul class="list clearfix">
                                <li><a href="/jc/football/hunhe">混合过关 <i class="ico ico-hot"></i></a></li>
                                <li><a href="/jc/football/win-handicapWin">让球/胜平负<i class="ico ico-hot"></i></a></li>
                                <li><a href="/jc/football/haFu">半全场  <i class="ico ico-hot"></i></a></li>
                                <li><a href="/jc/football/correctScore">比分</a></li>
                                <li><a href="/jc/football/totalGoals">总进球</a></li>
                                <li><a href="/jc/football/single">单关 <i class="ico ico-new"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </li>
                <li class="it it-casino">
                    <a class="mu-big" href="#">电子娱乐<span class="sj"></span></a>
                    <div class="panel-menu">
                        <span class="p-sj"></span>
                        <div class="row">
                            <div class="title">百家乐 传统经典 奖金高</div>
                            <div class="cont clearfix">
                                <div class="cell cell-1">
                                    <div class="t">娱乐场 <span class="sm">单期限赔5万</span></div>
                                    <div class="link">
                                        <a href="{{ route('bets.bet', 44) }}">娱乐1桌45秒/期</a>
                                        <span>|</span>
                                        <a href="{{ route('bets.bet', 45) }}">娱乐2桌60秒/期</a>
                                        <span>|</span>
                                        <a href="{{ route('bets.bet', 46) }}" class="last">娱乐3桌75秒/期</a>
                                    </div>
                                </div>
                                <div class="cell cell-2">
                                    <div class="t">普通场 <span class="sm">单期限赔10万</span></div>
                                    <div class="link">
                                        <a href="{{ route('bets.bet', 47) }}">普通1桌45秒/期</a>
                                        <span>|</span>
                                        <a href="{{ route('bets.bet', 48) }}">普通2桌60秒/期</a>
                                        <span>|</span>
                                        <a href="{{ route('bets.bet', 49) }}" class="last">普通3桌75秒/期</a>
                                    </div>
                                </div>
                                <div class="cell cell-3">
                                    <div class="t">高级场 <span class="sm">单期限赔20万</span></div>
                                    <div class="link">
                                        <a href="{{ route('bets.bet', 50) }}">高级1桌45秒/期</a>
                                        <span>|</span>
                                        <a href="{{ route('bets.bet', 51) }}">高级2桌60秒/期</a>
                                        <span>|</span>
                                        <a href="{{ route('bets.bet', 52) }}" class="last">高级3桌75秒/期</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="title">21点 精彩刺激 趣味性强</div>
                            <div class="cont clearfix">
                                <div class="cell cell-1">
                                    <div class="t">娱乐场 <span class="sm">限投1-500元</span></div>
                                    <div class="link">
                                        <a href="{{ route('casino.bet',[8001,1]) }}" class="last">娱乐1桌-单人游戏</a>
                                    </div>
                                </div>
                                <div class="cell cell-2">
                                    <div class="t">普通场 <span class="sm">限投50-6000元</span></div>
                                    <div class="link">
                                        <a href="{{ route('casino.bet',[8001,2]) }}" class="last">普通1桌-单人游戏</a>
                                    </div>
                                </div>
                                <div class="cell cell-3">
                                    <div class="t">高级场 <span class="sm">限投100-12000元</span></div>
                                    <div class="link">
                                        <a href="{{ route('casino.bet',[8001,3]) }}" class="last">高级1桌-单人游戏</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="title">骰宝 全新感受 乐而忘返</div>
                            <div class="cont clearfix">
                                <div class="cell cell-1">
                                    <div class="t">娱乐场 <span class="sm">单期限赔5万</span></div>
                                    <div class="link">
                                        <a href="{{ route('bets.bet', 25) }}">娱乐1桌45秒/期</a>
                                        <span>|</span>
                                        <a href="{{ route('bets.bet', 26) }}">娱乐2桌60秒/期</a>
                                        <span>|</span>
                                        <a href="{{ route('bets.bet', 27) }}" class="last">娱乐3桌75秒/期</a>
                                    </div>
                                </div>
                                <div class="cell cell-2">
                                    <div class="t">普通场 <span class="sm">单期限赔10万</span></div>
                                    <div class="link">
                                        <a href="{{ route('bets.bet', 28) }}">普通1桌45秒/期</a>
                                        <span>|</span>
                                        <a href="{{ route('bets.bet', 29) }}">普通2桌60秒/期</a>
                                        <span>|</span>
                                        <a href="{{ route('bets.bet', 30) }}" class="last">普通3桌75秒/期</a>
                                    </div>
                                </div>
                                <div class="cell cell-3">
                                    <div class="t">高级场 <span class="sm">单期限赔20万</span></div>
                                    <div class="link">
                                        <a href="{{ route('bets.bet', 31) }}">高级1桌45秒/期</a>
                                        <span>|</span>
                                        <a href="{{ route('bets.bet', 32) }}">高级2桌60秒/期</a>
                                        <span>|</span>
                                        <a href="{{ route('bets.bet', 33) }}" class="last">高级3桌75秒/期</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="title">龙虎之争，谁与争锋</div>
                            <div class="cont clearfix">
                                <div class="cell cell-1">
                                    <div class="t">娱乐场 <span class="sm">单期限赔5万</span></div>
                                    <div class="link">
                                        <a href="{{ route('bets.bet', 34) }}">娱乐1桌45秒/期</a>
                                        <span>|</span>
                                        <a href="{{ route('bets.bet', 35) }}">娱乐2桌60秒/期</a>
                                        <span>|</span>
                                        <a href="{{ route('bets.bet', 37) }}" class="last">娱乐3桌75秒/期</a>
                                    </div>
                                </div>
                                <div class="cell cell-2">
                                    <div class="t">普通场 <span class="sm">单期限赔10万</span></div>
                                    <div class="link">
                                        <a href="{{ route('bets.bet', 38) }}">普通1桌45秒/期</a>
                                        <span>|</span>
                                        <a href="{{ route('bets.bet', 39) }}">普通2桌60秒/期</a>
                                        <span>|</span>
                                        <a href="{{ route('bets.bet', 40) }}" class="last">普通3桌75秒/期</a>
                                    </div>
                                </div>
                                <div class="cell cell-3">
                                    <div class="t">高级场 <span class="sm">单期限赔20万</span></div>
                                    <div class="link">
                                        <a href="{{ route('bets.bet', 41) }}">高级1桌45秒/期</a>
                                        <span>|</span>
                                        <a href="{{ route('bets.bet', 42) }}">高级2桌60秒/期</a>
                                        <span>|</span>
                                        <a href="{{ route('bets.bet', 43) }}" class="last">高级3桌75秒/期</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="title">轮盘 玩法多样 中奖率高</div>
                            <div class="cont clearfix">
                                <div class="cell cell-1">
                                    <div class="t">娱乐场 <span class="sm">单期限赔5万</span></div>
                                    <div class="link">
                                        <a href="{{ route('bets.bet', 62) }}">娱乐1桌45秒/期</a>
                                        <span>|</span>
                                        <a href="{{ route('bets.bet', 63) }}">娱乐2桌60秒/期</a>
                                        <span>|</span>
                                        <a href="{{ route('bets.bet', 64) }}" class="last">娱乐3桌75秒/期</a>
                                    </div>
                                </div>
                                <div class="cell cell-2">
                                    <div class="t">普通场 <span class="sm">单期限赔10万</span></div>
                                    <div class="link">
                                        <a href="{{ route('bets.bet', 65) }}">普通1桌45秒/期</a>
                                        <span>|</span>
                                        <a href="{{ route('bets.bet', 66) }}">普通2桌60秒/期</a>
                                        <span>|</span>
                                        <a href="{{ route('bets.bet', 67) }}" class="last">普通3桌75秒/期</a>
                                    </div>
                                </div>
                                <div class="cell cell-3">
                                    <div class="t">高级场 <span class="sm">单期限赔20万</span></div>
                                    <div class="link">
                                        <a href="{{ route('bets.bet', 68) }}">高级1桌45秒/期</a>
                                        <span>|</span>
                                        <a href="{{ route('bets.bet', 69) }}">高级2桌60秒/期</a>
                                        <span>|</span>
                                        <a href="{{ route('bets.bet', 70) }}" class="last">高级3桌75秒/期</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>


@section('end')
    @parent
    <script type="text/javascript">

        (function(){
            var button = $('.J-button-money-update'),locked = false,CLS = 'ico-update-animation';
            button.click(function(){
                if(locked){
                    return;
                }
                $.ajax({
                    url:'/users/user-monetary-info',
                    dataType:'json',
                    beforeSend:function(){
                        locked = true;
                        button.addClass(CLS);
                    },
                    success:function(data){
                        if(Number(data['isSuccess']) == 1){
                            var monetary = bomao.util.formatMoney(Number(data['data']['available']));
                            $('.J-text-money-value').text(monetary);
                        }
                    },
                    complete:function(){
                        locked = false;
                        button.removeClass(CLS);
                    }
                });
            });
        })();


        //点击隐藏余额
        $('.J-button-control-hidden').click(function(e){
            e.preventDefault();
            $('.J-button-control-hidden').each(function(){
                var el = $(this),par = el.parent(),CLS = 'menu-user-control-hidden',allItems = $('.panel-text-user-balance');
                if(par.hasClass(CLS)){
                    par.removeClass(CLS);
                    el.text('隐藏');
                    allItems.show();
                    $.removeCookie('user-balance-ishidden');
                }else{
                    par.addClass(CLS);
                    el.text('显示');
                    allItems.hide();
                    $.cookie('user-balance-ishidden', 1);
                }
            });

        });
        (function(){
            var button = $('.J-button-control-hidden'),par = button.parent(),CLS = 'menu-user-control-hidden',allItems = $('.panel-text-user-balance');
            if($.cookie('user-balance-ishidden')){
                par.addClass(CLS);
                button.text('显示');
                allItems.hide();
            }else{
                par.removeClass(CLS);
                button.text('隐藏');
                allItems.show();
            }
        })();


        setTimeout(function(){
            var dom = $('#J-header-container').find('.lantern');
            dom.show().fadeIn(function(){
                dom.removeClass().addClass('lantern animated swing');
            });
        }, 1200);


    </script>

    <!--站内信未读数量-->
    <script type="text/javascript">

        //获取未读信息数量
        var getUnreadMessageCount = function(){
            $.ajax({
                url: '../../users/get-im-unread-message',
                type: "GET",
                success: function(data) {
                    var count = JSON.parse(data)['count'];
                    if (count == 0) {
                        $('.chat-message-box').hide();
                    } else {
                        $('.char-unread-num').text(count);
                        $('.chat-message-box').show();
                    }
                }
            });
        }

        //初始化获取未读信息数量
        getUnreadMessageCount();
        //间隔30s获取未读信息数量
        setInterval(function(){
            getUnreadMessageCount();
        },15*1000);

    </script>
@stop






