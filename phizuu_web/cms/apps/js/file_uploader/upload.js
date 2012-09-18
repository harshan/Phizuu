
(function () {
    var input = document.getElementById("images"), 
    formdata = false;

    function showUploadedItem (source) {
        var list = document.getElementById("image-list"),
        li   = document.createElement("li"),
        img  = document.createElement("img");
        img.src = source;
        li.appendChild(img);
        list.appendChild(li);
    }   

    if (window.FormData) {
        formdata = new FormData();
        document.getElementById("btn").style.display = "none";
    }
	
    input.addEventListener("change", function (evt) {
        $('#response').show();
        document.getElementById("response").innerHTML = "<img src='../../../images/bigrotation2.gif'/>&nbsp;&nbsp;Loading..."
        var i = 0, len = this.files.length, img, reader, file;
	
        for ( ; i < len; i++ ) {
            file = this.files[i];
	
            if (!!file.type.match(/image.*/)) {
                if ( window.FileReader ) {
                    reader = new FileReader();
                    reader.onloadend = function (e) { 
                        showUploadedItem(e.target.result, file.fileName);
                    };
                    reader.readAsDataURL(file);
                }
                if (formdata) {
                    

                    formdata.append("images[]", file);
                }
            }	
        }
	
        if (formdata) {
            $.ajax({
                url: "upload.php",
                type: "POST",
                data: formdata,
                processData: false,
                contentType: false,
                success: function (res) {
                    res = jQuery.parseJSON(res);
                    //document.getElementById("response").innerHTML = res1.msg; 
                    
                    $('#response').html(res.msg);
                    setTimeout(function() {
                    $('#response').slideUp('slow');
      
                     }, 4000);
                    formdata = new FormData();
                    $('#list_1').append(res.html).fadeIn(500);
                    
                }
            },'json');
        }
    }, false);
}());
