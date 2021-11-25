$(document).ready(function() {
  clockUpdate();
  setInterval(clockUpdate, 1000);

  $('input.box').keydown(function(e) {
    if ((e.which == 8 || e.which == 46) && $(this).val() =='') {
      $(this).prev('input').focus();
    }
    if ($(this).val() != '') {
      $(this).next('input').focus();
    }
  });

  var container = document.getElementsByClassName("box-container")[0];
  container.onkeyup = function(e) {
      var target = e.srcElement;
      var maxLength = parseInt(target.attributes["maxlength"].value, 10);
      var myLength = target.value.length;
      if (myLength >= maxLength) {
          var next = target;
          while (next = next.nextElementSibling) {
              if (next == null)
                  break;
              if (next.tagName.toLowerCase() == "input") {
                  next.focus();
                  break;
              }
          }
      }
  }

  // $('.qr-problem').click(function(e) {
  //   var self = $(this);
  //   $(this).toggleText('use QR Code?', 'having problem with QR Code?');
  // });

  // $.fn.extend({
  //   toggleText: function(a, b){
  //     if (this.text() == b) {
  //     } else {
  //       $('#id_number').val('').prop('disabled', false);
  //       $('#request-otp-btn').prop('disabled', false);
  //       $('input[name="otp_1"]').val(''); $('input[name="otp_2"]').val('');
  //       $('input[name="otp_3"]').val(''); $('input[name="otp_4"]').val('');
  //       $('input[name="otp_5"]').val('');
  //     }
  //     return this.text(this.text() == b ? a : b);
  //   }
  // });
})



function clockUpdate() {
  var d = new Date();
  function addZero(x) {
    if (x < 10) {
      return x = '0' + x;
    } else {
      return x;
    }
  }

  function twelveHour(x) {
    if (x > 12) {
      return x = x - 12;
    } else if (x == 0) {
      return x = 12;
    } else {
      return x;
    }
  }

  var h = addZero(twelveHour(d.getHours()));
  var m = addZero(d.getMinutes());
  var s = addZero(d.getSeconds());

  const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "June",
    "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
  ];
  const dayNames = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"];
  const dayDate = addZero(d.getDate());

  var dateTime = dayNames[d.getDay()] + ' ' + monthNames[d.getMonth()] + '-' + dayDate + '-' +  d.getFullYear();

  $('.digital-clock .date').text(dateTime);

  if (d.getHours() >= 12) {
  	$('.digital-clock .time').text(h + ':' + m + ':' + s);
    $('.digital-clock .digit').text(' pm');
  } else {
    $('.digital-clock .time').text(h + ':' + m + ':' + s);
    $('.digital-clock .digit').text(' am');
  }
}