<div class="left side-menu">
  <div class="sidebar-inner slimscrollleft">
    <div id="sidebar-menu">
      @if(Auth::User()->usertype =="Moderator")
        <ul>
          <li><a href="{{ URL::to('moderator/dashboard') }}" class="waves-effect {{classActivePath('dashboard')}}"><i class="fa fa-dashboard"></i> <span> {{trans('words.dashboard_text')}}</span></a></li>
          <li><a href="{{ URL::to('moderator/movies') }}" class="waves-effect {{classActivePath('movies')}}"><i class="fa fa-video-camera"></i> <span> {{trans('words.movies_text')}}</span></a></li>
          <li class="has_sub"> 
            <a href="javascript:void(0);" class="waves-effect"><i class="fa fa-film"></i><span>{{trans('words.tv_shows_text')}} </span> <span class="menu-arrow"></span></a>
            <ul class="list-unstyled">                 
              <li class="{{classActivePath('series')}}"><a href="{{ URL::to('moderator/series') }}" class="{{classActivePath('series')}}"><i class="fa fa-image"></i> <span> {{trans('words.shows_text')}}</span></a></li>
              <li class="{{classActivePath('season')}}"><a href="{{ URL::to('moderator/season') }}" class="{{classActivePath('season')}}"><i class="fa fa-tree"></i> <span> {{trans('words.seasons_text')}}</span></a></li>
              <li class="{{classActivePath('episodes')}}"><a href="{{ URL::to('moderator/episodes') }}" class="{{classActivePath('episodes')}}"><i class="fa fa-list"></i> <span> {{trans('words.episodes_text')}}</span></a></li>
            </ul>
          </li>
          <li><a href="{{ URL::to('moderator/live_tv') }}" class="waves-effect {{classActivePath('live_tv')}}"><i class="fa fa-list"></i> <span> {{trans('words.tv_channel')}}</span></a></li>
          <li><a href="{{ URL::to('moderator/bank') }}" class="waves-effect {{classActivePath('bank')}}"><i class="fa fa-list"></i> <span> {{trans('words.bank')}}</span></a></li>
        </ul>  
        @endif
    </div>
  </div>
</div>