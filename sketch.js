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
var clearPass = 0;
var intermediatePass = 0;
var productivePass = 0;
var dystrophicPass = 0;
var dystrophicProductivePass = 0;

/* Lake Variables */
var lakeType;                 // String
var lakeColor;                // Color
var lakeDepth;                // Double
var lakeTarget;               // Double

var message = "Please begin.";

/*Test Questions * If you want to change the test questions or answers, do it here!
          Change the Key to whatever letter the correct answer is. */

var Question1 = "Why is a Secchi disk a good tool to measure water quality?";
  var Question1A = "It is not a tool to measure water quality";
  var Question1B = "It is quick, simple, and accurate";
  var Question1C = "It is difficult to measure algae";
  var Question1D = "None of the above";
    var Question1Key = "B";
var Question2 = "Is anchoring the boat required when taking a Secchi disk reading?";
  var Question2A = "No, the boat does not need to be anchored";
  var Question2B = "Yes, so the Secchi tape measure hangs straight down";
  var Question2C = "Yes, so you do not disturb the other boaters";
  var Question2D = "None of the above";
    var Question2Key = "B";
var Question3 = "What can influence Secchi disk readings?";
  var Question3A = "Amount of sediment suspended in the water";
  var Question3B = "Clay particles";
  var Question3C = "Amount of algae in the water";
  var Question3D = "All of the above";
  var Question3E = "None of the above";
    var Question3Key = "D";
var Question4 = "Why is a water view scope used?";
  var Question4A = "It makes objects appear closer than they really are";
  var Question4B = "It helps to focus attention on Secchi disk";
  var Question4C = "It reduces the effects of glare and wave disturbance";
  var Question4D = "None of the above";
    var Question4Key = "C";
var Question5 = "During which months of the year should Secchi readings be taken?";
  var Question5A = "April and October";
  var Question5B = "May through September";
  var Question5C = "___";
  var Question5D = "___";
    var Question5Key = "B";
var Question6 = "Ideally how often should Secchi readings be taken?";
  var Question6A = "Every day";
  var Question6B = "Once a week";
  var Question6C = "At two-week intervals";
  var Question6D = "Once a month";
    var Question6Key = "C";
var Question7 = "From which side of the boat should the readings be taken?";
  var Question7A = "Bow";
  var Question7B = "Stern";
  var Question7C = "Port";
  var Question7D = "Starboard";
  var Question7E = "Sunny";
  var Question7F = "Shady";
    var Question7Key = "F";
var Question8 = "What are the preferred hours to take a Secchi reading?";
  var Question8A = "6:00 a.m. to 12:00 p.m.";
  var Question8B = "9:00 a.m. to 3:00 p.m.";
  var Question8C = "12:00 p.m. to 6:00 p.m.";
  var Question8D = "3:00 p.m. to 9:00 p.m.";
    var Question8Key = "B";
var Question9 = "What can monitors do to increase reliability of Secchi data?";
  var Question9A = "Nothing can improve reliability of data";
  var Question9B = "Take readings at about the same time of day and weather conditions";
  var Question9C = "Take readings at different times of day each time";
  var Question9D = "None of the above";
    var Question9Key = "B";
var Question10 = "How should you record military time?";
  var Question10A = "It is not necessary to record in military time";
  var Question10B = "From 1 p.m. until midnight, add 12 hours to the time";
  var Question10C = "___";
  var Question10D = "___";
    var Question10Key = "B";
var Question11 = "When recording the wind speed, you should";
  var Question11A = "Write down the range of wind speed, as listed on the form. Ex: 0-7, 8-11, 12-16, 17-24, 25-35";
  var Question11B = "Write down a discrete number within a range of wind speed. Ex: 04";
  var Question11C = "It is not necessary to record the wind speed.";
  var Question11D = "___";
    var Question11Key = "B";
var Question12 = "How do you take a Secchi disk transparency reading?";
  var Question12A = "Take the reading when disk goes out of sight, while being lowered through the water column";
  var Question12B = "Take reading by lowering the disk until even the glow of the disk goes out of sight, \n then raising it until you see it, lowering it again to disappearance \n and raising it again until it reappears so that you are confident that \n you've honed in on the depth of the disappearance";
  var Question12C = "Take a reading just before disk goes out of sight, when lowering it";
  var Question12D = "___";
    var Question12Key = "A";
var Question13 = "Eyesight influences the results of Secchi transparency readings. \n Which of the following helps one obtain consistent readings?";
  var Question13A = "Compare readings taken with and without glasses,  \n then consistently take readings with whichever method allows you to obtian the deepest readings.";
  var Question13B = "Allow enough time for photogray glasses to lighten before taking the reading.";
  var Question13C = "Allow enough time for eyes to adjust to the ow light levels encountered \n when peering through the scope before obtaining readings.";
  var Question13D = "All of the above";
  var Question13E = "Never";
    var Question13Key = "D";
var Question14 = "When you take more than one Secchi reading, what is the best way to record it?";
  var Question14A = "Individual readings";
  var Question14B = "Average of readings";
  var Question14C = "___";
  var Question14D = "___";
    var Question14Key = "A";
var Question15 = "When more than one certified lake monitor is taking Secchi readings on the same lake, \n it is important to periodically compare the results of the individuals through side-by-side readings. \n While it is not necessary to do this every time a reading is taken, it should be done at least:";
  var Question15A = "At least once a year";
  var Question15B = "Every time they monitor together";
  var Question15C = "Only when a lake is blooming";
  var Question15D = "Never - Readings are confidential";
    var Question15Key = "A";
var Question16 = "When recording your required Quality Assurance duplicate readings, \n how should those readings be numbered on the form?";
  var Question16A = "By monitor, for each reading taken all season. Ex: John Smith, 1, 2, 3, 4, 5, 6, etc.";
  var Question16B = "By monitor, per boat trip. Ex: John Smith, 1, 2 and Jane Smith, 1, 2";
  var Question16C = "Per boat trip, counting all monitors. Ex: Jane Smith, 1, 2, and John Smith 3, 4";
  var Question16D = "___";
    var Question16Key = "B";
var Question17 = "In order for your Secchi readings to meet Quality Assurance standards, \n an essential part of certifying your data, you must periodically take and record a duplicate reading. \n How often should this be done?";
  var Question17A = "Never";
  var Question17B = "Every reading";
  var Question17C = "Once every ten readings, or at least once per season";
  var Question17D = "Only when the wind is gusty";
    var Question17Key = "C";
var Question18 = "When mailing in your completed Secchi or DO data form to the VLMP, do you send:";
  var Question18A = "Just the white page";
  var Question18B = "Just the yellow page";
  var Question18C = "Both yellow & white pages";
  var Question18D = "___";
    var Question18Key = "A";
var Question19 = "How often do you need to get re-certified (in person/on the water) to take Secchi readings?";
  var Question19A = "Never, re-certification is not necessary";
  var Question19B = "Every summer";
  var Question19C = "Every 5 years";
  var Question19D = "Every 6 years, if I test online annually using the Secchi Simulator; every 3 years if I do not use the Simulator";
    var Question19Key = "D";
var Question20 = "Whom do you call if you have questions about the Program, equiptment, or forms?";
  var Question20A = "Ghost Busters";
  var Question20B = "Inland Fisheries and Wildlife";
  var Question20C = "Volunteer Lake Monitoring Program";
  var Question20D = "Department of Environmental Protection";
    var Question20Key = "C";
var Question21 = "Has your eyesight changed over the last year? Have you had corrective eye surgery? \n If yes, when?";
  var Question21A = "___";
  var Question21B = "___";
  var Question21C = "___";
  var Question21D = "___";
    var Question21Key = "_";

    var TestFinished = "No";

//Test User Answers
var Question1Answer = "Select an Answer.";
var Question2Answer = "Select an Answer.";
var Question3Answer = "Select an Answer.";
var Question4Answer = "Select an Answer.";
var Question5Answer = "Select an Answer.";
var Question6Answer = "Select an Answer.";
var Question7Answer = "Select an Answer.";
var Question8Answer = "Select an Answer.";
var Question9Answer = "Select an Answer.";
var Question10Answer = "Select an Answer.";
var Question11Answer = "Select an Answer.";
var Question12Answer = "Select an Answer.";
var Question13Answer = "Select an Answer.";
var Question14Answer = "Select an Answer.";
var Question15Answer = "Select an Answer.";
var Question16Answer = "Select an Answer.";
var Question17Answer = "Select an Answer.";
var Question18Answer = "Select an Answer.";
var Question19Answer = "Select an Answer.";
var Question20Answer = "Select an Answer.";
var Question21Answer = "Type an Answer.";

//For keeping track of attempts for each lake
var clearLakeAttempts;
var intermediateLakeAttempts;
var productiveLakeAttempts;
var dystrophicLakeAttempts;
var dystrophicProductiveLakeAttempts;

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


var clearLake;
var intermediateLake;            //For using images in the future
var productiveLake;
var dystrophicLake;
var dystrophicProductiveLake;
var tapepic1;
var tapepic2;
var secchiReadings;
var secchiSkyPerson;
var secchiSolo;
var secchiDisk;
var manWithSecchi;

function preload() {          //Where we load all our images
    clearLake = loadImage('libraries/clearLake.jpg');
    intermediateLake = loadImage('libraries/intermediateLake.jpg');
    productiveLake = loadImage('libraries/productiveLake.jpg');
    dystrophicLake = loadImage('libraries/dystrophicLake.jpg');
    dystrophicProductiveLake = loadImage('libraries/dystrophicProductiveLake.jpg');
    tapepic1 = loadImage('libraries/tapepic1.jpg');
    tapepic2 = loadImage('libraries/tapepic2.jpg');
    secchiReadings = loadImage('libraries/SecchiReadings12.jpg');
    secchiSkyPerson = loadImage('libraries/Secchi-Sky-Person.jpg');
    secchiSolo = loadImage('libraries/Secchi-Solo-1.jpg');
    secchiDisk = loadImage('libraries/SecchiDisk.jpg');
    manWithSecchi = loadImage('libraries/manwithsecchi.jpg');
}

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

      scenes.background(0);
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
    // introBoard.addTab();
    introBoard.addText("Select Your Lake Type", 240, 30, "Helvetica", BOLD);
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
      if (clearPass == 1) {
        introBoard.addText("     Complete");
      }
    introBoard.addParagraph();
    introBoard.addParagraph();
    introBoard.addParagraph();
    introBoard.addParagraph();
    // introBoard.addText("Intermediate", "#000000", 16, "Helvetica", BOLD);
    // introBoard.addParagraph();
    introBoard.addText("                            " +
      "Blue or green-brown, with readings of 4 to 7 meters");
      if (intermediatePass == 1) {
        introBoard.addText("     Complete");
      }
      introBoard.addParagraph();
      introBoard.addParagraph();
      introBoard.addParagraph();
      introBoard.addParagraph();
      introBoard.addText("                            " +
        "Green Background, high algae, readings less than 3 meters");
      if (productivePass == 1) {
        introBoard.addText("     Complete");
      }
      introBoard.addParagraph();
      introBoard.addParagraph();
      introBoard.addParagraph();
      introBoard.addParagraph();
      introBoard.addText("                            " +
        "Distinct tea or rootbeer color, readings less than 3 meters");
      if (dystrophicPass == 1) {
        introBoard.addText("     Complete");
      }
      if (TestFinished == "Yes") {
        introBoard.addText("                    Complete");
      }
      introBoard.addParagraph();
      introBoard.addParagraph();
      introBoard.addParagraph();
      introBoard.addParagraph();
      introBoard.addText("                            " +
        "Green-brown and murky, readings less than 3 meters");
        if (dystrophicProductivePass == 1) {
          introBoard.addText("     Complete");
        }
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

    // introStartDys.color = [245, 245, 245];
    introStartDys.fontSize = 14;

    introStartTestTaker = new Button(900, top*1.2+240, 95, 60, "Take Test",
      function() {
        // Button Selected --> To be changed to a test
          scenes.setScene(6);
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
    introStartTestTaker.run();
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
          attemptsLeft--;
          if (lakeType == "Clear") {
            clearPass = 1;
            var depthClear = createDiv().hide().id("depthValuesClear").value(
              [
                1,
                floor(D0.currentDepth*100)/100,
                floor(lakeTarget*100)/100,
                3-attemptsLeft
              ]
              );
          }
          if (lakeType == "Intermediate") {
            intermediatePass = 1;
            var depthIntermediate = createDiv().hide().id("depthValuesIntermediate").value(
              [
                2,
                floor(D0.currentDepth*100)/100,
                floor(lakeTarget*100)/100,
                3-attemptsLeft
              ]
              );
          }
          if (lakeType == "Productive"){
            productivePass = 1;
            var depthProductive = createDiv().hide().id("depthValuesProductive").value(
              [
                3,
                floor(D0.currentDepth*100)/100,
                floor(lakeTarget*100)/100,
                3-attemptsLeft
              ]
              );
          }
          if (lakeType == "Dystrophic") {
            dystrophicPass = 1;
            var depthDystrophic = createDiv().hide().id("depthValuesDystrophic").value(
              [
                4,
                floor(D0.currentDepth*100)/100,
                floor(lakeTarget*100)/100,
                3-attemptsLeft
              ]
              );
          }
          if (lakeType == "Dystrophic Productive") {
            dystrophicProductivePass = 1;
            var depthDystrophicProductive = createDiv().hide().id("depthValuesDProductive").value(
              [
                5,
                floor(D0.currentDepth*100)/100,
                floor(lakeTarget*100)/100,
                3-attemptsLeft
              ]
              );
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

        // fill(lakeColor);
        strokeWeight(5);
        ellipse(width/3+30,height/2,width*.55, height*1.1);
        if (lakeType == "Clear") {
          image(clearLake, 0, 0);
        }
        if (lakeType == "Intermediate") {
          image(intermediateLake, 0, 0);
        }
        if (lakeType == "Productive"){
          image(productiveLake, 0, 0);
        }
        if (lakeType == "Dystrophic") {
          image(dystrophicLake, 0, 0);
        }
        if (lakeType == "Dystrophic Productive") {
          image(dystrophicProductiveLake, 0, 0);
        }

        strokeWeight(0);
        fill("black");
        rect(width - width/4.5, 0, width, height);

        D0.maxDepth = ceil(lakeDepth)-1;
        D0.run();
        // Make the button do
        submitButton.run();
        goBack.run();


      measureDepth = new TextBox(width - 300, height - 170, 240, 50, "Secchi Disk Simulator", function(){});
      measureDepth.run();
      visualAttempts = new TextBox(width - 300, height - 110, 240, 50, "Attempts Left: " + attemptsLeft, function(){});
      visualAttempts.run();

      messageDisplay = new TextBox(width - 300, height - 280, 240, 100, message, function(){});
      messageDisplay.run();

      simBackground = new TextBoxBackground(width - 300, height - 540, 240, 190, "", function(){});
      simBackground.run();

      simInfo = new TextBox3(width - 290, height - 530, 220, 170, "Current Depth: \n" + floor(D0.currentDepth*100)/100 + " Meters", function(){});
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
      animationY = windowHeight/12;

      resultsBoard =  new TextBoard(windowWidth/12, windowHeight/12, windowWidth*8/12, windowHeight*5/6);

      resultsBoard.background = 0;
      resultsBoard.accent = 50;
      resultsBoard.addParagraph(5);
      resultsBoard.addParagraph(5);
      resultsBoard.addTab(1);
      resultsBoard.addTab(1);
      resultsBoard.addText("Reading Results", color(255, 0, 0) , 40, "Helvetica", BOLD);
      resultsBoard.addParagraph(5);
      resultsBoard.addParagraph(5);
      resultsBoard.addParagraph(5);
      resultsBoard.addParagraph(5);
      resultsBoard.addTab(1);
      resultsBoard.addTab(1);
     //("Words", color, size, font, type);
      resultsBoard.addText("Lake Type", 200, 20, "Helvetica", BOLD);
      resultsBoard.addTab(1);
      resultsBoard.addTab(1);
      resultsBoard.addText(lakeType, 255, 20, "Helvetica", BOLD);
      resultsBoard.addParagraph(3);
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
      resultsBoard.addParagraph(3);
      resultsBoard.addTab(1);
      resultsBoard.addTab(1);
      resultsBoard.addText("Measured Depth", 200, 20, "Helvetica", BOLD);
      resultsBoard.addTab(1);
      resultsBoard.addTab(1);
      resultsBoard.addText(measuredDepth.toFixed(2) + " meters", 255, 20, "Helvetica", BOLD);
      resultsBoard.addParagraph(3);
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
      resultsBoard.addParagraph(3);
      resultsBoard.addTab(1);
      resultsBoard.addTab(1);
      resultsBoard.addText("Error (relative)", 200, 20, "Helvetica", BOLD);
      resultsBoard.addTab(1);
      resultsBoard.addTab(1);
      resultsBoard.addText(measuredErrorRel.toFixed(2) + "%", 255, 20, "Helvetica", BOLD);
      resultsBoard.addParagraph(3);
      resultsBoard.addParagraph(3);
      resultsBoard.addParagraph(3);
      resultsBoard.addTab(1);
      resultsBoard.addTab(1);
      resultsBoard.addText("Within Tolerance?", 200, 20, "Helvetica", BOLD);
      resultsBoard.addTab(1);
      resultsBoard.addText(measuredTolerance, 255, 20, "Helvetica", BOLD);

      // introStartDysProd.color = [245, 245, 245];
      introStartDysProd.fontSize = 14;

    },
    function() {
      // draw()
      scenes.background(60);
      resultsBoard.draw();

      push();
      var animationWindowX = windowWidth*9/12;
      var animationWindowY = windowHeight/12;
      var animationWindowW = windowWidth/6;
      var animationWindowH = windowHeight*5/6;
      var diskH = animationWindowH/50;
      var diskW = animationWindowW*2/3;
      var tapeW = animationWindowW/25;

      var percentMeasuredY = measuredDepth/lakeDepth;
      var measuredY = animationWindowY + (animationWindowH * percentMeasuredY);

      var percentY = lakeTarget/lakeDepth;
      var targetY = animationWindowY + (animationWindowH * percentY);
      //tolerance is +-0.10 meters
      var percentToleranceY = .1/lakeDepth;
      var upperToleranceY = targetY - (animationWindowH * percentToleranceY);
      var lowerToleranceY = targetY + (animationWindowH * percentToleranceY);
      var greenZone = lowerToleranceY - upperToleranceY;

      scenes.background(0);
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

      resultsRestart = new Button(780, 500, 95, 50, "Test Again",
        function() {
          // Button Selected
            setLakeType(5);
            scenes.setScene(2);
            scenes.setup();
        },
        function() {}
      );

      //lines
      strokeWeight(1);
      stroke(255, 0, 0, 255);
      //target line
      line(animationWindowX + 1, targetY, animationWindowX + animationWindowW - 1, targetY);
      //upper bound
      line(animationWindowX + 1, upperToleranceY, animationWindowX + (animationWindowW*1/29) - 1, upperToleranceY);
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
      line(animationWindowX + (animationWindowW*28/29), upperToleranceY, animationWindowX + animationWindowW - 1, upperToleranceY);
      //lower bound
      line(animationWindowX + 1, lowerToleranceY, animationWindowX + (animationWindowW*1/29) - 1, lowerToleranceY);
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
      line(animationWindowX + (animationWindowW*28/29), lowerToleranceY, animationWindowX + animationWindowW - 1, lowerToleranceY);
      //tape
      fill(255,255,255,255);
      noStroke();
      rect((animationWindowX + (animationWindowW/2)) - (tapeW/2), animationWindowY, tapeW, animationY);
      //disk
      rect((animationWindowX + (animationWindowW/2)) - (diskW/2), animationY + animationWindowY, diskW, diskH);
      fill(255,0,0);
      stroke(0);
      strokeWeight(1);
      triangle(animationWindowX - 10, (animationY + animationWindowY) - diskH, animationWindowX + (diskW/6), (animationY + animationWindowY), animationWindowX - 10, (animationY + animationWindowY) + diskH);
      animationY = animationY + 1;
        if (animationY > measuredY - animationWindowY) {
          animationY = measuredY - animationWindowY;
          animationY = animationY + 0;
        };
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

    backButton = new Button(right-110, top+20, 95, 50, "Go Back",
      function() {
        // Button Selected
          scenes.setScene(2);
          scenes.setup();
      },
      function() {}
    );

    skipButton = new Button(right-20, top+20, 95, 50, "(Testing)SkipToResults",
      function() {
        // Button Selected
          scenes.setScene(28);
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
    backButton.run();
    skipButton.run();
  })
);

//Question Screens

//Question 1
scenes.addScene(new Scene(windowWidth, windowHeight,
function() {

var left = windowWidth / 6;
var right = windowWidth - left;
var top = windowHeight / 6;
var bottom = windowHeight - top;

testBoard = new TextBoard(left, top, right - left/2, bottom - top/2);
testBoard.background = 60;
// descBoard.accent = 150;
testBoard.addText("Secchi Disk Test",240, 40, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addText("\nQuestion 1: " + Question1, 240, 24, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);

AnswerA = new AnswerButton(right-750, bottom-260, 800, 50,"A. " + Question1A,
function() {
// Button Selected
Question1Answer = "A";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question1Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerB = new AnswerButton(right-750, bottom-200, 800, 50,"B. " + Question1B,
function() {
// Button Selected
Question1Answer = "B";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question1Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerC = new AnswerButton(right-750, bottom-140, 800, 50, "C. " + Question1C,
function() {
// Button Selected
Question1Answer = "C";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question1Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerD = new AnswerButton(right-750, bottom-80, 800, 50, "D. " + Question1D,
function() {
// Button Selected
Question1Answer = "D";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question1Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
// Question1Answer = "D";
// simInfo.run();
);

simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question1Answer, function(){});

finishButton = new Button(right-395, bottom-20, 95, 50, "Next",
  function() {
    // Button Selected
      scenes.setScene(8);
      scenes.setup();
  },
  function() {}
);

backButton = new Button(right-20, top+20, 95, 50, "Go Back",
  function() {
    // Button Selected
      scenes.setScene(6);
      scenes.setup();
  },
  function() {}
);


introStartDysProd.fontSize = 14;
},
function() {
scenes.background(0);
testBoard.draw();
AnswerA.run();
AnswerB.run();
AnswerC.run();
AnswerD.run();
simInfo.run();
backButton.run();
if (Question1Answer != "Select an Answer.") {
  finishButton.run();
}
})
);


//Question 2
scenes.addScene(new Scene(windowWidth, windowHeight,
function() {

var left = windowWidth / 6;
var right = windowWidth - left;
var top = windowHeight / 6;
var bottom = windowHeight - top;

testBoard = new TextBoard(left, top, right - left/2, bottom - top/2);
testBoard.background = 60;
// descBoard.accent = 150;
testBoard.addText("Secchi Disk Test",240, 40, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addText("\nQuestion 2: " + Question2, 240, 23, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);

AnswerA = new AnswerButton(right-750, bottom-260, 800, 50, "A. " + Question2A,
function() {
// Button Selected
Question2Answer = "A";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question2Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerB = new AnswerButton(right-750, bottom-200, 800, 50, "B. " + Question2B,
function() {
// Button Selected
Question2Answer = "B";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question2Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerC = new AnswerButton(right-750, bottom-140, 800, 50, "C. " + Question2C,
function() {
// Button Selected
Question2Answer = "C";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question2Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerD = new AnswerButton(right-750, bottom-80, 800, 50, "D. " + Question2D,
function() {
// Button Selected
Question2Answer = "D";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question2Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
// Question1Answer = "D";
// simInfo.run();
);

simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question2Answer, function(){});

finishButton = new Button(right-395, bottom-20, 95, 50, "Next",
  function() {
    // Button Selected
      scenes.setScene(9);
      scenes.setup();
  },
  function() {}
);

backButton = new Button(right-20, top+20, 95, 50, "Go Back",
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
testBoard.draw();
AnswerA.run();
AnswerB.run();
AnswerC.run();
AnswerD.run();
simInfo.run();
backButton.run();
if (Question2Answer != "Select an Answer.") {
  finishButton.run();
}
})
);


//Question 3
scenes.addScene(new Scene(windowWidth, windowHeight,
function() {

var left = windowWidth / 6;
var right = windowWidth - left;
var top = windowHeight / 6;
var bottom = windowHeight - top;

testBoard = new TextBoard(left, top, right - left/2, bottom - top/2);
testBoard.background = 60;
// descBoard.accent = 150;
testBoard.addText("Secchi Disk Test",240, 40, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addText("\nQuestion 3: " + Question3, 240, 24, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);

AnswerA = new AnswerButton(right-750, bottom-260, 800, 50, "A. " + Question3A,
function() {
// Button Selected
Question3Answer = "A";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question3Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerB = new AnswerButton(right-750, bottom-200, 800, 50, "B. " + Question3B,
function() {
// Button Selected
Question3Answer = "B";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question3Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerC = new AnswerButton(right-750, bottom-140, 800, 50, "C. " + Question3C,
function() {
// Button Selected
Question3Answer = "C";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question3Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerD = new AnswerButton(right-750, bottom-80, 800, 50, "D. " + Question3D,
function() {
// Button Selected
Question3Answer = "D";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question3Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
// Question1Answer = "D";
// simInfo.run();
);

simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question3Answer, function(){});

finishButton = new Button(right-395, bottom-20, 95, 50, "Next",
  function() {
    // Button Selected
      scenes.setScene(10);
      scenes.setup();
  },
  function() {}
);

backButton = new Button(right-20, top+20, 95, 50, "Go Back",
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
AnswerA.run();
AnswerB.run();
AnswerC.run();
AnswerD.run();
simInfo.run();
backButton.run();
if (Question3Answer != "Select an Answer.") {
  finishButton.run();
}
})
);

//Question 4
scenes.addScene(new Scene(windowWidth, windowHeight*2,
function() {

var left = windowWidth / 6;
var right = windowWidth - left;
var top = windowHeight / 6;
var bottom = windowHeight*1.5;

testBoard = new TextBoard(left, top, right - left/2, bottom - top/2);
testBoard.background = 60;
// descBoard.accent = 150;
testBoard.addText("Secchi Disk Test",240, 40, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addText("\nQuestion 4: " + Question4, 240, 24, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);

image(manWithSecchi, 300, 300, 100, 200);


AnswerA = new AnswerButton(right-750, bottom-260, 800, 50, "A. " + Question4A,
function() {
// Button Selected
Question4Answer = "A";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question4Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerB = new AnswerButton(right-750, bottom-200, 800, 50, "B. " + Question4B,
function() {
// Button Selected
Question4Answer = "B";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question4Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerC = new AnswerButton(right-750, bottom-140, 800, 50, "C. " + Question4C,
function() {
// Button Selected
Question4Answer = "C";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question4Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerD = new AnswerButton(right-750, bottom-80, 800, 50, "D. " + Question4D,
function() {
// Button Selected
Question4Answer = "D";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question4Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
// Question1Answer = "D";
// simInfo.run();
);

simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question4Answer, function(){});

finishButton = new Button(right-395, bottom-20, 95, 50, "Next",
  function() {
    // Button Selected
      scenes.setScene(11);
      scenes.setup();
  },
  function() {}
);

backButton = new Button(right-20, top+20, 95, 50, "Go Back",
  function() {
    // Button Selected
      scenes.setScene(9);
      scenes.setup();
  },
  function() {}
);

introStartDysProd.fontSize = 14;
},
function() {
scenes.background(0);
testBoard.draw();
AnswerA.run();
AnswerB.run();
AnswerC.run();
AnswerD.run();
simInfo.run();
backButton.run();
if (Question4Answer != "Select an Answer.") {
  finishButton.run();
}
})
);

//Question 5
scenes.addScene(new Scene(windowWidth, windowHeight,
function() {

var left = windowWidth / 6;
var right = windowWidth - left;
var top = windowHeight / 6;
var bottom = windowHeight - top;

testBoard = new TextBoard(left, top, right - left/2, bottom - top/2);
testBoard.background = 60;
// descBoard.accent = 150;
testBoard.addText("Secchi Disk Test",240, 40, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addText("\nQuestion 5: " + Question5, 240, 23, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);

AnswerA = new AnswerButton(right-750, bottom-260, 800, 50, "A. " + Question5A,
function() {
// Button Selected
Question5Answer = "A";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question5Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerB = new AnswerButton(right-750, bottom-200, 800, 50, "B. " + Question5B,
function() {
// Button Selected
Question5Answer = "B";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question5Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

// AnswerC = new AnswerButton(right-750, bottom-140, 800, 50, "C. " + Question5C,
// function() {
// // Button Selected
// Question5Answer = "C";
// simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question5Answer, function(){});
// simInfo.run();
// },
// function() {
// // Button Unselected
// }
// );

// AnswerD = new AnswerButton(right-750, bottom-80, 800, 50, "D. " + Question5D,
// function() {
// // Button Selected
// Question5Answer = "D";
// simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question5Answer, function(){});
// simInfo.run();
// },
// function() {
// // Button Unselected
// }
// // Question1Answer = "D";
// // simInfo.run();
// );

simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question5Answer, function(){});

finishButton = new Button(right-395, bottom-20, 95, 50, "Next",
  function() {
    // Button Selected
      scenes.setScene(12);
      scenes.setup();
  },
  function() {}
);

backButton = new Button(right-20, top+20, 95, 50, "Go Back",
  function() {
    // Button Selected
      scenes.setScene(10);
      scenes.setup();
  },
  function() {}
);


introStartDysProd.fontSize = 14;
},
function() {
scenes.background(0);
testBoard.draw();
AnswerA.run();
AnswerB.run();
// AnswerC.run();
// AnswerD.run();
simInfo.run();
backButton.run();
if (Question5Answer != "Select an Answer.") {
  finishButton.run();
}
})
);

//Question 6
scenes.addScene(new Scene(windowWidth, windowHeight,
function() {

var left = windowWidth / 6;
var right = windowWidth - left;
var top = windowHeight / 6;
var bottom = windowHeight - top;

testBoard = new TextBoard(left, top, right - left/2, bottom - top/2);
testBoard.background = 60;
// descBoard.accent = 150;
testBoard.addText("Secchi Disk Test",240, 40, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addText("\nQuestion 6: " + Question6, 240, 24, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);

AnswerA = new AnswerButton(right-750, bottom-260, 800, 50, "A. " + Question6A,
function() {
// Button Selected
Question6Answer = "A";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question6Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerB = new AnswerButton(right-750, bottom-200, 800, 50, "B. " + Question6B,
function() {
// Button Selected
Question6Answer = "B";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question6Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerC = new AnswerButton(right-750, bottom-140, 800, 50, "C. " + Question6C,
function() {
// Button Selected
Question6Answer = "C";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question6Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerD = new AnswerButton(right-750, bottom-80, 800, 50, "D. " + Question6D,
function() {
// Button Selected
Question6Answer = "D";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question6Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
// Question1Answer = "D";
// simInfo.run();
);

simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question6Answer, function(){});

finishButton = new Button(right-395, bottom-20, 95, 50, "Next",
  function() {
    // Button Selected
      scenes.setScene(13);
      scenes.setup();
  },
  function() {}
);

backButton = new Button(right-20, top+20, 95, 50, "Go Back",
  function() {
    // Button Selected
      scenes.setScene(11);
      scenes.setup();
  },
  function() {}
);


introStartDysProd.fontSize = 14;
},
function() {
scenes.background(0);
testBoard.draw();
AnswerA.run();
AnswerB.run();
AnswerC.run();
AnswerD.run();
simInfo.run();
backButton.run();
if (Question6Answer != "Select an Answer.") {
  finishButton.run();
}
})
);

//Question 7
scenes.addScene(new Scene(windowWidth, windowHeight,
function() {

var left = windowWidth / 6;
var right = windowWidth - left;
var top = windowHeight / 120;
var bottom = windowHeight - top;

testBoard = new TextBoard(left, top, right - left/2, bottom - top);
testBoard.background = 60;
// descBoard.accent = 150;
testBoard.addText("Secchi Disk Test",240, 40, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addText("\nQuestion 7: " + Question7, 240, 24, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);

AnswerA = new AnswerButton(right-750, bottom-420, 800, 50, "A. " + Question7A,
function() {
// Button Selected
Question7Answer = "A";
simInfo = new TextBox(right-650, bottom-60, 250, 50, "Current Answer: " + Question7Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerB = new AnswerButton(right-750, bottom-360, 800, 50, "B. " + Question7B,
function() {
// Button Selected
Question7Answer = "B";
simInfo = new TextBox(right-650, bottom-60, 250, 50, "Current Answer: " + Question7Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerC = new AnswerButton(right-750, bottom-300, 800, 50, "C. " + Question7C,
function() {
// Button Selected
Question7Answer = "C";
simInfo = new TextBox(right-650, bottom-60, 250, 50, "Current Answer: " + Question7Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerD = new AnswerButton(right-750, bottom-240, 800, 50, "D. " + Question7D,
function() {
// Button Selected
Question7Answer = "D";
simInfo = new TextBox(right-650, bottom-60, 250, 50, "Current Answer: " + Question7Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
// Question1Answer = "D";
// simInfo.run();
);

AnswerE = new AnswerButton(right-750, bottom-180, 800, 50, "E. " + Question7E,
function() {
// Button Selected
Question7Answer = "E";
simInfo = new TextBox(right-650, bottom-60, 250, 50, "Current Answer: " + Question7Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
// Question1Answer = "D";
// simInfo.run();
);

AnswerF = new AnswerButton(right-750, bottom-120, 800, 50, "F. " + Question7F,
function() {
// Button Selected
Question7Answer = "F";
simInfo = new TextBox(right-650, bottom-60, 250, 50, "Current Answer: " + Question7Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
// Question1Answer = "D";
// simInfo.run();
);

simInfo = new TextBox(right-650, bottom-60, 250, 50, "Current Answer: " + Question7Answer, function(){});

finishButton = new Button(right-395, bottom-60, 95, 50, "Next",
  function() {
    // Button Selected
      scenes.setScene(14);
      scenes.setup();
  },
  function() {}
);

backButton = new Button(right-20, top+20, 95, 50, "Go Back",
  function() {
    // Button Selected
      scenes.setScene(12);
      scenes.setup();
  },
  function() {}
);


introStartDysProd.fontSize = 14;
},
function() {
scenes.background(0);
testBoard.draw();
AnswerA.run();
AnswerB.run();
AnswerC.run();
AnswerD.run();
AnswerE.run();
AnswerF.run();
simInfo.run();
backButton.run();
if (Question7Answer != "Select an Answer.") {
  finishButton.run();
}
})
);

//Question 8
scenes.addScene(new Scene(windowWidth, windowHeight,
function() {

var left = windowWidth / 6;
var right = windowWidth - left;
var top = windowHeight / 6;
var bottom = windowHeight - top;

testBoard = new TextBoard(left, top, right - left/2, bottom - top/2);
testBoard.background = 60;
// descBoard.accent = 150;
testBoard.addText("Secchi Disk Test",240, 40, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addText("\nQuestion 8: " + Question8, 240, 24, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);

AnswerA = new AnswerButton(right-750, bottom-260, 800, 50, "A. " + Question8A,
function() {
// Button Selected
Question8Answer = "A";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question8Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerB = new AnswerButton(right-750, bottom-200, 800, 50, "B. " + Question8B,
function() {
// Button Selected
Question8Answer = "B";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question8Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerC = new AnswerButton(right-750, bottom-140, 800, 50, "C. " + Question8C,
function() {
// Button Selected
Question8Answer = "C";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question8Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerD = new AnswerButton(right-750, bottom-80, 800, 50, "D. " + Question8D,
function() {
// Button Selected
Question8Answer = "D";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question8Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
// Question1Answer = "D";
// simInfo.run();
);

simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question8Answer, function(){});

finishButton = new Button(right-395, bottom-20, 95, 50, "Next",
  function() {
    // Button Selected
      scenes.setScene(15);
      scenes.setup();
  },
  function() {}
);

backButton = new Button(right-20, top+20, 95, 50, "Go Back",
  function() {
    // Button Selected
      scenes.setScene(13);
      scenes.setup();
  },
  function() {}
);


introStartDysProd.fontSize = 14;
},
function() {
scenes.background(0);
testBoard.draw();
AnswerA.run();
AnswerB.run();
AnswerC.run();
AnswerD.run();
simInfo.run();
backButton.run();
if (Question8Answer != "Select an Answer.") {
  finishButton.run();
}
})
);

//Question 9
scenes.addScene(new Scene(windowWidth, windowHeight,
function() {

var left = windowWidth / 6;
var right = windowWidth - left;
var top = windowHeight / 6;
var bottom = windowHeight - top;

testBoard = new TextBoard(left, top, right - left/2, bottom - top/2);
testBoard.background = 60;
// descBoard.accent = 150;
testBoard.addText("Secchi Disk Test",240, 40, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addText("\nQuestion 9: " + Question9, 240, 24, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);

AnswerA = new AnswerButton(right-750, bottom-260, 800, 50, "A. " + Question9A,
function() {
// Button Selected
Question9Answer = "A";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question9Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerB = new AnswerButton(right-750, bottom-200, 800, 50, "B. " + Question9B,
function() {
// Button Selected
Question9Answer = "B";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question9Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerC = new AnswerButton(right-750, bottom-140, 800, 50, "C. " + Question9C,
function() {
// Button Selected
Question9Answer = "C";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question9Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerD = new AnswerButton(right-750, bottom-80, 800, 50, "D. " + Question9D,
function() {
// Button Selected
Question9Answer = "D";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question9Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
// Question1Answer = "D";
// simInfo.run();
);

simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question9Answer, function(){});

finishButton = new Button(right-395, bottom-20, 95, 50, "Next",
  function() {
    // Button Selected
      scenes.setScene(16);
      scenes.setup();
  },
  function() {}
);

backButton = new Button(right-20, top+20, 95, 50, "Go Back",
  function() {
    // Button Selected
      scenes.setScene(14);
      scenes.setup();
  },
  function() {}
);


introStartDysProd.fontSize = 14;
},
function() {
scenes.background(0);
testBoard.draw();
AnswerA.run();
AnswerB.run();
AnswerC.run();
AnswerD.run();
simInfo.run();
backButton.run();
if (Question9Answer != "Select an Answer.") {
  finishButton.run();
}
})
);

//Question 10
scenes.addScene(new Scene(windowWidth, windowHeight,
function() {

var left = windowWidth / 6;
var right = windowWidth - left;
var top = windowHeight / 6;
var bottom = windowHeight - top;

testBoard = new TextBoard(left, top, right - left/2, bottom - top/2);
testBoard.background = 60;
// descBoard.accent = 150;
testBoard.addText("Secchi Disk Test",240, 40, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addText("\nQuestion 10: " + Question10, 240, 24, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);

AnswerA = new AnswerButton(right-750, bottom-260, 800, 50, "A. " + Question10A,
function() {
// Button Selected
Question10Answer = "A";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question10Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerB = new AnswerButton(right-750, bottom-200, 800, 50, "B. " + Question10B,
function() {
// Button Selected
Question10Answer = "B";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question10Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

// AnswerC = new AnswerButton(right-750, bottom-140, 800, 50, "C. " + Question10C,
// function() {
// // Button Selected
// Question10Answer = "C";
// simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question10Answer, function(){});
// simInfo.run();
// },
// function() {
// // Button Unselected
// }
// );

// AnswerD = new AnswerButton(right-750, bottom-80, 800, 50, "D. " + Question10D,
// function() {
// // Button Selected
// Question10Answer = "D";
// simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question10Answer, function(){});
// simInfo.run();
// },
// function() {
// // Button Unselected
// }
// // Question1Answer = "D";
// // simInfo.run();
// );

simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question10Answer, function(){});

finishButton = new Button(right-395, bottom-20, 95, 50, "Next",
  function() {
    // Button Selected
      scenes.setScene(17);
      scenes.setup();
  },
  function() {}
);

backButton = new Button(right-20, top+20, 95, 50, "Go Back",
  function() {
    // Button Selected
      scenes.setScene(15);
      scenes.setup();
  },
  function() {}
);


introStartDysProd.fontSize = 14;
},
function() {
scenes.background(0);
testBoard.draw();
AnswerA.run();
AnswerB.run();
// AnswerC.run();
// AnswerD.run();
simInfo.run();
backButton.run();
if (Question10Answer != "Select an Answer.") {
  finishButton.run();
}
})
);

//Question 11
scenes.addScene(new Scene(windowWidth, windowHeight,
function() {

var left = windowWidth / 6;
var right = windowWidth - left;
var top = windowHeight / 6;
var bottom = windowHeight - top;

testBoard = new TextBoard(left, top, right - left/2, bottom - top/2);
testBoard.background = 60;
// descBoard.accent = 150;
testBoard.addText("Secchi Disk Test",240, 40, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addText("\nQuestion 11: " + Question11, 240, 24, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);

AnswerA = new AnswerButton(right-750, bottom-260, 800, 50, "A. " + Question11A,
function() {
// Button Selected
Question11Answer = "A";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question11Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerB = new AnswerButton(right-750, bottom-200, 800, 50, "B. " + Question11B,
function() {
// Button Selected
Question11Answer = "B";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question11Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerC = new AnswerButton(right-750, bottom-140, 800, 50, "C. " + Question11C,
function() {
// Button Selected
Question11Answer = "C";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question11Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

// AnswerD = new AnswerButton(right-750, bottom-80, 800, 50, "D. " + Question11D,
// function() {
// // Button Selected
// Question11Answer = "D";
// simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question11Answer, function(){});
// simInfo.run();
// },
// function() {
// // Button Unselected
// }
// // Question1Answer = "D";
// // simInfo.run();
// );

simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question11Answer, function(){});

finishButton = new Button(right-395, bottom-20, 95, 50, "Next",
  function() {
    // Button Selected
      scenes.setScene(18);
      scenes.setup();
  },
  function() {}
);

backButton = new Button(right-20, top+20, 95, 50, "Go Back",
  function() {
    // Button Selected
      scenes.setScene(16);
      scenes.setup();
  },
  function() {}
);


introStartDysProd.fontSize = 14;
},
function() {
scenes.background(0);
testBoard.draw();
AnswerA.run();
AnswerB.run();
AnswerC.run();
// AnswerD.run();
simInfo.run();
backButton.run();
if (Question11Answer != "Select an Answer.") {
  finishButton.run();
}
})
);

//Question 12
scenes.addScene(new Scene(windowWidth, windowHeight,
function() {

var left = windowWidth / 6;
var right = windowWidth - left;
var top = windowHeight / 6;
var bottom = windowHeight - top;

testBoard = new TextBoard(left, top, right - left/2, bottom - top/2);
testBoard.background = 60;
// descBoard.accent = 150;
testBoard.addText("Secchi Disk Test",240, 40, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addText("\nQuestion 12: " + Question12, 240, 24, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);

AnswerA = new AnswerButton(right-750, bottom-260, 800, 50, "A. " + Question12A,
function() {
// Button Selected
Question12Answer = "A";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question12Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerB = new AnswerButton(right-750, bottom-200, 800, 100, "B. " + Question12B,
function() {
// Button Selected
Question12Answer = "B";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question12Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerC = new AnswerButton(right-750, bottom-90, 800, 50, "C. " + Question12C,
function() {
// Button Selected
Question12Answer = "C";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question12Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

// AnswerD = new AnswerButton(right-750, bottom-80, 800, 50, "D. " + Question12D,
// function() {
// // Button Selected
// Question12Answer = "D";
// simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question12Answer, function(){});
// simInfo.run();
// },
// function() {
// // Button Unselected
// }
// // Question1Answer = "D";
// // simInfo.run();
// );

simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question12Answer, function(){});

finishButton = new Button(right-395, bottom-20, 95, 50, "Next",
  function() {
    // Button Selected
      scenes.setScene(19);
      scenes.setup();
  },
  function() {}
);

backButton = new Button(right-20, top+20, 95, 50, "Go Back",
  function() {
    // Button Selected
      scenes.setScene(17);
      scenes.setup();
  },
  function() {}
);


introStartDysProd.fontSize = 14;
},
function() {
scenes.background(0);
testBoard.draw();
AnswerA.run();
AnswerB.run();
AnswerC.run();
// AnswerD.run();
simInfo.run();
backButton.run();
if (Question12Answer != "Select an Answer.") {
  finishButton.run();
}
})
);

//Question 13
scenes.addScene(new Scene(windowWidth, windowHeight,
function() {

var left = windowWidth / 6;
var right = windowWidth - left;
var top = windowHeight / 8;
var bottom = windowHeight - top;

testBoard = new TextBoard(left, top, right - left/2, bottom - top/2);
testBoard.background = 60;
// descBoard.accent = 150;
testBoard.addText("Secchi Disk Test",240, 40, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addText("\nQuestion 13: " + Question13, 240, 18, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);

AnswerA = new AnswerButton(right-750, bottom-280, 800, 50, "A. " + Question13A,
function() {
// Button Selected
Question13Answer = "A";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question13Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerB = new AnswerButton(right-750, bottom-220, 800, 50, "B. " + Question13B,
function() {
// Button Selected
Question13Answer = "B";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question13Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerC = new AnswerButton(right-750, bottom-160, 800, 50, "C. " + Question13C,
function() {
// Button Selected
Question13Answer = "C";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question13Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerD = new AnswerButton(right-750, bottom-100, 800, 50, "D. " + Question13D,
function() {
// Button Selected
Question13Answer = "D";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question13Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
// Question1Answer = "D";
// simInfo.run();
);

simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question13Answer, function(){});

finishButton = new Button(right-395, bottom-20, 95, 50, "Next",
  function() {
    // Button Selected
      scenes.setScene(20);
      scenes.setup();
  },
  function() {}
);

backButton = new Button(right-20, top+20, 95, 50, "Go Back",
  function() {
    // Button Selected
      scenes.setScene(18);
      scenes.setup();
  },
  function() {}
);


introStartDysProd.fontSize = 14;
},
function() {
scenes.background(0);
testBoard.draw();
AnswerA.run();
AnswerB.run();
AnswerC.run();
AnswerD.run();
simInfo.run();
backButton.run();
if (Question13Answer != "Select an Answer.") {
  finishButton.run();
}
})
);

//Question 14
scenes.addScene(new Scene(windowWidth, windowHeight,
function() {

var left = windowWidth / 6;
var right = windowWidth - left;
var top = windowHeight / 6;
var bottom = windowHeight - top;

testBoard = new TextBoard(left, top, right - left/2, bottom - top/2);
testBoard.background = 60;
// descBoard.accent = 150;
testBoard.addText("Secchi Disk Test",240, 40, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addText("\nQuestion 14: " + Question14, 240, 20, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);

AnswerA = new AnswerButton(right-750, bottom-260, 800, 50, "A. " + Question14A,
function() {
// Button Selected
Question14Answer = "A";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question14Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerB = new AnswerButton(right-750, bottom-200, 800, 50, "B. " + Question14B,
function() {
// Button Selected
Question14Answer = "B";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question14Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

// AnswerC = new AnswerButton(right-750, bottom-140, 800, 50, "C. " + Question14C,
// function() {
// // Button Selected
// Question14Answer = "C";
// simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question14Answer, function(){});
// simInfo.run();
// },
// function() {
// // Button Unselected
// }
// );

// AnswerD = new AnswerButton(right-750, bottom-80, 800, 50, "D. " + Question14D,
// function() {
// // Button Selected
// Question14Answer = "D";
// simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question14Answer, function(){});
// simInfo.run();
// },
// function() {
// // Button Unselected
// }
// // Question1Answer = "D";
// // simInfo.run();
// );

simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question14Answer, function(){});

finishButton = new Button(right-395, bottom-20, 95, 50, "Next",
  function() {
    // Button Selected
      scenes.setScene(21);
      scenes.setup();
  },
  function() {}
);

backButton = new Button(right-20, top+20, 95, 50, "Go Back",
  function() {
    // Button Selected
      scenes.setScene(19);
      scenes.setup();
  },
  function() {}
);


introStartDysProd.fontSize = 14;
},
function() {
scenes.background(0);
testBoard.draw();
AnswerA.run();
AnswerB.run();
// AnswerC.run();
// AnswerD.run();
simInfo.run();
backButton.run();
if (Question14Answer != "Select an Answer.") {
  finishButton.run();
}
})
);

//Question 15
scenes.addScene(new Scene(windowWidth, windowHeight,
function() {

var left = windowWidth / 6;
var right = windowWidth - left;
var top = windowHeight / 12;
var bottom = windowHeight - top;

testBoard = new TextBoard(left, top, right - left/2, bottom - top/2);
testBoard.background = 60;
// descBoard.accent = 150;
testBoard.addText("Secchi Disk Test",240, 40, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addText("\nQuestion 15: " + Question15, 240, 18, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);

AnswerA = new AnswerButton(right-750, bottom-300, 800, 50, "A. " + Question15A,
function() {
// Button Selected
Question15Answer = "A";
simInfo = new TextBox(right-650, bottom-60, 250, 50, "Current Answer: " + Question15Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerB = new AnswerButton(right-750, bottom-240, 800, 50, "B. " + Question15B,
function() {
// Button Selected
Question15Answer = "B";
simInfo = new TextBox(right-650, bottom-60, 250, 50, "Current Answer: " + Question15Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerC = new AnswerButton(right-750, bottom-180, 800, 50, "C. " + Question15C,
function() {
// Button Selected
Question15Answer = "C";
simInfo = new TextBox(right-650, bottom-60, 250, 50, "Current Answer: " + Question15Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerD = new AnswerButton(right-750, bottom-120, 800, 50, "D. " + Question15D,
function() {
// Button Selected
Question15Answer = "D";
simInfo = new TextBox(right-650, bottom-60, 250, 50, "Current Answer: " + Question15Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
// Question1Answer = "D";
// simInfo.run();
);

simInfo = new TextBox(right-650, bottom-60, 250, 50, "Current Answer: " + Question15Answer, function(){});

finishButton = new Button(right-395, bottom-60, 95, 50, "Next",
  function() {
    // Button Selected
      scenes.setScene(22);
      scenes.setup();
  },
  function() {}
);

backButton = new Button(right-20, top+20, 95, 50, "Go Back",
  function() {
    // Button Selected
      scenes.setScene(20);
      scenes.setup();
  },
  function() {}
);


introStartDysProd.fontSize = 14;
},
function() {
scenes.background(0);
testBoard.draw();
AnswerA.run();
AnswerB.run();
AnswerC.run();
AnswerD.run();
simInfo.run();
backButton.run();
if (Question15Answer != "Select an Answer.") {
  finishButton.run();
}
})
);

//Question 16
scenes.addScene(new Scene(windowWidth, windowHeight,
function() {

var left = windowWidth / 6;
var right = windowWidth - left;
var top = windowHeight / 6;
var bottom = windowHeight - top;

testBoard = new TextBoard(left, top, right - left/2, bottom - top/2);
testBoard.background = 60;
// descBoard.accent = 150;
testBoard.addText("Secchi Disk Test",240, 40, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addText("\nQuestion 16: " + Question16, 240, 18, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);

AnswerA = new AnswerButton(right-750, bottom-250, 800, 50, "A. " + Question16A,
function() {
// Button Selected
Question16Answer = "A";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question16Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerB = new AnswerButton(right-750, bottom-190, 800, 50, "B. " + Question16B,
function() {
// Button Selected
Question16Answer = "B";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question16Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerC = new AnswerButton(right-750, bottom-130, 800, 50, "C. " + Question16C,
function() {
// Button Selected
Question16Answer = "C";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question16Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

// AnswerD = new AnswerButton(right-750, bottom-80, 800, 50, "D. " + Question16D,
// function() {
// // Button Selected
// Question16Answer = "D";
// simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question16Answer, function(){});
// simInfo.run();
// },
// function() {
// // Button Unselected
// }
// // Question1Answer = "D";
// // simInfo.run();
// );

simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question16Answer, function(){});

finishButton = new Button(right-395, bottom-20, 95, 50, "Next",
  function() {
    // Button Selected
      scenes.setScene(23);
      scenes.setup();
  },
  function() {}
);

backButton = new Button(right-20, top+20, 95, 50, "Go Back",
  function() {
    // Button Selected
      scenes.setScene(21);
      scenes.setup();
  },
  function() {}
);


introStartDysProd.fontSize = 14;
},
function() {
scenes.background(0);
testBoard.draw();
AnswerA.run();
AnswerB.run();
AnswerC.run();
// AnswerD.run();
simInfo.run();
backButton.run();
if (Question16Answer != "Select an Answer.") {
  finishButton.run();
}
})
);

//Question 17
scenes.addScene(new Scene(windowWidth, windowHeight,
function() {

var left = windowWidth / 6;
var right = windowWidth - left;
var top = windowHeight / 12;
var bottom = windowHeight - top;

testBoard = new TextBoard(left, top, right - left/2, bottom - top/2);
testBoard.background = 60;
// descBoard.accent = 150;
testBoard.addText("Secchi Disk Test",240, 40, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addText("\nQuestion 17: " + Question17, 240, 18, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);

AnswerA = new AnswerButton(right-750, bottom-320, 800, 50, "A. " + Question17A,
function() {
// Button Selected
Question17Answer = "A";
simInfo = new TextBox(right-650, bottom-40, 250, 50, "Current Answer: " + Question17Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerB = new AnswerButton(right-750, bottom-260, 800, 50, "B. " + Question17B,
function() {
// Button Selected
Question17Answer = "B";
simInfo = new TextBox(right-650, bottom-40, 250, 50, "Current Answer: " + Question17Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerC = new AnswerButton(right-750, bottom-200, 800, 50, "C. " + Question17C,
function() {
// Button Selected
Question17Answer = "C";
simInfo = new TextBox(right-650, bottom-40, 250, 50, "Current Answer: " + Question17Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerD = new AnswerButton(right-750, bottom-140, 800, 50, "D. " + Question17D,
function() {
// Button Selected
Question17Answer = "D";
simInfo = new TextBox(right-650, bottom-40, 250, 50, "Current Answer: " + Question17Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
// Question1Answer = "D";
// simInfo.run();
);

simInfo = new TextBox(right-650, bottom-40, 250, 50, "Current Answer: " + Question17Answer, function(){});

finishButton = new Button(right-395, bottom-40, 95, 50, "Next",
  function() {
    // Button Selected
      scenes.setScene(24);
      scenes.setup();
  },
  function() {}
);

backButton = new Button(right-20, top+20, 95, 50, "Go Back",
  function() {
    // Button Selected
      scenes.setScene(22);
      scenes.setup();
  },
  function() {}
);


introStartDysProd.fontSize = 14;
},
function() {
scenes.background(0);
testBoard.draw();
AnswerA.run();
AnswerB.run();
AnswerC.run();
AnswerD.run();
simInfo.run();
backButton.run();
if (Question17Answer != "Select an Answer.") {
  finishButton.run();
}
})
);

//Question 18
scenes.addScene(new Scene(windowWidth, windowHeight,
function() {

var left = windowWidth / 6;
var right = windowWidth - left;
var top = windowHeight / 6;
var bottom = windowHeight - top;

testBoard = new TextBoard(left, top, right - left/2, bottom - top/2);
testBoard.background = 60;
// descBoard.accent = 150;
testBoard.addText("Secchi Disk Test",240, 40, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addText("\nQuestion 18: " + Question18, 240, 18, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);

AnswerA = new AnswerButton(right-750, bottom-260, 800, 50, "A. " + Question18A,
function() {
// Button Selected
Question18Answer = "A";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question18Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerB = new AnswerButton(right-750, bottom-200, 800, 50, "B. " + Question18B,
function() {
// Button Selected
Question18Answer = "B";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question18Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerC = new AnswerButton(right-750, bottom-140, 800, 50, "C. " + Question18C,
function() {
// Button Selected
Question18Answer = "C";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question18Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

// AnswerD = new AnswerButton(right-750, bottom-80, 800, 50, "D. " + Question18D,
// function() {
// // Button Selected
// Question18Answer = "D";
// simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question18Answer, function(){});
// simInfo.run();
// },
// function() {
// // Button Unselected
// }
// // Question1Answer = "D";
// // simInfo.run();
// );

simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question18Answer, function(){});

finishButton = new Button(right-395, bottom-20, 95, 50, "Next",
  function() {
    // Button Selected
      scenes.setScene(25);
      scenes.setup();
  },
  function() {}
);

backButton = new Button(right-20, top+20, 95, 50, "Go Back",
  function() {
    // Button Selected
      scenes.setScene(23);
      scenes.setup();
  },
  function() {}
);


introStartDysProd.fontSize = 14;
},
function() {
scenes.background(0);
testBoard.draw();
AnswerA.run();
AnswerB.run();
AnswerC.run();
// AnswerD.run();
simInfo.run();
backButton.run();
if (Question18Answer != "Select an Answer.") {
  finishButton.run();
}
})
);

//Question 19
scenes.addScene(new Scene(windowWidth, windowHeight,
function() {

var left = windowWidth / 6;
var right = windowWidth - left;
var top = windowHeight / 6;
var bottom = windowHeight - top;

testBoard = new TextBoard(left, top, right - left/2, bottom - top/2);
testBoard.background = 60;
// descBoard.accent = 150;
testBoard.addText("Secchi Disk Test",240, 40, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addText("\nQuestion 19: " + Question19, 240, 18, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);

AnswerA = new AnswerButton(right-750, bottom-260, 800, 50, "A. " + Question19A,
function() {
// Button Selected
Question19Answer = "A";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question19Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerB = new AnswerButton(right-750, bottom-200, 800, 50, "B. " + Question19B,
function() {
// Button Selected
Question19Answer = "B";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question19Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerC = new AnswerButton(right-750, bottom-140, 800, 50, "C. " + Question19C,
function() {
// Button Selected
Question19Answer = "C";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question19Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerD = new AnswerButton(right-750, bottom-80, 800, 50, "D. " + Question19D,
function() {
// Button Selected
Question19Answer = "D";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question19Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
// Question1Answer = "D";
// simInfo.run();
);

simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question19Answer, function(){});

finishButton = new Button(right-395, bottom-20, 95, 50, "Next",
  function() {
    // Button Selected
      scenes.setScene(26);
      scenes.setup();
  },
  function() {}
);

backButton = new Button(right-20, top+20, 95, 50, "Go Back",
  function() {
    // Button Selected
      scenes.setScene(24);
      scenes.setup();
  },
  function() {}
);


introStartDysProd.fontSize = 14;
},
function() {
scenes.background(0);
testBoard.draw();
AnswerA.run();
AnswerB.run();
AnswerC.run();
AnswerD.run();
simInfo.run();
backButton.run();
if (Question19Answer != "Select an Answer.") {
  finishButton.run();
}
})
);

//Question 20
scenes.addScene(new Scene(windowWidth, windowHeight,
function() {

var left = windowWidth / 6;
var right = windowWidth - left;
var top = windowHeight / 6;
var bottom = windowHeight - top;

testBoard = new TextBoard(left, top, right - left/2, bottom - top/2);
testBoard.background = 60;
// descBoard.accent = 150;
testBoard.addText("Secchi Disk Test",240, 40, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addText("\nQuestion 20: " + Question20, 240, 18, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);

AnswerA = new AnswerButton(right-750, bottom-260, 800, 50, "A. " + Question20A,
function() {
// Button Selected
Question20Answer = "A";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question20Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerB = new AnswerButton(right-750, bottom-200, 800, 50, "B. " + Question20B,
function() {
// Button Selected
Question20Answer = "B";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question20Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerC = new AnswerButton(right-750, bottom-140, 800, 50, "C. " + Question20C,
function() {
// Button Selected
Question20Answer = "C";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question20Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
);

AnswerD = new AnswerButton(right-750, bottom-80, 800, 50, "D. " + Question20D,
function() {
// Button Selected
Question20Answer = "D";
simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question20Answer, function(){});
simInfo.run();
},
function() {
// Button Unselected
}
// Question1Answer = "D";
// simInfo.run();
);

simInfo = new TextBox(right-650, bottom-20, 250, 50, "Current Answer: " + Question20Answer, function(){});

finishButton = new Button(right-395, bottom-20, 95, 50, "Next",
  function() {
    // Button Selected
      scenes.setScene(27);
      scenes.setup();
  },
  function() {}
);

backButton = new Button(right-20, top+20, 95, 50, "Go Back",
  function() {
    // Button Selected
      scenes.setScene(25);
      scenes.setup();
  },
  function() {}
);


introStartDysProd.fontSize = 14;
},
function() {
scenes.background(0);
testBoard.draw();
AnswerA.run();
AnswerB.run();
AnswerC.run();
AnswerD.run();
simInfo.run();
backButton.run();
if (Question20Answer != "Select an Answer.") {
  finishButton.run();
}
})
);

//Question 21
scenes.addScene(new Scene(windowWidth, windowHeight,
function() {

var left = windowWidth / 6;
var right = windowWidth - left;
var top = windowHeight / 8;
var bottom = windowHeight - top;

testBoard = new TextBoard(left, top, right - left/2, bottom - top/2);
testBoard.background = 60;
// descBoard.accent = 150;
testBoard.addText("Secchi Disk Test",240, 40, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addText("\nQuestion 21: " + Question21, 240, 18, "Helvetica", BOLD);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addText("\n ")
testBoard.addParagraph(1);
testBoard.addParagraph(1);
testBoard.addParagraph(1);

input = createElement('textarea');
input.position(right-750, bottom-260, );
input.size(600, 200);

  submitAnswer = new Button(right-110, bottom-265, 95, 50, "Submit",
    function() {
      // Button Selected
        Question21Answer = input.value();
        simInfo.run();
    },
    function() {}
  );

simInfo = new TextBox(right-650, bottom-20, 250, 50, Question21Answer, function(){});

finishButton = new Button(right-395, bottom-20, 95, 50, "Finish",
  function() {
    // Button Selected
      scenes.setScene(28);
      scenes.setup();
      input.hide();
      serverConnect();
      TestFinished = "Yes";
  },
  function() {}
);

backButton = new Button(right-20, top+20, 95, 50, "Go Back",
  function() {
    // Button Selected
      scenes.setScene(26);
      scenes.setup();
  },
  function() {}
);


introStartDysProd.fontSize = 14;
},
function() {
scenes.background(0);
testBoard.draw();
submitAnswer.run();
simInfo.run();
backButton.run();
if (Question21Answer != "Type an Answer.") {
  finishButton.run();
}
})
);

//Test Finished Screen
scenes.addScene(new Scene(windowWidth, windowHeight*2,
    function() {

      var left = windowWidth / 12;
      var right = windowWidth - left;
      var top = windowHeight / 12;
      var bottom = windowHeight*1.5;

      var Question1Conclusion, Question2Conclusion, Question3Conclusion, Question4Conclusion, Question5Conlusion, Question6Conclusion, Question7Conclusion, Question8Conclusion, Question9Conclusion, Question10Conclusion, Question11Conclusion, Question12Conclusion, Question13Conclusion, Question14Conclusion, Question15Conclusion, Question16Conclusion, Question17Conclusion, Question18Conclusion, Question19Conclusion, Question20Conclusion, Question21Conclusion;
      var numCorrect = 0;

      if (Question1Answer == Question1Key) {
        Question1Conclusion = "Correct";
<<<<<<< HEAD
        numCorrect++;
      } else {
        Question1Conclusion = "Incorrect";
=======
        createDiv().hide().id("question1").value([
            1,
            Question1Answer,
            1,
            Question1Key
          ]);
        numCorrect++;
      } else {
        Question1Conclusion = "Incorrect";
        createDiv().hide().id("question1").value([
            1,
            Question1Answer,
            0,
            Question1Key
          ]);
>>>>>>> 6c131a015c0415f575f997aa4aef40306e09fba1
      }

      if (Question2Answer == Question2Key) {
        Question2Conclusion = "Correct";
        createDiv().hide().id("question2").value([
            2,
            Question2Answer,
            1,
            Question2Key
          ]);
        numCorrect++;
      } else {
        Question2Conclusion = "Incorrect";
        createDiv().hide().id("question2").value([
            2,
            Question2Answer,
            0,
            Question2Key
          ]);
      }

      if (Question3Answer == Question3Key) {
        Question3Conclusion = "Correct";
        createDiv().hide().id("question3").value([
            3,
            Question3Answer,
            1,
            Question3Key
          ]);
        numCorrect++;
      } else {
        Question3Conclusion = "Incorrect";
        createDiv().hide().id("question3").value([
            3,
            Question3Answer,
            0,
            Question3Key
          ]);
      }

      if (Question4Answer == Question4Key) {
        Question4Conclusion = "Correct";
        createDiv().hide().id("question4").value([
            4,
            Question4Answer,
            1,
            Question4Key
          ]);
        numCorrect++;
      } else {
        Question4Conclusion = "Incorrect";
        createDiv().hide().id("question4").value([
            4,
            Question4Answer,
            0,
            Question4Key
          ]);
      }

      if (Question5Answer == Question5Key) {
        Question5Conclusion = "Correct";
        createDiv().hide().id("question5").value([
            5,
            Question5Answer,
            1,
            Question5Key
          ]);
        numCorrect++;
      } else {
        Question5Conclusion = "Incorrect";
        createDiv().hide().id("question5").value([
            5,
            Question5Answer,
            0,
            Question5Key
          ]);
      }

      if (Question6Answer == Question6Key) {
        Question6Conclusion = "Correct";
        createDiv().hide().id("question6").value([
            6,
            Question6Answer,
            1,
            Question6Key
          ]);
        numCorrect++;
      } else {
        Question6Conclusion = "Incorrect";
        createDiv().hide().id("question6").value([
            6,
            Question6Answer,
            0,
            Question6Key
          ]);
      }

      if (Question7Answer == Question7Key) {
        Question7Conclusion = "Correct";
        createDiv().hide().id("question7").value([
            7,
            Question7Answer,
            1,
            Question7Key
          ]);
        numCorrect++;
      } else {
        Question7Conclusion = "Incorrect";
        createDiv().hide().id("question7").value([
            7,
            Question7Answer,
            0,
            Question7Key
          ]);
      }

      if (Question8Answer == Question8Key) {
        Question8Conclusion = "Correct";
        createDiv().hide().id("question8").value([
            8,
            Question8Answer,
            1,
            Question8Key
          ]);
        numCorrect++;
      } else {
        Question8Conclusion = "Incorrect";
        createDiv().hide().id("question8").value([
            8,
            Question8Answer,
            0,
            Question8Key
          ]);
      }

      if (Question9Answer == Question9Key) {
        Question9Conclusion = "Correct";
        createDiv().hide().id("question9").value([
            9,
            Question9Answer,
            1,
            Question9Key
          ]);
        numCorrect++;
      } else {
        Question9Conclusion = "Incorrect";
        createDiv().hide().id("question9").value([
            9,
            Question9Answer,
            0,
            Question9Key
          ]);
      }

      if (Question10Answer == Question10Key) {
        Question10Conclusion = "Correct";
        createDiv().hide().id("question10").value([
            10,
            Question10Answer,
            1,
            Question10Key
          ]);
        numCorrect++;
      } else {
        Question10Conclusion = "Incorrect";
        createDiv().hide().id("question10").value([
            10,
            Question10Answer,
            0,
            Question10Key
          ]);
      }

      if (Question11Answer == Question11Key) {
        Question11Conclusion = "Correct";
        createDiv().hide().id("question11").value([
            11,
            Question11Answer,
            1,
            Question11Key
          ]);
        numCorrect++;
      } else {
        Question11Conclusion = "Incorrect";
        createDiv().hide().id("question11").value([
            11,
            Question11Answer,
            0,
            Question11Key
          ]);
      }

      if (Question12Answer == Question12Key) {
        Question12Conclusion = "Correct";
        createDiv().hide().id("question12").value([
            12,
            Question12Answer,
            1,
            Question12Key
          ]);
        numCorrect++;
      } else {
        Question12Conclusion = "Incorrect";
        createDiv().hide().id("question12").value([
            12,
            Question12Answer,
            0,
            Question12Key
          ]);
      }

      if (Question13Answer == Question13Key) {
        Question13Conclusion = "Correct";
        createDiv().hide().id("question13").value([
            13,
            Question13Answer,
            1,
            Question13Key
          ]);
        numCorrect++;
      } else {
        Question13Conclusion = "Incorrect";
        createDiv().hide().id("question13").value([
            13,
            Question13Answer,
            0,
            Question13Key
          ]);
      }

      if (Question14Answer == Question14Key) {
        Question14Conclusion = "Correct";
        createDiv().hide().id("question14").value([
            14,
            Question14Answer,
            1,
            Question14Key
          ]);
        numCorrect++;
      } else {
        Question14Conclusion = "Incorrect";
        createDiv().hide().id("question14").value([
            14,
            Question14Answer,
            0,
            Question14Key
          ]);
      }

      if (Question15Answer == Question15Key) {
        Question15Conclusion = "Correct";
        createDiv().hide().id("question15").value([
            15,
            Question15Answer,
            1,
            Question15Key
          ]);
        numCorrect++;
      } else {
        Question15Conclusion = "Incorrect";
        createDiv().hide().id("question15").value([
            15,
            Question15Answer,
            0,
            Question15Key
          ]);
      }

      if (Question16Answer == Question16Key) {
        Question16Conclusion = "Correct";
        createDiv().hide().id("question16").value([
            16,
            Question16Answer,
            1,
            Question16Key
          ]);
        numCorrect++;
      } else {
        Question16Conclusion = "Incorrect";
        createDiv().hide().id("question16").value([
            16,
            Question16Answer,
            0,
            Question16Key
          ]);
      }

      if (Question17Answer == Question17Key) {
<<<<<<< HEAD
        Question17Conclusion = "Correct";
        numCorrect++;
      } else {
        Question17Conclusion = "Incorrect";
=======
        Question1Conclusion = "Correct";
        createDiv().hide().id("question17").value([
            17,
            Question17Answer,
            1,
            Question17Key
          ]);
        numCorrect++;
      } else {
        Question1Conclusion = "Incorrect";
        createDiv().hide().id("question17").value([
            17,
            Question17Answer,
            0,
            Question17Key
          ]);
>>>>>>> 6c131a015c0415f575f997aa4aef40306e09fba1
      }

      if (Question18Answer == Question18Key) {
        Question18Conclusion = "Correct";
        createDiv().hide().id("question18").value([
            18,
            Question18Answer,
            1,
            Question18Key
          ]);
        numCorrect++;
      } else {
        Question18Conclusion = "Incorrect";
        createDiv().hide().id("question18").value([
            18,
            Question18Answer,
            0,
            Question18Key
          ]);
      }

      if (Question19Answer == Question19Key) {
        Question19Conclusion = "Correct";
        createDiv().hide().id("question19").value([
            19,
            Question19Answer,
            1,
            Question19Key
          ]);
        numCorrect++;
      } else {
        Question19Conclusion = "Incorrect";
        createDiv().hide().id("question19").value([
            19,
            Question19Answer,
            0,
            Question19Key
          ]);
      }

      if (Question20Answer == Question20Key) {
        Question20Conclusion = "Correct";
        createDiv().hide().id("question20").value([
            20,
            Question20Answer,
            1,
            Question20Key
          ]);
        numCorrect++;
      } else {
        Question20Conclusion = "Incorrect";
        createDiv().hide().id("question20").value([
            20,
            Question20Answer,
            0,
            Question20Key
          ]);
      }

      Question21Conclusion = Question21Answer
      createDiv().hide().id("question21").value([
            21,
            Question21Answer,
            1,
            "All Responses Correct"
          ]);

      TestResultsBoard = new TextBoard(left, top, right - left/2, bottom - top/2);
      TestResultsBoard.background = 60;
      // descBoard.accent = 150;
      TestResultsBoard.addText("You're finished!",240, 20, "Helvetica", BOLD);
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addText("\nHere's how you did.");
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addText("\nQuestion 1: " + Question1Conclusion + "\n ");
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addText("\nQuestion 2: " + Question2Conclusion+ "\n ");
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addText("\nQuestion 3: " + Question3Conclusion+ "\n ");
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addText("\nQuestion 4: " + Question4Conclusion+ "\n ");
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addText("\nQuestion 5: " + Question5Conclusion+ "\n ");
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addText("\nQuestion 6: " + Question6Conclusion+ "\n ");
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addText("\nQuestion 7: " + Question7Conclusion+ "\n ");
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addText("\nQuestion 8: " + Question8Conclusion+ "\n ");
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addText("\nQuestion 9: " + Question9Conclusion+ "\n ");
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addText("\nQuestion 10: " + Question10Conclusion+ "\n ");
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addText("\nQuestion 11: " + Question11Conclusion+ "\n ");
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addText("\nQuestion 12: " + Question12Conclusion+ "\n ");
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addText("\nQuestion 13: " + Question13Conclusion+ "\n ");
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addText("\nQuestion 14: " + Question14Conclusion+ "\n ");
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addText("\nQuestion 15: " + Question15Conclusion+ "\n ");
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addText("\nQuestion 16: " + Question16Conclusion+ "\n ");
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addText("\nQuestion 17: " + Question17Conclusion+ "\n ");
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addText("\nQuestion 18: " + Question18Conclusion+ "\n ");
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addText("\nQuestion 19: " + Question19Conclusion+ "\n ");
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addText("\nQuestion 20: " + Question20Conclusion+ "\n ");
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addParagraph(1);
      TestResultsBoard.addText("\nTotal Correct: " + numCorrect+ "\n ");

      startOver = new Button(right-110, bottom-65, 95, 50, "Return",
        function() {
          // Button Selected
            scenes.setScene(1);
            scenes.setup();
            TestFinished = "Yes";
        },
        function() {}
      );
      introStartDysProd.fontSize = 14;
    },
    function() {
      scenes.background(0);
      TestResultsBoard.draw();
      startOver.run();
    })
  );

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
    rect(850, 0, 40, 900);
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
