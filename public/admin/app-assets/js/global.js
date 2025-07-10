    /* Only Numbers */
    $(document).on('keyup', '.only_numbers', function(e)
    {
        if (/\D/g.test(this.value))
        {
            // Filter non-digits from input value.
            this.value = this.value.replace(/\D/g, '');
        }
    });

    /* Float Number */
    $(document).on("input", ".float_numbers", function() {
        this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1'); // Allow first dot in number
        //this.value = this.value.replace(/[^0-9.]+/g, '').replace(/^(\d+\.\d{2}).*$/g, '$1'); // allow number first dot and after dot two digit only
    });

    /* Upper Case Character */
    $('.uppercase').keyup(function(){
        this.value = this.value.toUpperCase();
    });

    /* All Chechkbox Check */
    $('.check_all').on('change', function(){
        $('.checkboxes:checkbox').prop('checked', $(this).prop('checked'));
    });

    $(document).on('change', '.checkboxes', function() {
        if ($(this).is(':checked'))
        {
            if ($('.checkboxes:checked').length == $('.checkboxes').length)
            {
                $('.check_all').prop('checked', true);
            }
            else
            {
                $('.check_all').prop('checked', false);
            }
        }
        else
        {
            $('.check_all').prop('checked', false);
        }
    });

    /* Status Change Code */
    $(document).on("change", ".on_off", function(){

        var status_id = $(this).val();
        var status = INACTIVE;

        if($(this).is(":checked")) {
            status=ACTIVE;
        }

        $.ajax({
            url:status_url,
            method:"GET",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data:{status_id:status_id,status:status},
            success:function(data){
                if (data.status==true) {
                    var success_html=SUCCESS_MESSAGE;
                    $(".flash_messages").html(success_html.replace("FLASH_MESSAGE", data.message));

                    if ($("html, body").animate({ scrollTop: 0 }, "slow")) {
                        setTimeout(function() { $('.alert').alert('close'); }, 5000);
                    }
                }
                else
                {
                    var error_html=ERROR_MESSAGE;
                    $(".flash_messages").html(error_html.replace("FLASH_MESSAGE", data.message));
                    if ($("html, body").animate({ scrollTop: 0 }, "slow")) {
                        setTimeout(function() { $('.alert').alert('close'); }, 5000);
                    }
                }
            }
        });
    });

    //================= MULTIPLE DELETE START=================//
    $(document).on('click','.delete_records',function(){
        var data_id=[];

        $('.checkboxes').each(function(){
            if ($(this).is(":checked")) {
                data_id.push($(this).val());
            }
        });

        if (data_id.length > 0)
        {
            if (confirm("are you sure you want to delete record ?")) {
                $.ajax({
                    url:delete_url,
                    method:"GET",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data:{data_id:JSON.stringify(data_id)},
                    success:function(data){
                        table_id.DataTable().ajax.reload();

                        var flash_html = ERROR_MESSAGE;
                        if (data.status==true) {
                            var flash_html=SUCCESS_MESSAGE;
                        }

                        $(".flash_messages").html(flash_html.replace("FLASH_MESSAGE", data.message));
                        if ($("html, body").animate({ scrollTop: 0 }, "slow")) {
                            setTimeout(function() { $('.alert').alert('close'); }, 5000);
                        }
                    }
                });
            }
        }
        else
        {
            var error_html=ERROR_MESSAGE;
            $(".flash_messages").html(error_html.replace("FLASH_MESSAGE", "Please select at least one record"));

            if ($("html, body").animate({ scrollTop: 0 }, "slow")) {
                setTimeout(function() { $('.alert').alert('close'); }, 5000);
            }
            return false;
        }
    });

    $.validator.addMethod("categoryImageExtension", function(value, element, param) {
        param = typeof param === "string" ? param.replace(/,/g, "|") : "jpg|jpeg|png";
        return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
    }, "Please Choose Only .jpg, .jpeg, .png file.");

    $.validator.addMethod("imageExtension", function(value, element, param) {
        param = typeof param === "string" ? param.replace(/,/g, "|") : "jpg|jpeg|png";
        return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
    }, "Please Choose Only .jpg, .jpeg, .png file Allowed.");
    
    $.validator.addMethod("greaterThanEqual", function (value, element, param) {
        var otherElement = $(param);
        if(otherElement.val() > 0){
            return parseInt(value, 10) >= parseInt(otherElement.val(), 10);
        }
        return true;
    }, "Please enter a greater or equal value.");
    
    $.validator.addMethod("lessThanEqual", function (value, element, param) {
        var otherElement = $(param);
        if(otherElement.val() > 0){
            return parseInt(value, 10) <= parseInt(otherElement.val(), 10);
        }
        return true;
    }, "Please enter a less or equal value.");

    $.validator.addMethod("variant_mrp", function(value, element) {
        
        var sp = $(element).parent().next().find(".variant_sp").val();
        if(sp > 0){
            return parseFloat(value, 10) >= parseFloat(sp, 10);
        }
        //console.log('variant_mrp => '+value+'/'+sp+'==>'+parseFloat(value, 10)+'/'+parseFloat(sp, 10));
        return true;
    }, "Please enter a greater or equal value then Selling Price.");

    $.validator.addMethod("variant_sp", function(value, element) {
        var mrp = $(element).parent().prev().find(".variant_mrp").val();
        if(mrp > 0){
            return parseFloat(value, 10) <= parseFloat(mrp, 10);
        }
        //console.log('variant_sp => '+value+'/'+mrp+'==>'+parseFloat(value, 10)+'/'+parseFloat(mrp, 10));
        return true;
    }, "Please enter a less or equal value then MRP.");

    $.validator.addMethod("passwordRegex", function(value, element) {
        var firstName = $('#first_name').val();
        var lastName = $('#last_name').val();
        var password = value;
    
        // Define the regex patterns
        var minLengthRegex = /^.{8,}$/; // At least 8 characters
        var uppercaseRegex = /[A-Z]/;  // At least one uppercase letter
        var lowercaseRegex = /[a-z]/;  // At least one lowercase letter
        var numericRegex = /[0-9]/;    // At least one numeric character
        var specialCharRegex = /[@$!%*?&]/; // At least one special character
    
        // Check if password contains first name or last name (case insensitive)
        var containsName = new RegExp(firstName, 'i').test(password) || new RegExp(lastName, 'i').test(password);
    
        // Validate the password against each rule
        if (!minLengthRegex.test(password)) {
            errorMessage = 'The password must be at least 8 characters long.';
        } else if (!uppercaseRegex.test(password)) {
            errorMessage = 'The password must contain at least one uppercase letter.';
        } else if (!lowercaseRegex.test(password)) {
            errorMessage = 'The password must contain at least one lowercase letter.';
        } else if (!numericRegex.test(password)) {
            errorMessage = 'The password must contain at least one numeric character.';
        } else if (!specialCharRegex.test(password)) {
            errorMessage = 'The password must contain at least one special character.';
        } else if (containsName) {
            errorMessage = 'The password should not contain your first or last name.';
        }
    
        // Display the error message or submit the form
        if (errorMessage) {
            return true;
        } else {
            $('#passwordError').text('');
            return false;
        }
    
    }, "The password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.");

    $('body').on('change', '.variantType', function () {
        var variantType = $(this).find("option:selected").text();
        var variantValue = $(this).parent().next().find(".variantValue").find("option:selected").text();

        //console.log($("#product_name").val() + ' '+variantType + ' '+variantValue);
        $(this).parent().parent().find('.productName').val($("#product_name").val() + ' '+variantType+ ' '+variantValue); // + ' '+variantValue
    })

    $('body').on('change', '.variantValue', function () {
        var variantValue = $(this).find("option:selected").text();
        var variantType = $(this).parent().prev().find(".variantType").find("option:selected").text();
        //console.log($("#product_name").val() + ' '+variantType + ' '+variantValue);
        $(this).parent().parent().find('.productName').val($("#product_name").val() + ' '+variantType + ' '+variantValue);
    })

    $('.select2_multiple').select2({
        tags: true,
        maximumSelectionLength: 25,
        tag_separators: [',', ' '],
        placeholder: "Please Select",
    });

    //Get State  & city & Area List Start
        function getState(countryId, stateId, cityId, areaId) {
            $.ajax({
                url: STATEURL,
                type: "POST",
                async: true,
                data: {'_token': token, 'country_id': countryId, 'state_id': stateId},
                beforeSend:function(){
                    $(".ajax_loader").show();
                    $('#state_id').html('<option value="">Loading...</option>');
                },
                success:function(response){
                    $("#state_id").html(response.data);

                    /*if(cityId != ''){
                        $("#city_id").empty();
                        $("#city_id").html('<option value="">--- Select ---</option>');
                        getCity(stateId, cityId, areaId);
                    }*/
                    $(".ajax_loader").hide();
                },
                error: function (xhr, exception, thrownError) {
                    var msg = ajaxErrorMsg(xhr, exception);
                }
            });
        }

        function getCity(stateId, cityId, areaId) {
            $.ajax({
                url: CITYURL,
                type: "POST",
                async: true,
                data: {'_token': token, 'state_id': stateId, 'city_id': cityId},
                beforeSend:function(){
                    $(".ajax_loader").show();
                    $('#city_id').html('<option value="">Loading...</option>');
                },
                success:function(response){
                    $('#city_id').html(response.data);

                    if(areaId != ''){
                        $("#area_id").empty();
                        $("#area_id").html('<option value="">--- Select ---</option>');
                        //getArea(cityId, areaId);
                    }
                    $(".ajax_loader").hide();
                },
                error: function (xhr, exception, thrownError) {
                    var msg = ajaxErrorMsg(xhr, exception);
                }
            });  
        }

        function getArea(distributorId, areaId) {
            $.ajax({
                url: AREAURL,
                type: "POST",
                async: true,
                data: {'_token': token, 'distributor_id': distributorId, 'area_id': areaId},
                beforeSend:function(){
                    $(".ajax_loader").show();
                    $('#area_id').html('<option value="">Loading...</option>');
                },
                success:function(response){
                    $('#area_id').html(response.data);
                    $(".ajax_loader").hide();
                },
                error: function (xhr, exception, thrownError) {
                    var msg = ajaxErrorMsg(xhr, exception);
                }
            });  
        }
    //Get State  & city & Area List End

    function getCategory(categoryTypeId, categoryId) {
        $.ajax({
            url: CATEGORYURL,
            type: "POST",
            async: true,
            data: {'_token': token, 'category_type_id': categoryTypeId, 'category_id': categoryId},
            beforeSend:function(){
                $(".ajax_loader").show();
                $('#category_id').html('<option value="">Loading...</option>');
            },
            success:function(response){
                $('#category_id').html(response.data);
                $(".ajax_loader").hide();
            },
            error: function (xhr, exception, thrownError) {
                var msg = ajaxErrorMsg(xhr, exception);
            }
        });  
    }

    function getDistributor(zoneId, distributorId, type) {
        $.ajax({
            url: DISRIBUTORURL,
            type: "POST",
            async: true,
            data: {'_token': token, 'zone_id': zoneId, 'distributor_id': distributorId, 'type': type},
            beforeSend:function(){
                $(".ajax_loader").show();
                $('#distributor_id').html('<option value="">Loading...</option>');
            },
            success:function(response){
                $('#distributor_id').html(response.data);
                $(".ajax_loader").hide();
            },
            error: function (xhr, exception, thrownError) {
                var msg = ajaxErrorMsg(xhr, exception);
            }
        });  
    }