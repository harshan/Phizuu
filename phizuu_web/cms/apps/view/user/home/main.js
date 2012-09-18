/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(function(){
   controller.init(); 
});

var controller = {
    init: function() {
	var homeImage = new HomeImage('http://localhost/phizuu_web/cms/apps/application_dirs/5033/HomeImage2.jpg', '', "moduleName", "imageLink");
	$('#list_1').append(homeImage.htmlObj);
	var homeImage = new HomeImage('http://localhost/phizuu_web/cms/apps/application_dirs/5033/HomeImage2.jpg', '', "moduleName", "imageLink");
	$('#list_1').append(homeImage.htmlObj);
    }
}

function HomeImage(imageUrl, imagePath, moduleName, imageLink) {
    this.imageUrl = imageUrl;
    this.imagePath = imagePath;
    this.moduleName = moduleName;
    this.imageLink = imageLink;
    
    this.htmlObj = $(
    "<li>"+
    "<img class='image' src='"+this.imageUrl+"'/>"+
    "<div class='module'>Module</div>"+
    "<div class='link'>Link</div>"+
    "<div class='hover'>Hover Area</div>"+
    "</li>"
    );
    
    
}
