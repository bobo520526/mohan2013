var gameOver=(function(_super){
    function gameOver(_super){
        gameOver.super(this);
        this.init();
    };
    Laya.class(gameOver,"gameOver",_super);
    var _proto=gameOver.prototype;
    _proto.gamescore=function(){
  
    }
    return gameOver;
})(ui.gameOverUI);