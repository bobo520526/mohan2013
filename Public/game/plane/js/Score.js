/*
* name;
*/
var Score = (function () {
    function Score(pattern) {
        this.reset(pattern);
    }
    var _proto = Score.prototype;
    _proto.reset = function(pattern){
        this.BoxScore= 2;
        if(pattern=="onemachine"){
            this.score = 0;
            return;
        }
        this.roomerScore = 0;
        this.roomsOwnerScore = 0;
    }
    return Score;
}());