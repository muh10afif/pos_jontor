# Video2Image
## jQuery plugin for viewing/playing/stopping a webcam with image capture
This plugin requires [jQuery](https://jquery.com/)
###### Video capture may not work in all browsers

#### Do not try to run locally, browser permissions require running with web server only

### Package Manager
This plugin can be downloaded using [npm](https://www.npmjs.com/package/jquery-video2image)

`npm install jquery-video2image`


## Usage

**selector** should be a `<canvas>` element you have added to the page.

`<canvas id="photocanvas" width="320" height="240"></canvas>`

### Initialize
`$("#photocanvas").video2image();`

### Initialize with options
```
$(selector).video2image({
  width : 320,
  height : 240,
  autoplay : true,
  onsuccess : function () {},
  onerror: function () {}
});
```

### Find out if your browser supports this

Returns true or false

`var isSupported = $(selector).video2image('isSupported');`

### To get a PNG DataURL from the canvas

Returns an image/png in base64

`var dataurl = $(selector).video2image('capture');`   

### To Stop the video
`$(selector).video2image('stop');`

### To start/restart the video
`$(selector).video2image('start');`
