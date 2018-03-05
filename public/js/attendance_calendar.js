$(function () {
    /* initialize the calendar
     -----------------------------------------------------------------*/
    var date = new Date();
    var d = date.getDate(),
        m = date.getMonth(),
        y = date.getFullYear();
    $('#calendar').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title'
      },
      buttonText: {
        today: 'today'
      },
      editable: true,
      dayClick: function(date, jsEvent, view) {
        window.location="/manage/developers/attendance/"+date.format();
      }
    });

  });