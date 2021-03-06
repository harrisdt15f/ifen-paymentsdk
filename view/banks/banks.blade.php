@extends('pamentsdkonly.common.home')
@section('title')
    网银快捷充值-充值
    @parent
@stop
@section ('styles')
    @parent
    {{ style('proxy-global') }}
    {{ style('proxy') }}
    <style type="text/css">
        .main-content .content {
            padding-top: 10px;
        }

        /*.main {
            padding: 0;
            margin-top: 0
        }
        .layout-row {
            float: left;
        }*/
        .page-content .row {
            padding: 20px 0 10px 0;
            margin: 0;
        }

        .page-content-inner {
            box-shadow: 1px 1px 10px rgba(102, 102, 102, 0.1);
            border: 0px solid #CCC;
            border-top: 0;
            background: #FFF;
        }

        /*here is banks style overwritting */
        .ABOC {
            background-position: 23px 5px;
        }
        .BKCH {
            background-position: 23px -68px;
        }
        .PCBC {
            background-position: 23px -141px;
        }
        .EVER {
            background-position: 23px -177px;
        }
        .CIBK {
            background-position: 23px -212px;
        }
        .CMBC {
            background-position: 23px -248px;
        }

        .MSBC {
            background-position: 23px -284px;
        }
        .COMM {
            background-position: 23px -322px;
        }

        .ICBK {
            background-position: 22px -535px;
        }

        .FJIB {
            background-position: 23px -1040px;
        }

        .SZDB {
            background-position: 23px -861px;
        }

        .HXBK {
            background-position: 23px -465px;
        }
    </style>
@stop
@section ('main')
    <div class="nav-inner nav-bg-tab">
        <div class="title-normal">
            充值
        </div>
        @include ('pamentsdkonly.common._bank_tab')
    </div>
    <div class="content page-content-inner recharge-netbank">
        <div class="prompt">
            请在新打开支付页面进行充值操作。 </br>
            如果您的浏览器未弹出新的支付页面，请取消浏览器对弹出页面的阻拦，并选择允许（信任）
        <!--充值额度为 {{--{{$fMinLoad}}--}} 至 {{--{{$fMaxLoad}}--}} 元，给您带来的不便，敬请谅解。-->
        </div>
        <form action="{{$confirm_internal["banks"]}}" method="post" id="J-form" target="_blank">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
            <input type="hidden" name="deposit_mode" value="{{ $deposit_mode }}"/>
            <input type="hidden" name="payment_data_json" value="{{ $payment_data_json }}"/>
            <table width="100%" class="table-field">
                {{--
                <tr>
                    <td></td>
                    <td class="r-gc"></td>
                </tr>
                --}}
                <tr>
                    <td width="120" align="right" valign="top"><span class="field-name">选择充值银行：</span></td>
                    <td>
                        <div class="bank-more-content">
                            <div class="bank-list" id="J-bank-list">
                                @foreach($oAllBanks as $oBank)
                                    {{--<label class="img-bank" for="J-bank-name-{{ $oBank->identifier }}">--}}
                                    <label class="img-bank" for="J-bank-name-{{ $oBank->code }}">
                                        <input data-id="{{ $oBank->code }}" name="bank" value="{{ $oBank->code }}"
                                               id="J-bank-name-{{ $oBank->code }}"
                                               {{--@if($oBank->is_mbank)
                                               disabled
                                               @endif--}}
                                               type="radio"/>
                                        <span class="ico-bank {{ $oBank->code }}"></span>
                                        {{--@if($oBank->is_mbank)
                                            <div class="whz">
                                                <div class="a1">
                                                    {{$oBank->is_mbank}}
                                                    <div class="a1-1"></div>
                                                </div>
                                            </div>
                                        @endif--}}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td align="right" valign="top"><span class="field-name">充值金额：</span></td>
                    <td>
                        <input type="text" class="input w-2 input-ico-money" id="J-input-money" name="amount"/>
                        <br/>
                        <span class="tip">充值额度限定：最低 <span id="J-money-min">{{--{{ $fMinLoad }}--}}</span>,最高 <span
                                    id="J-money-max">{{--{{ $fMaxLoad }}--}}</span> 元<br/>
                    单日充值总额无上限，充值无手续费</span>
                    </td>
                </tr>
                @if(isset($bCheckFundPassword))
                    <tr>
                        <td align="right" valign="top">资金密码：</td>
                        <td>
                            <input type="password" maxlength="16" class="input w-2 input-ico-lock" id="J-input-password"
                                   name="fund_password"/>
                        </td>
                    </tr>
                @endif
                <tr>
                    <td align="right" valign="top">&nbsp;</td>
                    <td>
                        <input id="J-submit" class="btn" type="submit" value="   立即充值   "/>
                    </td>
                </tr>
            </table>
        </form>
    </div>
@stop
@section('end')
    @parent
    <script>
        (function ($) {
                    {{-- 未设置资金密码 --}}
                    @if (!$bSetFundPassword)
            var msg = bomao.Message.getInstance();
            msg.show({
                content: "<div style='padding-bottom:10px;font-size:14px;'>使用充值前需设置资金密码，是否现在进行设置？</div>",
                confirmIsShow: true,
                cancelIsShow: true,
                isShowMask: true,
                confirmFun: function () {
                    location.href = "{{ route('users.safe-reset-fund-password') }}";
                },
                cancelFun: function () {
                    msg.hide();
                }
            });
            /**
             if(confirm("使用充值前需设置资金密码，是否现在进行设置？")) {
             location.href = "{{--{{ route('users.safe-reset-fund-password') }}--}}";
             } else {
             location.href = "/";
             }
             **/
                    @endif
                    {{-- 银行及用户银行卡JS数据接口 --}}
            var bankCache = {{$sAllBanksJs}};
            var banks = $('#J-bank-list').children(), inputs = banks.find('input'), loadBankInfoById, buildingView,
                moneyInput = $('#J-input-money'),
                tip = new bomao.Tip({cls: 'j-ui-tip-b j-ui-tip-input-floattip'});
            loadBankInfoById = function (id, callback) {
                var data = bankCache[id];
                callback(data);
            };
            buildingView = function (bankData) {
                $('#J-money-min').text(bomao.util.formatMoney(Number(bankData['currency_min'])));
                $('#J-money-max').text(bomao.util.formatMoney(Number(bankData['currency_max'])));
                $('#J-input-money').val('');
                $('#J-input-password').val('');
            };
            inputs.click(function () {
                var el = $(this), checked = this.checked, id = $.trim(el.attr('data-id'));
                if (checked) {
                    loadBankInfoById(id, buildingView);
                }
            });
            moneyInput.keyup(function (e) {
                var v = $.trim(this.value), arr = [], code = e.keyCode;
                if (code == 37 || code == 39) {
                    return;
                }
                v = v.replace(/[^\d|^\.]/g, '');
                arr = v.split('.');
                if (arr.length > 2) {
                    v = '' + arr[0] + '.' + arr[1];
                }
                arr = v.split('.');
                if (arr.length > 1) {
                    arr[1] = arr[1].substring(0, 2);
                    v = arr.join('.');
                }
                this.value = v;
                v = v == '' ? '&nbsp;' : v;
                tip.setText(v);
                tip.getDom().css({left: moneyInput.offset().left + moneyInput.width() / 2 - tip.getDom().width() / 2});
            });
            moneyInput.focus(function () {
                var v = $.trim(this.value);
                if (v == '') {
                    v = '&nbsp;';
                }
                tip.setText(v);
                tip.show(moneyInput.width() / 2 - tip.getDom().width() / 2, tip.getDom().height() * -1 - 20, this);
            });
            moneyInput.blur(function () {
                var v = Number(this.value), minNum = Number($('#J-money-min').text().replace(/,/g, '')),
                    maxNum = Number($('#J-money-max').text().replace(/,/g, ''));
                v = v < minNum ? minNum : v;
                v = v > maxNum ? maxNum : v;
                this.value = bomao.util.formatMoney(v).replace(/,/g, '');
                tip.hide();
            });
            $('#J-submit').click(function () {
                var money = $('#J-input-money'),
                    password = $('#J-input-password'),
                    banks = $('input:radio[name="bank"]:checked').val();
                // bankCard = $('.choose-input-disabled');
                //if没有开启银行卡判断
                if (banks == undefined || banks == '') {
                    alert('请选择充值银行');
                    return false;
                }
                if ($.trim(money.val()) == '') {
                    alert('金额不能为空');
                    money.focus();
                    return false;
                }
                {{--@if ($bCheckFundPassword)
                if ($.trim(password.val()) == '') {
                    alert('资金密码不能为空');
                    password.focus();
                    return false;
                }
                @endif--}}
                    return true;
            });
            $('.whz').hover(function () {
                $(this).find('.a1').show();
            }, function () {
                $(this).find('.a1').hide();
            })
//        $('#J-bank-list label').hover(function () {
//            $(this).addClass('current');
//        },function(){
//            $(this).removeClass('current');
//        }).on('click',function () {
//            $(this).siblings('label').removeClass('active');
//            $('#J-bank-list input:checked').parents('label').addClass('active');
//        })
            $('#J-bank-list label').on('click', function () {
                $(this).siblings('label').find('span').removeClass('active');
                $('#J-bank-list input:checked').siblings('span').addClass('active');
            })
        })(jQuery);
    </script>
@stop