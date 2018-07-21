<!doctype html>
<html>
<head>
	@include('includes.head')
</head>
<body>

  <div class="off-canvas-wrap">
      <div class="inner-wrap">         
          <div class="header">
              <nav class="tab-bar" data-offcanvas>
                  <section class="left-small"> 
                    <a class="left-off-canvas-toggle menu-icon"><span></span></a>
                  </section>
                  <section class="right tab-bar-section">
                      <div class="title-app">
                        <a href="{{ URL::to('home') }}">SALARY SYSTEMS.</a>
                      </div>
                  </section>
              </nav>
          </div>
          <aside class="left-off-canvas-menu">
              <div class="content-main">
            
                  <div id="sidebar-main">
                   @include('includes.sidebar-menu')
                  </div>
              
             </div>
          </aside>
          <article class="small-12 columns">
              <section id="main-content">
                    <div class="detail-content">
                     @yield('content')
                    </div>
                </section>  
          </article> 

          <a class="exit-off-canvas"></a>
      </div>
  </div>



   
			
	
	<!-- @include('includes.footer') -->
	
<script>

//set height body
var sidebar = $('#sidebar-main').innerHeight();
windowHeight = $(window).innerHeight();
$('#main-content').css('min-height', windowHeight+sidebar);

//menu-left-main click
$('#menu-left-main').click(function(){
  var sidebar = $('#sidebar-main').innerHeight();
  windowHeight = $(window).innerHeight();

  $('#main-content').css('height', 0 );
  $('#main-content').css('min-height', windowHeight+sidebar+sidebar+150);
});


 $(document).foundation();

 $(document).foundation({
    accordion: {
      // specify the class used for accordion panels
      content_class: 'content',
      // specify the class used for active (or open) accordion panels
      active_class: 'active',
      // allow multiple accordion panels to be active at the same time
      multi_expand: false,
      // allow accordion panels to be closed by clicking on their headers
      // setting to false only closes accordion panels when another is opened
      toggleable: true
    }
  });


 $(document).on('close.fndtn.reveal', '[data-reveal]', function () {
  	 location.reload(true);  
 });

</script>
</body>
</html>