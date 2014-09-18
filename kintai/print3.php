<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script type="text/javascript">

    function jsonpCallback(data) { 
 
   alert('Latitude: ' + data.latitude + 
         '\nLongitude: ' + data.longitude + 
         '\nCountry: ' + data.address.country); 
		
		 var country=data.address.country;
          $.post(
            "controller/ctrl.time.php",
            { location: country },
             function(data) {
              alert(data);
            }

         );
  }
</script>
<script src="http://api.wipmania.com/jsonp?callback=jsonpCallback"
        type="text/javascript"></script>
