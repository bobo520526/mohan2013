var win_W=600;
var win_H=966;
var player,backLayer,_gamestart;
var SoundManges;
var urlStrings = "/Public/game/Fruitwars/";
   (function GameInit(){
        Laya.init(win_W,win_H,Laya.WebGL);
        // Laya.Stat.show(0,0); //显示帧数
        Laya.stage.alignH = Laya.Stage.ALIGN_CENTER;
        Laya.stage.alignV = Laya.Stage.ALIGN_TOP;
        Laya.stage.scaleMode = 'exactfit';
        Laya.stage.screenMode = "none";
        Laya.stage.bgColor = "#000000";
        player = new Laya.Sprite();
        player.width = win_W;
        player.height = win_H;
        player.pos(0,0)
        Laya.stage.addChild(player);
        loadUI();
    })()
    function loadUI(){
        var imgArray = [
            {url:urlStrings+"comp/beginGame.png",type:Laya.loader.IMAGE},
            {url:urlStrings+"comp/shangfangui.png",type:Laya.loader.IMAGE},
            {url:urlStrings+"res/atlas/comp.json",type:Laya.Loader.ATLAS},
            {url:urlStrings+"res/sounds/bgmusic.mp3",type:Laya.Loader.SOUND},
            {url:urlStrings+"res/sounds/gg.mp3",type:Laya.Loader.SOUND},
            {url:urlStrings+"res/sounds/attend.mp3",type:Laya.Loader.SOUND},
        ]
        Laya.loader.load(imgArray,Laya.Handler.create(this,onLoadUI),Laya.Handler.create(this,onProgress,null,false));
        lodaTextTip = new Laya.Text();
        lodaTextTip.text = "正在加载中...";
        lodaTextTip.color = "#ffffff";
        lodaTextTip.fontSize = 30;
        lodaTextTip.pos((win_W-lodaTextTip.width)/2,win_H/2);
        player.addChild(lodaTextTip);
    }
    function onProgress(progress){
        lodaTextTip.text = "";
       	lodaTextTip.text = "正在加载中..."+Math.floor(progress*100)+"%"

    }
    function onLoadUI(){
        backLayer = new Laya.Sprite();
        player.addChild(backLayer);
       
        _gamestart=new gameStart();
        player.addChild(_gamestart);
        SoundManges = new SoundManage();
        SoundManges.SoundPlay("bgMusic"); 
    
        
    }


