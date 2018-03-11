/**
 * Spencer Ward
 *
 * p5.js Framework Tools:
 * A set of tools for building projects in p5.js:
 *
 * SceneManager
 *  -> Scene
 * MouseRegion
 *  -> Button
 * dropShadow()
 *
 */


/**
 * SceneManager:
 * A manager for controlling multiple scenes.
 *
 * Create using
 * var sb = new SceneManager();
 *
 * Add a new scene using
 * sb.addScene(new Scene(width, height,
 *  function() {
 *    // Scene Setup
 *  },
 *  function() {
 *    // Scene Draw
 *  }, canvasWidth, canvasHeight, cornerX, cornerY);
 *
 * Call setup() on the current scene using
 * sb.setup();
 *
 * Call draw() on the current scene using
 * sb.draw();
 *
 * Change scenes with
 * sb.nextScene();
 * or
 * sb.setScene(sceneNum);       Scenes start at 1
 *
 * Get the width or the height of the current scene
 * sb.width
 * sb.height
 * and access the topleft and topright of the current scene with
 * sb.xzero
 * sb.yzero
 * sb.xmax
 * sb.ymax
 *
 */
function SceneManager() {
  this.sceneCount = 0;
  this.current = 0;
  this.currentScene = null;
  this.sceneArray = [];
  this.parent = true;

  // Dimensions of the current scene
  this.width;
  this.height;
  this.xzero;
  this.yzero;
  this.xmax;
  this.ymax;

  this.addScene = function(newScene) {
    this.sceneArray.push(newScene);
    this.sceneCount++;

    if (this.currentScene === null) {
      this.setScene(1);
    }
  }

  this.addChild = function(sceneNum, newScene) {
    this.sceneArray[sceneNum - 1].addChild(newScene);
  }

  // Move to the given scene (starting at 1)
  this.setScene = function(sceneNum) {
        this.current = sceneNum - 1;
        this.currentScene = this.sceneArray[this.current];
  }

  // Move to the next scene
  this.nextScene = function() {
    this.setScene((this.current + 1) % this.sceneCount + 1);
  }

  // Get scene number
  this.sceneIndex = function() {
    return this.current + 1;
  }

  this.setup = function() {
    this.width = this.currentScene.width;
    this.height = this.currentScene.height;
    this.xzero = this.currentScene.xzero;
    this.xmax = this.xzero + this.width;
    this.yzero = this.currentScene.yzero;
    this.ymax = this.yzero + this.height;
    createCanvas(this.currentScene.cWidth, this.currentScene.cHeight);
    this.currentScene.setup();
    for (var i = 0; i < this.currentScene.children.length; i++) {
      this.width = this.currentScene.children[i].width;
      this.height = this.currentScene.children[i].height;
      this.xzero = this.currentScene.children[i].xzero;
      this.xmax = this.currentScene.children[i].xzero + this.width;
      this.yzero = this.currentScene.children[i].yzero;
      this.ymax = this.currentScene.children[i].yzero + this.height;
      this.currentScene.children[i].setup();
    }
  }

  this.draw = function() {
    this.xzero = this.currentScene.xzero;
    this.xmax = this.xzero + this.width;
    this.yzero = this.currentScene.yzero;
    this.ymax = this.yzero + this.height;
    this.parent = true;
    this.currentScene.draw();
    for (var i = 0; i < this.currentScene.children.length; i++) {
      this.parent = false;
      this.xzero = this.currentScene.children[i].xzero;
      this.xmax = this.currentScene.children[i].xzero + this.currentScene.children[i].width;
      this.yzero = this.currentScene.children[i].yzero;
      this.ymax = this.currentScene.children[i].yzero + this.currentScene.children[i].height;
      this.currentScene.children[i].draw();
    }
  }

  this.background = function() {
    if (this.parent) {
      background(Array.prototype.slice.call(arguments));
    } else {
      push();
      fill(Array.prototype.slice.call(arguments));
      rectMode(CORNERS);
      rect(this.xzero, this.yzero, this.xmax, this.ymax);
      pop();
    }
  }
}

/**
 * Scene:
 * An isolated frame of elements and actions.
 * Equivalent to a fresh project on a new canvas.
 * Use sceneObject.setup() to initialize
 *
 */
function Scene(width, height, setup, draw, cWidth, cHeight, xzero, yzero) {
  // Check if parameters were passed, fills defaults otherwise
  if (width === undefined) {
    this.width = 400;
    this.height = 400;
  } else {
    this.width = width;
    this.height = height;
  }
  if (setup === undefined) {
    this.setup = function() {
      console.log("Scene setup function has not been initialized!");
    }
    this.draw = function() {
      background(0);
    }
  } else {
    this.setup = setup;
    this.draw = draw;
  }
  // If parent, setup parent fields
  if (cWidth === undefined) {
    this.parent = false;
    this.children = [];
    this.cWidth = this.width;
    this.cHeight = this.height;
  } else {
    this.parent = false;
    this.children = [];
    this.cWidth = cWidth;
    this.cHeight = cHeight;
  }
  if (xzero === undefined) {
    this.xzero = 0;
    this.yzero = 0;
  } else {
    this.xzero = xzero;
    this.yzero = yzero;
  }

  this.addChild = function(newScene) {
    this.children.push(newScene);
  }
}

/**
 * MouseRegion:
 * A rectangular zone of the screen that supplies methods for basic mouse
 * interaction.
 */
function MouseRegion(cornerX, cornerY, width, height) {
  this.leftX = cornerX;
  this.topY = cornerY;
  this.rightX = cornerX + width;
  this.bottomY = cornerY + height;
  this.mouseClicked = false;

  this.checkHover = function() {
    if (mouseX > this.leftX && mouseX < this.rightX
        && mouseY > this.topY && mouseY < this.bottomY) {
          return true;
    }

    return false;
  }

  this.checkClick = function() {
    if (this.mouseClicked && !mouseIsPressed) {
      this.mouseClicked = false;
      return false;
    }

    if (!this.mouseClicked && mouseIsPressed) {
      this.mouseClicked = true;
      return true;
    }
  }
}

function infoBox(cornerX, cornerY, width, height)
{
  this.position = createVector(cornerX, cornerY);
  this.width = width;
  this.height = height;
  this.label = label;
  this.region = new MouseRegion(cornerX, cornerY, width, height);

  // Base background color
  this.color = [100, 100, 100];
  // Accent bar adjustment
  this.accent = [0, 0, 0];
  // Accent bar adjsutment for selected state
  this.selectedColor = [-80, -80, -80];
  // Button adjustment for highlighted state
  this.highlightColor = [-20, -20, -20];
  // Color for text
  this.fontColor = [255, 255, 255];

  this.selected = false;
  this.highlght = false;

  this.fontSize = 14;
}

/**
 * Button:
 * A simple callback driven button for program interaction
 *
 */
 function Button(cornerX, cornerY, width, height, label, callbackSelected, callbackUnselected)
 {
   this.position = createVector(cornerX, cornerY);
   this.width = width;
   this.height = height;
   this.label = label;
   this.region = new MouseRegion(cornerX, cornerY, width, height);

   // Base background color
   this.color = [100, 200, 300];
   // Accent bar adjustment
   this.accent = [-40, -40, -40];
   // Accent bar adjsutment for selected state
   this.selectedColor = [-80, -80, -80];
   // Button adjustment for highlighted state
   this.highlightColor = [-20, -20, -20];
   // Color for text
   this.fontColor = [255, 255, 255];

   this.selected = false;
   this.highlght = false;

   this.callbackSelected = callbackSelected;
   this.callbackUnselected = callbackUnselected;

   // Calculate font size
   // var fontSize = 10;
   // var errorPerc = 10.0;
   // push();
   // while (abs(errorPerc) > 0.05) {
   //   textSize(fontSize);
   //   errorPerc = (textWidth(label) - this.width * 0.6) / (this.width * 0.6);
   //   console.log(errorPerc);
   //   if (errorPerc > 0) {
   //     fontSize--;
   //   } else {
   //     fontSize++;
   //   }
   //   console.log(fontSize);
   // }
   // pop();
   this.fontSize = 14;

   this.run = function() {
     // Check if the mouse is currently hovering over the button

     var mouseClicked = this.region.checkClick();

     if (this.region.checkHover()) {
       this.highlight = true;
       // Check if mouse has already been pressed
       if (mouseClicked) {
         this.onClick();
       }
     } else {
       this.highlight = false;
     }

     this.draw();
   }

   this.onClick = function() {
     if (!this.selected) {
       this.selected = true;
       this.callbackSelected();
     } else {
     this.selected = false;
     this.callbackUnselected();
   } }

   this.draw = function() {
     adjustColor = function(c, adjustment) {
       c[0] += adjustment[0];
       c[1] += adjustment[1];
       c[2] += adjustment[2];
     }
     var c = [this.color[0], this.color[1], this.color[2]];

     push();
     strokeWeight(0);
     // Draw main box
     if (this.highlight) {
       dropShadow(2, 2, 4, "rgba(0, 0, 0, 0.2)");
       adjustColor(c, this.highlightColor);
     } else {
       dropShadow(1, 1, 2, "rgba(0, 0, 0, 0.2)");
     }

     fill(c[0], c[1], c[2]);
     rect(this.position.x, this.position.y, this.width, this.height);
     dropShadow(0, 0, 0, 0);

     // Draw accent bar
     if (this.selected) {
       adjustColor(c, this.selectedColor);
     }

     adjustColor(c, this.accent);
     fill(c[0], c[1], c[2]);
     rectMode(CORNERS);
     rect(this.position.x, this.position.y + this.height * 0.85,
           this.position.x + this.width, this.position.y + this.height);

     // Draw text
     textFont("Helvetica");
     textStyle(BOLD);
     textSize(this.fontSize);
     fill(this.fontColor);
     textAlign(CENTER, CENTER);
     text(label, this.position.x + this.width / 2, this.position.y + this.height * 0.45);

     pop();
   }
 }

function Button2(cornerX, cornerY, width, height, label, callbackSelected, callbackUnselected)
{
  this.position = createVector(cornerX, cornerY);
  this.width = width;
  this.height = height;
  this.label = label;
  this.region = new MouseRegion(cornerX, cornerY, width, height);

  // Base background color
  this.color = [100, 200, 300];
  // Accent bar adjustment
  this.accent = [-40, -40, -40];
  // Accent bar adjsutment for selected state
  this.selectedColor = [-80, -80, -80];
  // Button adjustment for highlighted state
  this.highlightColor = [-20, -20, -20];
  // Color for text
  this.fontColor = [255, 255, 255];

  this.selected = false;
  this.highlght = false;

  this.callbackSelected = callbackSelected;
  this.callbackUnselected = callbackUnselected;

  // Calculate font size
  // var fontSize = 10;
  // var errorPerc = 10.0;
  // push();
  // while (abs(errorPerc) > 0.05) {
  //   textSize(fontSize);
  //   errorPerc = (textWidth(label) - this.width * 0.6) / (this.width * 0.6);
  //   console.log(errorPerc);
  //   if (errorPerc > 0) {
  //     fontSize--;
  //   } else {
  //     fontSize++;
  //   }
  //   console.log(fontSize);
  // }
  // pop();
  this.fontSize = 14;

  this.run = function() {
    // Check if the mouse is currently hovering over the button

    var mouseClicked = this.region.checkClick();

    if (this.region.checkHover()) {
      this.highlight = true;
      // Check if mouse has already been pressed
      if (mouseClicked) {
        this.onClick();
      }
    } else {
      this.highlight = false;
    }

    this.draw();
  }

  this.onClick = function() {
    if (!this.selected) {
      this.selected = true;
      this.callbackSelected();
    }
    this.selected = false;
    this.callbackUnselected();
  }

  this.draw = function() {
    adjustColor = function(c, adjustment) {
      c[0] += adjustment[0];
      c[1] += adjustment[1];
      c[2] += adjustment[2];
    }
    var c = [this.color[0], this.color[1], this.color[2]];

    push();
    strokeWeight(0);
    // Draw main box
    if (this.highlight) {
      dropShadow(2, 2, 4, "rgba(0, 0, 0, 0.2)");
      adjustColor(c, this.highlightColor);
    } else {
      dropShadow(1, 1, 2, "rgba(0, 0, 0, 0.2)");
    }

    fill(c[0], c[1], c[2]);
    rect(this.position.x, this.position.y, this.width, this.height);
    dropShadow(0, 0, 0, 0);

    // Draw accent bar
    if (this.selected) {
      adjustColor(c, this.selectedColor);
    }

    adjustColor(c, this.accent);
    fill(c[0], c[1], c[2]);
    rectMode(CORNERS);
    rect(this.position.x, this.position.y + this.height * 0.85,
          this.position.x + this.width, this.position.y + this.height);

    // Draw text
    textFont("Helvetica");
    textStyle(BOLD);
    textSize(this.fontSize);
    fill(this.fontColor);
    textAlign(CENTER, CENTER);
    text(label, this.position.x + this.width / 2, this.position.y + this.height * 0.45);

    pop();
  }
}

function TextBox(cornerX, cornerY, width, height, label, callbackSelected, callbackUnselected)
{
  this.position = createVector(cornerX, cornerY);
  this.width = width;
  this.height = height;
  this.label = label;
  this.region = new MouseRegion(cornerX, cornerY, width, height);

  // Base background color
  this.color = [60, 60, 60];
  // Accent bar adjustment
  this.accent = [-80, -80, -80];
  // Accent bar adjsutment for selected state
  this.selectedColor = [-80, -80, -80];
  // Button adjustment for highlighted state
  this.highlightColor = [-20, -20, -20];
  // Color for text
  this.fontColor = [255, 255, 255];

  this.selected = false;
  this.highlght = false;

  this.callbackSelected = callbackSelected;
  this.callbackUnselected = callbackUnselected;

  // Calculate font size
  // var fontSize = 10;
  // var errorPerc = 10.0;
  // push();
  // while (abs(errorPerc) > 0.05) {
  //   textSize(fontSize);
  //   errorPerc = (textWidth(label) - this.width * 0.6) / (this.width * 0.6);
  //   console.log(errorPerc);
  //   if (errorPerc > 0) {
  //     fontSize--;
  //   } else {
  //     fontSize++;
  //   }
  //   console.log(fontSize);
  // }
  // pop();
  this.fontSize = 14;

  this.run = function() {
    // Check if the mouse is currently hovering over the button

    var mouseClicked = this.region.checkClick();

    // if (this.region.checkHover()) {
    //   this.highlight = true;
    //   // Check if mouse has already been pressed
    //   if (mouseClicked) {
    //     this.onClick();
    //   }
    // } else {
    //   this.highlight = false;
    // }

    this.draw();
  }

  // this.onClick = function() {
  //   if (!this.selected) {
  //     this.selected = true;
  //     this.callbackSelected();
  //   } else {
  //     this.selected = false;
  //     this.callbackUnselected();
  //   }
  // }

  this.draw = function() {
    adjustColor = function(c, adjustment) {
      c[0] += adjustment[0];
      c[1] += adjustment[1];
      c[2] += adjustment[2];
    }
    var c = [this.color[0], this.color[1], this.color[2]];

    push();
    strokeWeight(0);
    // Draw main box
    if (this.highlight) {
      dropShadow(2, 2, 4, "rgba(0, 0, 0, 0.2)");
      adjustColor(c, this.highlightColor);
    } else {
      dropShadow(1, 1, 2, "rgba(0, 0, 0, 0.2)");
    }

    fill(c[0], c[1], c[2]);
    rect(this.position.x, this.position.y, this.width, this.height);
    dropShadow(0, 0, 0, 0);

    // // Draw accent bar
    // if (this.selected) {
    //   adjustColor(c, this.selectedColor);
    // }
    //
    // adjustColor(c, this.accent);
    // fill(c[0], c[1], c[2]);
    // rectMode(CORNERS);
    // rect(this.position.x, this.position.y + this.height * 0.85,
    //       this.position.x + this.width, this.position.y + this.height);

    // Draw text
    textFont("Helvetica");
    textStyle(BOLD);
    textSize(this.fontSize);
    fill(this.fontColor);
    textAlign(CENTER, CENTER);
    text(label, this.position.x + this.width / 2, this.position.y + this.height * 0.45);

    pop();
  }
}

function TextBoxBackground(cornerX, cornerY, width, height, label, callbackSelected, callbackUnselected)
{
  this.position = createVector(cornerX, cornerY);
  this.width = width;
  this.height = height;
  this.label = label;
  this.region = new MouseRegion(cornerX, cornerY, width, height);

  // Base background color
  this.color = [160, 160, 160];
  // Accent bar adjustment
  this.accent = [-160, -160, -160];
  // Accent bar adjsutment for selected state
  this.selectedColor = [-80, -80, -80];
  // Button adjustment for highlighted state
  this.highlightColor = [-20, -20, -20];
  // Color for text
  this.fontColor = [255, 255, 255];

  this.selected = false;
  this.highlght = false;

  this.callbackSelected = callbackSelected;
  this.callbackUnselected = callbackUnselected;

  // Calculate font size
  // var fontSize = 10;
  // var errorPerc = 10.0;
  // push();
  // while (abs(errorPerc) > 0.05) {
  //   textSize(fontSize);
  //   errorPerc = (textWidth(label) - this.width * 0.6) / (this.width * 0.6);
  //   console.log(errorPerc);
  //   if (errorPerc > 0) {
  //     fontSize--;
  //   } else {
  //     fontSize++;
  //   }
  //   console.log(fontSize);
  // }
  // pop();
  this.fontSize = 14;

  this.run = function() {
    // Check if the mouse is currently hovering over the button

    var mouseClicked = this.region.checkClick();

    // if (this.region.checkHover()) {
    //   this.highlight = true;
    //   // Check if mouse has already been pressed
    //   if (mouseClicked) {
    //     this.onClick();
    //   }
    // } else {
    //   this.highlight = false;
    // }

    this.draw();
  }

  // this.onClick = function() {
  //   if (!this.selected) {
  //     this.selected = true;
  //     this.callbackSelected();
  //   } else {
  //     this.selected = false;
  //     this.callbackUnselected();
  //   }
  // }

  this.draw = function() {
    adjustColor = function(c, adjustment) {
      c[0] += adjustment[0];
      c[1] += adjustment[1];
      c[2] += adjustment[2];
    }
    var c = [this.color[0], this.color[1], this.color[2]];

    push();
    strokeWeight(0);
    // Draw main box
    if (this.highlight) {
      dropShadow(2, 2, 4, "rgba(0, 0, 0, 0.2)");
      adjustColor(c, this.highlightColor);
    } else {
      dropShadow(1, 1, 2, "rgba(0, 0, 0, 0.2)");
    }

    fill(c[0], c[1], c[2]);
    rect(this.position.x, this.position.y, this.width, this.height);
    dropShadow(0, 0, 0, 0);

    // // Draw accent bar
    // if (this.selected) {
    //   adjustColor(c, this.selectedColor);
    // }
    //
    // adjustColor(c, this.accent);
    // fill(c[0], c[1], c[2]);
    // rectMode(CORNERS);
    // rect(this.position.x, this.position.y + this.height * 0.85,
    //       this.position.x + this.width, this.position.y + this.height);

    // Draw text
    textFont("Helvetica");
    textStyle(BOLD);
    textSize(this.fontSize);
    fill(this.fontColor);
    textAlign(CENTER, CENTER);
    text(label, this.position.x + this.width / 2, this.position.y + this.height * 0.45);

    pop();
  }
}

function TextChunk(text, fontColor, fontSize, fontName, fontStyle) {
  this.text = text;
  this.fontSize = fontSize;
  this.fontName = fontName;
  this.fontStyle = fontStyle;
  this.fontColor = fontColor;
}

function TextBoard(cornerX, cornerY, width, height) {
  this.position = createVector(cornerX, cornerY);
  this.width = width;
  this.height = height;
  // Defines default font color, size, name, and style
  this.text = [new TextChunk("", "#000000", 12, "Helvetica", BOLD, LEFT)];

  this.background = "#FFFFFF";
  this.accent = "#CCCCCC"

  this.addText = function(text, fontColor, fontSize, fontName, fontStyle) {
    newText = new TextChunk(text, fontColor, fontSize, fontName, fontStyle);
    if (fontSize === undefined) {
      lastText = this.text.pop();
      newText.fontSize = lastText.fontSize;
      newText.fontName = lastText.fontName;
      newText.fontStyle = lastText.fontStyle;
      if (fontColor === undefined) {
        newText.fontColor = lastText.fontColor;
      }
      this.text.push(lastText);
    }
    this.text.push(newText);
  }

  this.addParagraph = function() {
    this.addText("\n");
  }

  this.addTab = function() {
    this.addText("\t");
  }

  this.empty = function() {
    this.text = [new TextChunk("", "#000000", 12, "Helvetica", BOLD)];
  }

  this.draw = function() {
    push();
    dropShadow(2, 3, 4, "rgba(0, 0, 0, 0.2)");
    fill(this.background);
    strokeWeight(0);
    rect(this.position.x, this.position.y, this.width, this.height);
    dropShadow(0, 0, 0, 0);
    // fill(this.accent);      Turned accents off. Looks better
    rect(this.position.x, this.position.y, this.width, this.height / 8);

    textCursor = createVector(this.position.x + 10, this.position.y + this.height / 8 + 20, 0);

    for (var i = 0; i < this.text.length; i++) {
      textBlock = this.text[i];
      if (textBlock.text.valueOf() == new String("\n").valueOf()) {
        textCursor.x = this.position.x + 10;
        textCursor.y += 15;
      } else if (textBlock.text.valueOf() == new String("\t").valueOf()) {
        textCursor.x = Math.round((textCursor.x + 105) / 105) * 105;
      } else {
        fill(textBlock.fontColor);
        textSize(textBlock.fontSize);
        textFont(textBlock.fontName);
        textStyle(textBlock.fontStyle);
        text(textBlock.text, textCursor.x, textCursor.y);
        textCursor.x += textWidth(textBlock.text);
      }
    }
    pop();
  }

}

/**
 * dropShadow:
 * Basic function to change the global dropShadow on newly created elements
 *
 * offsetX/offsetY: The amount to offset the shadow from the element itself
 * blur: The blur radius to use for the shadow
 * color: The color of the drop shadow, including alpha
 *
 * Use before creating an element and reset afterwards
 *
 * Example usage:
 * dropShadow(2, 2, 4, "rbga(0, 0, 0, 0.5)");
 * ellipse(50, 50, 20, 20);
 * dropShadow(0, 0, 0, 0);
 *
 * Note: Strokes are drawn as separate HTML5 elements from their shapes, meaning
 *  they cast a shadow on whatever shape they're drawn around.
 *  This can be avoided by drawing the stroke separately from the shape itself.
 *
 */
function dropShadow(offsetX, offsetY, blur, color) {
  drawingContext.shadowOffsetX = offsetX;
  drawingContext.shadowOffsetY = offsetY;
  drawingContext.shadowBlur = blur;
  drawingContext.shadowColor = color;
}
