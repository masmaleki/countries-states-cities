
    function ajaxCall() {
        this.send = function(data, url, method, success, type) {
          type = type||'json';
          var successRes = function(data) {
              success(data);
          };

          var errorRes = function(e) {
              console.log(e);
              alert("Error found \nError Code: "+e.status+" \nError Message: "+e.statusText);
          };
            jQuery.ajax({
                url: url,
                type: method,
                data: data,
                success: successRes,
                error: errorRes,
                dataType: type,
                timeout: 60000
            });

          }

        }

function locationInfo() {
    var rootUrl = "api.php";
    var call = new ajaxCall();
    this.getCities = function(id) {
        jQuery(".cities option:gt(0)").remove();
        var url = rootUrl+'?type=getCities&stateId=' + id;
        var method = "post";
        var data = {};
        jQuery('.cities').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            jQuery('.cities').find("option:eq(0)").html("Select City");
            if(data.tp == 1){
               jQuery.each(data['result'], function(key, val) {
                    var option = jQuery('<option />');
                    option.attr('value', val).text(val);
                    jQuery('.cities').append(option);
                });
                jQuery(".cities").prop("disabled",false);
            }
            else{
                 alert(data.msg);
            }
        });
    };

    this.getStates = function(id) {
        jQuery(".states option:gt(0)").remove(); 
        jQuery(".cities option:gt(0)").remove(); 
        var url = rootUrl+'?type=getStates&countryId=' + id;
        var method = "post";
        var data = {};
        jQuery('.states').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            jQuery('.states').find("option:eq(0)").html("Select State");
            if(data.tp == 1){

                jQuery.each(data['result'], function(key, val) {
                    // create new option element
                    const option = document.createElement('option');
                    // set the text content of the option element
                    option.textContent = val;
                    // set the value of the option element
                    option.value = val;
                    // set the data-countryId attribute of the option element
                    option.setAttribute('data-state-id', key);
                    // append the option element to the select element
                    jQuery('.states').append(option);
                });
                jQuery(".states").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }
        }); 
    };

    this.getCountries = function() {
        var url = rootUrl+'?type=getCountries';
        var method = "post";
        var data = {};
        jQuery('.countries').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            jQuery('.countries').find("option:eq(0)").html("Select Country");
            if(data.tp == 1){
                jQuery.each(data['result'], function(key, val) {
                    // create new option element
                    const option = document.createElement('option');
                    // set the text content of the option element
                    option.textContent = val;
                    // set the value of the option element
                    option.value = val;
                    // set the data-countryId attribute of the option element
                    option.setAttribute('data-country-id', key);
                    // append the option element to the select element
                    jQuery('.countries').append(option);
                });
                jQuery(".countries").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }
        }); 
    };

}

jQuery(function() {
var loc = new locationInfo();
loc.getCountries();
 jQuery(".countries").on("change", function(ev) {
     var countryId = jQuery(this).find('option:selected').data('country-id');
      if(countryId != ''){
        loc.getStates(countryId);
        }
        else{
            jQuery(".states option:gt(0)").remove();
        }
    });
 jQuery(".states").on("change", function(ev) {
        var stateId = jQuery(this).data('state-id');
     var stateId = jQuery(this).find('option:selected').data('state-id');

     if(stateId != ''){
        loc.getCities(stateId);
        }
        else{
           jQuery(".cities option:gt(0)").remove();
        }
    });
});

