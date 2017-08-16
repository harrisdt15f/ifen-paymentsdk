<?php
if (isset($_POST['result'])) {
	$result =$_POST['result'];
	$result = json_decode($result,true);
	extract($result);
	$bSetFundPassword = true;
	$sAllBanksJs = json_encode($payment_setting_data['banks']); // 页面JS数据接口
    $oAllBanks = json_decode($sAllBanksJs);
    $is_tester = false;
    $fMinLoad =2;
    $fMaxLoad =10;
    $current_tab = 'banks';
    $aTabList = $available_gateway_and_name; //上面菜单数据
	?>
	<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge"  />
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
	<title>-博猫彩票</title>
 		<link rel="stylesheet" type="text/css" href="../assets/css/animate.css">
 		<link rel="stylesheet" type="text/css" href="../assets/css/font-awesome.css">
 		<link rel="stylesheet" type="text/css" href="../assets/css/global.css">
 		<link rel="stylesheet" type="text/css" href="../assets/css/proxy.css">
 		<link rel="stylesheet" type="text/css" href="../assets/css/ucenter.css">
 		<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
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
                @font-face{
                    font-family:dyBebas;
                    src:url('/assets/images/global/dyBebas.ttf');
                }
                @font-face{
                    font-family:FZLanTingHei-R-GBK;
                    src:url('/assets/images/global/FZLanTingHei-R-GBK.ttf');
                }
                @font-face {
                    font-family: 'open24';
                    src: url( /assets/images/game/pk10/open24.eot ); /* IE */
                    src: url( /assets/images/game/pk10/open24.ttf ) format("truetype");  /* 非IE */
                }
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
        /**/
        .way-img-weixin {
            background: url('../assets/images/way-6.png') no-repeat;
        }
        .way-img-banks {
            background: url('../assets/images/way-2.png') no-repeat;
            background-position: 0px 1px;
        }
        .way-img-unionpay {
            background: url('../assets/images/unionpay.png') no-repeat;
            background-position: 0px 1px;
        }
            </style>
        <script type="text/javascript" src="../assets/js/jquery-1.9.1.js"></script>
        <script type="text/javascript" src="../assets/js/base-all.js"></script>
</head>
<body>
<?php
if ($message = @$_SESSION['success']) {?>
<div class="pop" id="popWindow" style="display:none;">
    <div class="pop-hd">
        <!-- <a href="#" class="pop-close"></a> -->
        <button type="button" class="pop-close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h3 class="pop-title">标题</h3>
    </div>
    <div class="pop-bd">
        <div class="pop-content">
            <i class="ico-success"></i>
            <p class="pop-text">{{ $message }}</p>
        </div>
        <div class="pop-btn">
            <input type="button" value="确 定" class="btn">
            <input type="button" value="取 消" class="btn btn-normal">
        </div>
    </div>
</div>
<?php
}
?>
<?php
if ($message = @$_SESSION['error']) {?>
<div class="pop" id="popWindow" style="display:none;">
    <div class="pop-hd">
        <a href="#" class="pop-close"></a>
        <h3 class="pop-title">标题</h3>
    </div>
    <div class="pop-bd">
        <div class="pop-content">
            <i class="ico-error"></i>
            <p class="pop-text">{{ $message }}</p>
        </div>
        <div class="pop-btn">
            <input type="button" value="确 定" class="btn">
            <input type="button" value="取 消" class="btn btn-normal">
        </div>
    </div>
</div>
<?php
}
?>
<?php
if ($message = @$_SESSION['error']) {?>
<div class="pop" id="popWindow" style="display:none;">
    <div class="pop-hd">
        <a href="#" class="pop-close"></a>
        <h3 class="pop-title">标题</h3>
    </div>
    <div class="pop-bd">
        <div class="pop-content">
            <i class="ico-waring"></i>
            <p class="pop-text">{{ $message }}</p>
        </div>
        <div class="pop-btn">
            <input type="button" value="确 定" class="btn">
            <input type="button" value="取 消" class="btn btn-normal">
        </div>
    </div>
</div>
<?php
}
?>
<?php
if ($message = @$_SESSION['info']) {?>
<div class="pop w-13" id="popWindow" style="display:none;">
    <div class="pop-hd">
        <a href="#" class="pop-close"></a>
        <h3 class="pop-title">信息确认</h3>
    </div>
    <div class="pop-bd">
        <div class="pop-content">
            <p class="pop-text">该用户的具体信息如下，是否立即开户？</p>
            <div class="bonusgroup-title">
                <table width="100%">
                    <tbody><tr>
                        <td>Terence2014<br><span class="tip">用户名称</span></td>
                        <td>特伦苏<br><span class="tip">用户昵称</span></td>
                        <td>代理<br><span class="tip">用户类型</span></td>
                        <td>66,888,888.00 元<br><span class="tip">可用余额</span></td>
                        <td class="last">66,888,888.00 元<br><span class="tip">奖金限额</span></td>
                    </tr>
                </tbody></table>
            </div>
        </div>
        <div class="pop-btn">
            <input type="button" value="确 定" class="btn">
            <input type="button" value="取 消" class="btn btn-normal">
        </div>
    </div>
</div>
<?php
}
?>
<!-- ############################################################################### -->
<div class="page-content">
            <div class="container main clearfix">
                <div class="main-content">
                   <!-- ############################## -->
                    <div class="nav-inner nav-bg-tab">
        <div class="title-normal">
            充值
        </div>
        <!-- #######################banks tabs##################################### -->
        <ul class="tab-title">
        <?php
        foreach ($aTabList as $sTabKey => $sTabText) {
        	if ($sTabKey == 'pay'){
        		?>
        		<li <?php if($sTabKey == $current_tab) echo 'class="current"'; ?> >
		                <span <?php if($sTabKey == $current_tab)echo 'class="top-bg"';?>></span>
		                <a target="_blank" href="<?=$confirm_internal[$sTabKey]?>"><span><div
		                                class="way-img way-img-{{$sTabKey}}"></div><?=$sTabText?></span></a>
		            </li>
        	<?php
        	}
        	else {
        		?>
        		<li <?php if($sTabKey == $current_tab) echo 'class="current"'; ?>>
		                <span <?php if($sTabKey == $current_tab) echo 'class="top-bg"';?>></span>
		                <a href="<?=$confirm_internal[$sTabKey]?>"><span><div
		                                class="way-img way-img-<?=$sTabKey == 'alipay-to-netbank' ? 'alipay' : $sTabKey?>"></div>
		                                <?php
		                                if(count($sTabText) > 0)
		                                {
		                                	echo $sTabText;
		                                }
		                            	else{
		                            		echo $sTabKey;
		                            	}
		                                ?>
		            </span></a>
		            </li>
        		<?php
        	}
        }
        ?>
		</ul>
        <!-- ############################################################### -->
    </div>
    <div class="content page-content-inner recharge-netbank">
        <div class="prompt">
            请在新打开支付页面进行充值操作。 </br>
            如果您的浏览器未弹出新的支付页面，请取消浏览器对弹出页面的阻拦，并选择允许（信任）
        </div>
        <form action="<?= $confirm_internal["banks"] ?>" method="post" id="J-form" target="_blank">
           <!--  <input type="hidden" name="_token" value="{{ csrf_token() }}"/> -->
            <input type="hidden" name="deposit_mode" value="<?= $deposit_mode ?>"/>
            <input type="hidden" name="payment_data_json" value="<?= $payment_data_json ?>"/>
            <table width="100%" class="table-field">
                <tr>
                    <td width="120" align="right" valign="top"><span class="field-name">选择充值银行：</span></td>
                    <td>
                        <div class="bank-more-content">
                            <div class="bank-list" id="J-bank-list">
                            <?php
                            foreach ($oAllBanks as $oBank) {?>
                            <label class="img-bank" for="J-bank-name-<?= $oBank->code?>">
                                        <input data-id="<?=$oBank->code ?>" name="bank" value="<?=$oBank->code ?>"
                                               id="J-bank-name-<?= $oBank->code ?>"
                                               type="radio"/>
                                        <span class="ico-bank <?=$oBank->code ?>"></span>
                                    </label>
                            <?php
                            }
                            ?>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td align="right" valign="top"><span class="field-name">充值金额：</span></td>
                    <td>
                        <input type="text" class="input w-2 input-ico-money" id="J-input-money" name="amount"/>
                        <br/>
                        <span class="tip">充值额度限定：最低 <span id="J-money-min"><?= $fMinLoad ?></span>,最高 <span
                                    id="J-money-max"><?=$fMaxLoad ?></span> 元<br/>
                    单日充值总额无上限，充值无手续费</span>
                    </td>
                </tr>
                <?php
                if (isset($bCheckFundPassword)) {
                	?>
                	<tr>
                        <td align="right" valign="top">资金密码：</td>
                        <td>
                            <input type="password" maxlength="16" class="input w-2 input-ico-lock" id="J-input-password"
                                   name="fund_password"/>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                <tr>
                    <td align="right" valign="top">&nbsp;</td>
                    <td>
                        <input id="J-submit" class="btn" type="submit" value="   立即充值   "/>
                    </td>
                </tr>
            </table>
        </form>
    </div>
                   <!-- ############################## -->
                </div>
            </div>
        </div>
<!-- ############################################################################### -->
<script type="text/javascript">
    (function(){
        if ($('#popWindow').length) {
            var popWindow = new bomao.Message();
            var data = {
                            title          : '提示',
                            content        : $('#popWindow').find('.pop-bd > .pop-content').html(),
                            isShowMask     : true,
                            closeIsShow    : true,
                            closeButtonText: '关闭',
                            closeFun       : function() {
                                this.hide();
                            }
            };
            popWindow.show(data);
        }
    })();
</script>
<script>
        (function ($) {
                    /*{{-- 未设置资金密码 --}}*/
                    <?php
                    if (!$bSetFundPassword) {
                    	?>
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
                    	<?php
                    }
                    ?>
                    // {{-- 银行及用户银行卡JS数据接口 --}}
            var bankCache = <?= $sAllBanksJs ?>;
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
                /*{{--@if ($bCheckFundPassword)
                if ($.trim(password.val()) == '') {
                    alert('资金密码不能为空');
                    password.focus();
                    return false;
                }
                @endif--}}*/
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
</body>
</html>

<?php
}
else{
	echo 'wrong parameter';
}
?>
