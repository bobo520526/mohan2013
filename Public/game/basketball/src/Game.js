/**
* name 
*/
var price;
var kprice;
var Pindex;
var coins;
gl();
coin();

var scoreNum;//金币数
var Game = (function (_super) {
    function Game() {
        Game.super(this);
        this.reset();
    }
    Laya.class(Game, "Game", _super);
    var _proto = Game.prototype;//变量本地

    //初始化
    _proto.reset = function () {
        this.util = new util();//实例化方法
        this.Ar_cock_ball = [];//待发篮球数组
        this.Ar_sent_ball = [];//已发篮球数组
        this.Ar_hoop = [];
        this.liDu = 0;
        this.canUp = true;//是否可以投球
        // this.cock_area.zOrder = 2;//待发区框架层级提升
        this.CanShootTime = 0;//投篮节流阀时间
        this.CanShoot = true;//投篮节流阀
        this.addHoopTime = 101;//添加篮框节流事件

        Laya.timer.frameLoop(1, this, this.onLoop);//定时器
        //倒计时
        Laya.SoundManager.playMusic("/Public/game/basketball/bin/muisc/backmuisc.mp3", 0, null, 0);
        this.n = 0
        //分数
        this.score_1_n = 0;
        this.score_2_n = 0;
        this.score_3_n = 0;
        scoreNum = price;//金币数
        this.goldNum(scoreNum);//初始化界面显示金币数
        this.inloading.height = 30;//初始化能量条
        this.upBack.on(Laya.Event.MOUSE_DOWN, this, this.StageClick)//点击屏幕事件
        this.upBack.on(Laya.Event.MOUSE_UP, this, this.onBackUp)//点击屏幕事件

    }

    //加载篮球
    _proto.addBoll = function (x) {
        this.ballplayer = new Laya.Sprite();
        this.ballplayer.zOrder = 99;
        this.ballplayer.loadImage('/Public/game/basketball/bin/image/ball.png');
        this.ballplayer.pos(294, 870);
        this.ballplayer.pivotX = 45.5;
        this.ballplayer.pivotY = 45.5;
        this.addChild(this.ballplayer);
        this.btn_Shoot_ball.skin = "";
        this.CanShoot = false;
        scoreNum -= 5;//每次发射一个球扣除5金币；
        this.goldNum(scoreNum);
    }
    // 加载金币数
    _proto.goldNum = function (num) {
        this.ttf.fontSize = 42;
        this.ttf.color = "#FFFC00";
        this.ttf.text = num;
        this.ttf.font = "yy";
        this.ttf.stroke = 3;
        this.ttf.italic = true;
    }
    //点击投篮按钮
    _proto.StageClick = function () {
        // 点击前判断是否有金币？
        var s = this;
        if (scoreNum <= 0) {
            s.reChange = new Rechange();
        }
        else {
            if (!this.CanShoot) return;
            this.liDu = 0;
            this.goTime = true;
        }
    };

    _proto.onBackUp = function () {
        if (!this.canUp) return;
        var s = this;
        this.goTime = false;
        this.btn_Shoot_biu.skin = "/Public/game/basketball/bin/comp/ui/biu1.png";
        if (s.CanShoot) {
            this.addBoll();
            console.log(s.CanShoot)
        }
        var lodBall = s.ballplayer;
        console.log(lodBall)
        Laya.Tween.to(s.ballplayer, { y: 900 - this.liDu, rotation: 120, scaleX: 0.8, scaleY: 0.8 }, 500, null, Laya.Handler.create(this, addAr_sentball, [lodBall]), 0, true);
        function addAr_sentball(lodBall) {
            //状态1 自由落体   状态2 按角度上升
            lodBall.status = 1;
            lodBall.t = 0;//时间
            lodBall.a = 1;//重力加速度
            lodBall.bin = false;
            lodBall.crash = 2;
            this.Ar_sent_ball.push(lodBall);
        };
        clearTimeout(time1);
        var time1 = setTimeout(function () {
            s.btn_Shoot_biu.skin = "/Public/game/basketball/bin/comp/ui/biu2.png";
        }, 1000);
        // this.upBack.off(Laya.Event.MOUSE_DOWN,this,this.onBackUp);
        this.canUp = false;
    }
    //倒计时
    _proto.conTime = function () {
        if (this.time_unit.index == 0) {
            if (this.time_ten.index == 0) {
                this.gameOver();
                return;
            };
            this.time_unit.index = 9;
            this.time_ten.index--;
            return;
        }
        this.time_unit.index--;
    };

    //游戏结束
    _proto.gameOver = function () {
        Laya.timer.clearAll(this);
        Laya.Tween.clearAll(this);
        this.CanShoot = false;
        this.GameOver = new GameOverUI();
        Laya.SoundManager.stopMusic();
        Laya.SoundManager.playMusic("/Public/game/basketball/bin/muisc/over.mp3", 0, null, 0);
        this.GameOver.zOrder = 999;
        this.addChild(this.GameOver);
        this.GameOver.item4.index = this.score_3.index;
        this.GameOver.item3.index = this.score_2.index;
        this.GameOver.item2.index = this.score_1.index;
        
        /*后台添加START*/
        ord(5);
         /*后台添加END*/
        
        this.GameOver.share.on(Laya.Event.MOUSE_DOWN, this, this.Share);
        this.GameOver.again.on(Laya.Event.MOUSE_DOWN, this, this.Again);
        this.GameOver.more.on(Laya.Event.MOUSE_DOWN, this, this.More);
        this.canUp = true;
    };

    //点击分享
    _proto.Share = function () {

    };

    //点击更多
    _proto.More = function () {
        window.location.href="/Mobile/Index/gamelist";
    };

    //点击再玩一次
    _proto.Again = function () {
                  if(price-kprice<0){
            layer.confirm('您的金币不足，是否充值金币？', {
                btn: ['好的', '取消'], //可以无限个按钮

            }, function(index, layero){
                //按钮【按钮一】的回调
                window.location.href="";
            }, function(index){
                layer.close(index);
            });
          }
          else{
            kcoin();
            this.removeSelf();
            LayaSample.game = new Game();
            Laya.stage.addChild(LayaSample.game);
          }
    }
    //定时器方法,只发射一次
    _proto.onLoop = function () {
        var _thisBall, _thisHoop;
        //点击节流阀
        var s = this;
        //添加篮框节流
        if (this.addHoopTime > 60) {
            this.addHoopTime = 0;
            this.addBasket(600, 200, 0);
            this.addBasket(-100, 380, 1);
        };
        this.addHoopTime++;

        if (this.n > 100) {
            // this.conTime();
            this.n = 0;
        }
        this.n++;
        for (var i = 0; i < this.Ar_sent_ball.length; i++) {
            if (this.Ar_sent_ball[i].status == 1) {
                this.Ar_sent_ball[i].t++;
                this.Ar_sent_ball[i].v = this.Ar_sent_ball[i].t * this.Ar_sent_ball[i].a;//步长
                this.Ar_sent_ball[i].y += this.Ar_sent_ball[i].v;
                if (this.Ar_sent_ball[i].y < 112) {
                    Laya.Tween.clearAll(this.Ar_sent_ball[i]);
                }
                if (this.Ar_sent_ball[i].y >= 750) {
                    if (!this.Ar_sent_ball[i].bin) {
                        this.combo_1.index = 0;
                        this.combo_2.index = 0;
                    }
                    this.Ar_sent_ball[i].removeSelf();
                    clearTimeout(time2);
                    var time2 = setTimeout(function () {
                        s.gameOver();
                    }, 300);
                    this.Ar_sent_ball.splice(i, 1);

                };

            } else if (this.Ar_sent_ball[i].status == 2) {
                this.Ar_sent_ball[i].t = 0;
                this.Ar_sent_ball[i].v -= this.Ar_sent_ball[i].a;
                this.Ar_sent_ball[i].y -= this.Ar_sent_ball[i].v;
                if (this.Ar_sent_ball[i].v < 0) {
                    this.Ar_sent_ball[i].status = 1;
                }
            } else if (this.Ar_sent_ball[i].status == 3) {
                this.Ar_sent_ball[i].t++;
                this.Ar_sent_ball[i].v = this.Ar_sent_ball[i].t * this.Ar_sent_ball[i].a;//步长
                this.Ar_sent_ball[i].y += this.Ar_sent_ball[i].v;
                if (this.Ar_sent_ball[i].y >= 750) {
                    this.Ar_sent_ball[i].removeSelf();
                    clearTimeout(time3);
                    var time3 = setTimeout(function () {
                        s.gameOver();
                    }, 300);
                    this.Ar_sent_ball.splice(i, 1)
                };
            }
        };
        for (key in s.Ar_hoop) {
            for (var j = 0; j < this.Ar_sent_ball.length; j++) {
                _thisBall = this.Ar_sent_ball[j];
                _thisHoop = s.Ar_hoop[key];
                if (this.Ar_sent_ball[j].getBounds().intersects(s.Ar_hoop[key].getBounds())) {
                    this.score_1.index = this.score_1_n;
                    this.score_2.index = this.score_2_n;
                    this.score_3.index = this.score_3_n;
                    if ((this.Ar_sent_ball[j].y >= 250 && this.Ar_sent_ball[j].y <= 280) || (this.Ar_sent_ball[j].y >= 450 && this.Ar_sent_ball[j].y <= 460)) {
                        if (this.Ar_sent_ball[j].status == 1) {
                            if (s.Ar_hoop[key].x + 10 < this.Ar_sent_ball[j].x && this.Ar_sent_ball[j].x < s.Ar_hoop[key].x + 70) {
                                if (this.Ar_sent_ball[j].status == 1) {
                                    // this.util.Ball_in(s.Ar_hoop[key], this.Ar_sent_ball[j], this);
                                }
                                this.Ar_sent_ball[j].crash--;
                                this.Ar_sent_ball[j].status = 2;
                            }
                            //框的x+20  大于球的x
                            else if (s.Ar_hoop[key].x + 20 > this.Ar_sent_ball[j].x && this.Ar_sent_ball[j].x > s.Ar_hoop[key].x - 40 && this.Ar_sent_ball[j].crash > 0) {
                                this.Ar_sent_ball[j].crash--;
                                this.Ar_sent_ball[j].status = 2;
                            }
                            //球的x 大于框的x加30  
                            else if (this.Ar_sent_ball[j].x > s.Ar_hoop[key].x + 30 && this.Ar_sent_ball[j].crash > 0) {
                                this.Ar_sent_ball[j].crash--;
                                this.Ar_sent_ball[j].status = 2;
                            }
                        }
                    }
                }
            };
        }



        if (this.goTime) {

            this.liDu += 10;
            this.inloading.height += 1.83333333333 * 2;
            if (this.liDu >= 900) {
                this.liDu = 900;
                if (this.inloading.height >= 330) {
                    this.inloading.height = 330;
                }

            }
        }
        // else{
        //         this.liDu = 0;
        //         this.inloading.height = 30;
        //     }



    };

    //蓝框生成
    _proto.addBasket = function (x1, y1, _type) {
        var hoop;
        var index = Math.ceil(Math.random() * 10);
        if (_type == 0) {
            if (index < 4) {
                hoop = new Hoop_1UI();
                hoop.score = 1;
                hoop.isComBo = true;
            } else if (index >= 4 && index < 6) {
                hoop = new Hoop_2UI();
                hoop.score = 0;
                hoop.isComBo = false;
            } else if (index >= 6 && index < 9) {
                hoop = new Hoop_3UI();
                hoop.score = 4;
                hoop.isComBo = true;
            } else {
                hoop = new Hoop_1UI();
                hoop.score = 2;
                hoop.isComBo = true;
            }
        } else {
            hoop = new Hoop_4UI();
        }


        hoop.x = x1;
        hoop.y = y1;
        var x2;
        switch (x1) {
            case 600:
                x2 = -300;
                break;
            case -100:
                x2 = 900;
                break;
        }
        Laya.Tween.to(hoop, { x: x2 }, 5000, Laya.Ease.linearInOut, Laya.Handler.create(this, removeHoop, [hoop]), 0, true);
        this.addChild(hoop);
        this.Ar_hoop.push(hoop)
        function removeHoop(hoop) {
            hoop.removeSelf();
            this.Ar_hoop.splice(0, 1);
        }
    }

    return Game;

})(ui.GameUI);

//获取后台操作数据
function ord(coin){

  $.post("Basketball/operate",{"coin":coin,"type":1},function (data){
    if(data.status==1){
      getcoin();
    }
  },"json");
}
function getcoin(){//获取金币
  $.post("Basketball/getcoin",function (data){
    if(data.status==1){
      price=data.price;
      kprice=data.kprice;
    }
  },"json");
}
function kcoin(){//扣除金币
  $.post("Basketball/member_cut_coin",function (data){
    if(data.status==1){

    }
  },"json");
}
//概率
function gl(){
  $.post("Basketball/gl",function (data){
    if(data.status==1){
      Pindex=data.ary;
    }
  },"json");
}
//金币
function coin(){
  $.post("Basketball/coin",function (data){
    if(data.status==1){
      coins=data.ary;
    }
  },"json");
}