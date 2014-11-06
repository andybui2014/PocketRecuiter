
var http_arr = new Array();
 
function doUpload() {
    document.getElementById('progress-group').innerHTML = ''; 
    var files = document.getElementById('myfile').files; 
    for (i=0;i<files.length;i++) {
        uploadFile(files[i], i);
    }
    return false;
}
 
function uploadFile(file, index) {
    var http = new XMLHttpRequest();
    http_arr.push(http);

    //Div.Progress-group
    var ProgressGroup = document.getElementById('progress-group');
    //Div.Progress
    var Progress = document.createElement('div');
    Progress.className = 'progress';
    //Div.Progress-bar
    var ProgressBar = document.createElement('div');
    ProgressBar.className = 'progress-bar';
    //Div.Progress-text
    var ProgressText = document.createElement('div');
    ProgressText.className = 'progress-text';   
    //completed
    var completed = document.createElement('span');
    completed.className = 'completed'; 
    //Them Div.Progress-bar và Div.Progress-text vào Div.Progress
    Progress.appendChild(ProgressBar);
    Progress.appendChild(ProgressText);
    //Them Div.Progress và Div.Progress-bar vào Div.Progress-group  
    ProgressGroup.appendChild(Progress);
    //them completed
    ProgressGroup.appendChild(completed);
 
 

    var oldLoaded = 0;
    var oldTime = 0;
    
    http.upload.addEventListener('progress', function(event) {  
        if (oldTime == 0) { 
            oldTime = event.timeStamp;
        }   

        var fileName = file.name; 
        var fileLoaded = event.loaded; 
        var fileTotal = event.total; 
        var fileProgress = parseInt((fileLoaded/fileTotal)*100) || 0; 
        var speed = speedRate(oldTime, event.timeStamp, oldLoaded, event.loaded);
        
        ProgressBar.innerHTML = fileName + ' dang duoc upload...';
        ProgressBar.style.width = fileProgress + '%';
        ProgressText.innerHTML = fileProgress + '% Upload Speed: '+speed+'KB/s';
        ProgressText.innerHTML = fileProgress + '%';
        completed.innerHTML =fileProgress + '%'+" completed ";
        
        if (fileProgress == 100) {
          //  ProgressBar.style.background = 'url("images/progressbar.gif")';
        }
        oldTime = event.timeStamp; 
        oldLoaded = event.loaded; 
    }, false);
     
 
    
    var data = new FormData();
    data.append('filename', file.name);
    data.append('myfile', file);
  //  http.open('POST', 'uploadfile', true);
    http.open('POST', 'uploadfile', true);
    http.send(data);
            
 

    http.onreadystatechange = function(event) {
        
     //  console.log(event);
        if (http.readyState == 4 && http.status == 200) {
            ProgressBar.style.background = ''; 
            try { 
               // alert(http.responseText);
                var server = JSON.parse(http.responseText);
                if (server.status) {
                    ProgressBar.className += ' progress-bar-success'; 
                    ProgressBar.innerHTML = server.message; 
                    window.location = 'profile';   
                   // alert(server.message);      
                } else {
                    ProgressBar.className += ' progress-bar-danger'; 
                    ProgressBar.innerHTML = server.message; 
                    //alert(server.message);   
                }
            } catch (e) {
                ProgressBar.className += ' progress-bar-danger'; 
                ProgressBar.innerHTML = 'upload error'; 
                //alert("upload error");   
                
            }
        }
        http.removeEventListener('progress'); 
    }
    
}
 
function cancleUpload() {
    for (i=0;i<http_arr.length;i++) {
        http_arr[i].removeEventListener('progress');
        http_arr[i].abort();
    }
    var ProgressBar = document.getElementsByClassName('progress-bar');
    for (i=0;i<ProgressBar.length;i++) {
        ProgressBar[i].className = 'progress progress-bar progress-bar-danger';
    }   
}
 
 
function speedRate(oldTime, newTime, oldLoaded, newLoaded) {
        var timeProcess = newTime - oldTime; 
        if (timeProcess != 0) {
            var currentLoadedPerMilisecond = (newLoaded - oldLoaded)/timeProcess; 
            return parseInt((currentLoadedPerMilisecond * 1000)/1024); 
        } else {
            return parseInt(newLoaded/1024); 
        }
}