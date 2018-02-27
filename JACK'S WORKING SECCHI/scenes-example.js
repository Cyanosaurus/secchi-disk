var scenes = new SceneManager();

scenes.addScene(new Scene(400, 400,
  function() {
    // Setup
  },
  function() {
    // Draw
    this.background(0);
  }, 400, 400, 0, 0));

scenes.addScene(new Scene(400, 600,
  function() {
    // Setup
  },
  function() {
    // Draw
    scenes.background(0);
  });

function setup() {
  // Call setup on the current (first) scene
  scenes.setup();
}

function draw() {
  // Call draw on the current scene
  scenes.draw();
  // Example of switching scenes
  if (true) {
    scenes.nextScene();
    scenes.setup();
  }
}
