// 碰撞检测
function isSameQuadrant(cood,objA,objB){  
    var coodX = cood.x;  
    var coodY = cood.y;  
    var xoA = objA.x  
    ,yoA = objA.y  
    ,xoB = objB.x  
    ,yoB = objB.y;  
    
    if(xoA-coodX>0 && xoB-coodX>0){  
        if((yoA-coodY>0 && yoB-coodY>0) || (yoA-coodY<0 && yoB-coodY<0)){  
            return true;  
        }  
        return false;  
    }else if(xoA-coodX<0 && xoB-coodX<0){  
        if((yoA-coodY>0 && yoB-coodY>0) || (yoA-coodY<0 && yoB-coodY<0)){  
            return true;  
        }  
        return false;  
    }else{  
        return false;  
    }  
}  
// 对象1，对象2，对象2的数组高和宽，半径r，偏移的X轴，
function hitTestRectArc(rectObj,arcObj,rectVec,arcR,clearX,clearY){
    var clearX = clearX || 0 ;
    var clearY = clearY || 0 ;
    var rw = rectObj.width  
    ,rh = rectObj.height-clearY
    ,ar = arcObj.width*0.5   
    ,rx = rectObj.x  - clearX
    ,ry = rectObj.y  
    ,ax = arcObj.x  
    ,ay = arcObj.y;  
    
    if(rectVec){  
        
        rx += (rw - rectVec[0])*0.5;  
        ry += (rh - rectVec[1])*0.5;  
        rw = rectVec[0];  
        rh = rectVec[1];  
    }  
    if(arcR){  
        ax += (ar - arcR);  
        ay += (ar - arcR);  
        ar = arcR;  
    }  
    
    var rcx = rx+rw*0.5,rcy = ry+rh*0.5;  
    var rltx = rx  
    ,rlty = ry  
    ,rlbx = rx  
    ,rlby = ry+rh  
    ,rrtx = rx+rw  
    ,rrty = ry  
    ,rrbx = rx+rw  
    ,rrby = ry+rh;  
    
    if(  
        isSameQuadrant(  
            {x:ax,y:ay},  
            {x:rltx,y:rlty},  
            {x:rrbx,y:rrby}  
        )  
    ){  
        var dX1 = Math.abs(ax-rltx),dY1 = Math.abs(ay-rlty);  
        var dX2 = Math.abs(ax-rlbx),dY2 = Math.abs(ay-rlby);  
        var dX3 = Math.abs(ax-rrtx),dY3 = Math.abs(ay-rrty);  
        var dX4 = Math.abs(ax-rrbx),dY4 = Math.abs(ay-rrby);  
        
        if(  
            (((dX1*dX1) + (dY1*dY1)) <= (ar*ar))  
            ||(((dX2*dX2) + (dY2*dY2)) <= (ar*ar))  
            ||(((dX3*dX3) + (dY3*dY3)) <= (ar*ar))  
            ||(((dX4*dX4) + (dY4*dY4)) <= (ar*ar))  
        ){  
            return true;  
        }  
        return false;  
    }else{  
        var result = false;  
        var squareX = ax  
        ,squareY = ay  
        ,squareW = ar*2  
        ,squareH = squareW;  
        if(  
            (Math.abs(squareX-rcx) <= (squareW+rw)*0.5)  
            &&(Math.abs(squareY-rcy) <= (squareH+rh)*0.5)  
        ){  
            result = true;  
        }  
        return result;  
    } 
}