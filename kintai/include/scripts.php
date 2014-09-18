<script>
	$(function () {
		/** hiding scroll bar of .container tag **/
		var cw = $(".container").width();
		var sw = 17;
		var ncw = cw + sw;
		$(".container").width(ncw);

		$(".popup").click(function () {
			window.open($(this).attr("url"), '_blank', 'directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,width=500');
		});

		// *** time ***
		$(".increase_hrs").click(function () {
			var hrs = $(this).parent().find("input.hrs");
			(hrs.val() == "") ? hrs.val(0) : hrs.val();
			var temp = parseInt(hrs.val()) + 1;
			hrs.val(temp);
		});
		$(".decrease_hrs").click(function () {
			var hrs = $(this).parent().find("input.hrs");
			(hrs.val() == "") ? hrs.val(0) : hrs.val();
			var temp = parseInt(hrs.val()) - 1;
			if (temp < 0) {
				return false;
			}
			hrs.val(temp);
		});

		$(".increase_min").click(function () {
			var hrs = $(this).parent().find("input.min");
			(hrs.val() == "") ? hrs.val(0) : hrs.val();
			var temp = parseInt(hrs.val()) + 10;
			if (temp > 60) {
                hrs.val(59);
                return false;
			}
			hrs.val(temp);
		});
		$(".decrease_min").click(function () {
			var hrs = $(this).parent().find("input.min");
			(hrs.val() == "") ? hrs.val(0) : hrs.val();
			var temp = parseInt(hrs.val()) - 10;
			if (temp < 0) {
                hrs.val(0);
				return false;
			}
			hrs.val(temp);
		});

		$(".increase_sec").click(function () {
			var hrs = $(this).parent().find("input.sec");
			(hrs.val() == "") ? hrs.val(0) : hrs.val();
			var temp = parseInt(hrs.val()) + 10;
			if (temp > 60) {
                hrs.val(59);
				return false;
			}
			hrs.val(temp);
		});
		$(".decrease_sec").click(function () {
			var hrs = $(this).parent().find("input.sec");
			(hrs.val() == "") ? hrs.val(0) : hrs.val();
			var temp = parseInt(hrs.val()) - 10;
			if (temp < 0) {
                hrs.val(0);
				return false;
			}
			hrs.val(temp);
		});

        $("input.min").keyup(function() {
           if($(this).val() > 60) {
               $(this).val(59);
               return false;
           }else if($(this).val() < 0) {
               $(this).val(0);
               return false;
           }
        });

        $("input.sec").keyup(function() {
            if($(this).val() > 60) {
                $(this).val(59);
                return false;
            }else if($(this).val() < 0) {
                $(this).val(0);
                return false;
            }
        });

        $("input.hrs").keyup(function() {
            if($(this).val() < 0) {
                $(this).val(0);
                return false;
            }
        });
	});
	// *** calculate time on table ***
	function calculateTotal(tblid, colclass) {
		tblid = $(tblid);
		var totalsecs = 0;

		tblid.find("tr").each(function () {
			$(this).find("td" + colclass).each(function () {
				if ($(this).html() != "-") {
					var tmp = $(this).html().split(":");
					var h = parseInt(tmp[0] * 60 * 60);
					var m = parseInt(tmp[1] * 60);
					var s = parseInt(tmp[2]);
					totalsecs += h + m + s;
				}
			});
		});
		var fhour = Math.floor(totalsecs / 3600);
		totalsecs = totalsecs - fhour * 3600;
		var fmins = Math.floor(totalsecs / 60);
		var fsecs = totalsecs - (fmins * 60);
		var ottime = fhour + ":" + fmins + ":" + fsecs;

		return ottime;
	}

	function PrintDiv(divToPrint, stylelink) {
		var divToPrint = document.getElementById(divToPrint);
		var popupWin = window.open('', '_blank', 'width=300,height=300');
		var style = "<link href='" + stylelink + "' type='text/css' rel='stylesheet' />";
		popupWin.document.open();
		popupWin.document.write('<html><body onload="window.print()">' + style + divToPrint.innerHTML + '</html>');
		popupWin.document.close();
	}
</script>