<?php
header("Content-type: text/css");

require_once("../../../../../wp-config.php");

$options = get_option('wp-avorg-multisite-catalog');
?>

/**
 * All of the CSS for your public-facing functionality should be
 * included in this file.
 */

.responsive-image {
  /* max-width: 100%; */
  width: 100%;
  height: 100%
}

.cell {
  position: relative;
  background-color: #ccc;
  height: 500px
}

.cell img {
  display: block
}

.backdrop {
  background: rgba(0,0,0,0.4);
  position: absolute;
  top: 0;
  width: 100%;
  height: 100%;
}

.duration {
  position: absolute;
  top: 0;
  right: 20px;
  color: #fff;
  font-size: 1.5em;
  font-weight: bold
}

.title, .subtitle {
  text-align: center;
  color: #fff !important;
  font-weight: bold;
  line-height: 1.3
}

.title {
  font-size: 2.678em;
  -webkit-box-orient: vertical;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  overflow: hidden
}

.subtitle {
  font-size: 2em;
  text-transform: uppercase;
  margin: .5em 0 !important
}

.inner-content {
  position: absolute;
  bottom: 0;
  width: 80%;
  margin: 0 10%
}

.show-more {
  border: 1px solid #ccc;
  width: 180px;
  margin: 20px auto;
  padding: 5px;
  text-align: center;
  text-transform: uppercase
}

@media screen and (min-width: 768px) {
  .grid {
    display: flex;
    flex-wrap: wrap;
    flex-direction: row
  }

  .cell {
    width: 50%
  }
}

/* overlay */
.overlay {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  background-color: <?php echo !empty($options['overlayBackgroundColor']) ? $options['overlayBackgroundColor'] : 'rgba(255,255,255,0.3)' ?>;
  overflow: hidden;
  width: 100%;
  height: 0;
  transition: .5s ease;
}

.cell:hover .overlay {
  height: <?php echo !empty($options['overlayHeight']) ? $options['overlayHeight'] : '100%' ?>;
}

.cell:hover .title,
.cell:hover .subtitle {
  visibility: hidden
}

.text {
  font-size: 1.5em;
  color: <?php echo !empty($options['descriptionColor']) ? $options['descriptionColor'] : '#fff' ?>;
  -webkit-box-orient: vertical;
  display: -webkit-box;
  -webkit-line-clamp: <?php echo !empty($options['descriptionLines']) ? $options['descriptionLines'] : '5' ?>;
  overflow: hidden;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%);
}

.text p {
  margin-bottom: 0 !important
}

/* responsive video */
.video-container {
  background: #ccc
}
.video-wrapper {
  max-width: 1100px;
  margin: 0 auto
}
.embed-responsive {
  position: relative;
  display: block;
  height: 0;
  padding: 0;
  overflow: hidden;
}
.embed-responsive.embed-responsive-16by9 {
  padding-bottom: 56.25%;
}
.embed-responsive-item {
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border: 0;
}