function SoundManage(){
    this.init();
}

SoundManage.prototype.init = function(){
}

SoundManage.prototype.SoundPlay = function(name){

    switch (name){
        case "bgMusic":
            Laya.SoundManager.playMusic(urlStrings+"res/sounds/bgmusic.mp3",0);
            break;
        case "hitSound":
            Laya.SoundManager.playSound(urlStrings+"res/sounds/attend.mp3",1);
            break;
        case "GameOverSound":
            Laya.SoundManager.playSound(urlStrings+"res/sounds/gg.mp3",1);
            break;
    }

}

SoundManage.prototype.StopPlay = function(name){
    switch (name){
        case "bgMusic":
            Laya.SoundManager.stopMusic(urlStrings+"res/sounds/bgmusic.mp3",0);
            break;
        case "hitSound":
            Laya.SoundManager.stopSound(urlStrings+"res/sounds/attend.mp3",1);
            break;
        case "GameOverSound":
            Laya.SoundManager.stopSound(urlStrings+"res/sounds/gg.mp3",1);
            break;
    }
}

