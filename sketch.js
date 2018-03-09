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
var attemptsLeftBoard;

/* Lake Variables */
var lakeType;                 // String
var lakeColor;                // Color
var lakeDepth;                // Double
var lakeTarget;               // Double

var message = "Please begin.";

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
/* Animation Variable on Results Page*/
var animationY;

function setup() {

  windowWidth = 1200;     //Static Window Width and Height
  windowHeight = 600;

  /* --- Compatibility Check Scene --- */
  scenes.addScene(new Scene(windowWidth, windowHeight,
    function() {
      // setup
      background(0);
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
      var bottom = height - top  / 6;

      introBoard = new TextBoard(left, top/2, right - left, bottom - top);
      introBoard.background = 60;
      introBoard.accent = 200;
      introBoard.addText("Select Your Lake Type", 240, 40, "Helvetica", BOLD);
      introBoard.addParagraph();
      introBoard.addParagraph();
      // introBoard.addParagraph();
      // introBoard.addText("Dys",
      //   "#000000", 16, "Helvetica", BOLD);
      introBoard.addParagraph();
      introBoard.addParagraph();
      introBoard.addText("                            " +
        "Bluish color, with readings above 4 meters",
        240, 18, "Helvetica", BOLD);
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
      introStartClear = new Button(200, top*1.2, 95, 40, "Clear",
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

      introStartIntmdt = new Button(200, top*1.2+60, 95, 40, "Intermediate",

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

      introStartProd = new Button(200, top*1.2+120, 95, 40, "Productive",

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

      introStartDys = new Button(200, top*1.2+180, 95, 40, "Dystrophic",

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

      introStartDysProd = new Button(200, top*1.2+240, 95, 60, "Dystrophic\nProductive",

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
      scenes.background(0);
      introBoard.draw();
      introStartClear.run();
      introStartIntmdt.run();
      introStartProd.run();
      introStartDys.run();
      introStartDysProd.run();
    })
  );
  /* --- End Intro Menu Scene --- */

  /* --- Instruction Scene --- */
  scenes.addScene(new Scene(windowWidth, windowHeight,
    function() {

      var left = windowWidth / 12;
      var right = windowWidth - left;
      var top = windowHeight / 12;
      var bottom = windowHeight - top;

      descBoard = new TextBoard(left, top, right - left, bottom - top);
      descBoard.background = 60;
      // descBoard.accent = 150;
      descBoard.addText("How to Use the Secchi Disk Simulator", 240, 40, "Helvetica", BOLD);
      descBoard.addParagraph(5);
      descBoard.addParagraph(5);
      descBoard.addParagraph(5);
      // descBoard.addTab(1);
      descBoard.addText("The up and down arrows on your keyboard control the movement of the secchi disk. \nThe up arrow moves the disk further into the water, and the down arrow retrieves it.", 240, 18, "Helvetica", BOLD);
      descUpArrow = new RoundedBox(right-(windowWidth/9), top+(windowHeight/5), 80, 60);
      descDownArrow = new RoundedBox(right-(windowWidth/9), top+1.5*(windowHeight/5), 80, 60);
      descBoard.addParagraph(5);
      descBoard.addParagraph(5);
      descBoard.addParagraph(5);
      descBoard.addParagraph(5);

      // descBoard.addTab(1);
      descBoard.addText("Holding down the arrow keys will build up momentum, if you want to move it with\nprecision, try just tapping the arrow keys.");

      descBoard.addParagraph(5);
      descBoard.addParagraph(5);
      descBoard.addParagraph(5);
      descBoard.addParagraph(5);
      // descBoard.addTab(1);
      descBoard.addText("Measure the depth at which the disk has just disappeared out of view. Click \nsubmit when you think the reading is accurate.");

      descBoard.addParagraph(5);
      descBoard.addParagraph(5);
      descBoard.addParagraph(5);
      descBoard.addParagraph(5);
      // descBoard.addTab(1);
      descBoard.addText("You will have three attempts to find where the secchi disk will disappear from sight.");

      descBoard.addParagraph(5);
      descBoard.addParagraph(5);
      descBoard.addParagraph(5);
      descBoard.addParagraph(5);
      descBoard.addParagraph(5);
      descBoard.addParagraph(5);

      descBoard.addTab(1);
      descBoard.addText("Click Okay when you are ready to start.", 240, 24, "Helvetica", BOLD);

      descBoardClear = new Button(right-110, bottom-65, 95, 50, "Okay",
        function() {
          // Button Selected
            scenes.nextScene();
            scenes.setup();
        },
        function() {}
      );
      introStartDysProd.fontSize = 14;
    },
    function() {
      scenes.background(0);
      descBoard.draw();
      descBoardClear.run();
      descUpArrow.run();
      // descDownArrow.run();
    })
  );
  /* --- End Instruction Scene --- */

  /* --- Simulator Scene --- */
  scenes.addScene(new Scene(windowWidth, windowHeight,
    function() {
      // setup()
      D0 = new disk();

      attemptsLeft = 3;

      submitButton = new Button2(width - 300, height - 340, 110, 50, "Submit",

      function() {
       // Button Selected
       // Analyze trial and give feedback
       // NOTE: THESE SELECT AND DESELECT FUNCTIONS ARE THE SAME BECAUSE IT ALTERNATES PER PRESS

      if (D0.currentDepth != -0.1) {
        measuredDepth = D0.currentDepth;
        if (analyzeTrial()) {                          // if correct move to next scene, otherwise the run section below
          scenes.nextScene();                        // will deal with the case of no chances left
          scenes.setup();
          if (lakeType == "Clear") {
            clearPass = 1;
          }
          if (lakeType == "Intermediate") {
            intermediatePass = 1;
          }
          if (lakeType == "Productive"){
            productivePass = 1;
          }
          if (lakeType == "Dystrophic") {
            dystrophicPass = 1;
          }
          if (lakeType == "Dystrophic Productive") {
            dystrophicProductivePass = 1;
          }
          } else {
            attemptsLeft--;
          }
        }
     },
     function() {
       // Button Deselected
       // if (D0.currentDepth != 0) {
        // measuredDepth = D0.currentDepth;
        // if (analyzeTrial()) {
        // 	scenes.nextScene();
        // 	scenes.setup();
        // 	} else {
        // 		// console.log(attemptsLeft);
       //      message = "You're too far off. Try again!";
        // 		attemptsLeft--;
        // 	}
       //  }
     }
  );

      goBack = new Button(width - 170, height - 340, 110, 50, "Switch Types",
        function() {
          scenes.setScene(2);
          scenes.setup();
        }
      );

    },
    function() {                                    // THIS IS WHERE THE STUFF FOR THE SIM IS DRAWN
      scenes.background(0);

      fill(lakeColor);
        strokeWeight(5);
        ellipse(width/3+30,height/2,width*.55, height*1.1);

        strokeWeight(0);
        fill("black");
        rect(width - width/4.5, 0, width, height);

        D0.maxDepth = ceil(lakeDepth)-1;
        D0.run();
        // Make the button do
        submitButton.run();
        goBack.run();


      measureDepth = new TextBox(width - 300, height - 170, 240, 50, "Current Depth: \n" + floor(D0.currentDepth*100)/100 + " Meters", function(){});
      measureDepth.run();
      visualAttempts = new TextBox(width - 300, height - 110, 240, 50, "Attempts Left: " + attemptsLeft, function(){});
      visualAttempts.run();

      messageDisplay = new TextBox(width - 300, height - 280, 240, 100, message, function(){});
      messageDisplay.run();

      simBackground = new TextBoxBackground(width - 300, height - 540, 240, 190, "", function(){});
      simBackground.run();

      simInfo = new TextBox(width - 290, height - 530, 220, 170, "Secchi Disk Simulator", function(){});
      simInfo.run();


      // If chances are zero, move to results
      if(attemptsLeft == 0)
      {
      	scenes.nextScene();
        scenes.setup();
      }

      strokeWeight(0);
      dropShadow(0, 0, 0, 0);

      push();
      fill(0, 0, 0, 0);
      strokeWeight(50);
      stroke(50);
      rect(0, 0, 1200, 600);
      pop();
    })
  );
  /* --- End Simulator Scene --- */

  /* --- Reading Results Scene --- */
  scenes.addScene(new Scene(windowWidth, windowHeight,
    function() {
      // setup()

      resultsBoard =  new TextBoard(windowWidth/12, windowHeight/12, windowWidth*9/12 - windowWidth/12, windowHeight*5/6);

      resultsBoard.background = 0;
      resultsBoard.accent = 50;
      resultsBoard.addParagraph(5);
      resultsBoard.addParagraph(5);
      resultsBoard.addTab(1);
      resultsBoard.addTab(1);
      resultsBoard.addTab(1);
      resultsBoard.addText("Reading Results", 200, 30, "Helvetica", BOLD);
      resultsBoard.addParagraph(5);
      resultsBoard.addParagraph(5);
      resultsBoard.addParagraph(5);
      resultsBoard.addParagraph(5);
      resultsBoard.addTab(1);
      resultsBoard.addTab(1);
     // resultsBoard.addText("Lake Type", 0, 16, "Helvetica", BOLD);
     //("Words", color, size, font, type);
      resultsBoard.addText("Lake Type", 200, 20, "Helvetica", BOLD);
      resultsBoard.addTab(1);
      resultsBoard.addTab(1);
      resultsBoard.addText(lakeType, 255, 20, "Helvetica", BOLD);
      resultsBoard.addParagraph(3);
      resultsBoard.addParagraph(3);
      resultsBoard.addTab(1);
      resultsBoard.addTab(1);
      resultsBoard.addText("Target Depth", 200, 20, "Helvetica", BOLD);
      resultsBoard.addTab(1);
      resultsBoard.addTab(1);
      resultsBoard.addText(lakeTarget.toFixed(2) + " meters", 255, 20, "Helvetica", BOLD);
      resultsBoard.addParagraph(3);
      resultsBoard.addParagraph(3);
      resultsBoard.addTab(1);
      resultsBoard.addTab(1);
      resultsBoard.addText("Measured Depth", 200, 20, "Helvetica", BOLD);
      resultsBoard.addTab(1);
      resultsBoard.addTab(1);
      resultsBoard.addText(measuredDepth.toFixed(2) + " meters", 255, 20, "Helvetica", BOLD);
      resultsBoard.addParagraph(3);
      resultsBoard.addParagraph(3);
      resultsBoard.addTab(1);
      resultsBoard.addTab(1);
      resultsBoard.addText("Error (absolute)", 200, 20, "Helvetica", BOLD);
      resultsBoard.addTab(1);
      resultsBoard.addTab(1);
      resultsBoard.addText(measuredError.toFixed(2) + " meters", 255, 20, "Helvetica", BOLD);
      resultsBoard.addParagraph(3);
      resultsBoard.addParagraph(3);
      resultsBoard.addTab(1);
      resultsBoard.addTab(1);
      resultsBoard.addText("Error (relative)", 200, 20, "Helvetica", BOLD);
      resultsBoard.addTab(1);
      resultsBoard.addTab(1);
      resultsBoard.addText(measuredErrorRel.toFixed(2) + "%", 255, 20, "Helvetica", BOLD);
      resultsBoard.addParagraph(3);
      resultsBoard.addParagraph(3);
      resultsBoard.addTab(1);
      resultsBoard.addTab(1);
      resultsBoard.addText("Within Tolerance?", 200, 20, "Helvetica", BOLD);
      resultsBoard.addTab(1);
      resultsBoard.addText(measuredTolerance, 255, 20, "Helvetica", BOLD);

      resultsRestart = new Button(windowWidth/2, windowHeight/2 - 50, 95, 50, "Test Again",

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
      var animationWindowX = windowWidth*9/12;
      var animationWindowY = windowHeight/12;
      var animationWindowW = windowWidth/6;
      var animationWindowH = windowHeight*5/6;
      var diskH = animationWindowH/50;
      var diskW = animationWindowW*2/3;
      var tapeW = animationWindowW/25;

      var animationY = animationWindowY;

      var percentMeasuredY = measuredDepth/lakeDepth;
      var measuredY = animationWindowY + (animationWindowH * percentMeasuredY);

      var percentY = lakeTarget/lakeDepth;
      var targetY = animationWindowY + (animationWindowH * percentY);

      //tolerance is +-0.10 meters
      var percentToleranceY = .1/lakeDepth;
      var upperToleranceY = targetY - (animationWindowH * percentToleranceY);
      var lowerToleranceY = targetY + (animationWindowH * percentToleranceY);

      var greenZone = lowerToleranceY - upperToleranceY;

      scenes.background(60);
      resultsBoard.draw();
      push();

      //lake background
      fill(lakeColor);
      strokeWeight(2);
      stroke(255);
      rect(animationWindowX, animationWindowY, animationWindowW, animationWindowH);
      fill(255,255,255,100);
      noStroke();
      rect(animationWindowX, animationWindowY, animationWindowW, animationWindowH/12);

      //green area
      fill(100,255,100,200);
      rect(animationWindowX, upperToleranceY, animationWindowW, greenZone);
      if (greenZone > animationWindowX + animationWindowH) {
        greenZone = animationWindowX + animationWindowH;
      }

      //lines
      strokeWeight(2);
      stroke(255, 0, 0, 255);
      //target line
      line(animationWindowX, targetY, animationWindowX + animationWindowW, targetY);
      //upper bound
      line(animationWindowX, upperToleranceY, animationWindowX + (animationWindowW*1/29), upperToleranceY);
      line(animationWindowX + (animationWindowW*2/29), upperToleranceY, animationWindowX + (animationWindowW*3/29), upperToleranceY);
      line(animationWindowX + (animationWindowW*4/29), upperToleranceY, animationWindowX + (animationWindowW*5/29), upperToleranceY);
      line(animationWindowX + (animationWindowW*6/29), upperToleranceY, animationWindowX + (animationWindowW*7/29), upperToleranceY);
      line(animationWindowX + (animationWindowW*8/29), upperToleranceY, animationWindowX + (animationWindowW*9/29), upperToleranceY);
      line(animationWindowX + (animationWindowW*10/29), upperToleranceY, animationWindowX + (animationWindowW*11/29), upperToleranceY);
      line(animationWindowX + (animationWindowW*12/29), upperToleranceY, animationWindowX + (animationWindowW*13/29), upperToleranceY);
      line(animationWindowX + (animationWindowW*14/29), upperToleranceY, animationWindowX + (animationWindowW*15/29), upperToleranceY);
      line(animationWindowX + (animationWindowW*16/29), upperToleranceY, animationWindowX + (animationWindowW*17/29), upperToleranceY);
      line(animationWindowX + (animationWindowW*18/29), upperToleranceY, animationWindowX + (animationWindowW*19/29), upperToleranceY);
      line(animationWindowX + (animationWindowW*20/29), upperToleranceY, animationWindowX + (animationWindowW*21/29), upperToleranceY);
      line(animationWindowX + (animationWindowW*22/29), upperToleranceY, animationWindowX + (animationWindowW*23/29), upperToleranceY);
      line(animationWindowX + (animationWindowW*24/29), upperToleranceY, animationWindowX + (animationWindowW*25/29), upperToleranceY);
      line(animationWindowX + (animationWindowW*26/29), upperToleranceY, animationWindowX + (animationWindowW*27/29), upperToleranceY);
      line(animationWindowX + (animationWindowW*28/29), upperToleranceY, animationWindowX + animationWindowW, upperToleranceY);
      //lower bound
      line(animationWindowX, lowerToleranceY, animationWindowX + (animationWindowW*1/29), lowerToleranceY);
      line(animationWindowX + (animationWindowW*2/29), lowerToleranceY, animationWindowX + (animationWindowW*3/29), lowerToleranceY);
      line(animationWindowX + (animationWindowW*4/29), lowerToleranceY, animationWindowX + (animationWindowW*5/29), lowerToleranceY);
      line(animationWindowX + (animationWindowW*6/29), lowerToleranceY, animationWindowX + (animationWindowW*7/29), lowerToleranceY);
      line(animationWindowX + (animationWindowW*8/29), lowerToleranceY, animationWindowX + (animationWindowW*9/29), lowerToleranceY);
      line(animationWindowX + (animationWindowW*10/29), lowerToleranceY, animationWindowX + (animationWindowW*11/29), lowerToleranceY);
      line(animationWindowX + (animationWindowW*12/29), lowerToleranceY, animationWindowX + (animationWindowW*13/29), lowerToleranceY);
      line(animationWindowX + (animationWindowW*14/29), lowerToleranceY, animationWindowX + (animationWindowW*15/29), lowerToleranceY);
      line(animationWindowX + (animationWindowW*16/29), lowerToleranceY, animationWindowX + (animationWindowW*17/29), lowerToleranceY);
      line(animationWindowX + (animationWindowW*18/29), lowerToleranceY, animationWindowX + (animationWindowW*19/29), lowerToleranceY);
      line(animationWindowX + (animationWindowW*20/29), lowerToleranceY, animationWindowX + (animationWindowW*21/29), lowerToleranceY);
      line(animationWindowX + (animationWindowW*22/29), lowerToleranceY, animationWindowX + (animationWindowW*23/29), lowerToleranceY);
      line(animationWindowX + (animationWindowW*24/29), lowerToleranceY, animationWindowX + (animationWindowW*25/29), lowerToleranceY);
      line(animationWindowX + (animationWindowW*26/29), lowerToleranceY, animationWindowX + (animationWindowW*27/29), lowerToleranceY);
      line(animationWindowX + (animationWindowW*28/29), lowerToleranceY, animationWindowX + animationWindowW, lowerToleranceY);

      //tape
      fill(255,255,255,150);
      noStroke();
      rect((animationWindowX + (animationWindowW/2)) - (tapeW/2), animationWindowY, tapeW, measuredY - animationWindowY);
      //disk
      fill(255,255,255,255);
      rect((animationWindowX   + (animationWindowW/2)) - (diskW/2), measuredY - (diskH/2), diskW, diskH);

      stroke(0);
      strokeWeight(2);
      textSize(14);
      text("TOLERANCE RANGE", animationWindowX + (animationWindowW*1/29), upperToleranceY - 8, BOLD);
      textSize(25);
      strokeWeight(3);
      text("TARGET DEPTH", ((animationWindowX + (animationWindowW)/2)) - 105, targetY + 11, BOLD);


      pop();

      resultsRestart.run();

    })
  );
  /* --- End Reading Results Scene --- */

  //Test Pre-Screen
scenes.addScene(new Scene(windowWidth, windowHeight,
  function() {

    var left = windowWidth / 12;
    var right = windowWidth - left;
    var top = windowHeight / 12;
    var bottom = windowHeight - top;

    preTestBoard = new TextBoard(left, top, right - left, bottom - top);
    preTestBoard.background = 60;
    // descBoard.accent = 150;
    preTestBoard.addText("Secchi Disk Test",240, 20, "Helvetica", BOLD);
    preTestBoard.addParagraph(3);
    preTestBoard.addTab(1);
    preTestBoard.addText("\nYou will now begin a multiple choice test.\n\n Please press 'Okay' to begin.");


    testStart = new Button(right-110, bottom-65, 95, 50, "Okay",
      function() {
        // Button Selected
          scenes.setScene(7);
          scenes.setup();
      },
      function() {}
    );
    introStartDysProd.fontSize = 14;
  },
  function() {
    scenes.background(0);
    preTestBoard.draw();
    testStart.run();
  })
);

//Question Screens
scenes.addScene(new Scene(windowWidth, windowHeight,
function() {

var left = windowWidth / 6;
var right = windowWidth - left;
var top = windowHeight / 6;
var bottom = windowHeight - top;

testBoard = new TextBoard(left, top, right - left/2, bottom - top);
testBoard.background = 60;
// descBoard.accent = 150;
testBoard.addText("Secchi Disk Test",240, 40, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addText("\nQuestion 1: Why is a Secchi disk a good tool to measure water quality?\n", 240, 24, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n A.")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n B.")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n C.")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n D.")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);

finishButton = new Button(right-20, bottom-65, 95, 50, "Next",
  function() {
    // Button Selected
      scenes.setScene(8);
      scenes.setup();
  },
  function() {}
);
introStartDysProd.fontSize = 14;
},
function() {
scenes.background(0);
testBoard.draw();
finishButton.run();
})
);

scenes.addScene(new Scene(windowWidth, windowHeight,
  function() {

    var left = windowWidth / 6;
    var right = windowWidth - left;
    var top = windowHeight / 6;
    var bottom = windowHeight - top;

    testBoard = new TextBoard(left, top, right - left/2, bottom - top);
    testBoard.background = 60;
    // descBoard.accent = 150;
    testBoard.addText("Secchi Disk Test",240, 40, "Helvetica", BOLD);
    testBoard.addParagraph(1);
    testBoard.addText("\nQuestion 2: Why is a Secchi disk a good tool to measure water quality?\n", 240, 24, "Helvetica", BOLD);
    testBoard.addParagraph(1);
    testBoard.addParagraph(1);
    testBoard.addParagraph(1);
    testBoard.addParagraph(1);
    testBoard.addParagraph(1);
    testBoard.addText("\n A.")
    testBoard.addParagraph(1);
    testBoard.addParagraph(1);
    testBoard.addParagraph(1);
    testBoard.addText("\n B.")
    testBoard.addParagraph(1);
    testBoard.addParagraph(1);
    testBoard.addParagraph(1);
    testBoard.addText("\n C.")
    testBoard.addParagraph(1);
    testBoard.addParagraph(1);
    testBoard.addParagraph(1);
    testBoard.addText("\n D.")
    testBoard.addParagraph(1);
    testBoard.addParagraph(1);
    testBoard.addParagraph(1);

    finishButton = new Button(right-20, bottom-65, 95, 50, "Next",
      function() {
        // Button Selected
          scenes.nextScene();
          scenes.setup();
      },
      function() {}
    );
    introStartDysProd.fontSize = 14;
  },
  function() {
    scenes.background(0);
    testBoard.draw();
    finishButton.run();
  })

);

//Test Finished Screen

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
        lakeDepth = 7;
        lakeTarget = random(4, 7);
        break;
      case 2:
        lakeType = "Intermediate";
        lakeColor = "#bfc18b";
        lakeDepth = 7;
        lakeTarget = random(3.5, 7);
        break;
      case 3:
        lakeType = "Productive";
        lakeColor = "#6b8474";
        lakeDepth = 5;
        lakeTarget = random(2, 3);
        break;
      case 4:
        lakeType = "Dystrophic";
        lakeColor = "#82753a";
        lakeDepth = 5;
        lakeTarget = random(2, 3);
        break;
      case 5:
        lakeType = "Dystrophic Productive";
        lakeColor = "#8a9663";
        lakeDepth = 5;
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
   if (measuredDepth < lakeTarget) {
     message = "You're too shallow! Try again.";
   }
   if (measuredDepth > lakeTarget) {
     message = "You're too deep! Try again.";
   }

   if (measuredErrorRel < 2.0) {
     measuredTolerance = "Yes";
     return true;
   } else {
     measuredTolerance = "No";
     return false;
   }
 }

function disk(){ //THE BIG DISK CLASS
 this.P0 = createVector(width/6, height/6); // BEGIN POINT    // changed from height/8
 this.P1 = createVector(width/2, height/2); // END POINT

 this.maxDepth = lakeDepth; //10; // MAXIMUM DEPTH OF DISK

 this.currentDepth = 0; //CURRENT DEPTH OF DISK
 this.deltaDepth = 0;
 this.deltaDelta = 0;

 this.x = 0; //  X POSITON OF THE DISK, INITIALIZE TO BEGIN POINT
 this.y = 0 //  Y POSITON OF THE DISK, INITIALIZE TO BEGIN POINT

 this.rad = 0;  // RADIUS OF DISK
 this.hide = false; // DO YA WANNA SEE IT OR NA?

 this.dx = 0; // USE THIS FOR PERLIN NOISE
 this.detheta = 0; // VARIATION OF POSITION

 this.meterPos = createVector(width - width/3-10, height/10); // POSITION OF THE DEPTH METER

this.disp = function(){
  if(this.hide == false){ // WE DON'T WANT TO DRAW THE DISK IF IT'S HIDDEN
     //******* DRAW THE DISK
    push();

    var t = this.currentDepth; // a temp value for the lerp below                            // I think this is where the disk opacity is defined; old text below \/

    //New Opacity
    var alpha = ((1 - map(t,0,lakeTarget,0,1)) * 255 + map(t,0,lakeTarget,0,1) * 0);
    var delta = ((1 - map(t,0,lakeTarget,0,1)) * 255 + map(t,0,lakeTarget,0,1) * 0);

    stroke(255, alpha);
    noFill();
    ellipse(this.x, this.y, this.rad + delta, this.rad + delta);

    noStroke();
    fill(0, alpha);
    arc(this.x, this.y, this.rad, this.rad, 0, PI/2);
    arc(this.x, this.y, this.rad, this.rad, PI, 3*PI/2);

    fill(255, alpha);
    arc(this.x, this.y, this.rad, this.rad, PI/2, PI);
    arc(this.x, this.y, this.rad, this.rad, 3*PI/2, 0);



    pop();

    push();
    //Put a measuring tape on the secchi disk

    function gradientRect(x1, y1, x2, y2, x3, y3, x4, y4, color1, color2)
    {
      var xO = (x1+x2)/2;
      var xT = (x4+x3)/2;
      var yO = (y1+y2)/2;
      var yT = (y4+y3)/2;

      var grad = this.drawingContext.createLinearGradient(xO, yO, xT, yT);
      grad.addColorStop(0, color1);
      grad.addColorStop(1, color2);

      this.drawingContext.fillStyle = grad;

      beginShape();
        vertex(x1, y1);
        vertex(x2, y2);
        vertex(x3, y3);
        vertex(x4, y4);
      endShape();
    }


    gradientRect(-25, -25, -25, 75, this.x, this.y+this.rad/40, this.x, this.y-this.rad/40, color(255, 255, 255, 255), color(255, 255, 255, alpha));




    //For the depth of the lake, put a major tick mark on the measuring tape
    for(i = 1; i <= lakeDepth; i++)
    {
      //Finds where on the x and y axis to put the lines
      tickTimeX = map(i, 0, this.currentDepth+1, this.x, -25);
      tickTimeYBottom = map(i, 0, this.currentDepth+1, this.y + this.rad/40, 75);
      tickTimeYTop = map(i, 0, this.currentDepth+1, this.y - this.rad/40, -25);

      stroke("black");
      strokeWeight(3);
      line(tickTimeX, tickTimeYBottom, tickTimeX, tickTimeYTop);
      strokeWeight(1);
      //The 1 comes in weird, this is how I "fixed" it
      if(this.currentDepth > .68)
      {
        textSize(50*i/this.currentDepth);
        fill("black");
        text(i, tickTimeX, tickTimeYBottom);
      }
      else
      {
        textSize(50*i/this.currentDepth);
        fill("black");
        text(i, tickTimeX, tickTimeYTop);
      }
    }

    noFill();
    stroke(0);
    strokeWeight(174);
    ellipse(width/3+30,height/2,width*.7, height*1.4);
    strokeWeight(0);
    noStroke();

    noFill();
    stroke(40, 40, 40);
    strokeWeight(10);
    ellipse(width/3+30,height/2,width*.55, height*1.1);
    strokeWeight(0);
    noStroke();


    pop();

    push();
    //This draws the depth meter rectangle based on variables defined above
    strokeWeight(0);
    fill("white");
    dropShadow(4, 4, 4, "rgba(0, 0, 0, 0.2)");
    rect(this.meterPos.x, this.meterPos.y, 100, height/1.25);
    dropShadow(0, 0, 0, 0);
    fill(0, 0, 0, 0);
    strokeWeight(3);
    var depthMeter = rect(this.meterPos.x, this.meterPos.y, 100, height/1.25);
    //This adds the major tick marks to the depth meter
    for(var i = 0; i < lakeDepth; i++)
    {
      //Map values from 0 to the depth of lake to beginning of box to end of box in y direction and add tick marks along evenly
      var theta = map(i, 0, lakeDepth, this.meterPos.y, this.meterPos.y+height/1.25);
      strokeWeight(3);
      line(this.meterPos.x, theta, this.meterPos.x+25, theta);
      if(i > 0)
      {
        textSize(32);
        fill("black");
        text(i, this.meterPos.x+50, theta+12);
      }
      //This adds the smaller tick marks in between the other larger ones created right before this
      for(var j = 0; j < 10; j++)
      {
        var iota = map(j, 0, 10, this.meterPos.y, this.meterPos.y+height/1.25)/lakeDepth;
        strokeWeight(1);
        line(this.meterPos.x, theta+iota-2, this.meterPos.x+15, theta+iota-2);
      }
    }

    //This bit draws the moving triangle along the side of the rectangle, t is the current depth of the secchi disk
    fill("red");
    var tip = map(t, 0, lakeDepth, this.meterPos.y, this.meterPos.y+height/1.25);
    var depthTriangle = triangle(this.meterPos.x-15, tip+8, this.meterPos.x+15, tip, this.meterPos.x-15, tip-8);

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

     if(keyIsDown(UP_ARROW) && this.currentDepth < lakeDepth)            // gets input only if the disk is under the limit
     	this.deltaDepth += .0005
     if(keyIsDown(DOWN_ARROW) && this.currentDepth >= 0)                // get input only when the disk is at or above 0
     	this.deltaDepth += -.0005
     if((this.currentDepth >= lakeDepth && this.deltaDepth > 0)|| (this.currentDepth < 0 && this.deltaDepth < 0))     // if the disk is at the limit and is still going up, or below zero and going down, stop it
     	this.deltaDepth = 0;
     this.deltaDepth += this.deltaDelta;
     this.currentDepth += this.deltaDepth;

     this.x = this.P0.x + (direction.x*(this.currentDepth * 60 + dTheta))%(d0*cos(atan(direction.y/direction.x)));
     this.y = this.P0.y + (direction.y*(this.currentDepth * 60 + dTheta))%(d0/direction.y);

     this.rad = dist(this.x, this.y, this.P1.x-10, this.P1.y-10);// decrease size of disk as it gets closer to the center
   }
 }

 this.sendIt = function(dir){
   // this.deltaDepth += dir*.005;        // Input is now handled in the update method
 }


 this.run = function(){
  this.update();
  this.disp();
 }

}

function RoundedBox(cornerX, cornerY, width, height)
{
  this.position = createVector(cornerX, cornerY);
  this.width = width;
  this.height = height;

  this.run = function()
  {
      fill(200, 200, 200);
      strokeWeight(5);
      stroke(100, 100, 100);

      rect(cornerX, cornerY, width, height, 5);
      rect(cornerX, cornerY+height+20, width, height, 5);

      fill("red");
      strokeWeight(0);

      triangle(cornerX+(width/4), cornerY+height+20+(height/3), cornerX+(width/2), cornerY+height+20+(3*(height/4)), cornerX+(3*(width/4)), cornerY+height+20+(height/3));
      triangle(cornerX+(width/4), cornerY+(2*(height/3)), cornerX+(width/2), cornerY+(height/4), cornerX+(3*(width/4)), cornerY+(2*(height/3)));
      fill("white");
  }
}
