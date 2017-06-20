function SoundManage(){
    this.init();
}

SoundManage.prototype.init = function(){
}

SoundManage.prototype.SoundPlay = function(name){

    switch (name){
        case "bgMusic":
            Laya.SoundManager.playMusic(urlString+"res/sounds/bgmusic.mp3",0);
            break;
        case "hitSound":
            Laya.SoundManager.playSound(urlString+"res/sounds/hitSound.wav",1);
            break;
        case "GameOverSound":
            Laya.SoundManager.playSound(urlString+"res/sounds/gg.mp3",1);
            break;
    }

}

SoundManage.prototype.StopPlay = function(name){
    switch (name){
        case "bgMusic":
            Laya.SoundManager.stopMusic(urlString+"res/sounds/bgmusic.mp3");
            break;
        case "hitSound":
            Laya.SoundManager.stopMusic(urlString+"res/sounds/hitSound.wav");
            break;
        case "GameOverSound":
            Laya.SoundManager.stopSound(urlString+"res/sounds/gg.mp3",1);
            break;
        case "all":
            Laya.SoundManager.stopMusic();
            
    }
}