var _version ="1.0";
var _company ="AbhiTech"
var rawtext;
var resource = new Map();
document.addEventListener('readystatechange', event => {
    if (event.target.readyState === 'interactive') {
      rawtext = document.body.innerHTML;
      main();
    }
    else if (event.target.readyState === 'complete') {
       
    }
  });

/* ********************************************************
 *********************** Main Js ************************** 
*/

String.prototype.splice = function(idx, rem, str) {
    // uses :
    // var result = "foo baz".splice(4, 0, "bar ");
    // document.body.innerHTML = result; // "foo bar baz"
    return this.slice(0, idx) + str + this.slice(idx + Math.abs(rem));
}
function includeFile(r, file){
    xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
          if (this.status == 200) {res =  this.responseText;
            // mainHTML = mainHTML.splice(r[0], r[1]-r[0]+1, res);
            // document.body.innerHTML = mainHTML;
            // resource.push([r,res]);
            resource.set(r,res);
            render(resource);
            }
          if (this.status == 404) {res =  "Page not found.";}
          /* Remove the attribute, and call this function once more: */
   
        }
      }
      xhttp.open("GET", file, true);
      xhttp.send();
}

function parseCMD(text){
  text = text.slice(3,text.length-2);
  cmdList = text.split(" ");
  t = cmdList.indexOf("");
  while(t != -1){
    cmdList.splice(t,1);
    t = cmdList.indexOf("");
  }
  return cmdList;
}

function extractCmdFromHTML(rawtext){
  list = [];
  let insideComment = false;
    for(i=0; i<rawtext.length-3; i++){
        if(rawtext.slice(i,i+4) == "<!--"){
          insideComment = true;
        }
        else if(rawtext.slice(i,i+3) == "-->"){
          insideComment = false;
        }
        if(!insideComment && rawtext.slice(i,i+3) == "${{"){
            j = i;
            cmd = "";
            while(rawtext[j-2] != '}'){
                cmd += rawtext[j];
                j++;
            }
            
            list.push(cmd);
        }
    }
  return list.reverse();
}

function render(){
  // htmlText = document.body.innerHTML;
  htmlText = rawtext;
  resource.forEach((val, key) => {
    htmlText = htmlText.replace(key, val);
  });
  document.body.innerHTML = htmlText;
  // console.log(htmlText);
}
function main(){
    /* Extracting the CMDs from HTML file and make a list in reverse order. */
    list = extractCmdFromHTML(rawtext);

    // Process over all cmds listed in list 
    list.forEach(r => {
        // breaking cmd in parts 
        cmdList = parseCMD(r);
        // evaluate the cmds 
        
        switch(cmdList[0]){
          case "file":{
            if(cmdList[1] == "var"){
              includeFile(r, eval(cmdList[2]));
            }
            else{
              includeFile(r, cmdList[1]);
            } 
            break;
          }
          case "var":{
            try{
              //mainHTML = mainHTML.splice(r[0], r[1]-r[0]+1, eval(cmdList[1]));
              res = eval(cmdList[1]);
              // resource.push([r,res]);
              resource.set(r,res);
            }
            catch(e){
              console.error(e)
            }
            
            break;
          }
          default:{
            throw "'"+cmdList[0]+"' is illegal keyword";
          }
        }
    });

    render(resource);
}