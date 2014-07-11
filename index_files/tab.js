// JavaScript Document

function g(o){return document.getElementById(o);}
function tab(x,m,n){
	for(var i=1;i<=m;i++)
	{
	g(x+i).className='';
	g(x+'0'+i).style.display='none';		
	}
g(x+n).className='current';
g(x+'0'+n).style.display='block';
}

function Hide(i){
  document.getElementById(i).style.display = 'none';
}
function Show(i){
  document.getElementById(i).style.display = 'block';
}

function change(I) { 
if ( document.getElementById(I).style.display=="none" )
    { document.getElementById(I).style.display="block" } 
else if( document.getElementById(I).style.display="block" )
    { document.getElementById(I).style.display="none"}
}

function zoom(I) { 
if ( document.getElementById(I).className=="spic" )
    { document.getElementById(I).className="bpic"; document.getElementById(I).title="点击关闭大图"; } 
else if( document.getElementById(I).className=="bpic" )
    { document.getElementById(I).className="spic"; document.getElementById(I).title="点击查看大图"; }
}

function pick(o,i){
	  document.getElementById(i).value = document.getElementById(i).value=='快来发布你的签到吧！' ? '['+ o.name+']' :document.getElementById(i).value +'['+ o.name+']';
}