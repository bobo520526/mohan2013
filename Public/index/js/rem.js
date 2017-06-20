(function() {
	
	/*设计图为640 字体大小为40*/
	function changefontsize(){
			var _this=this;
			//这里指的是当前的HTML
			var html=document.getElementsByTagName('html')[0];
			//创建个screenWitdh让他的值等于当前设备的宽度
			var screenWitdh=_this.innerWidth;
			//当前页面的文字大小=设备宽度*0.125/2
			html.style.fontSize=(screenWitdh*0.125)/2+"px";
	}
	changefontsize();

	window.onresize=function(){
		changefontsize();
	}

})();