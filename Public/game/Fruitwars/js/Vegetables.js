//主体
(function() {
	var self;

	function Vegetables() {
		Vegetables.__super.call(this)
		this.init();
	}
	Laya.class(Vegetables, "Vegetables", laya.display.Sprite);

	var _proto = Vegetables.prototype;
	_proto.init = function(){
		self = this;
		// 初始化音效
		this.sound=Laya.SoundManager;
		// 青蛙进度条。
		this.hpText = 184;
		// 怪血量减少数组,西红柿掉5，西瓜掉6
		this.hpArr = [5.5, 6,9];
		//子弹费用,橙子5，西红柿3,西瓜4
		this.biuCostArr = [parseInt(cut_score_one), parseInt(cut_score_two), parseInt(cut_score_three)];
		//子弹打中得分,橙子10，西红柿8,西瓜9，
		this.biuGetArr = [parseInt(get_score_one), parseInt(get_score_two), parseInt(get_score_three)];
		this.addTimer = 120;
		this.biulIndex;
		// 添加子弹节流
		this.Canbui = true;
		this.Canbiutt=0;
		this.CanbuiTime = 0;
		this.fx = 1;
		// 实例化主页
		this.homeBg = new homeBgUI();
		this.addChild(this.homeBg);
		this.homeTopbg = new homeTopbgUI();
		this.addChild(this.homeTopbg);
		this.home = new homeUI();
		this.addChild(this.home);
		// 围栏的生命值
		this.hpitem0=100;
		this.hpitem1=150;
		this.hpitem2=180;
		// 初始化分数score
		this.isFashe = true;
		this.sCoring(scoreNum);
		//初始化时间
		this.homeTopbg.min.index=hour;
		this.homeTopbg.tenSec.index=min;
		this.homeTopbg.sec.index=second;
		
		this.countDown();
		//判断是否按下
		this.isDONW = false;
		// 定义鼠标状态
		this.isMove = false;
		this.donwTimer;
		self.isAddAni = false;
		self.firename = 0;
		//把打怪的水果层实例化
		this.beatItLayer = new beatIt();
		this.addChild(this.beatItLayer);
		this.beatItLayer.zOrder = -1;
		this.homeBg.zOrder = -2;
		this.home.zOrder = 2;
		this.homeTopbg.zOrder = 4;
		//水果炸弹/橘子，花，西瓜
		this.fruitsArr = [
            urlStrings+"res/atlas/fruit1.json",
             urlStrings+"res/atlas/fruit2.json",
              urlStrings+"res/atlas/fruit3.json",
               urlStrings+"res/atlas/fire1.json",
                urlStrings+"res/atlas/fire2.json", 
                urlStrings+"res/atlas/vertigo.json"];
		//水果皮肤/橘子，花，西瓜
		this._index = 0;
		this.fruitsPlayer = new Laya.Sprite();
		this.addChild(this.fruitsPlayer);
		this.fruitsPlayer.zOrder = 3;
		Laya.timer.frameLoop(1, this, this.onFrame);
		this.createApe();
		// this.onPlayMusic();
		//最后一发是否打中判断
		this.biu=true;

	}

	//鼠标事件
	_proto.createApe = function() {
		var s = this;
		s.a1 = new Laya.Sprite();
		s.a2 = new Laya.Sprite();
		s.a1.graphics.drawLine(212, 758, 280, 830, "#0d0b16", 6);
		s.a2.graphics.drawLine(380, 758, 330, 830, "#0d0b16", 6);
		this.addChild(s.a1);
		this.addChild(s.a2);
		s.a1.zOrder = 3;
		s.a2.zOrder = 3;
		this.home.box_1.on(Laya.Event.MOUSE_DOWN, this.home.box_1, this.downStart);
		this.home.on(Laya.Event.MOUSE_UP, this, this.onMoveUp);
		this.home.on(Laya.Event.MOUSE_MOVE, this, this.onMove);
		this.home.btn_right1.on(Laya.Event.MOUSE_DOWN, this, this.toggle1);
		this.home.btn_left1.on(Laya.Event.MOUSE_DOWN, this, this.toggle2);
	}
	//点击切换图片
	_proto.toggle1 = function() {
		this.ape = this.home.fruit_1.skin;
		this.home.fruit_1.skin = this.home.btn_right1.skin;
		this.home.btn_right1.skin = this.ape;
		this.changeSkin();
	}

	_proto.toggle2 = function() {
		this.ape = this.home.fruit_1.skin;
		this.home.fruit_1.skin = this.home.btn_left1.skin;
		this.home.btn_left1.skin = this.ape;
		this.changeSkin();
	}

	_proto.changeSkin = function() {
		if (this.home.fruit_1.skin == "comp/fruit_1.png") {
			this._index = 0;
			
		} else if (this.home.fruit_1.skin == "comp/fruit_2.png") {
			this._index = 1;
		} else if (this.home.fruit_1.skin == "comp/fruit_3.png") {
			this._index = 2;
		}
	
	}
	_proto.onMoveUp = function(e) {
		var s = this;
		self.isAddAni = false;
		clearInterval(self.donwTimer);
		
		var timer, timer2;
		clearInterval(timer);
		clearInterval(timer2);
		

			if (this.isMove) {
			if(s.isFashe&&scoreNum>=s.biuCostArr[this._index]){
				
				this.addfruitBiu(this, this._angle, this.home.box_1.x, this.home.box_1.y, this._index);
			}else {s.delay();}
			// if(s.isFashe && scoreNum>=0){
				
			// this.addfruitBiu(this, this._angle, this.home.box_1.x, this.home.box_1.y, this._index);

			// }
			// this.addfruitBiu(this, this._angle, this.home.box_1.x, this.home.box_1.y, this._index);
			this.home.fruit_1.visible = false;
			var a1_x = this.a1._graphics._one[2];
			var a1_y = this.a1._graphics._one[3];
			var a2_x = this.a2._graphics._one[2];
			var a2_y = this.a2._graphics._one[3];
			Laya.Tween.to(this.home.box_1, {
				x: 301,
				y: 814,
				rotation: 0,
				a1_x: 280
			}, 500, Laya.Ease.backOut, Laya.Handler.create(this, function() {
				this.home.fruit_1.visible = true;
				self.home.fruit_1.removeChild(s.fireAni);
				self.firename = 0;
			}));
			timer = setInterval(function() {
				s.a1._graphics._one[2] += 10;
				s.a1._graphics._one[3] -= 10;
				if (s.a1._graphics._one[2] >= 280) {
					s.a1._graphics._one[2] = 280;
				}
				if (s.a1._graphics._one[3] <= 830) {
					s.a1._graphics._one[3] = 830;
				}

				if (s.a1._graphics._one[2] >= 280 && s.a1._graphics._one[3] <= 830) {
					clearInterval(timer)
				}

			}, 10);
			timer2 = setInterval(function() {

				s.a2._graphics._one[2] += 15;
				s.a2._graphics._one[3] -= 15;
				if (s.a2._graphics._one[2] >= 330) {
					s.a2._graphics._one[2] = 330;
				}
				if (s.a2._graphics._one[3] <= 830) {
					s.a2._graphics._one[3] = 830;
				}

				if (s.a2._graphics._one[2] >= 330 && s.a2._graphics._one[3] <= 830) {
					clearInterval(timer2)
				}

			}, 10)


		}
		this.isDONW = false;
		this.isMove = false;
	}
	//按下时，判断
	_proto.downStart = function(e) {
		// 两秒执行火球函数
		self.isAddAni = true;
		clearInterval(self.donwTimer);
		self.donwTimer = setInterval(function() {
			if (self.isAddAni) {
				self.fireBall();
			}
			clearInterval(self.donwTimer);
		}, 1000)
		self.downStartX = this.x;
		self.downStartY = this.y;
		self.backLayerX = e.stageX
		self.backLayerY = e.stageY
		self.isDONW = true;
		self.allEndX = e.stageX - this.x;
		self.allEndY = e.stageY - this.y;

	}
	_proto.onMove = function(e) {
		if(!this.Canbui)return;
		if (!this.isDONW) return;
		this.isMove = true;
		var r = Math.sqrt(Math.pow((self.backLayerX - e.stageX), 2) + Math.pow((self.backLayerY - e.stageY), 2));

		if (r > 120) r = 120;
		var angle = Math.atan2(e.stageX - self.backLayerX, e.stageY - self.backLayerY);
		this._angle = angle * 180 / Math.PI;
		this.home.box_1.rotation = -this._angle - 20

		var sX = self.backLayerX - self.allEndX;
		var sY = self.backLayerY - self.allEndY;


		if (angle <= 1.7 && angle >= -1.7) {
			this.home.box_1.x = Math.sin(angle) * r + sX
			this.home.box_1.y = Math.cos(angle) * r + sY

		} else if (angle > 1.7) {
			this.home.box_1.x = Math.sin(-1.7 + angle) * r + sX
			this.home.box_1.y = Math.cos(-1.7 + angle) * r + sY
			this.home.box_1.rotation = 0;
			this._angle = (-1.7 + angle) * 180 / Math.PI;
		} else if (angle < -1.7) {
			this.home.box_1.x = Math.sin(1.7 + angle) * r + sX
			this.home.box_1.y = Math.cos(1.7 + angle) * r + sY
			this.home.box_1.rotation = 0;
			this._angle = (1.7 + angle) * 180 / Math.PI;
		}
		this.a1._graphics._one[2] = this.home.box_1.x - 26
		this.a1._graphics._one[3] = this.home.box_1.y + 20
		this.a2._graphics._one[2] = this.home.box_1.x + 26
		this.a2._graphics._one[3] = this.home.box_1.y + 22



	}

	_proto.onFrame = function() {
		var s = this;
		// 发射子弹节流阀
			if(this.Canbiutt>60){
			this.Canbiutt=0;
			this.Canbui=true;
		}
		this.Canbiutt++;
		if(self.biu==false)return;
		for (var i = 0; i < s.beatItLayer._childs[0]._childs.length; i++) {	
               if (s.beatItLayer._childs[0]._childs[i].mode == "die") {
					scoreNum+=this.biuGetArr[s.beatItLayer._childs[0]._childs[i].mIndex];
					s.hpText-=4;
					// console.log(s.hpText,"----------")
					s.sCoring(scoreNum);
					s.HpBarLaod();
                    s.beatItLayer._childs[0]._childs[i].removeSelf();
					
                }
            if(!s.beatItLayer._childs[0]._childs[i]) return
			if (s.beatItLayer._childs[0]._childs[i].y < 700) {
				if (s.beatItLayer._childs[0]._childs[i].mode == "run") {
					s.beatItLayer._childs[0]._childs[i].y += 1;
				}
			}else{
				s.beatItLayer._childs[0]._childs[i].types="end";
				this.addTimer++;
				if(this.addTimer>=200){
					this.hitLanGan();
					this.addTimer = 0;
				}
			}
			//  怪
			if (s.beatItLayer._childs[0]._childs[i].mode == "Hit") {
				//西瓜效果
				if (s.beatItLayer._childs[0]._childs[i].mIndex == 2) {
					s.beatItLayer._childs[0]._childs[i].mode = "stop";
                    this.beatItLayer._childs[0]._childs[i].hp -= this.hpArr[1];
              	if (this.beatItLayer._childs[0]._childs[i].hp > 0) {
						this.watermelon(s.beatItLayer._childs[0]._childs[i].x, s.beatItLayer._childs[0]._childs[i].y, s.beatItLayer._childs[0]._childs[i]);
						s.beatItLayer._childs[0]._childs[i].play(1, true);
                        if (s.beatItLayer._childs[0]._childs[i].mode == "stop") {
							s.beatItLayer._childs[0]._childs[i].mode = "play";
							Laya.Tween.to(s.beatItLayer._childs[0]._childs[i], {}, 1000, null, Laya.Handler.create(s.beatItLayer._childs[0]._childs[i], function() {
								s.beatItLayer._childs[0].removeChild(s.tomatoesAni);
								this.mode = "run";
								this.play(0, true);
							}))
						}
					} else {
						this.goldTomoto(s.beatItLayer._childs[0]._childs[i].x, s.beatItLayer._childs[0]._childs[i].y, s.beatItLayer._childs[0]._childs[i].mIndex);
						s.beatItLayer._childs[0]._childs[i].mode = "die";
						
						
					}
				}
				//西红柿效果
				if (s.beatItLayer._childs[0]._childs[i].mIndex == 1) {
					s.beatItLayer._childs[0]._childs[i].mode = "stop";
					//西红柿掉血
					this.beatItLayer._childs[0]._childs[i].hp -= this.hpArr[0];
					if (this.beatItLayer._childs[0]._childs[i].hp > 0) {
						this.tomato(s.beatItLayer._childs[0]._childs[i].x, s.beatItLayer._childs[0]._childs[i].y, s.beatItLayer._childs[0]._childs[i]);
						if (s.beatItLayer._childs[0]._childs[i].mode == "stop") {
							s.beatItLayer._childs[0]._childs[i].mode = "play";
							Laya.Tween.to(s.beatItLayer._childs[0]._childs[i], {}, 1000, null, Laya.Handler.create(s.beatItLayer._childs[0]._childs[i], function() {
								s.beatItLayer._childs[0].removeChild(s.tomatoesAni);
								this.mode = "run";
								this.play(0, true)
							}))
						}
					} else {
						this.goldTomoto(s.beatItLayer._childs[0]._childs[i].x, s.beatItLayer._childs[0]._childs[i].y, s.beatItLayer._childs[0]._childs[i].mIndex);
						s.beatItLayer._childs[0]._childs[i].mode = "die";
					}

				}
                if (s.beatItLayer._childs[0]._childs[i].mIndex == 0) {
					this.hitPHText(s.beatItLayer._childs[0]._childs[i].x, s.beatItLayer._childs[0]._childs[i].y, s.beatItLayer._childs[0]._childs[i].mIndex);
					s.beatItLayer._childs[0]._childs[i].mode ="die";
				}


             
			}
			
		}
		for (key in s.fruitsPlayer._childs) {


			if (s.fruitsPlayer._childs[key].x <= 0) {
				if (s.fruitsPlayer._childs[key]._angle > 0) {
					s.fruitsPlayer._childs[key].fx = -1;
				} else {
					s.fruitsPlayer._childs[key].fx = 1;
				}

			}
			if (s.fruitsPlayer._childs[key].x > 540) {
				if (s.fruitsPlayer._childs[key]._angle > 0) {
					s.fruitsPlayer._childs[key].fx = 1;
					// s.fruitsPlayer._childs[key]._angle=
				} else {
					s.fruitsPlayer._childs[key].fx = -1;
				}
			}
			s.fruitsPlayer._childs[key].x += (s.fruitsPlayer._childs[key].fx * -1) * (Math.sin(s.fruitsPlayer._childs[key]._angle * Math.PI / 180) * 10);
			s.fruitsPlayer._childs[key].y += -1 * (Math.cos(s.fruitsPlayer._childs[key]._angle * Math.PI / 180) * 10);
			// 根据球的位置来判断移动方向
			if (s.fruitsPlayer._childs[key].y <= -s.fruitsPlayer._childs[key].height || s.fruitsPlayer._childs[key].y >= win_H || s.fruitsPlayer.getChildAt(key).mode == "die") {
				s.fruitsPlayer._childs[key].removeSelf();

			}
		}
		// 遍历所有的怪,碰撞
		// ,[50,100],10
		for (var i = 0; i < s.beatItLayer._childs[0].numChildren; i++) {
			for (var key = 0; key < this.fruitsPlayer.numChildren; key++) {
				if (hitTestRectArc(this.fruitsPlayer.getChildAt(key), this.beatItLayer._childs[0].getChildAt(i), [20, 20], 80,50,20)) {
					s.beatItLayer._childs[0]._childs[i].mIndex = s.fruitsPlayer.getChildAt(key).mIndex;
					s.beatItLayer._childs[0]._childs[i].mode = "Hit";
					s.fruitsPlayer.getChildAt(key).mode = "die";
					// 碰撞的声音
					SoundManges.SoundPlay("hitSound");
				}
			}

		}


	}
	//两秒后的火球函数
	_proto.fireBall = function() {
		var s = this;
		s.fireAni = new Laya.Animation();
		s.fireAni.loadAtlas(this.fruitsArr[3]);
		s.fireAni.name = "fireAni";
		s.fireAni.pos(5, -18)
		s.fireAni.play();
		s.fireAni.interval = 60;
		this.home.fruit_1.addChild(s.fireAni);
		// 给火球状态赋值
		s.firename = 1;
	}

	//水果飞出去效果,记录松开时的坐标，角度,炸弹的品种,两秒后的子弹效果，带火球
	_proto.addfruitBiu = function(e, angle, x1, y1, _index) {
		var s = this;
		//如果分数大于被扣分数
		if(scoreNum>=s.biuCostArr[_index]){
			s.isFashe=true;
		}else{
			s.isFashe=false;
			
		}
		if(!s.isFashe || scoreNum<=0) {s.delay();};
		switch(_index){
			case 0:
			scoreNum-=s.biuCostArr[0];
			s.sCoring(scoreNum);
			break;
			case 1:
			scoreNum-=s.biuCostArr[1];
			s.sCoring(scoreNum);
			break;
			case 2:
			scoreNum-=s.biuCostArr[2];
			s.sCoring(scoreNum);
			break;
		}
			
		// 最外层Sprite
		s.fruitChilds = new Laya.Sprite();
		s.fruitChilds._angle = angle + 12;
		s.fruitChilds.mode = "run";
		s.fruitChilds.fx = 1;
		s.fruitChilds.pos(x1, y1);
		s.fruitChilds.mIndex = _index;
		s.fruitsPlayer.addChild(s.fruitChilds);
		// Sprite里的怪动画
		s.fruits = new Laya.Animation();
		s.fruits.play();
		s.fruits.interval = 120;
		s.fruits.loadAtlas(s.fruitsArr[_index]);
		s.fruitChilds.addChild(s.fruits);
		if (s.firename == 1) {
			// Sprite里的火动画
			s.fireAni2 = new Laya.Animation();
			s.fireAni2.loadAtlas(s.fruitsArr[4]);
			s.fireAni2._angle = angle + 12;
			s.fireAni2.play();
			s.fireAni2.interval = 120;
			s.fruitChilds.addChild(s.fireAni2);
		}	
/**
             * 
             <div class="s.fruitChilds">
                <span class="s.fruits"></span>
                <span class="s.fireAni2"></span>
             <div>
             * 
             */
		this.Canbui=false;
	}
	// 西红柿效果，眩晕
	_proto.tomato = function(x, y, s_name1) {
		this.tomatoesAni = new Laya.Animation();
		this.tomatoesAni.loadAtlas(this.fruitsArr[5]);
		this.tomatoesAni.pos(x-65, y - 100);
		this.tomatoesAni.play();
		this.tomatoesAni.interval = 60;
		this.addChild(this.tomatoesAni);
		// 血条
		this.hp = new Laya.Sprite();
		this.hp.graphics.drawRect(15, 0, 60, 18, "#ff0000");
		this.tomatoesAni.addChild(this.hp);
		// 血条边框
		this.hpBorder = new Laya.Sprite();
		this.hpBorder.graphics.drawRect(15, -2, 100, 20, null, "#119033", 4)
		this.tomatoesAni.addChild(this.hpBorder);

		Laya.Tween.to(this.tomatoesAni, {}, 1000, null, Laya.Handler.create(this.tomatoesAni, function() {
			this.removeSelf();
		}))
		//掉血
		this.bleedingAni(x,y);
		
	}
    //西瓜效果
	_proto.watermelon = function(x, y, s_name2) {
        this.wplayer=new Laya.Sprite();
        this.addChild(this.wplayer);
		this.wplayer.pos(x - 90, y - 100);
		this.bloodFrameAni(x,y);
		//掉血
		this.bleedingAni(x,y);
		
	}

	// 如果青蛙血量小于0执行金币动画
	_proto.goldTomoto = function(x, y, i) {
		// 文字效果
		this.textAni(x,y,i);
		this.goldAni(x,y);
		this.bloodSpatter(x, y);
	}
	
	// 橘子碰撞后的动画效果（金币）                                                                                                                                                                                                                            
	_proto.hitPHText = function(x, y, i) {
		// 文字效果
		this.textAni(x,y,i);
		this.goldAni(x,y);
		this.bloodSpatter(x, y);
	}
	//血条动画
	_proto.bloodFrameAni=function(x,y){
		// 血条
		var s=this;
		s.hpw = new Laya.Sprite();
		s.hpw.graphics.drawRect(50, 0, 50, 18, "#ff0000");
		s.wplayer.addChild(this.hpw);
		// 血条边框
		s.hpwBorder = new Laya.Sprite();
		s.hpwBorder.graphics.drawRect(50, -2, 100, 20, null, "#119033", 4)
		s.wplayer.addChild(s.hpwBorder);
		Laya.Tween.to(s.wplayer, {}, 1000, null, Laya.Handler.create(s.wplayer, function() {
			this.removeSelf();
		}));
	}
	//掉血动画
	_proto.bleedingAni=function(x,y){
		var s=this;
		s.p4 = new Laya.Text();
		s.p4.fontSize = 25;
		s.p4.color = "#ff0000";
		s.p4.x = x;
		s.p4.y = y;
		s.p4.text = "-6";
		s.p4.bold = true;
		s.beatItLayer.addChild(s.p4);
		Laya.Tween.to(s.p4, {
			y: y - 120,
			alpha: 0
		}, 1000, null, Laya.Handler.create(s.p4, function() {
			s.p4.removeSelf();
		}));
	}
	//文字动画
	_proto.textAni=function(x,y,i){
		var s=this;
		s.p1 = new Laya.Text();
		s.p2 = new Laya.Text();
		s.p1.fontSize = 25;
		s.p2.fontSize = 25;
		s.p1.color = "#ff0000";
		s.p2.color = "#fdee1b";
		s.p1.x = x;
		s.p1.y = y - 30;
		s.p2.x = x;
		s.p2.y = y;
		s.p1.text = "-"+self.hpArr[i];
		s.p2.text = "+"+self.biuGetArr[i];
		// s.p1.text = "-8";
		// s.p2.text = "+10";
		s.p1.bold = true;
		s.p2.bold = true;
		s.beatItLayer.addChild(this.p1);
		s.beatItLayer.addChild(this.p2);
		Laya.Tween.to(s.p1, {
			y: y - 120,
			alpha: 0
		}, 1000, null, Laya.Handler.create(s.p1, function() {
			s.p1.removeSelf();
		}));
		Laya.Tween.to(s.p2, {
			y: y - 70,
			alpha: 0
		}, 1000, null, Laya.Handler.create(s.p2, function() {
			s.p1.removeSelf();
		}));
	}
	//金币动画
	_proto.goldAni=function(x,y){
		var s=this;
		s.gold = new Laya.Animation();
		s.gold.loadAtlas(urlStrings+"res/atlas/coinAni.json");
		s.gold.play();
		s.gold.interval = 50;
		s.gold.pos(x, y) //鱼的初始位置
		s.gold.name = "gold";
		s.beatItLayer.addChild(this.gold); //把鱼添加到舞台
		s.gold.stop = false;
		Laya.Tween.to(s.gold, {
			x: 460,
			y: -150,
			rotation: 360
		}, 1000, null, Laya.Handler.create(s.gold, function() {
			s.gold.removeSelf();
		}));
	}
	// 血飞贱
	_proto.bloodSpatter= function (x, y){
		var s=this;
		s.bloodAni = new Laya.Animation();
		s.bloodAni.loadAtlas(urlStrings+"res/atlas/bloodAni.json");
		s.bloodAni.pos(x, y);
		s.bloodAni.play();
		s.bloodAni.interval = 240;
		s.beatItLayer.addChild(s.bloodAni);
		Laya.Tween.to(s.bloodAni, {
			y: y - 80,
			alpha: 0
		}, 1000, null, Laya.Handler.create(s.bloodAni, function() {
			s.bloodAni.removeSelf();
		}));
	}
	//分数
	_proto.sCoring=function(num){
		if(num<=0){
			num=0;
			self.biu=false;
		}
		this.homeTopbg.ttf.fontSize=40;
		this.homeTopbg.ttf.color="#FFFC00";
		this.homeTopbg.ttf.text=num;
		this.homeTopbg.ttf.font="yy";
		this.homeTopbg.ttf.stroke = 3;
		
	





	}
	//倒计时
	_proto.countDown=function(){
		self.timer3;
		var s = this;
		self.timer3 = setInterval(function(){
		if(s.homeTopbg.sec.index == 0 ){
			s.homeTopbg.sec.index = 10;
            s.homeTopbg.tenSec.index--;

			if(s.homeTopbg.min.index > 0&& s.homeTopbg.tenSec.index< 0){
			s.homeTopbg.tenSec.index = 5;
			s.homeTopbg.sec.index = 9;
            s.homeTopbg.min.index--;
			
		}
		}
           s.homeTopbg.sec.index--;  
		if(s.homeTopbg.min.index==0 &&s.homeTopbg.tenSec.index== 0&&s.homeTopbg.sec.index== 0){
			
			s.homeTopbg.tenSec.index = 0;
			s.homeTopbg.sec.index = 0;
            s.homeTopbg.min.index=0;
			s.delay();
            return;
		}
			}, 1000)
	}
	//攻击围栏
	_proto.HpBarLaod = function(){
		if(this.homeTopbg.Hp_bar.width<184){
			this.homeTopbg.Hp_bar.width += 4;
			this.homeTopbg.enemy_load.x += 4;
		}
		else{
			this.delay();
            return;
		}
	}

	_proto.hitLanGan= function(){
		var s = this;
		// clearInterval(timer4);
		for (var i in s.beatItLayer._childs[0]._childs) {
			if(s.beatItLayer._childs[0]._childs[i].types=="end"){
				s.CanbuiTime++
			}
		}
			s.attackEnclosure(s.CanbuiTime);
			this.CanbuiTime=0;
	}
	_proto.attackEnclosure= function(enemyNum){
		var s = this;
		if(s.hpitem0<=0){
			s.home.item0.visible=false;
			s.home.item0_red.visible=false;
			s.home.item1.visible=true;
			if(s.hpitem1<=0){
				s.home.item1.visible=false;
				s.home.item1_red.visible=false;
				s.home.item2.visible=true;
				if(s.hpitem2<=0){
				s.home.item2.visible=true;
				s.delay();
                return;
			}
			if(s.hpitem2>0&&s.home.item2.visible){
				
				s.home.item2_red.visible=true;
				s.home.item2_red.alpha=1;
				Laya.Tween.to(s.home.item2_red,{alpha:1},50,null,Laya.Handler.create(this,function(){
					s.home.item2_red.alpha=0;
			}),100)
			s.hpitem2-=1*enemyNum;
			}
			}
			if(s.hpitem1>0&&s.home.item1.visible){
				
				s.home.item1_red.visible=true;
				s.home.item1_red.alpha=1;
			Laya.Tween.to(s.home.item1_red,{alpha:1},50,null,Laya.Handler.create(this,function(){
				s.home.item1_red.alpha=0;
			}),100)
			s.hpitem1-=1*enemyNum;
			}
		}
		if(s.hpitem0>0&&s.home.item0.visible){
			
			s.home.item0_red.visible=true;
			s.home.item0_red.alpha=1;
			Laya.Tween.to(s.home.item0_red,{alpha:1},50,null,Laya.Handler.create(this,function(){
				s.home.item0_red.alpha=0;
			}),100)
			s.hpitem0-=1*enemyNum;
		}
		
	}

	// 游戏结束
	 _proto.gameOver = function(){
	//  发射节流为false
		//结束游戏扣除金币
		if (scoreNum<0) {
			scoreNum = 0;
		}
        $.ajax({
		    type: 'POST',
		    url: '/index.php/Mobile/Fruitwars/operate',
		    data: {
		    	'coin':scoreNum
		    },
		    dataType:'json',
		    success:function(data){
		    	
		    }

		});
        //结束游戏扣除金币结束
		clearInterval(self.timer3)
		SoundManges.SoundPlay("GameOverSound");
		this.CanShoot = false;
		Laya.timer.clearAll(this); 
		Laya.Tween.clearAll(this);
		this.GameOver=new gameOverUI();
		this.GameOver.zOrder=9;
		this.addChild(this.GameOver);
		this.GameOver.total_sco.fontSize=40;
		this.GameOver.total_sco.color="#FFFC00";
		this.GameOver.total_sco.text=scoreNum;
		this.GameOver.total_sco.font="yy";
		this.GameOver.total_sco.stroke = 3;
		this.GameOver.btn_reStart.on(Laya.Event.MOUSE_DOWN,this,this.Again);
		this.GameOver.btn_penson.on(Laya.Event.MOUSE_DOWN,this,this.backPersonal);
		

	 }
	_proto.delay=function(){
		//  发射节流为false
		this.CanShoot = false;
		var s=this;
		setTimeout(function(){
			SoundManges.StopPlay("bgMusic");
			s.gameOver();
		},1000);
	}
		//点击再玩一次
	_proto.Again =function(){
		_gamestart.backLayers.removeSelf();
		_gamestart.startGame();
	}
	//点击回到个人中心
	_proto.backPersonal=function(){
		window.location.href = '/Mobile/Index/index';
	}

})()