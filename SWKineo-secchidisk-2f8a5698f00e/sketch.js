var scenes = new SceneManager();

// /* GUI Params */
// var Menu_Hide = true;
// var Lake_Color = '#7bceef';
// var menui;

/* Compatibility Check Elements */
var compatBoard;

/* Opening Menu Elements */
var introBoard;
var introStartDys;
var introStartIntmdt;
var introStartProd;
var introStartDys;
var introStartDysProd;

/* Simulator Elements */
var D0;
var submitButton;
var goBack;
var measurements;     // the display panel 
var drawMeasurements = true; 

/* Lake Variables */
var lakeType;                 // String
var lakeColor;                // Color
var lakeDepth;                // Double
var lakeTarget;               // Double

/* Reading Results Elements */
var resultsBoard;
var resultsRestart;
var failedMessage;   
/* Results Variables */
var attemptsLeft = 3;
var measuredDepth;            // Double
var measuredError;            // Double
var measuredErrorRel;         // Double
var measuredTolerance;        // String

function setup() {

  /* --- Compatibility Check Scene --- */
  scenes.addScene(new Scene(windowWidth, windowHeight,
    function() {
      // setup

      compatBoard = new TextBoard(width / 6, height / 4, width * 2 / 3, height / 2);
    },
    function() {
      // draw

      // If the compatbility check passes, skip to the full app
      if (compatCheck()) {
        scenes.nextScene();
        scenes.setup();
      }

      scenes.background(60);
      compatBoard.draw();
    }
  ));

  /* --- Opening Menu Scene --- */
  scenes.addScene(new Scene(windowWidth, windowHeight,
    function() {
      // setup()

      var left = width / 7;
      var right = width - left;
      var top = height / 4;
      var bottom = height - top;

      introBoard = new TextBoard(left, top, right - left, bottom - top);
      introBoard.background = 240;
      introBoard.accent = 150;
      introBoard.addParagraph();
      introBoard.addText("Select Your Lake Type", 100, 20, "Helvetica", BOLD);
      introBoard.addParagraph();
      introBoard.addParagraph();
      introBoard.addParagraph();
      // introBoard.addText("Dys",
      //   "#000000", 16, "Helvetica", BOLD);
      introBoard.addParagraph();
      introBoard.addText("                            " +
        "Bluish color, with readings above 4 meters",
        100, 16, "Helvetica", BOLD);
      introBoard.addParagraph();
      introBoard.addParagraph();
      introBoard.addParagraph();
      introBoard.addParagraph();
      // introBoard.addText("Intermediate", "#000000", 16, "Helvetica", BOLD);
      // introBoard.addParagraph();
      introBoard.addText("                            " +
        "Blue or green-brown, with readings of 4 to 7 meters");
        introBoard.addParagraph();
        introBoard.addParagraph();
        introBoard.addParagraph();
        introBoard.addParagraph();
        introBoard.addText("                            " +
          "Green Background, high algae, readings less than 3 meters");
        introBoard.addParagraph();
        introBoard.addParagraph();
        introBoard.addParagraph();
        introBoard.addParagraph();
        introBoard.addText("                            " +
          "Distinct tea or rootbeer color, readings less than 3 meters");
        introBoard.addParagraph();
        introBoard.addParagraph();
        introBoard.addParagraph();
        introBoard.addParagraph();
        introBoard.addText("                            " +
          "Green-brown and murky, readings less than 3 meters");
          // "Productive     Green background, high algae, readings lower than 3 meters\n" +
          // "Dystrophic     Distinct tea or rootbeer color, readings lower than  meters");

      introStartClear = new Button(290, 370, 95, 40, "Clear",
        function() {
          // Button Selected
            setLakeType(1);
            scenes.nextScene();
            scenes.setup();
        },
        function() {}
      );
      // introStartClear.color = [245, 245, 245];
      introStartClear.fontSize = 14;

      introStartIntmdt = new Button(290, 430, 95, 40, "Intermediate",
        function() {
          // Button Selected
          setLakeType(2);
          scenes.nextScene();
          scenes.setup();
        },
        function() {}
      );
      introStartIntmdt.fontSize = 14;
      // introStartIntmdt.color = [245, 245, 245];

      introStartProd = new Button(290, 490, 95, 40, "Productive",
        function() {
          // Button Selected
            setLakeType(3);
            scenes.nextScene();
            scenes.setup();
        },
        function() {}
      );
      // introStartProd.color = [245, 245, 245];
      introStartProd.fontSize = 14;

      introStartDys = new Button(290, 550, 95, 40, "Dystrophic",
        function() {
          // Button Selected
            setLakeType(4);
            scenes.nextScene();
            scenes.setup();
        },
        function() {}
      );
      // introStartDys.color = [245, 245, 245];
      introStartDys.fontSize = 14;

      introStartDysProd = new Button(290, 610, 95, 60, "Dystrophic\nProductive",
        function() {
          // Button Selected
            setLakeType(5);
            scenes.nextScene();
            scenes.setup();
        },
        function() {}
      );
      // introStartDysProd.color = [245, 245, 245];
      introStartDysProd.fontSize = 14;

    },
    function() {
      // draw()
      scenes.background(60);
      introBoard.draw();
      introStartClear.run();
      introStartIntmdt.run();
      introStartProd.run();
      introStartDys.run();
      introStartDysProd.run();
    })
  );
  /* --- End Intro Menu Scene --- */

  /* --- Simulator Scene --- */
  scenes.addScene(new Scene(windowWidth, windowHeight,
    function() {
      // setup()
      D0 = new disk();
      attemptsLeft = 3;
       submitButton = new Button(width - 170, 120, 110, 50, "Submit",   
          function() {
           // Button Selected 
           // Analyze trial and give feedback 
           // NOTE: THESE SELECT AND DESELECT FUNCTIONS ARE THE SAME BECAUSE IT ALTERNATES PER PRESS

        	if (D0.currentDepth != 0) {
      			measuredDepth = D0.currentDepth;
      			if (analyzeTrial()) {                          // if correct move to next scene, otherwise the run section below
        			scenes.nextScene();                        // will deal with the case of no chances left
        			scenes.setup();
      				} else {
        				attemptsLeft--;
        				// TODO: Tell the user something, check try number
      				}
            }
         },
         function() {
           // Button Deselected
           if (D0.currentDepth != 0) {
      			measuredDepth = D0.currentDepth;
      			if (analyzeTrial()) {   
        			scenes.nextScene();
        			scenes.setup();
      				} else {
      					console.log(attemptsLeft);
        				attemptsLeft--;
        				// TODO: Tell the user something, check try number
      				}
            }
         }
       );
      goBack = new Button(width - 170, 60, 110, 50, "Switch Types",
        function() {
          scenes.setScene(2);
          scenes.setup();
        }
      );
    },
    function() {                                    // THIS IS WHERE THE STUFF FOR THE SIM IS DRAWN
      // draw()
      scenes.background(lakeColor);

     // push();
      //fill(0, 2.2);
      // noStroke();
        //for(var i = 0; i < 100; i++){
        //  var j = map(i, 0, 99, width + 200, 0);
        //  ellipse(windowWidth/2, windowHeight/2, j, j);           // I don't know what this is for so I commented it out
        //}
      //pop();

      D0.maxDepth = ceil(lakeDepth)-1;
      D0.run();


      // Make the button do
      submitButton.run();
      goBack.run();

      // If chances are zero, move to results
      if(attemptsLeft == 0)
      {
      	scenes.nextScene();
        scenes.setup();
      }

      strokeWeight(0);
       //ellipse(200, 700, 20, 20);
       dropShadow(0, 0, 0, 0);

      push();
      fill(0, 0, 0, 0);
      strokeWeight(100);
      stroke(50);
      rect(0, 0, windowWidth, windowHeight);
      pop();
    })
  );
  /* --- End Simulator Scene --- */

  /* --- Reading Results Scene --- */
  scenes.addScene(new Scene(windowWidth, windowHeight,
    function() {
      // setup()

      resultsBoard = new TextBoard(200, 200, 700, 500);
      resultsBoard.background = 240;
      resultsBoard.accent = 150;
      resultsBoard.addParagraph();
      resultsBoard.addText(" Reading Results", 100, 20, "Helvetica", BOLD);
      resultsBoard.addParagraph();
      resultsBoard.addParagraph();
      resultsBoard.addParagraph();
      resultsBoard.addParagraph();
      resultsBoard.addText("\tLake Type", 100, 16, "Helvetica", BOLD);
      resultsBoard.addTab();
      resultsBoard.addText(lakeType);
      resultsBoard.addParagraph();
      resultsBoard.addParagraph();
      resultsBoard.addText("\tTarget Depth");
      resultsBoard.addTab();
      resultsBoard.addText(lakeTarget.toFixed(2) + " meters");
      resultsBoard.addParagraph();
      resultsBoard.addParagraph();
      resultsBoard.addText("\tMeasured Depth");
      resultsBoard.addTab();
      resultsBoard.addText(measuredDepth.toFixed(2) + " meters");
      resultsBoard.addParagraph();
      resultsBoard.addParagraph();
      resultsBoard.addText("\tError (absolute)");
      resultsBoard.addTab();
      resultsBoard.addText(measuredError.toFixed(2) + " meters");
      resultsBoard.addParagraph();
      resultsBoard.addParagraph();
      resultsBoard.addText("\tError (relative)");
      resultsBoard.addTab();
      resultsBoard.addText(measuredErrorRel.toFixed(2) + "%");
      resultsBoard.addParagraph();
      resultsBoard.addParagraph();
      resultsBoard.addText("\tWithin Tolerance?");
      resultsBoard.addTab();
      resultsBoard.addText(measuredTolerance);



      resultsRestart = new Button(780, 630, 95, 50, "Test Again",
        function() {
          // Button Selected
            setLakeType(5);
            scenes.setScene(2);
            scenes.setup();
        },
        function() {}
      );
      // introStartDysProd.color = [245, 245, 245];
      introStartDysProd.fontSize = 14;

    },
    function() {
      // draw()
      scenes.background(60);
      resultsBoard.draw();
      push();
      strokeWeight(4);
      stroke(200);
      line(210, 317, 890, 317);
      line(385, 317, 385, 680);
      pop();
      resultsRestart.run();
    })
  );
  /* --- End Reading Results Scene --- */

  scenes.setup();

  // Create the GUI
  sliderRange(0, 90, 1);

}



function draw() {
  scenes.draw();
}

function compatCheck () {
  return true;
}

function setLakeType (type) {
  /* type : int
   * Clear : 1
   * Intermediate : 2
   * Productive : 3
   * Dystrophic : 4
   * Dystrophic Productive : 5
   */

   switch (type) {
      case 1:
        lakeType = "Clear";
        lakeColor = "#6fa5fc";
        lakeDepth = random(10, 15);
        lakeTarget = random(4, 10);
        break;
      case 2:
        lakeType = "Intermediate";
        lakeColor = "#bfc18b";
        lakeDepth = random(10, 15);
        lakeTarget = random(3.5, 7.5);
        break;
      case 3:
        lakeType = "Productive";
        lakeColor = "#6b8474";
        lakeDepth = random(5, 10);
        lakeTarget = random(2, 3);
        break;
      case 4:
        lakeType = "Dystrophic";
        lakeColor = "#82753a";
        lakeDepth = random(5, 10);
        lakeTarget = random(2, 3);
        break;
      case 5:
        lakeType = "Dystrophic Productive";
        lakeColor = "#8a9663";
        lakeDepth = random(5, 10);
        lakeTarget = random(1, 3);
        break;
   }
}



/*
 * Trial Analysis Skeleton
 * Actual Target Value: #.## (+-0.10) meters
 * measuredDepth: #.## meters
 * measuredError (absolute): #.## meters
 * measuredErrorRel (relative): ##.##%
 * measuredTolerance: "Yes"/"No"
 *
 */
function analyzeTrial() {
  measuredError = abs(measuredDepth - lakeTarget);
  measuredErrorRel = measuredError / lakeTarget * 100;

  if (measuredErrorRel < 2.0) {
    measuredTolerance = "Yes";
    return true;
  } else {
    measuredTolerance = "No";
    return false;
  }
}

function keyPressed() {
  if (keyCode == UP_ARROW){
    D0.sendIt(-1);
  }

  if (keyCode == DOWN_ARROW){
    D0.sendIt(1);
  }

 /*  if (keyCode == ENTER && scenes.sceneIndex() == 2) {
    // Only check the trial if the depth isn't 0
    if (D0.currentDepth != 0) {
      measuredDepth = D0.currentDepth;
      if ((analyzeTrial() || attemptsLeft == 0)) {             // this got repurposed to the measure button
        scenes.nextScene();
        scenes.setup();
      } else {
        attemptsLeft--;
        // Tell the user something, check try number
      }
    }
  }  */
}

function keyTyped(){
/* 
    if (keyCode == ENTER && scenes.sceneIndex() == 2) {
    // Only check the trial if the depth isn't 0
    if (D0.currentDepth != 0) {
      measuredDepth = D0.currentDepth;                            // read above ^^^^
      if ((analyzeTrial() || attemptsLeft == 0)) {
        scenes.nextScene();
        scenes.setup();
      } else {
        attemptsLeft--;
        // Tell the user something, check try number
      }
    }
  } */
}

function disk(){ //THE BIG DISK CLASS
 this.P0 = createVector(width/8, height/8); // BEGIN POINT
 this.P1 = createVector(width/2, height/2); // END POINT

 this.maxDepth = 10; // MAXIMUM DEPTH OF DISK
 this.currentDepth = 0; //CURRENT DEPTH OF DISK
 this.deltaDepth = 0;
 this.deltaDelta = 0;

 this.x = 0; //  X POSITON OF THE DISK, INITIALIZE TO BEGIN POINT
 this.y = 0 //  Y POSITON OF THE DISK, INITIALIZE TO BEGIN POINT

 this.rad = 0;  // RADIUS OF DISK
 this.hide = false; // DO YA WANNA SEE IT OR NA?

 this.dx = 0; // USE THIS FOR PERLIN NOISE
 this.detheta = 0; // VARIATION OF POSITION

 this.meterPos = createVector(width - width/5, height/5); // POSITION OF THE DEPTH METER

 this.disp = function(){
   if(this.hide == false){ // WE DON'T WANT TO DRAW THE DISK IF IT'S HIDDEN
     //******* DRAW THE DISK
     push();   
     var t = currentDepth; // a temp value for the lerp below                            // I think this is where the disk opacity is defined; old text below \/
     var alpha = (1 - map(t,0,lakeTarget,0,1)) * 255 + map(t,0,lakeTarget,0,1) * 0;       //map(this.rad, 0, sqrt(width*width/4 + height*height/4), -30, 153); 
     stroke(255, alpha);

     var seedValue = noise(this.dx);
     var nois = map(seedValue, 0, 1, 0, .5);
     var numberOfIterations = map(this.currentDepth, 0, this.maxDepth, 60, 60);

     for(var i = 0; i < numberOfIterations ; i++){
       var j = map(i, 0, numberOfIterations , 0, this.rad)
       var k = map(i, 0, numberOfIterations -1, 0, 0);//map(i, 0, numberOfIterations -1, map(this.currentDepth, 0, this.maxDepth, 0, PI/8), 0);
       var l = map(i, 0, numberOfIterations -1, this.rad/30, 0);

       noStroke();
       fill(0, alpha);
       arc(this.x + l*cos(PI/4 + nois), this.y + l*sin(PI/4 + nois), j, j, nois + k, PI/2 + nois - k);
       arc(this.x + l*cos(5*PI/4 + nois), this.y + l*sin(5*PI/4 + nois), j, j, PI + nois + k, 3*PI/2 + nois - k);

       fill(255, alpha);
       arc(this.x + l*cos(3*PI/4 + nois), this.y + l*sin(3*PI/4 + nois), j, j, PI/2 + nois + k, PI + nois - k);
       arc(this.x + l*cos(7*PI/4 + nois), this.y + l*sin(7*PI/4 + nois), j, j, 3*PI/2 + nois + k, nois - k);
     }

     stroke(153, 153);
     strokeWeight(10);
     line(0, 0, this.x, this.y);
     pop();

     //***DRAWING THE DEPTH METER
     push();

     // Gotta draw the outline separately from the shape so HTML5 doesn't add
     // a drop shadow to both the shape and its outline
     strokeWeight(0);
     dropShadow(4, 4, 4, "rgba(0, 0, 0, 0.2)");
     ellipse(this.meterPos.x, this.meterPos.y, 200, 200);
     dropShadow(0, 0, 0, 0);
     fill(0, 0, 0, 0);
     strokeWeight(3);
     ellipse(this.meterPos.x, this.meterPos.y, 200, 200);

     for(var i = 0; i < this.maxDepth; i += 1){
       var theta = map(i, 0, this.maxDepth, 0, 2*PI);

       strokeWeight(3);
       line(this.meterPos.x + 75*cos(theta - PI/2), this.meterPos.y + 75*sin(theta - PI/2),
            this.meterPos.x + 90*cos(theta - PI/2), this.meterPos.y + 90*sin(theta - PI/2)); //DRAW TIC MARKS

       for(var j = 0; j < 6; j++){

       var iota = map(j, 0, 5, theta , theta + 8*PI/this.maxDepth );

       strokeWeight(1);
       line(this.meterPos.x + 75*cos(iota - PI/2), this.meterPos.y + 75*sin(iota - PI/2),
            this.meterPos.x + 90*cos(iota - PI/2), this.meterPos.y + 90*sin(iota - PI/2));

       }

       fill(0);
       textSize(12);
       textAlign(CENTER, CENTER);
       text(i, this.meterPos.x + 60*cos(theta - PI/2), this.meterPos.y + 60*sin(theta - PI/2));
     }

     var phi = map(this.currentDepth, 0, this.maxDepth, 0, 2*PI);

     stroke(255, 153, 153);
     line(this.meterPos.x, this.meterPos.y,
          this.meterPos.x + 65*cos(phi - PI/2), this.meterPos.y + 65*sin(phi - PI/2));

     pop();
   }
 }

 this.update = function(){
   if(this.hide == false){ // WE DON'T WANT TO DRAW THE DISK IF IT'S HIDDEN

     this.dx += .005;
     var noiz = noise(this.dx);
     var dTheta = map(noiz, 0, 1, 0, 35);  //UPDATE PERLIN NOISE, HIGHER RANGE GREATER FLUCTUATION

     this.detheta += .02;
     var dRadius = map(noiz, 0, 1, 2, 10);
     var changeInDir = createVector(this.P1.x + dRadius*cos(3*this.detheta) , this.P1.y + dRadius*sin(2*this.detheta));

     var d0 = dist(this.P0.x, this.P0.y, this.P1.x, this.P1.y);

     //******* UPDATE X, Y POSITON OF DISK GIVEN DEPTH
     //this.currentDepth += .01; //UPDATE CURRENT DEPTH

     var direction = createVector(changeInDir.x - this.P0.x, changeInDir.y - this.P0.y);
     direction.normalize();

     this.deltaDelta = -.03*this.deltaDepth;
     this.deltaDepth += this.deltaDelta;
     this.currentDepth += this.deltaDepth;

     this.x = this.P0.x + (direction.x*(this.currentDepth * 100 + dTheta))%(d0*cos(tan(direction.y/direction.x)));
     this.y = this.P0.y + (direction.y*(this.currentDepth * 100 + dTheta))%(d0/direction.y);

     this.rad = dist(this.x, this.y, this.P1.x, this.P1.y);// decrease size of disk as it gets closer to the center
   }
 }

 this.sendIt = function(dir){
   this.deltaDepth += dir*.01;
 }


 this.run = function(){
  this.update();
  this.disp();
 }

}
