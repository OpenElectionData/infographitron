$(document).ready(function(){

    // Initial Counts
    var graphicsCount = 0;

    // Get URL and render the configuration panels for graphics and text
    var url = $("input[name='url']").val();
    graphicsConfigRender(url);

    // Graphic Configuration Renderer
    function graphicsConfigRender(url) {
        var urlArray = parseQuery(url);
        var html = "";
        var graphicsArray = [];

        // Count the number of graphics in the URL
        for(var key in urlArray) {
            if(key.indexOf('g[') == 0) {// or any other index.
                var id = key.substring(key.lastIndexOf("[")+1,key.lastIndexOf("]"));
                graphicsArray.push(id);
            }
        }

        // Render the listen item
        if(graphicsArray.length > 0) {
            var html = "";
            $.each(graphicsArray, function(index, item) {
                // Remove old g_x/g_y input fields
                $("input[name='g["+item+"]']").remove();
                $("input[name='g_x["+item+"]']").remove();
                $("input[name='g_y["+item+"]']").remove();
                html += "<li class='list-group-item' id='graphic"+item+"'>"+
                            "<div class='row'>"+
                                "<div class='col-md-9'>"+
                                    "<input type='hidden' name='g["+item+"]' value='"+urlArray['g['+item+']']+"' />"+
                                    "<img class='img-responsive center-block' src='http://oediblog.loc/infographitron2/uploads/graphics/"+urlArray['g['+item+']']+"' alt='"+urlArray['g['+item+']']+"' title='"+urlArray['g['+item+']']+"' />"+
                                "</div><div class='col-md-3'>"+
                                    "<a role='button' class='btn btn-danger btn-xs deleteGraphic' data-graphic-id='"+item+"'><span class='glyphicon glyphicon-remove' aria=hidden='true'></span></a>"+
                                "</div>"+
                            "</div><div class='row' style='margin-top:20px;'>"+
                                "<div class='col-md-6' style='padding-right:2px;'>"+
                                    "<div class='form-group'>"+
                                        "<input type='range' min='0' max='500' name='g_x["+item+"]' id='g_x["+item+"]' value='"+urlArray['g_x['+item+']']+"' />"+
                                    "</div>"+
                                "</div>"+
                                "<div class='col-md-6' style='padding-left:2px;'>"+
                                    "<div class='form-group'>"+
                                        "<input type='range' min='0' max='500' name='g_y["+item+"]' id='g_y["+item+"]' value='"+urlArray['g_y['+item+']']+"' />"+
                                    "</div>"+
                                "</div>"+
                            "</div>"+
                        "</li>";
            });

            html += "<li class='list-group-item bg-lightgrey'><button class='btn btn-primary' type='submit'>Set</button></li>";
            $("#collapse-step3").html(html);
            graphicsCount = graphicsArray.length;

            $('input[type="range"]').rangeslider({
                polyfill : false,
                onInit : function() {
                    this.output = $( '<div class="range-output" />' ).insertAfter( this.$range ).html( this.$element.val() );
                },
                onSlide : function( position, value ) {
                    this.output.html( value );
                }
            });
        }
        
    }

    // Delete/Remove Graphic
    $('#collapse-step3').on('click', '.deleteGraphic', function(){
        var value = $(this).data('graphic-id');
        $('#graphic'+value).remove();
        $('#foo').submit();
    });

    // Submit the form when we click on a background image or a graphic
    $('img.imgFormSubmit').click(function(){
        if($(this).data('background-id')) {
            var value = $(this).data('background-id');
            $('input[name=b]').val(value);
        }
        else if($(this).data('graphic-id')) {
            var value = $(this).data('graphic-id');
            $('#foo').append("<input type='hidden' name='g["+graphicsCount+"]' value='"+value+"' />");
            $('#foo').append("<input type='hidden' name='g_x["+graphicsCount+"]' value='150' />");
            $('#foo').append("<input type='hidden' name='g_y["+graphicsCount+"]' value='225' />");
            graphicsCount++;
        }
        $('#foo').submit();
    });

    // Add new text line
    $('#collapse-step4').on('click', '.addNew', function(){
        // Get the most recent list item
        var container = $('.textContainer:last');

        // Read the Number from that DIV's ID & increase it by 1
        var num = parseInt( $('.textContainer:last input').prop("id").match(/\d+/g), 10 );
        var newNum = num + 1;

        // Clone it and assign the new ID
        var clone = container.clone().prop('id', 'textContainer'+newNum);
        clone.html(function(i, oldHTML) {
            var re = new RegExp("["+num+"]","g");
            return oldHTML.replace(re, newNum);
        });
        container.after(clone);
        // Destroy & recreate ranges
        $('#textContainer'+newNum+' .rangeslider').remove();
        $('#textContainer'+newNum+' .range-output').remove();
        $('#textContainer'+newNum+' input[type="range"]').rangeslider('destroy');
        $('#textContainer'+newNum+' input[type="range"]').rangeslider({
            polyfill : false,
            onInit : function() {
                this.output = $( '<div class="range-output" />' ).insertAfter( this.$range ).html( this.$element.val() );
            },
            onSlide : function( position, value ) {
                this.output.html( value );
            }
        });

    });

    // Variable to hold request
    var request;

    // Bind to the submit event of our form
    $("#foo").submit(function(event){

        // Prevent default posting of form - put here to work in case of errors
        event.preventDefault();

        // Abort any pending request
        if (request) {
            request.abort();
        }
        // setup some local variables
        var $form = $(this);

        // Let's select and cache all the fields
        var $inputs = $form.find("input, select, button, textarea");

        // Serialize the data in the form
        var serializedData = $form.find("input, select, button, textarea").filter(function(index, element) {
            return $(element).val() != "";
        })
        .serialize();

        // Let's disable the inputs for the duration of the Ajax request.
        // Note: we disable elements AFTER the form data has been serialized.
        // Disabled form elements will not be serialized.
        $inputs.prop("disabled", true);

        // Fire off the request to /form.php
        request = $.ajax({
            url: "/custom/updateURL",
            type: "post",
            data: serializedData
        });

        // Callback handler that will be called on success
        request.done(function (response, textStatus, jqXHR){
            serializedData = serializedData.replace(/%5B/g, '[').replace(/%5D/g, ']');
            console.log(serializedData);

            // Update the image being rendered
            if($('#imgContainer img').length) {
                $('#imgContainer img').attr('src', 'http://oediblog.loc/infographitron2/infographic/showInfographic?'+serializedData);
            }
            else {
                $('#imgContainer').html('<img class="img-responsive center-block" style="width:600px;height:600px" src="http://oediblog.loc/infographitron2/infographic/showInfographic?'+serializedData+'" />');
            }

            // Update URL input & download link
            $("input[name='url']").val(serializedData);
            $(".downloadLink").attr('src', 'http://oediblog.loc/infographitron2/infographic/showInfographic?'+serializedData);

            // Update infographic title
            $("input[name='f_n']").val($("#f_n").val());
            $("#infographicTitle").html($("#f_n").val());

            // Render Graphics
            graphicsConfigRender(serializedData);

            // Log a message to the console
            console.log("Hooray, it worked!");
        });

        // Callback handler that will be called on failure
        request.fail(function (jqXHR, textStatus, errorThrown){
            // Log the error to the console
            console.error(
                "The following error occurred: "+
                textStatus, errorThrown
            );
        });

        // Callback handler that will be called regardless
        // if the request failed or succeeded
        request.always(function () {
            // Reenable the inputs
            $inputs.prop("disabled", false);
        });

    });


    // Parse Query String
    function parseQuery(qstr) {
        var query = {};
        var a = (qstr[0] === '?' ? qstr.substr(1) : qstr).split('&');
        for (var i = 0; i < a.length; i++) {
            var b = a[i].split('=');
            query[decodeURIComponent(b[0])] = decodeURIComponent(b[1] || '');
        }
        return query;
    }

});