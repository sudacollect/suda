# Datetimepicker for Bootstrap 4
[![Build Status](https://travis-ci.org/pingcheng/bootstrap4-datetimepicker.svg?branch=master)](https://travis-ci.org/pingcheng/bootstrap4-datetimepicker)

The js and css files had been changed to the suit Bootstrap v4.

Since Bootstrap 4 removed the glyphicon, I replaced all icons with font-awesome v4, please includes the font-awesome css as well.

You can override font icon class like this -
```js
// Using font-awesome 5 icons
  $.extend(true, $.fn.datetimepicker.defaults, {
    icons: {
      time: 'far fa-clock',
      date: 'far fa-calendar',
      up: 'fas fa-arrow-up',
      down: 'fas fa-arrow-down',
      previous: 'fas fa-chevron-left',
      next: 'fas fa-chevron-right',
      today: 'fas fa-calendar-check',
      clear: 'far fa-trash-alt',
      close: 'far fa-times-circle'
    }
  });
```
Click [here](http://eonasdan.github.io/bootstrap-datetimepicker/) for the official usage documentation.

## Install
```
npm install pc-bootstrap4-datetimepicker
```

## Changes

* JS DOM class name control
* CSS stylesheet
* Replaced glyphicon with font-awesome icons
