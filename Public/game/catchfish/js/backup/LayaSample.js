
var WIN_W = 600;
var WIN_H = 966;
var urlString = "/Public/game/catchfish/";
var backLayer,allBackLayer;
    (function GameInit(){
        Laya.init(WIN_W,WIN_H,Laya.WebGL);
      //  Laya.Stat.show(0,0); //显示帧数
        Laya.stage.alignH = Laya.Stage.ALIGN_CENTER;
        Laya.stage.alignV = Laya.Stage.ALIGN_TOP;
        Laya.stage.scaleMode = 'showall';
        Laya.stage.screenMode = "none";
        Laya.stage.bgColor = "#000000";
        allBackLayer = new Laya.Sprite();
        allBackLayer.width = WIN_W;
        allBackLayer.height = WIN_H;
        allBackLayer.pos(0,0)
        Laya.stage.addChild(allBackLayer);
        loadUI();
    })()
    function loadUI(){
        var imgArray = [
           {url:urlString+"res/atlas/comp.json",type:Laya.Loader.ATLAS},
           {url:urlString+"res/atlas/anim/coinAni1.json",type:Laya.Loader.ATLAS},
           {url:urlString+"res/atlas/anim/coinAni2.json",type:Laya.Loader.ATLAS},
           {url:urlString+"res/atlas/anim/fish1.json",type:Laya.Loader.ATLAS}
        ]
        Laya.loader.load(imgArray,Laya.Handler.create(this,onLoadUI),Laya.Handler.create(this,onProgress,null,false));
        lodaTextTip = new Laya.Text();
        lodaTextTip.text = "正在加载中...";
        lodaTextTip.color = "#ffffff";
        lodaTextTip.fontSize = 30;
        lodaTextTip.pos((WIN_W-lodaTextTip.width)/2,WIN_H/2);
        allBackLayer.addChild(lodaTextTip);
    }
    function onProgress(progress){
       lodaTextTip.text = "";
       lodaTextTip.text = "正在加载中..."+Math.floor(progress*100)+"%"
    }
    function onLoadUI(){
    
        lodaTextTip.removeSelf()
        backLayer = new Laya.Sprite();
        allBackLayer.addChild(backLayer);
        var stratMeun = new StratMeun()
        backLayer.addChild(stratMeun)
    }
