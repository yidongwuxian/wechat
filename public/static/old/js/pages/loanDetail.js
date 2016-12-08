$(function(){
	
	/*左右滚动*/
	$(".loanDetailPlay").pageSlider({
	    pageSelector:".fullItem",
	    horizontal:true,
	    loop:true,
	    prevBtn:$("#scrollLeftArrow"),
	    nextBtn:$("#scrollRightArrow"),
	    beforeMove: function(a,b,c){
	    	var $itemTop = $('.fullItem').eq(c).find('.item_top');
	    	if($itemTop.height()==0)
	  			$('.loanDetailPlay_nav').hide();
		},
	    afterMove: function(a,b,c){
	    	var $itemTop = $('.fullItem').eq(c).find('.item_top');
	    	if($itemTop.height()>0)
	  			$('.loanDetailPlay_nav').show();
		}
  	});
	$('body').on('swipeUp',function(e){
		var $item = $(e.target).parents('.fullItem');
		$item.find('.item_top').addClass('item_top_hide');
		setTimeout(function(){
			$('.loanDetailPlay_nav').hide();
		},100)
	})
	$('body').on('swipeDown',function(e){
		var $item = $(e.target).parents('.fullItem');
		$item.find('.item_top').removeClass('item_top_hide');
		setTimeout(function(){
			$('.loanDetailPlay_nav').show();
		},300)
	})
});








