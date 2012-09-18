<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>Test Image Chooser</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" type="text/css" href="../../../css/styles.css"/>
    <link type="text/css" href="../../../common/jquery/css/custom-theme/jquery-ui-1.7.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../../js/jquery-1.3.2.min.js"></script>
    <script type="text/javascript" src="../../../js/jquery-ui-1.7.2.custom.min.js"></script>
    <script type="text/javascript" src="../../../common/jquery.imagechooser.js"></script>
    <script type="text/javascript" src="../../../common/jquery.Jcrop.min.js"></script>
    
    <script type="text/javascript">
        $(document).ready(function() {
            $('#imageSelect1').imagechooser({
                method:'bank',
                callback: function(image, thumb) {
                    $('#imageSelect1').imagechooserLoadImage(image);
                }
            });

             $('#imageSelect2').imagechooser({
                 method:'upload',
                callback: function(image, thumb) {
                    $('#imageSelect2').imagechooserLoadImage(image);
                },
                image_size:{width:200, height: 300}
            });

            
        });
                 
    </script>

  </head>
  <body>
      <img id="imageSelect1" src="" width="200" height="300"/>
      <img id="imageSelect2" src="" width="200" height="300"/>
  </body>
</html>
