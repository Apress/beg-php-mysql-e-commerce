// Holds an instance of XMLHttpRequest
var xmlHttp = createXmlHttpRequestObject();

// Display error messages (true) or degrade to non-AJAX behavior (false) 
var showErrors = true;

// Contains the link or form clicked or submitted by the visitor
var actionObject = '';

// This is true when the Place Order button is clicked, false otherwise
var placingOrder = false;

// Creates an XMLHttpRequest instance
function createXmlHttpRequestObject()
{
  // Will store the XMLHttpRequest object
  var xmlHttp;

  // Create the XMLHttpRequest object
  try
  {
    // Try to create native XMLHttpRequest object 
    xmlHttp = new XMLHttpRequest();
  }
  catch(e)
  {
    // Assume IE6 or older
    var XmlHttpVersions = new Array(
      "MSXML2.XMLHTTP.6.0", "MSXML2.XMLHTTP.5.0", "MSXML2.XMLHTTP.4.0", 
      "MSXML2.XMLHTTP.3.0", "MSXML2.XMLHTTP", "Microsoft.XMLHTTP");

    // Try every id until one works
    for (i = 0; i < XmlHttpVersions.length && !xmlHttp; i++)
    {
      try
      {
        // Try to create XMLHttpRequest object
        xmlHttp = new ActiveXObject(XmlHttpVersions[i]);
      }
      catch (e) {} // Ignore potential error
    }
  }

  // If the XMLHttpRequest object was created successfully, return it 
  if (xmlHttp)
  {
    return xmlHttp;
  }
  // If an error happened, pass it to handleError
  else 
  {
    handleError("Error creating the XMLHttpRequest object.");
  }
}

// Displays an the error message or degrades to non-AJAX behavior
function handleError($message)
{
  // Ignore errors if showErrors is false
  if (showErrors)
  {
    // Display error message
    alert("Error encountered: \n" + $message);
    return false;
  }
  // Fall back to non-AJAX behavior 
  else if (!actionObject.tagName)
  {
    return true;
  }
  // Fall back to non-AJAX behavior by following the link
  else if (actionObject.tagName == 'A')
  {
    window.location = actionObject.href;
  }
  // Fall back to non-AJAX behavior by submitting the form
  else if (actionObject.tagName == 'FORM')
  {
    actionObject.submit();
  }
}

// Adds a product to the shopping cart
function addProductToCart(form)
{
  // Display "Updating" message
  document.getElementById('updating').style.visibility = 'visible';

  // Degrade to classical form submit if XMLHttpRequest is not available 
  if (!xmlHttp) return true;

  // Create the URL we open asynchronously 
  request = form.action + '&AjaxRequest';
  params  = null;

  // obtain selected attributes
  formSelects = form.getElementsByTagName('SELECT');
  if (formSelects)
  {
    for (i = 0; i < formSelects.length; i++)
    {
      params += '&' + formSelects[i].name + '=';
      selected_index = formSelects[i].selectedIndex;
      params += encodeURIComponent(formSelects[i][selected_index].text);
    }
  }

  // Try to connect to the server
  try
  {
    // Continue only if the XMLHttpRequest object isn't busy
    if (xmlHttp.readyState == 4 || xmlHttp.readyState == 0)
    {
      // Make a server request to validate the extracted data
      xmlHttp.open("POST", request);
      xmlHttp.setRequestHeader("Content-Type", 
                               "application/x-www-form-urlencoded");
      xmlHttp.onreadystatechange = addToCartStateChange;
      xmlHttp.send(params);
    }
  }
  catch (e)
  {
    // Handle error
    handleError(e.toString());
  }

  // Stop classical form submit if AJAX action succeeded
  return false;
}

// Function that retrieves the HTTP response
function addToCartStateChange()
{
  // When readyState is 4, we also read the server response
  if (xmlHttp.readyState == 4)
  {
    // Continue only if HTTP status is "OK"
    if (xmlHttp.status == 200)
    {
      try
      {
        updateCartSummary();
      }
      catch (e)
      {
        handleError(e.toString());
      }
    }
    else
    {
      handleError(xmlHttp.statusText);
    }
  }
}

// Process server's response
function updateCartSummary()
{
  // Read the response
  response = xmlHttp.responseText;

  // Server error?
  if (response.indexOf("ERRNO") >= 0 || response.indexOf("error") >= 0)
  {
    handleError(response);
  }
  else
  {
    // Extract the contents of the cart_summary div element
    var cartSummaryRegEx = /^<div class="box" id="cart-summary">([\s\S]*)<\/div>$/m;
    matches = cartSummaryRegEx.exec(response);
    response = matches[1];

    // Update the cart summary box and hide the Loading message
    document.getElementById("cart-summary").innerHTML = response;
    // Hide the "Updating..." message
    document.getElementById('updating').style.visibility = 'hidden';
  }
}

// Called on shopping cart update actions
function executeCartAction(obj)
{
  // Degrade to classical form submit for Place Order action
  if (placingOrder) return true;

  // Display "Updating..." message
  document.getElementById('updating').style.visibility = 'visible';

  // Degrade to classical form submit if XMLHttpRequest is not available 
  if (!xmlHttp) return true;

  // Save object reference 
  actionObject = obj;
  
  // Initialize response and parameters
  response = '';
  params = '';

  // If a link was clicked we get its href attribute
  if (obj.tagName == 'A')
  {
    url = obj.href + '&AjaxRequest';
  }
  // If the form was submitted we get its elements
  else
  {
    url = obj.action + '&AjaxRequest';
    formElements = obj.getElementsByTagName('INPUT');

    if (formElements)
    {
      for (i = 0; i < formElements.length; i++)
      {
        if (formElements[i].name != 'place_order')
        {
          params += '&' + formElements[i].name + '=';
          params += encodeURIComponent(formElements[i].value);
        }
      }
    }
  }

  // Try to connect to the server
  try
  {
    // Make server request only if the XMLHttpRequest object isn't busy
    if (xmlHttp.readyState == 4 || xmlHttp.readyState == 0)
    { 
      xmlHttp.open("POST", url, true);
      xmlHttp.setRequestHeader("Content-Type", 
                               "application/x-www-form-urlencoded");
      xmlHttp.onreadystatechange = cartActionStateChange;
      xmlHttp.send(params);
    }
  }
  catch (e)
  {
    // Handle error
    handleError(e.toString());
  }

  // Stop classical form submit if AJAX action succeeded
  return false;
}

// Function that retrieves the HTTP response
function cartActionStateChange()
{
  // When readyState is 4, we also read the server response
  if (xmlHttp.readyState == 4)
  {
    // Continue only if HTTP status is "OK"
    if (xmlHttp.status == 200)
    {
      try
      {
        // Read the response
        response = xmlHttp.responseText;
      
        // Server error?
        if (response.indexOf("ERRNO") >= 0 || response.indexOf("error") >= 0)
        {
          handleError(response);
        }
        else
        {
          // Update the cart
          document.getElementById("contents").innerHTML = response;
          // Hide the "Updating..." message
          document.getElementById('updating').style.visibility = 'hidden';
        }
      }
      catch (e)
      {
        // Handle error
        handleError(e.toString());
      }
    }
    else
    {
      // Handle error
      handleError(xmlHttp.statusText);
    }
  }
}
