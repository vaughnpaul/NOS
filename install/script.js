function submitForm (button) {
  var recurse = function (element) {
    if (element.tagName == 'FORM') return element;
    else {
      if (element.parentNode != null) return recurse(element.parentNode);
      else return null;
    }
  }
  var form = recurse(button);
  if (form != null) form.submit();
}