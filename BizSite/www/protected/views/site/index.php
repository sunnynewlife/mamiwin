<<style>
<!--
.swiper-container {
  height: 30%;
}
.swiper-slide {
  background: #000;
  position: relative;
}
.swiper-slide img {
  position: absolute;
  left:50%;
  top:50%;
  width:100%;
  max-width: 100%;
  max-height: 100%;
  -webkit-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
}
 .demo-card-header-pic .card-header {
    height: 40vw;
    background-size: cover;
    background-position: center;
  }

-->
</style>


<!-- Status bar overlay for fullscreen mode-->
<div class="statusbar-overlay"></div>
<!-- Panels overlay-->
<div class="panel-overlay"></div>
<div class="panel panel-left panel-reveal">
	<div class="content-block-title"><i class="icon icon-f7"></i><b>小明</b></div>
    <div class="list-block">
      <ul>
        <li class="item-content">
          <div class="item-media"><i class="icon icon-f7"></i></div>
          <div class="item-inner">
            <div class="item-title">成长评测</div>            
          </div>
        </li>
        <li class="item-content">
          <div class="item-media"><i class="icon icon-f7"></i></div>
          <div class="item-inner">
            <div class="item-title">我的课程</div>
            <div class="item-after"><span class="badge">5</span></div>
          </div>
        </li>
        <li class="item-content">
          <div class="item-media"><i class="icon icon-f7"></i></div>
          <div class="item-inner">
            <div class="item-title">分享交流</div>
          </div>
        </li>
        <li class="item-content">
          <div class="item-media"><i class="icon icon-f7"></i></div>
          <div class="item-inner">
            <div class="item-title">课程回顾</div>
          </div>
        </li>
        <li class="item-content">
          <div class="item-media"><i class="icon icon-f7"></i></div>
          <div class="item-inner">
            <div class="item-title">退出登录</div>
          </div>
        </li>                 
      </ul>     
    </div>
</div>

<div class="views">
	<div class="view view-main">
		<!-- Top Navbar-->
		<div class="navbar">
			<div class="navbar-inner">
				<!-- We have home navbar without left link-->
				<div class="center sliding">父母赢</div>
				<div class="right">
					<!-- Right link contains only icon - additional "icon-only" class-->
					<a href="#" class="link icon-only open-panel"> <i
						class="icon icon-bars"></i></a>
				</div>
			</div>
		</div>		
		<!-- Pages, because we need fixed-through navbar and toolbar, it has additional appropriate classes-->
		<div class="pages navbar-through toolbar-through">
			<!-- Page, data-page contains page name-->
			<div data-page="index" class="page">
				<!-- Scrollable page content-->
				<div class="page-content">
					<div class="swiper-container">
					    <!-- Slides wrapper -->
					    <div class="swiper-wrapper">

					        <div class="swiper-slide">
					        	<img data-src="/static/img/slide_1.jpg" class="swiper-lazy">
      							<div class="preloader"></div>
      						</div>
							<div class="swiper-slide">
							     <img data-src="/static/img/slide_2.jpg" class="swiper-lazy">
							     <div class="preloader"></div>
							</div>
						    <div class="swiper-slide">
						      	<img data-src="/static/img/slide_3.jpg" class="swiper-lazy">
						      	<div class="preloader"></div>
						    </div>
						    <div class="swiper-slide">
						      	<img data-src="/static/img/slide_4.jpg" class="swiper-lazy">
						      	<div class="preloader"></div>
						    </div>
						    <div class="swiper-slide">
						      	<img data-src="/static/img/slide_5.jpg" class="swiper-lazy">
						      	<div class="preloader"></div>
						    </div>
					    </div>
					    <div class="swiper-pagination color-white"></div>
					</div>				
				
				
					<div class="card demo-card-header-pic">
					  <div style="background-image:url(/static/img/slide_3.jpg)" valign="bottom" class="card-header color-white no-border">
					  	山间旅行
					  </div>
					  <div class="card-content">
					    <div class="card-content-inner">
					      <p class="color-gray">发表于 2017-03-12</p>
					      <p>什么是新型的父子关系?...</p>
					    </div>
					  </div>
					  <div class="card-footer">
					    <a href="#" class="link">点赞</a>
					    <a href="#" class="link">阅读全文</a>
					  </div>
					</div>
				
						
					<div class="card">
					  <div class="card-header">最新文章:</div>
					 
					  <div class="card-content">
					    <div class="list-block media-list">
					      <ul>
					        <li class="item-content">
					          <div class="item-media">
					            <img src="/static/img/doc_1.jpg" width="44">
					          </div>
					          <div class="item-inner">
					            <div class="item-title-row">
					              <div class="item-title">参与孩子的游戏</div>
					            </div>
					            <div class="item-subtitle">互动</div>
					          </div>
					        </li>
					        <li class="item-content">
					          <div class="item-media">
					            <img src="/static/img/doc_2.jpg" width="44">
					          </div>
					          <div class="item-inner">
					            <div class="item-title-row">
					              <div class="item-title">你需要更加了解自己的孩子</div>
					            </div>
					            <div class="item-subtitle">陪伴</div>
					          </div>
					        </li>
					        <li class="item-content">
					          <div class="item-media">
					            <img src="/static/img/doc_3.jpg" width="44">
					          </div>
					          <div class="item-inner">
					            <div class="item-title-row">
					              <div class="item-title">儿童心里健康</div>
					            </div>
					            <div class="item-subtitle">学习</div>
					          </div>
					        </li>					        					       
					      </ul>
					    </div>
					  </div>
					 
					  <div class="card-footer">
					    <span>2017-03-12</span>
					    <span>3 篇</span>
					  </div>
					</div> 						
	
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
var mySwiper = new Swiper('.swiper-container', {
	  preloadImages: false,
	  lazyLoading: true,
	  pagination: '.swiper-pagination'
	})  
</script>
