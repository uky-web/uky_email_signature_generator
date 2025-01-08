//// Global vars
//////
// setup vars ***
//////
var companyName = 'University of Kentucky';
var companyInitals = 'UK';
// server path ***
// this may change based on your local or live server set
var www = "";
if(window.location.href.indexOf("www") > -1){ www = "www."};
var serverPath = "http://www.uky.edu/prmarketing/branding-tools/email-signature-generator";
// custom video tutorial link

var first, signature;

// collect headshots of people (staff)
var teamImgs = ["", "No Photo"];
var fileExt = ".jpg";
function getTeamImages(){
  // return $.ajax({
  //   //This will retrieve the contents of the folder if the folder is configured as 'browsable'
  //   url: serverPath + '/assets/images/staff/',
  //   success: function (data) {
  //     //List all jpg file names in the folder
  //   }
  // });
}

//////
// Start Signature CMS
//////
$(document).ready(function() {
  // update company name
  $('#company-name').text(companyName);


  var clipboard = new Clipboard('#copy-button');

  clipboard.on('success', function(e) {
    document.getElementById("copy-button").innerHTML = "HTML Copied! Copy again?";
  });

  clipboard.on('error', function(e) {
    console.error('error', e);
  });

  // add text fields ***
  // createTextField(label, id, placeholder)
  $.when(getTeamImages()).done(function(results){
    $(results).find("a:contains(" + fileExt + ")").each(function () {
      teamImgs.push($(this).text().trim());
    });
    createTextField("First Name*", "first", "John");
    createTextField("Last Name*", "last", "Smith");
    createTextField("Credentials", "creds", "Ph.D");
    createTextField("Position/Title", "title", "Director");
    createTextField("Department/Unit", "department", "Department/Unit");
    createTextField("Address", "address", "Address");
    createTextField("City, State ZIP", "city", "Lexington, KY 40506");
    createTextField("Phone", "phone", "901.555.5555");
    // createTextField("Cell Phone", "cellphone", "901.555.5555");
    createTextField("Email", "email", "Email");
  });
});

// on submit button click
function buidSignature(sigType="normal") {
  showControls(true);

  //Get the value of input fields with id="INPUT-FIELD-ID" ***
  first = document.getElementById("first").value;
  last = document.getElementById("last").value;
  creds = document.getElementById("creds").value;
  if(creds !== '') creds = ', ' + creds;
  title = document.getElementById("title").value;
  department = document.getElementById("department").value.replace(/university\ of\ kentucky/ig, '');
  phone = formatPhoneNumber( document.getElementById("phone").value );
  email = document.getElementById("email").value;
  address = document.getElementById("address").value;
  city = document.getElementById("city").value;
  // cellphone = formatPhoneNumber( document.getElementById("cellphone").value );

  templateUrl = Drupal.settings.basePath + "branding-tools/email-signature-generator/code";

  if (sigType == "simplified"){
    templateUrl = Drupal.settings.basePath + "branding-tools/email-signature-generator/code-simplified";
  }

  $.ajax({
    url: templateUrl,
    success:function(data) {
      // headshot = document.getElementById("headshot").value;
      // put in all the variables need here ***
      // to add data for `dist/code.html` email signature template
      signature = convertStringToTemplate(
        data, 
        first, 
        last, 
        creds, 
        title, 
        department,
        address,
        city,
        email,
        phone, 
        serverPath
      );


      // if headshot is empty or null ...whatever
        //signature = moveCompanyLogo(signature, "#top-logo", "#bottom-logo");

      // optional fields ***
      if(creds===''){signature = removeElementFromTemplate('creds', signature);}
      if(title===''){signature = removeElementFromTemplate('title', signature);}
      if(department===''){signature = removeElementFromTemplate('department', signature);}
      if(address===''){signature = removeElementFromTemplate('address', signature);}
      if(city===''){signature = removeElementFromTemplate('city', signature);}
      if(phone===''){signature = removeElementFromTemplate('phone', signature);}
      if(email===''){signature = removeElementFromTemplate('email', signature);}


      // show the results && pass the first name for file download
      presentSignature(first, signature);
    }
  });
}