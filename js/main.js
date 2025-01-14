function showControls(flag){
  if(flag){
    jQuery( ".copy-me" ).show();
    jQuery( "#directions" ).show();
    jQuery( "#copy-button" ).show();
    jQuery( "#download-file-button" ).show();
  }
  else {
    jQuery( ".copy-me" ).hide();
    jQuery( "#directions" ).hide();
    jQuery( "#copy-button" ).hide();
    jQuery( "#download-file-button" ).hide();
  }
}

showControls(false);

function presentSignature(fName, signature){
  // show user the the results
  document.getElementById("demo").innerHTML=signature;
  //suggest to the user to edit or make another
  //document.getElementById("build-button").innerHTML = "Update";

  // add signature to hidden input field
  jQuery('#zc-input').val(signature);
  jQuery('#copy-button').attr('data-clipboard-text', signature);


  // add HTML to file link
  var fileName = companyInitals + '_' + fName + '.html';
  addSignatureToFile(fileName, signature);
}


function addSignatureToFile(filename, emailSig) {
  // stores HTML into link for file downloading
  var jQueryelement = jQuery('a#download-file-button');
  jQueryelement.attr('href','data:text/html; charset=utf-8,' + encodeURIComponent(emailSig));
  jQueryelement.attr('download', filename);
}

function formatPhoneNumber(number){
  var numberStripped = number.replace(/[^a-zA-Z0-9]/g, '');
  var numberTailored = numberStripped.replace(/(\d\d\d)(\d\d\d)(\d\d\d\d)/, "jQuery1-jQuery2-jQuery3");
  return numberTailored;
}

function getQueryVariable(variable){
  var query = window.location.search.substring(1);
  var vars = query.split("&");
  for (var i=0;i<vars.length;i++) {
    var pair = vars[i].split("=");
    if(pair[0] == variable){return pair[1];}
  }
  return(false);
}

function convertStringToTemplate(tpl, ...rest){
  // tpl = tpl.replace(/`/g, ''); // safety precaution
  var t = new Function('return `' + tpl + '`');
  return t(...rest);
}

function loadTemplate(...rest) {
  return jQuery.ajax({
    url:"inline-code.html",
    success:function(data) {
      convertStringToTemplate(data, ...rest);
    }
  });
}

function createTextField(label, id, placeholder){
  // Create new input field
  var newInput = document.createElement("INPUT");
  newInput.id = id;
  newInput.name = id;
  newInput.type = "text";
  newInput.placeholder = placeholder;

  var newlabel = document.createElement("Label");
  newlabel.setAttribute("for",id);
  newlabel.innerHTML = label;

  // create new div.field
  var newDiv = document.createElement("div");
  // add this new field and label to new .field
  newDiv.appendChild(newlabel);
  newDiv.appendChild(newInput);

  // add to #form
  document.getElementById("form").appendChild(newDiv);
  jQuery('#form div').last().addClass('field');
}

function getFiles(dir){
  fileList = [];

  var files = fs.readdirSync(dir);
  for(var i in files){
    if (!files.hasOwnProperty(i)) continue;
    var name = dir+'/'+files[i];
    if (!fs.statSync(name).isDirectory()){
      fileList.push(name);
    }
  }
  return fileList;
}

function createDataSelectBox(label, id, dataArray) {
  var staff = [];
  staff = dataArray;

  // Create select field
  var newSelect=document.createElement('select');

  // Create id
  newSelect.id = id;
  newSelect.name = id;
  newSelect.className = "chosen-select";

  // Create label
  var newlabel = document.createElement("Label");
  newlabel.setAttribute("for",id);
  newlabel.innerHTML = label;

  // Create select field options
  for(person in staff) {
   var opt = document.createElement("option");
   opt.value = staff[person];
   opt.innerHTML = staff[person]; // whatever property it has
   opt.innerHTML = cleanUpText(opt.innerHTML);
   // then append it to the select element
   newSelect.appendChild(opt);
 }

  // create new div.field
  var newDiv = document.createElement("div");
  // add this new field and label to new .field
  newDiv.appendChild(newlabel);
  newDiv.appendChild(newSelect);

  // add to #form
  document.getElementById("form").appendChild(newDiv);
  jQuery('#form div').last().addClass('field');
}

function cleanUpText(str)
{
 str = str.replace('-', ' ');
 str = str.replace('.jpg', '');
 return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
}

// if field is empty
function removeElementFromTemplate(id, sig){
  // turn string to html
  var jQueryel =  jQuery(sig);
  id = "#"+id;
  // remove #cell
  jQueryel.find(id).remove();
  // turn html back to string
  sig = jQueryel.prop('outerHTML');
  console.log(sig);
  return sig;
}

function moveCompanyLogo(sig, topSec, bottomSec){
  var jQueryel =  jQuery(sig);
  // if no headshot image do this...
  jQuery(jQueryel).find(topSec)
    .attr('src',serverPath + '/assets/images/lane-college.png')
    .removeAttr('height','100%')
    .css('border-radius','0');
  jQuery(jQueryel).find(".company-span")
    .css('display', 'block')
    .css('margin-top', '0.5em');
  jQuery(jQueryel).find(bottomSec).remove();
  // next: move address up to top table
  // move sig3 below sig2
  var jQuerytoMove = jQuery(jQueryel).find("#sig3");
  jQuery(jQueryel).find("#sig2").append(jQuerytoMove);

  // turn html back to string
  sig = jQueryel.prop('outerHTML');
  return sig;
}


//////
// if testing variable exists
//////
var urlvar = getQueryVariable("test")
if (urlvar) {
  showControls(true);

  //headshot = "bill-doe.jpg";
  first = "John";
  last = "Smith";
  creds = "Ph.D";
  if(creds !== '') creds = ', ' + creds;
  title = "Director of Awesomeness";
  division = "Sales";
  phone = "901-555-7777";
  cellphone = "731-555-7575";

   var tempfile = Drupal.settings.basePath + "branding-tools/email-signature-generator/code-simplified";

  jQuery.ajax({
    url:tempfile,
    success:function(data) {

      signature = convertStringToTemplate(data, first, last, creds, title, phone);
      // if(creds==''){signature = removeElementFromTemplate('creds', signature);}
      // if(title==''){signature = removeElementFromTemplate('title', signature);}
      if(phone==''){signature = removeElementFromTemplate('phone', signature);}

      // show the results && pass the first name for file download
      presentSignature(first, signature);
    }
  });
}
