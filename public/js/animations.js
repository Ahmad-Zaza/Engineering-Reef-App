init = function(){
      jarallax = new Jarallax();
      
      jarallax.setDefault('.s1 h2,.s2 h2,.s3 h2,.s4 h2,.s1 p,.s2 p,.s3 p,.s4 p', {opacity:'0'});
      jarallax.setDefault('.s1 p,.s2 p,.s3 p,.s4 p,.s5 p', {marginLeft:'-1000px'});
      jarallax.addAnimation('.s1 img',[{progress: "0%", top:"70%"}, {progress: "100%", top: "40%"}]);
      jarallax.addAnimation('.s2 img',[{progress: "0%", top:"90%"}, {progress: "100%", top: "-5%"}]);
      jarallax.addAnimation('.s4 img',[{progress: "0%", top:"0%"}, {progress: "100%", top: "-10%"}]);
      
      jarallax.addAnimation('.s1 h2',[{progress: "0%", left:"-800px"}, {progress: "10%", left: "100px"}]);
      jarallax.addAnimation('.s1 h2',[{progress: "10%", left:"100px"}, {progress: "40%", left: "150px"}]);
      jarallax.addAnimation('.s1 h2',[{progress: "0%", opacity:"1"}, {progress: "30%", opacity:"1"}]);
      jarallax.addAnimation('.s1 h2',[{progress: "30%", opacity:"1"}, {progress: "40%", opacity:"0"}]);
      
      jarallax.addAnimation('.s2 h2',[{progress: "30%", left:"-800px"}, {progress: "40%", left: "100px"}]);
      jarallax.addAnimation('.s2 h2',[{progress: "40%", left:"100px"}, {progress: "70%", left: "150px"}]);
      jarallax.addAnimation('.s2 h2',[{progress: "30%", opacity:"1"}, {progress: "60%", opacity:"1"}]);
      jarallax.addAnimation('.s2 h2',[{progress: "60%", opacity:"1"}, {progress: "70%", opacity:"0"}]);
      
      jarallax.addAnimation('.s3 h2',[{progress: "60%", left:"-800px"}, {progress: "70%", left: "100px"}]);
      jarallax.addAnimation('.s3 h2',[{progress: "70%", left:"100px"}, {progress: "100%", left: "150px"}]);
      jarallax.addAnimation('.s3 h2',[{progress: "60%", opacity:"1"}, {progress: "100%", opacity:"1"}]);
      
      jarallax.addAnimation('.s1 p',[{progress: "15%", opacity:"0"}, {progress: "20%", opacity:"1"}]);
      jarallax.addAnimation('.s1 p',[{progress: "20%", opacity:"1"}, {progress: "30%"}]);
      jarallax.addAnimation('.s1 p',[{progress: "30%", opacity:"1"}, {progress: "40%", opacity:"0"}]);
      jarallax.addAnimation('.s1 p',[{progress: "15%", marginLeft:"0"}, {progress: "40%"}]);
      
      jarallax.addAnimation('.s2 p',[{progress: "45%", opacity:"0"}, {progress: "50%", opacity:"1"}]);
      jarallax.addAnimation('.s2 p',[{progress: "50%", opacity:"1"}, {progress: "60%"}]);
      jarallax.addAnimation('.s2 p',[{progress: "60%", opacity:"1"}, {progress: "70%", opacity:"0"}]);     
      jarallax.addAnimation('.s2 p',[{progress: "45%", marginLeft:"0"}, {progress: "70%"}]);
      
      jarallax.addAnimation('.s3 p',[{progress: "75%", opacity:"0"}, {progress: "80%", opacity:"1"}]);
      jarallax.addAnimation('.s3 p',[{progress: "80%", opacity:"1"}, {progress: "100%"}]);
      jarallax.addAnimation('.s3 p',[{progress: "75%", marginLeft:"0"}, {progress: "100%"}]);
    }
