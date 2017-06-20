var win_w=600;
var win_h=966;
var player;
var loadingTip;
var __ai = 0;
var urlString = '/Public/game/pumpkin/';
(function(GameInit){
    Laya.init(win_w,win_h,Laya.WebGL);
    // Laya.Stat.show(0,0);
    Laya.stage.alignH=Laya.Stage.ALIGN_CENTER;
    Laya.stage.alignV=Laya.Stage.ALIGN_TOP;
    // Laya.stage.scaleMode = 'showall';
    Laya.stage.scaleMode = 'exactfit';
    Laya.stage.screenMode = "none";
    Laya.stage.bgColor = "#000000";
    player = new Laya.Sprite();
    player.width = win_w;
    player.height = win_h;
    player.pos(0,0);
    Laya.stage.addChild(player);
    loadUI();
})()

 function loadUI(){
        var imgArray = [
            {url:urlString+"comp/puk.png",type:Laya.loader.IMAGE},
            {url:urlString+"comp/boy.png",type:Laya.loader.IMAGE},
            {url:urlString+"comp/home_bg.png",type:Laya.loader.IMAGE},
            // {url:"http://wx.qlogo.cn/mmopen/RamveVcvq7MYibt0KHN43q1hicmEI7icAlxdoZa57icBqQo03kUnUWbUkppjQPvnV4Me6gpqgIQyGIThktDB6Bujjf6a06DgzBEQ/0",type:Laya.loader.IMAGE},
            {url:urlString+"comp/start_bg.png",type:Laya.loader.IMAGE},
            {url:urlString+"res/atlas/comp.json",type:Laya.Loader.ATLAS},
            {url:urlString+"res/atlas/puk.json",type:Laya.Loader.ATLAS},
            {url:urlString+"res/sounds/bgmusic.mp3",type:Laya.Loader.SOUND},
            {url:urlString+"res/sounds/hitSound.wav",type:Laya.Loader.SOUND}
        ]
        Laya.loader.load(imgArray,Laya.Handler.create(this,onLoadUI),Laya.Handler.create(this,onProgress,null,false));
        lodaTextTip = new Laya.Text();
        lodaTextTip.text = "正在加载中...";
        lodaTextTip.color = "#ffffff";
        lodaTextTip.fontSize = 30;
        lodaTextTip.pos((win_w-lodaTextTip.width)/2,win_h/2);
        player.addChild(lodaTextTip);
        
        loadingTip = new Laya.Sprite();
        player.addChild(loadingTip);
        player.addChild(loadingTip);
        loadingTip.loadImage(urlString+"comp/puk.png");
        loadingTip.pos(240,320)
    }
    function onProgress(progress){
        __ai++
        lodaTextTip.text = "";
        lodaTextTip.text = "正在加载中..."+Math.floor(progress*100)+"%";
        if(__ai%2==0){
            loadingTip.y=320;
        }else{
            loadingTip.y=300;
        }
        
    }
    function onLoadUI(){
        lodaTextTip.removeSelf();
        loadingTip.removeSelf();
        _gamestart=new gameStart();
        player.addChild(_gamestart);
       
    }
