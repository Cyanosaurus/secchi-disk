var ball;
// var wallBounces;
// var constrainBounce = 1;

function Ball(x, y, vx, vy) {
  this.pos;
  this.vel;
  this.grav = createVector(0,0.3);
  this.rad = 10;

  if (x instanceof p5.Vector) {
    this.pos = x;
    if (y instanceof p5.Vector) {
      this.vel = y;
    }
  } else {
    this.pos = createVector(x, y);
    if (vx instanceof p5.Vector) {
      this.vel = vx.copy();
    } else {
      this.vel = createVector(vx, vy);
    }
  }

  this.update = function() {

    if ((this.pos.x < this.rad) && this.vel.x < 0
          || (this.pos.x > width - this.rad) && this.vel.x > 0) {
      this.vel.x *= -0.95;

      // Random thing to switch scenes
      // wallBounces++;
    }
    if ((this.pos.y > height - this.rad) && this.vel.y > 0) {
      this.vel.y *= -0.95;
      // constrainBounce = (constrainBounce + 1) % 2;
    }
    // if ((this.pos.y < 0 + this.rad) && constrainBounce == 0) {
    //   this.vel.y *= -0.95;
    //   constrainBounce += 2;
    // }

    this.vel.add(this.grav);
    this.pos.add(this.vel);
    // Keep the ball from falling through the floor
    this.pos.y = constrain(this.pos.y, 0, height - this.rad + 1);
  }

  this.draw = function() {
    ellipse(this.pos.x, this.pos.y, this.rad * 2, this.rad * 2);
  }
}

function setup() {
  // Call setup on the current (first) scene
  createCanvas(400, 600);
  noStroke();
  fill(50, 200, 50);

  ball = new Ball(createVector(40, 40), createVector(2, 2));
  // wallBounces = 0;
}

function draw() {
  // Call draw on the current scene
  ball.update();
  background(50);
  ball.draw();

  // Example of switching scenes
  // if (wallBounces == 1) {
  //   wallBounces = 0;
  //   scenes.nextScene();
  //   scenes.setup();
  // }
}
