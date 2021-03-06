var ary;
(function(){
  alert(12);
  // gl();

    function Bullet(){
        Bullet.__super.call(this)
        this.init()
    }
    Laya.class(Bullet, "Bullet", laya.display.Sprite)


    Bullet.prototype.init = function(){
        // 添鱼网数组
        this.fishWebArr = [
            urlString+"comp/web_1.png",
            urlString+"comp/web_2.png",
            urlString+"comp/web_3.png",
            urlString+"comp/web_4.png",
            urlString+"comp/web_5.png",
            urlString+"comp/web_6.png",
            urlString+"comp/web_7.png",
        ]
       this.addBulletArray = [
           urlString+"comp/bullet_1.png",
           urlString+"comp/bullet_2.png",
           urlString+"comp/bullet_3.png",
           urlString+"comp/bullet_4.png",
           urlString+"comp/bullet_5.png",
           urlString+"comp/bullet_6.png",
           urlString+"comp/bullet_7.png",
       ]
        // 实例化主页ＵＩ
        this.home = new homeUI();
        this.addChild(this.home);
        //实例化鱼方法
        this.Fish = new Fish()
        this.timeOver = false;
        this.addChild(this.Fish)
        this.index = 0;
        this.biuTimer = 0;
        this.isCanBiu = true;
        // // 实例化子弹层
        // this.webLayer = new Laya.Sprite();
        // this.addChild(this.webLayer);
        // 实例化子弹层
        this.BulletLayer = new Laya.Sprite();
        this.addChild(this.BulletLayer);
        this.animLayer = new Laya.Sprite();
        this.addChild(this.animLayer);
        this.seep = 0;
        //炮台皮肤的数组
        this.camionArr = [this.home.cannon_0,this.home.cannon_1,this.home.cannon_2,this.home.cannon_3,this.home.cannon_4,this.home.cannon_5,this.home.cannon_6];
        //index++ 事件
        this.home.btn_add.on(Laya.Event.MOUSE_DOWN,this,this.onAdd)
        //index-- 事件
        this.home.btn_jj.on(Laya.Event.MOUSE_DOWN,this,this.onPlus)
        //点击舞台 发射子弹
        this.on(Laya.Event.MOUSE_DOWN,this,this.Bullets)
        //循环计时器
        Laya.timer.frameLoop(1,this,this.onFrame)
    }

    Bullet.prototype.onAdd = function(){
        //index值每次加1
        this.index++;
        if(this.index>=this.camionArr.length){
            this.index=0;
        }
        this.changeSkin()
    }
    Bullet.prototype.onPlus = function(){
        this.index--;
         //index值每次减1
        if(this.index<0){
            this.index=this.camionArr.length-1;
        }
        this.changeSkin()
    }
    Bullet.prototype.changeSkin =function(){
        //循环炮台皮肤数组
        for(key in this.camionArr){
            if(key==this.index){
                //为当前index时 当前index显示
                this.camionArr[key].visible = true;
            }else{
                //其他皮肤隐藏
                this.camionArr[key].visible = false;
            }
        }
    }

    Bullet.prototype.Bullets = function(e){
        //增加一个子弹方法
        var s = this;
        if(parseInt(s.home.goldMun.text)<=0){s.home.goldMun.text=0}
        if(e.stageY>=WIN_H-100 || !s.isCanBiu || parseInt(s.home.goldMun.text)<=0 || s.timeOver) return;
        this.getFish =  Math.floor(Math.random()*100)
        this.biuTimer=0;
        //角度计算
        var height =  350 - e.stageX; //获取起点到终点之间的高度
        var width =   900 - e.stageY;  //获取起点到终点之间的宽度
        var angle = Math.atan2(height,width); //角度计算
        var _angle = angle * 180 / Math.PI; //计算con值
        if(s.home.goldMun.text<(this.index+1)*5){
          layer.confirm('您的金币不足，是否充值金币？', {
              btn: ['好的', '取消'], //可以无限个按钮

          }, function(index, layero){
              //按钮【按钮一】的回调
              window.location.href=$("#rg").val();
          }, function(index){
              layer.close(index);
          });
          return;
        }
        else{
          s.angle = angle;
          s._angle = _angle;
          this.camionArr[this.index].rotation = -s._angle
          new addBullet(s,this.index,_angle) //增加一个子弹
        }


        s.home.goldMun.text = parseInt(s.home.goldMun.text)-(this.index+1)*5
          ord(s.home.goldMun.text);
          if(s.home.goldMun.text == 0){
            layer.confirm('您的金币不足，是否充值金币？', {
                btn: ['好的', '取消'], //可以无限个按钮

            }, function(index, layero){
                //按钮【按钮一】的回调
                window.location.href=$("#rg").val();
            }, function(index){
                layer.close(index);
            });
          }

    }

    function addBullet(s,index,angle){
        s.ape = new Laya.Sprite();
        s.ape.autoSize = true;
        // 根据index来改变子弹
        s.ape.loadImage(s.addBulletArray[index])
        s.ape.moveSeep = 0.2;//每个子弹移动的步数
        s.ape.pivotX = 18;//设置旋转X中心点
        s.ape.name = "Bullet";
        s.ape.pivotY = s.ape.width/2;//设置旋转Y中心点
        s.ape.pos(350,900);//子弹起始位置
        s.ape._angle = angle;
        s.ape.mode = "run"
        s.ape.rotation = -angle-6//子弹旋转角度
        s.ape.bulletIndex = index;//存储子弹的类型
        s.BulletLayer.addChild(s.ape);//往子弹层添加子弹
    }

    Bullet.prototype.onFrame = function(){
        var s = this;
        this.biuTimer++;
        if(this.biuTimer>20){
            this.isCanBiu = true
        }else{
            this.isCanBiu = false
        }
        for(key in s.BulletLayer._childs){
            if(s.BulletLayer._childs[key].mode=="die"){
                s.BulletLayer._childs[key].removeSelf();
            }
        }
        for(i in s.Fish.fishsLayer._childs){

              if(s.Fish.fishsLayer._childs[i].mode=="die"){
                s.Fish.fishsLayer._childs[i].stop = true;
                setTimeout(function(){
                    for(key in s.Fish.fishsLayer._childs){
                        if(s.Fish.fishsLayer._childs[key].stop){
                             s.addGold(s.Fish.fishsLayer._childs[key].x,s.Fish.fishsLayer._childs[key].y,s.Fish.fishsLayer._childs[key].HP)
                             s.Fish.fishsLayer._childs[key].removeSelf();
                        }
                    }
                },300)

            }
        }
        for(key in s.BulletLayer._childs){
            if(s.BulletLayer._childs[key].name=="Bullet"){
                // 子弹移动位置
                s.BulletLayer._childs[key].x+= -1*(Math.sin(s.BulletLayer._childs[key]._angle * Math.PI / 180)*6);
                s.BulletLayer._childs[key].y+= -1*(Math.cos(s.BulletLayer._childs[key]._angle * Math.PI / 180)*6);
                // 子弹超出屏幕时，移除子弹
                if(s.BulletLayer._childs[key].y<=-s.BulletLayer._childs[key].height || s.BulletLayer._childs[key].x<=-s.BulletLayer._childs[key].width ||s.BulletLayer._childs[key].x>WIN_W){
                    s.BulletLayer._childs[key].mode = "die";
                    console.log("移除自己")
                }
            }
        }/*
       for(var i=0;i<this.Fish.fishsLayer.numChildren;i++){
         for(var key=0;key<this.BulletLayer.numChildren;key++){
                if(hitTestRectArc(this.BulletLayer.getChildAt(key),this.Fish.fishsLayer.getChildAt(i),[80,80],40)){
                    this.BulletLayer._childs[key].mode = "die";
                   var index = this.BulletLayer._childs[key].bulletIndex
                   var x = this.Fish.fishsLayer.getChildAt(i).x
                        var y = this.Fish.fishsLayer.getChildAt(i).y
                    s.webIcon(x,y,index)
                    console.log(s.Fish.fishsLayer._childs[i].HP,this.BulletLayer._childs[key].bulletIndex)
                    if(s.Fish.fishsLayer._childs[i].HP<=this.BulletLayer._childs[key].bulletIndex){
                        var x = this.Fish.fishsLayer.getChildAt(i).x
                        var y = this.Fish.fishsLayer.getChildAt(i).y
                        s.Fish.fishsLayer._childs[i].mode = "die"
                    }
                    if(this.index>=6 && s.Fish.fishsLayer._childs[i].HP>6 && this.getFish>=80){
                            s.Fish.fishsLayer._childs[i].mode = "die"
                    }
                }
            }

            for(var key in this._childs){
                if(this._childs[key].name=="webIcon"){
                  alert(s.Fish.fishsLayer._childs[i].mode);
                    if(hitTestRectArc(this._childs[key],this.Fish.fishsLayer.getChildAt(i),[this._childs[key].width/2,this._childs[key].height/2],this._childs[key].width/4)){
                        if(s.Fish.fishsLayer._childs[i].HP<=this.index){
                            s.Fish.fishsLayer._childs[i].mode = "die"
                        }
                        if(this.index>=6 && s.Fish.fishsLayer._childs[i].HP>6 && this.getFish>=80){
                             s.Fish.fishsLayer._childs[i].mode = "die"
                        }
                    }
                }
            }
         }*/


         for(var i=0;i<this.Fish.fishsLayer.numChildren;i++){
           for(var key=0;key<this.BulletLayer.numChildren;key++){
                  if(hitTestRectArc(this.BulletLayer.getChildAt(key),this.Fish.fishsLayer.getChildAt(i),[80,80],40)){
                      this.BulletLayer._childs[key].mode = "die";
                     var index = this.BulletLayer._childs[key].bulletIndex;
                     var x = this.Fish.fishsLayer.getChildAt(i).x;
                          var y = this.Fish.fishsLayer.getChildAt(i).y;
                      s.webIcon(x,y,index);
                      console.log(s.Fish.fishsLayer._childs[i].HP,this.BulletLayer._childs[key].bulletIndex);
                      // if(s.Fish.fishsLayer._childs[i].HP<=this.BulletLayer._childs[key].bulletIndex){
                      //     var x = this.Fish.fishsLayer.getChildAt(i).x;
                      //     var y = this.Fish.fishsLayer.getChildAt(i).y;
                      //     s.Fish.fishsLayer._childs[i].mode = "die";
                      // }
                      // if(this.index>=6 && s.Fish.fishsLayer._childs[i].HP>6 && this.getFish>=80){
                      //         s.Fish.fishsLayer._childs[i].mode = "die";
                      // }
                  }
              }

              for(var key in this._childs){
                  if(this._childs[key].name=="webIcon"){
                      if(hitTestRectArc(this._childs[key],this.Fish.fishsLayer.getChildAt(i),[this._childs[key].width/2,this._childs[key].height/2],this._childs[key].width/4)){


                      //  gl(this.index,s.Fish.fishsLayer._childs[i].HP,this.getFish,s.Fish.fishsLayer._childs[i].mode);
                  //    gl();
            /*
                  for(var a=0;a<ary.lengt;a++){
                        alert(ary[a][0]);
                    for(var b=0;b<ary[a].length;b++){

                      if(this.index == a){

                        if(s.Fish.fishsLayer._childs[i].HP==b && this.getFish>=ary[a][b]){
                           s.Fish.fishsLayer._childs[i].mode = "die";
                        }

                      }
                    }
                  }*/

                          if(this.index==6){
                              for(var j=0;j<10;j++){
                                // alert(ary[6][j])
                                if(s.Fish.fishsLayer._childs[i].HP==j && this.getFish>=ary[6][j]){
                                   s.Fish.fishsLayer._childs[i].mode = "die";
                                }
                              }

                          }
                          if(this.index==5){
                            for(var j=0;j<10;j++){
                              // alert(ary[6][j])
                              if(s.Fish.fishsLayer._childs[i].HP==j && this.getFish>=ary[5][j]){
                                 s.Fish.fishsLayer._childs[i].mode = "die";
                              }
                            }
                          }
                          if(this.index==4){
                            for(var j=0;j<10;j++){
                              // alert(ary[6][j])
                              if(s.Fish.fishsLayer._childs[i].HP==j && this.getFish>=ary[4][j]){
                                 s.Fish.fishsLayer._childs[i].mode = "die";
                              }
                            }
                          }
                          if(this.index==3){
                            for(var j=0;j<10;j++){
                              // alert(ary[6][j])
                              if(s.Fish.fishsLayer._childs[i].HP==j && this.getFish>=ary[3][j]){
                                 s.Fish.fishsLayer._childs[i].mode = "die";
                              }
                            }
                          }
                          if(this.index==2){
                            for(var j=0;j<10;j++){
                              // alert(ary[6][j])
                              if(s.Fish.fishsLayer._childs[i].HP==j && this.getFish>=ary[2][j]){
                                 s.Fish.fishsLayer._childs[i].mode = "die";
                              }
                            }
                          }
                          if(this.index==1){
                            for(var j=0;j<10;j++){
                              // alert(ary[6][j])
                              if(s.Fish.fishsLayer._childs[i].HP==j && this.getFish>=ary[1][j]){
                                 s.Fish.fishsLayer._childs[i].mode = "die";
                              }
                            }
                          }
                          if(this.index==0){

                            for(var j=0;j<10;j++){
                              // alert(ary[6][j])
                              if(s.Fish.fishsLayer._childs[i].HP==j && this.getFish>=ary[0][j]){
                                 s.Fish.fishsLayer._childs[i].mode = "die";
                              }
                            }
                          }
                      }
                  }
              }
           }

    }
    // 撒网
    Bullet.prototype.webIcon = function(x,y,index){
        var s = this;
        var ape = new Laya.Sprite()
            ape.loadImage(this.fishWebArr[index])
            ape.autoSize = true;
            ape.name = "webIcon";
            ape.pos(x-index*10,y-index*10);
            this.addChild(ape);

            setTimeout(function(){
                for(key in s._childs){
                    if(s._childs[key].name=="webIcon"){
                        s._childs[key].removeSelf()
                    }
                }
            },200)
    }
    // 金币动画
    Bullet.prototype.addGold = function(x,y,index){
        var s= this;
        this.gold = new Laya.Animation();
        this.gold.loadAtlas(urlString+"res/atlas/anim/coinAni2.json");
        this.gold.autoSize = true;
        this.gold.play();//鱼动画播放
        this.gold.autoSize = true;//自动获取鱼的大小
        this.gold.interval =50;//鱼的动画播放间隔
        this.gold.pos(x,y) //鱼的初始位置
        this.gold.name = "gold";
        this.animLayer.addChild(this.gold); //把鱼添加到舞台
        this.gold.stop = false;
           addcoin(s.home.goldMun,index);
        this.gold.on(Laya.Event.COMPLETE,this.gold,function(){

            Laya.Tween.to(this,{x:460,y:-150,rotation:360},800,null,Laya.Handler.create(this,function(){

                this.removeSelf();
            }))

        })
    }
// a.intersects(b)
//获取鱼的金币  0 1 2 3 4 5 6 7 8 9...
function addcoin(a,fid){
  $.post("Catchfish/getfishcoin",{"fid":fid},function (data){
    a.text=parseInt(a.text)+data.fishcoin;
    ord(a.text);
  },"json");
}
//实时修改金币
function ord(coin){
  $.post("Catchfish/operate",{"coin":coin,"type":1},function (data){
    if(data.status==1){

    }
  },"json");
}
//概率 a   s.Fish.fishsLayer._childs[i].HP  b this.getFish c s.Fish.fishsLayer._childs[i].mode

function gl(){

console.log(ary)
  $.post("Catchfish/gl",{"index":1},function (data){
    ary=data.ary;
  },"json");
}

})()
