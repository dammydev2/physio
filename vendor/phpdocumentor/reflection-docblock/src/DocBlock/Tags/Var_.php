<!-- code by webdevtrick ( https://webdevtrick.com ) -->
<html>
 
<head>
  <meta charset="utf-8" />
  <title>Autoplay Slider With HTML CSS JS | Webdevtrick.com</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" />
  <link rel="stylesheet" href="style.css" />
  <style type="text/css">
    /** code by webdevtrick ( https://webdevtrick.com ) **/
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0
}
body {
  margin-top: 5%;
}
ul, ol {
    list-style: none
}
.main {
    width: 75%;
    margin: auto;
    overflow: hidden;
    color: #fff
}
.slider {
    position: relative
}
.slider .slider-parent {
    padding: 0;
    display: flex
}
.slider .slider-parent .images-list {
    background-size: cover;
}
.slider .slider-parent .images-list:first-of-type, .slider .slider-parent .images-list:last-of-type {
    background-image: url(https://images.pexels.com/photos/462146/pexels-photo-462146.jpeg)
}
.slider .slider-parent .images-list:nth-of-type(2) {
    background-image: url(https://images.pexels.com/photos/268261/pexels-photo-268261.jpeg)
}
.slider .slider-parent .images-list:nth-of-type(3) {
    background-image: url(https://images.pexels.com/photos/1107717/pexels-photo-1107717.jpeg)
}
.slider .slider-parent .images-list:nth-of-type(4) {
    background-image: url(https://images.pexels.com/photos/248797/pexels-photo-248797.jpeg)
}
.slider .slider-parent .images-list:nth-of-type(5) {
    background-image: url(https://images.pexels.com/photos/462162/pexels-photo-462162.jpeg)
}
.slider .buttom-circles {
    position: absolute;
    transform: translate(-50%, -50%);
    left: 50%;
    bottom: 10px
}
.slider .buttom-circles .buttom-circles-list {
    display: inline-block
}
.slider .buttom-circles .buttom-circles-list:hover .fa:before, .slider .buttom-circles .buttom-circles-list.slider-active .fa:before {
    content: "\f111";
}
.slider > .fa {
    position: absolute;
    top: 45%
}
.slider > .fa:first-of-type { right: 5px}
.slider > .fa:last-of-type { left: 5px}
.slider .fa {
    text-shadow: 5px 5px 5px #555;
    cursor: pointer
}
 
@media(min-width: 100px) and (max-width: 767px) {
    .slider > .fa { font-size: 25px }
    .slider .buttom-circles .buttom-circles-list { visibility: hidden }
}
@media(min-width: 768px) and (max-width: 991px) { 
    .slider > .fa { font-size: 50px }
    .slider .buttom-circles .buttom-circles-list { visibility: hidden }
}
@media(min-width: 992px) and (max-width: 1199px) { 
    .slider > .fa { font-size: 75px }
    .slider .buttom-circles .buttom-circles-list { font-size: 17px }
}
@media(min-width: 1200px) { 
    .slider > .fa { font-size: 100px }
    .slider .buttom-circles .buttom-circles-list { font-size: 25px }
}
  </style>
</head>
 
<body>
  <div class="main">
    <div class="slider">
      <ul class="slider-parent">
        <li class="images-list" data-slider="1"></li>
        <li class="images-list" data-slider="2"></li>
        <li class="images-list" data-slider="3"></li>
        <li class="images-list" data-slider="4"></li>
        <li class="images-list" data-slider="5"></li>
        <li class="images-list" data-slider="6"></li>
      </ul>
      <ol class="buttom-circles">
        <li class="buttom-circles-list slider-active" data-slider="1"><i class="fa fa-circle-thin"></i></li>
