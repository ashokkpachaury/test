
<div class="left side-menu">

      <div class="sidebar-inner slimscrollleft">
        <div id="sidebar-menu">
          @if(Auth::User()->usertype =="Admin")
                <ul>
                    <li><a href="{{ URL::to('admin/dashboard') }}"
                           class="waves-effect {{classActivePath('dashboard')}}"><i class="fa fa-dashboard"></i>
                            <span> {{trans('words.dashboard_text')}}</span></a></li>
                    <li><a href="{{ URL::to('admin/genres') }}" class="waves-effect {{classActivePath('genres')}}"><i
                                class="fa fa-list"></i> <span> {{trans('words.genres_text')}}</span></a></li>
                    <li><a href="{{ URL::to('admin/movies') }}" class="waves-effect {{classActivePath('movies')}}"><i
                                class="fa fa-video-camera"></i> <span> {{trans('words.movies_text')}}</span></a></li>
                    <li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect"><i
                                class="fa fa-film"></i><span>{{trans('words.tv_shows_text')}} </span> <span
                                class="menu-arrow"></span></a>
                        <ul class="list-unstyled">
                            <li class="{{classActivePath('series')}}"><a href="{{ URL::to('admin/series') }}"
                                                                         class="{{classActivePath('series')}}"><i
                                        class="fa fa-image"></i> <span> {{trans('words.shows_text')}}</span></a></li>
                            <li class="{{classActivePath('season')}}"><a href="{{ URL::to('admin/season') }}"
                                                                         class="{{classActivePath('season')}}"><i
                                        class="fa fa-tree"></i> <span> {{trans('words.seasons_text')}}</span></a></li>
                            <li class="{{classActivePath('episodes')}}"><a href="{{ URL::to('admin/episodes') }}"
                                                                           class="{{classActivePath('episodes')}}"><i
                                        class="fa fa-list"></i> <span> {{trans('words.episodes_text')}}</span></a></li>
                        </ul>
                    </li>
                    <li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect"><i
                                class="fa fa-tv"></i><span>{{trans('words.live_tv')}}</span> <span
                                class="menu-arrow"></span></a>
                        <ul class="list-unstyled">
                            <li class="{{classActivePath('tv_category')}}"><a href="{{ URL::to('admin/tv_category') }}"
                                                                              class="{{classActivePath('tv_category')}}"><i
                                        class="fa fa-tags"></i> <span> {{trans('words.live_tv_category')}}</span></a>
                            </li>
                            <li class="{{classActivePath('live_tv')}}"><a href="{{ URL::to('admin/live_tv') }}"
                                                                          class="{{classActivePath('live_tv')}}"><i
                                        class="fa fa-list"></i> <span> {{trans('words.tv_channel')}}</span></a></li>
                        </ul>
                    </li>
                    <li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect"><i
                                class="fa fa-sliders"></i><span>{{trans('words.home')}} </span> <span
                                class="menu-arrow"></span></a>
                        <ul class="list-unstyled">
                            <li class="{{classActivePath('slider')}}"><a href="{{ URL::to('admin/slider') }}"
                                                                         class="{{classActivePath('slider')}}"><i
                                        class="fa fa-sliders"></i> <span> {{trans('words.slider')}}</span></a></li>
                            <li class="{{classActivePath('home-section')}}"><a
                                    href="{{ URL::to('admin/home-section') }}"
                                    class="{{classActivePath('home-section')}}"><i class="fa fa-list"></i>
                                    <span> {{trans('words.home_section')}}</span></a></li>

{{--                            <li class="{{classActivePath('home_section')}}"><a--}}
{{--                                    href="{{ URL::to('admin/home_section') }}"--}}
{{--                                    class="{{classActivePath('home_section')}}"><i class="fa fa-list"></i>--}}
{{--                                    <span> {{trans('words.home_section')}} OLD</span></a></li>--}}
                        </ul>
                    </li>
                    <li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect"><i
                                class="fa fa-users"></i><span>{{trans('words.users')}} </span> <span
                                class="menu-arrow"></span></a>
                        <ul class="list-unstyled">
                            <li class="{{classActivePath('users')}}"><a href="{{ URL::to('admin/users') }}"
                                                                        class="{{classActivePath('users')}}"><i
                                        class="fa fa-users"></i> <span> {{trans('words.users')}}</span></a></li>
                            <li class="{{classActivePath('moderators')}}"><a href="{{ URL::to('admin/moderators') }}"
                                                                             class="{{classActivePath('moderators')}}"><i
                                        class="fa fa-users"></i> <span> {{trans('words.moderators')}}</span></a></li>
                            <li class="{{classActivePath('sub_admin')}}"><a href="{{ URL::to('admin/sub_admin') }}"
                                                                            class="{{classActivePath('sub_admin')}}"><i
                                        class="fa fa-users"></i> <span> {{trans('words.admin')}}</span></a></li>
                        </ul>
                    </li>
                    <!-- Introducing User Profiles -->
                    <li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect"><i
                                class="fa fa-users"></i><span>{{trans('words.profile')}}s </span> <span
                                class="menu-arrow"></span></a>
                        <ul class="list-unstyled">
                            <li class="{{classActivePath('user_profiles')}}"><a href="{{ URL::to('admin/user_profiles') }}"
                                                                        class="{{classActivePath('user_profiles')}}"><i
                                        class="fa fa-users"></i> <span> {{trans('words.profile')}}s</span></a></li>
                            <li class="{{classActivePath('user_profile_images')}}"><a href="{{ URL::to('admin/user_profile_images') }}"
                                                                             class="{{classActivePath('user_profile_images')}}"><i
                                        class="fa fa-users"></i> <span> {{trans('words.user_profile_images')}}</span></a></li>
                        </ul>
                    </li>
                    <li><a href="{{ URL::to('admin/coupons') }}" class="waves-effect {{classActivePath('coupons')}}"><i
                                class="fa fa-gift"></i> <span>{{trans('words.coupons')}}</span></a></li>
                    <li><a href="{{ URL::to('admin/subscription_plan') }}"
                           class="waves-effect {{classActivePath('subscription_plan')}}"><i class="fa fa-dollar"></i>
                            <span>{{trans('words.subscription_plan')}}</span></a></li>
                    <li><a href="{{ URL::to('admin/users_subscription') }}"
                           class="waves-effect {{classActivePath('users_subscription')}}"><i class="fa fa-users"></i>
                            <span> {{trans('words.users_subscription')}}</span></a></li>
                    <li><a href="{{ URL::to('admin/ads_list') }}"
                           class="waves-effect {{classActivePath('ads_list')}}"><i class="fa fa-buysellads"></i>
                            <span>{{trans('words.ad_management')}}</span></a></li>
                    <li><a href="{{ URL::to('admin/push_notification') }}"
                           class="waves-effect {{classActivePath('push_notification')}}"><i class="fa fa-send"></i>
                            <span> {{trans('words.push_settings')}}</span></a></li>
                    <li><a href="{{ URL::to('admin/transactions') }}"
                           class="waves-effect {{classActivePath('transactions')}}"><i class="fa fa-list"></i>
                            <span> {{trans('words.transactions')}}</span></a></li>
                    <li><a href="{{ URL::to('admin/revenue') }}" class="waves-effect {{classActivePath('revenue')}}"><i
                                class="fa fa-list"></i> <span> {{trans('words.revenue')}}</span></a></li>
                    <li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect"><i
                                class="fa fa-edit"></i><span>{{trans('words.pages')}} </span> <span
                                class="menu-arrow"></span></a>
                        <ul class="list-unstyled">
                            <li class="{{classActivePath('about_page')}}"><a href="{{ URL::to('admin/about_page') }}"
                                                                             class="{{classActivePath('about_page')}}"><i
                                        class="fa fa-file"></i> <span> {{trans('words.about_us')}}</span></a></li>
                            <li class="{{classActivePath('terms_page')}}"><a href="{{ URL::to('admin/terms_page') }}"
                                                                             class="{{classActivePath('terms_page')}}"><i
                                        class="fa fa-file"></i> <span> {{trans('words.terms_of_us')}}</span></a></li>
                            <li class="{{classActivePath('privacy_policy_page')}}"><a
                                    href="{{ URL::to('admin/privacy_policy_page') }}"
                                    class="{{classActivePath('privacy_policy_page')}}"><i class="fa fa-file"></i>
                                    <span> {{trans('words.privacy_policy')}}</span></a></li>
                            <li class="{{classActivePath('faq_page')}}"><a href="{{ URL::to('admin/faq_page') }}"
                                                                           class="{{classActivePath('faq_page')}}"><i
                                        class="fa fa-file"></i> <span> {{trans('words.faq')}}</span></a></li>
                            <li class="{{classActivePath('contact_page')}}"><a
                                    href="{{ URL::to('admin/contact_page') }}"
                                    class="{{classActivePath('contact_page')}}"><i class="fa fa-file"></i>
                                    <span> {{trans('words.contact_us')}}</span></a></li>
                        </ul>
                    </li>
                    <li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect"><i
                                class="fa fa-cog"></i><span>{{trans('words.settings')}} </span> <span
                                class="menu-arrow"></span></a>
                        <ul class="list-unstyled">
                            <li class="{{classActivePath('android_settings')}}"><a
                                    href="{{ URL::to('admin/android_settings') }}"
                                    class="{{classActivePath('android_settings')}}"><i class="fa fa-cog"></i>
                                    <span> {{trans('words.android_app_settings')}}</span></a></li>
                            <li class="{{classActivePath('email_settings')}}"><a
                                    href="{{ URL::to('admin/email_settings') }}"
                                    class="{{classActivePath('email_settings')}}"><i class="fa fa-envelope"></i>
                                    <span> {{trans('words.smtp_email')}}</span></a></li>
                            <li class="{{classActivePath('social_login_settings')}}"><a
                                    href="{{ URL::to('admin/social_login_settings') }}"
                                    class="{{classActivePath('social_login_settings')}}"><i class="fa fa-usb"></i>
                                    <span> {{trans('words.social_login')}}</span></a></li>
                            <li class="{{classActivePath('payment_settings')}}"><a
                                    href="{{ URL::to('admin/payment_settings') }}"
                                    class="{{classActivePath('payment_settings')}}"><i class="fa fa-ticket"></i>
                                    <span> {{trans('words.payment')}}</span></a></li>
                            <li class="{{classActivePath('player_settings')}}"><a
                                    href="{{ URL::to('admin/player_settings') }}"
                                    class="{{classActivePath('player_settings')}}"><i class="fa fa-play-circle"></i>
                                    <span> {{trans('words.player_settings')}}</span></a></li>
                            <li class="{{classActivePath('users_import')}}"><a
                                    href="{{ URL::to('admin/users_import') }}"
                                    class="{{classActivePath('users_import')}}"><i class="fa fa-refresh"></i>
                                    <span> {{trans('words.users_import')}}</span></a></li>


                                    <li class="{{classActivePath('import_feed')}}"><a href="{{ URL::to('admin/import_feed') }}"
                                        class="{{classActivePath('import_feed')}}"><i
  class="fa fa-refresh"></i> <span> Import from feed</span></a></li>

<li class="{{classActivePath('payment_settings')}}"><a href="{{ URL::to('clear-cache') }}"
                                             class="{{classActivePath('payment_settings')}}"><i
  class="fa fa-refresh"></i> <span> {{trans('words.clear-cache')}}</span></a></li>


  <li class="{{ classActivePath('clear_feed') }}">
        <a href="{{ URL::to('admin/clear_feed') }}" class="{{ classActivePath('clear_feed') }}"
            onclick="return confirmClearFeed(event)">
            <i class="fa fa-trash"></i>
 <span> Clear feed</span>
        </a>
    </li>
    
                            



                        </ul>
                    </li>
                    @else
                        <ul>
                            <li><a href="{{ URL::to('admin/dashboard') }}"
                                   class="waves-effect {{classActivePath('dashboard')}}"><i class="fa fa-dashboard"></i>
                                    <span> {{trans('words.dashboard_text')}}</span></a></li>
                            <li><a href="{{ URL::to('admin/genres') }}"
                                   class="waves-effect {{classActivePath('genres')}}"><i class="fa fa-list"></i>
                                    <span> {{trans('words.genres_text')}}</span></a></li>
                            <li><a href="{{ URL::to('admin/movies') }}"
                                   class="waves-effect {{classActivePath('movies')}}"><i class="fa fa-video-camera"></i>
                                    <span> {{trans('words.movies_text')}}</span></a></li>
                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i
                                        class="fa fa-tv"></i><span>{{trans('words.tv_shows_text')}} </span> <span
                                        class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li class="{{classActivePath('series')}}"><a href="{{ URL::to('admin/series') }}"
                                                                                 class="{{classActivePath('series')}}"><i
                                                class="fa fa-image"></i> <span> {{trans('words.shows_text')}}</span></a>
                                    </li>
                                    <li class="{{classActivePath('season')}}"><a href="{{ URL::to('admin/season') }}"
                                                                                 class="{{classActivePath('season')}}"><i
                                                class="fa fa-tree"></i>
                                            <span> {{trans('words.seasons_text')}}</span></a></li>
                                    <li class="{{classActivePath('episodes')}}"><a
                                            href="{{ URL::to('admin/episodes') }}"
                                            class="{{classActivePath('episodes')}}"><i class="fa fa-list"></i>
                                            <span> {{trans('words.episodes_text')}}</span></a></li>
                                </ul>
                            </li>
                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i
                                        class="fa fa-tv"></i><span>{{trans('words.live_tv')}}</span> <span
                                        class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li class="{{classActivePath('tv_category')}}"><a
                                            href="{{ URL::to('admin/tv_category') }}"
                                            class="{{classActivePath('tv_category')}}"><i class="fa fa-tags"></i>
                                            <span> {{trans('words.live_tv_category')}}</span></a></li>
                                    <li class="{{classActivePath('live_tv')}}"><a href="{{ URL::to('admin/live_tv') }}"
                                                                                  class="{{classActivePath('live_tv')}}"><i
                                                class="fa fa-list"></i> <span> {{trans('words.tv_channel')}}</span></a>
                                    </li>
                                </ul>
                            </li>
                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i
                                        class="fa fa-sliders"></i><span>{{trans('words.home')}} </span> <span
                                        class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li class="{{classActivePath('slider')}}"><a href="{{ URL::to('admin/slider') }}"
                                                                                 class="{{classActivePath('slider')}}"><i
                                                class="fa fa-sliders"></i> <span> {{trans('words.slider')}}</span></a>
                                    </li>
                                    <li class="{{classActivePath('home_section')}}"><a
                                            href="{{ URL::to('admin/home_section') }}"
                                            class="{{classActivePath('home_section')}}"><i class="fa fa-list"></i>
                                            <span> {{trans('words.home_section')}}</span></a></li>
                                </ul>
                            </li>
                        </ul>
                    @endif
          </ul>
        </div>
      </div>
    </div>




