<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<style>

body { font-family: sans-serif; }

.scene {
  width: 200px;
  height: 260px;
  border: 1px solid #CCC;
  margin: 40px 0;
  perspective: 600px;
}

.card {
  position: relative;
  width: 100%;
  height: 100%;
  cursor: pointer;
  transform-style: preserve-3d;
  transform-origin: center right;
  transition: transform 1s;
}

.card.is-flipped {
  transform: translateX(-100%) rotateY(-180deg);
}

.card__face {
  position: absolute;
  width: 100%;
  height: 100%;
  line-height: 260px;
  color: white;
  text-align: center;
  font-weight: bold;
  font-size: 40px;
  backface-visibility: hidden;
}

.card__face--front {
    margin-top: 20%;
    align-items: center;
    transform: rotate(270deg);
    width: 100%;
    object-fit: fill;
    height: 60%;
}

.card__face--back {
  background: blue;
  transform: rotateY(180deg);
}

</style>    


<div class="scene scene--card">
  <div class="card">
    <div class="card__face card__face--front"><img src=".\upguitpic\BTB.png" alt=""></div>
    <div class="card__face card__face--back">back</div>
  </div>
</div>
<p>Click card to flip.</p><script>
    var card = document.querySelector('.card');
card.addEventListener( 'click', function() {
  card.classList.toggle('is-flipped');
});
</script>
</body>
</html>