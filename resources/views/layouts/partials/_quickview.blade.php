<form id="customize-form" action="javascript:;" method="post" endpoint="{{ route('theme.uploads') }}">
<div id="quickview" class="quickview-wrapper open" data-pages="quickview">
  <!-- Nav tabs -->
  <ul class="nav nav-tabs">
    <li class="active" data-target="#theme-settings" data-toggle="tab">
      <a href="#">settings</a>
    </li>
    <li data-target="#section-settings" data-toggle="tab">
      <a href="#">Sections</a>
    </li>
  </ul>
  <a class="btn-link quickview-toggle" data-toggle-element="#quickview" data-toggle="quickview"></a>
  <!-- Tab panes -->
  
  <div class="tab-content">
    <div class="tab-pane active no-padding" id="theme-settings">

        <div class="view-port clearfix" id="theme-setting-list">
          <div class="view bg-white">
            <div data-init-list-view="ioslist" class="list-view boreded no-top-border">
              <div class="list-view-group-container">
                <ul>
                  <!-- BEGIN Chat User List Item  !-->
                  @foreach($setting_menu->roots() as $item)
                    <li class="chat-user-list clearfix">
                      {!! $item->afterHTML !!}

                    </li>
                  @endforeach
                  <!--<li class="chat-user-list clearfix">
                    <a data-view-animation="push-parrallax" data-view-port="#theme-setting-list" data-navigate="view" data-toggle-view="#subView1" class="" href="#">
                      <span class="thumbnail-wrapper d32 circular bg-success">
                          <i class="fa fa-paint-brush"></i>
                      </span>
                      <p class="p-l-10 ">
                        <span class="text-master">Colors</span>
                      </p>
                    </a>
                  </li>
                  <li class="chat-user-list clearfix">
                    <a data-view-animation="push-parrallax" data-view-port="#theme-setting-list" data-navigate="view" data-toggle-view="#subView2" class="" href="#">
                      <span class="thumbnail-wrapper d32 circular bg-success">
                          <i class="fa fa-paint-brush"></i>
                      </span>
                      <p class="p-l-10 ">
                        <span class="text-master">tests</span>
                      </p>
                    </a>
                  </li>-->
                  <!-- END Chat User List Item  !-->
                </ul>
              </div>
            </div>
          </div>
          <!-- BEGIN Conversation View  !-->
          <div  class="view bg-white">
            @foreach($setting_submenu->roots() as $value)
        
              {!! $value->afterHTML !!}
                    
             @endforeach
          </div>
        </div>
       
    </div>
    <div class="tab-pane no-padding" id="section-settings">

        <div class="view-port clearfix" id="section-setting-list">
          <div class="view bg-white">
            <div data-init-list-view="ioslist" class="list-view boreded no-top-border">
              <div class="list-view-group-container">
                <ul>
                  <!-- BEGIN Chat User List Item  !-->
                  @foreach($section_menu->roots() as $section_item)
                    <li class="chat-user-list clearfix">
                      {!! $section_item->afterHTML !!}

                    </li>
                  @endforeach
            
                </ul>
              </div>
            </div>
          </div>
          <!-- BEGIN Conversation View  !-->
          <div  class="view bg-white">
            @foreach($section_submenu->roots() as $section_value)
                    
              {!! $section_value->afterHTML !!}
                    
             @endforeach
          </div>
        </div>
       
    </div>
  </div>
</div>
</form>
