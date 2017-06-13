$(document).ready(function(){

    $("#file").change(function() {
        var files = this.files;
        var numFiles = files.length;
        var fileInputInfoContainer = $(".fileInputInfo:last");
        for(var i = 0; i < numFiles; i++) {
            console.log(files[i].name);
            if(i != 0) {
                var clone = fileInputInfoContainer.clone().prop('id', 'file'+i);
                // fileInputInfoContainer.after(clone);
                $(".multipleFiles-container").append(clone);
            }

            $("#file"+i+" .filename").val(files[i].name);
            $("#file"+i+" input[type=checkbox]").attr("name","tags["+i+"][]");
            console.log(i);

        }
    })

});