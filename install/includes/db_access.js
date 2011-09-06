function db_access() {
  var dbForm = document.installer;
  var req = new JsHttpRequest();
  req.onreadystatechange = function() {
      if (req.readyState == 4) {
        // Write result to page element (_RESULT become responseJS).
        var msg = document.getElementById('process_msg');
        for (i = 0; i < processes.length; i++) {
          if (req.responseJS.action == processes[i][0]) {
            if (req.responseJS.code == 'success') {
              processes[i][2] = 1;
            } else {
              processes[i][2] = 3;
              msg.style.display = '';
              msg.innerHTML = req.responseJS.msg + '<br />' + req.responseText;
            }
          }
        }
        db_process();
      }
  }
  // Prepare request object (automatically choose GET or POST).
  req.open(null, 'db_access.php', true);
  // Send data to backend.
  req.send( { q: dbForm } );
    
  return false;
}
function db_process() {
  // loop thru the processes array looking for work
  // the array the functiona area, the process msg, 0 = not processed, 1 = success, 3 = error
  var msg = document.getElementById('process_msg');
  var alldone = true;
  for (i = 0; i < processes.length; i++) {
    var text = document.getElementById('div'+processes[i][0]);
    if (processes[i][2] == 3) {
      alldone = false;
      text.className = 'convert-error';      
      // set i to prevent any additional process at this time
      i = processes.length + 1;
      enableBack();
    } else if (processes[i][2] == 1) {
      text.className = 'convert-complete';
    } else if (processes[i][2] == 0) {
      alldone = false;
      text.className = 'convert-active';      
      document.installer.function_call.value = processes[i][0];
      db_access();
      // set i to prevent any additional process at this time
      i = processes.length + 1;
    }
    
  }
  if (alldone) {
    document.getElementById('process_msg').style.display = '';
    enableBack();
    enableContinue();
  }
}

function enableBack () {
    document.getElementById('button-back').className = 'installation-button';
    document.getElementById('button_left_back').className = 'installation-button-left';
    document.getElementById('button_middle_back').className = 'installation-button-middle';
    document.getElementById('button_right_back').className = 'installation-button-right';
}

function enableContinue () {
    document.getElementById('button-continue').className = 'installation-button';
    document.getElementById('button_left_continue').className = 'installation-button-left';
    document.getElementById('button_middle_continue').className = 'installation-button-middle';
    document.getElementById('button_right_continue').className = 'installation-button-right';
}
