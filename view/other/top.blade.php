
<div class="layout-top">
	<div class="container">
		<div class="inner clearfix">

			<div class="left">
				<div class="notice">
					<a href="/pc-client/index.html">PC客户端</a>
					&nbsp;&nbsp;
					<a href="/mobile">手机客户端</a>
					&nbsp;&nbsp;
					<a href="/fastlogin/index.html">快速登录器</a>
					&nbsp;&nbsp;
					<a href="/events/repairDNS">防劫持教程</a>
				</div>
			</div>

			<div class="right">
				<ul>
					<li>
                        <a href="{{route('activity.index')}}" class="at">
                            <i class="gift"></i>
                            <span>活动专区</span>
                        </a>
					</li>
					<li class="chat-message-box" style="display: none;">
						<a class="at chat-unread" href="/users/chat" target="_blank">&nbsp;</a>
						<span class="char-unread-num"></span>
					</li>
					@if ($unreadMessagesNum)
						<li>
							<a class="at r-email0203" href="/station-letters">&nbsp;</a>
						</li>
					@endif

					<li class="mu mu-notice">
						<a class="at" href="javascript:;">平台公告</a>
						<div class="panel">
							<span class="p-sj"></span>
							<ul>
								@foreach($aLatestAnnouncements as $v)
									<li><a href="{{route('announcements.view', $v->id)}}"><i class="fa fa-angle-right"></i> {{$v->title}}</a></li>
								@endforeach
							</ul>
						</div>
					</li>
					<li class="mu mu-control">


						<a class="at" href="#">管理中心</a>

                        <?php $oUser = User::find(Session::get('user_id')); ?>
						@if($oUser->is_agent != User::TYPE_USER)
							<div class="panel panel-manage">
								@else
									<div class="panel">
										@endif
										<span class="p-sj"></span>
                                        <?php $aUserCenterMenu = UserCenterMenu::getMenu(); ?>
										@foreach($aUserCenterMenu as $k=>$v)

											@if('代理中心' == $v['title'])
												@if($oUser->is_agent != User::TYPE_USER)
													<div class="cell">
														<div class="title">{{$v['title']}}</div>
														@if(isset($v['children']) && $v['children'])
															<ul>
																@foreach($v['children'] as $j=>$n)
																	@if($n['route_name'] == 'my-overlimit-quotas.index')
																		@if(Session::get('show_overlimit'))<li><a href="{{$n['url']}}">{{$n['title']}}</a></li>@endif
																	@elseif($n['route_name'] == 'user-profits.bonus')
																		@if(Session::get('is_top_agent') ) <li><a href="{{$n['url']}}">{{$n['title']}}</a></li> @endif
																	@elseif($n['route_name'] == 'station-letters.index')
                                                                        <?php $iUnreadMsg = UserMessage::getUserUnreadMessagesNum();?>
																		@if($iUnreadMsg>0)
																			<li><i class="r-oval"></i><a href="{{$n['url']}}">{{$n['title']}}</a></li>
																		@else
																			<li><a href="{{$n['url']}}">{{$n['title']}}</a></li>
																		@endif
																	@else
																		<li><a href="{{$n['url']}}">{{$n['title']}}</a></li>
																	@endif
																@endforeach
															</ul>
														@endif
													</div>
												@endif
											@else
												<div class="cell">
													<div class="title">{{$v['title']}}</div>
													@if(isset($v['children']) && $v['children'])
														<ul>
															@foreach($v['children'] as $j=>$n)
																@if($n['route_name'] == 'my-overlimit-quotas.index')
																	@if(Session::get('show_overlimit'))<li><a href="{{$n['url']}}">{{$n['title']}}</a></li>@endif
																@elseif($n['route_name'] == 'user-profits.bonus')
																	@if(Session::get('is_top_agent') ) <li><a href="{{$n['url']}}">{{$n['title']}}</a></li> @endif
																@elseif($n['route_name'] == 'station-letters.index')
                                                                    <?php $iUnreadMsg = UserMessage::getUserUnreadMessagesNum();?>
																	@if($iUnreadMsg>0)
																		<li><i class="r-oval"></i><a href="{{$n['url']}}">{{$n['title']}}</a></li>
																	@else
																		<li><a href="{{$n['url']}}">{{$n['title']}}</a></li>
																	@endif
																@else
																	@if($n['route_name'] == 'user-transfers.index')
																		@if(Session::get('show_transfer_button'))
																			<li><a href="{{$n['url']}}">{{$n['title']}}</a></li>
																		@endif
																	@else
																		<li><a href="{{$n['url']}}">{{$n['title']}}</a></li>
																	@endif
																@endif
															@endforeach
														</ul>
													@endif
												</div>
											@endif

										@endforeach
									</div>

					</li>
					<li class="mu mu-user">
						<a class="at" href="#">
							<i class="ico-top-user"></i>
							<span>余额：<span id="J-top-user-balance" class="money-num">{{ number_format($fAvailable, 2) }}</span> 元</span>
							<i class="ico-top-sj"></i>
						</a>

						<div class="panel">
							<span class="p-sj"></span>
							<p>你好, {{ Session::get('nickname') }}</p>

							<p>
								<a href="{{ route('user-recharges.netbank') }}" class="go-recharge">立即充值</a>
							</p>
							<p class="fund-btns">
								<a href="{{ route('user-withdrawals.withdraw') }}">提款</a>

                                <?php if(Session::get('show_transfer_button')) {?>

								<a class="last" href="/user-transfers/index/">转账</a>
                                <?php }?>
							</p>
							<p class="row-logout">
								<a href="{{ route('logout') }}" class="logout"><span class="ico-logout"></span> 退出游戏</a>
							</p>
							<p class="info">
								<span>上次登录</span>
								<span>{{Session::get('last_signin_at')}}</span>
							</p>
						</div>
					</li>
                    <?php if(empty($oUser)){ $oUser = \User::find(Session::get('user_id')); } ?>
					@if ($oUser->isEnableVoucher())
						<li><span class="at">礼金：{{{ number_format($oUser->voucher_amount, 2) }}}元</span></li>
					@endif
					<li><a class="at" href="{{ route('user-recharges.netbank') }}">充值</a></li>
					<li><a class="at" href="{{ route('user-withdrawals.withdraw') }}">提款</a></li>
					<li><a class="at at-chat online-server">客服</a></li>
				</ul>
			</div>

		</div>
	</div>
</div>

