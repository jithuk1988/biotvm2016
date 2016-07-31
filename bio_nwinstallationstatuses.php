<?php
$PageSecurity = 80;
include('includes/session.inc');
$title = _('Post Installation Calls');  
include('includes/header.inc');
echo '<head><link href="menu_assets/styles.css" rel="stylesheet" type="text/css"></head>';
?>

<style type="text/css">

<!--
.style1 {color: #FF0000}
-->

#hd
{
background: rgb(219,219,219); /* Old browsers */
background: -moz-linear-gradient(top,  rgba(219,219,219,1) 6%, rgba(226,226,226,1) 9%, rgba(219,219,219,1) 42%, rgba(209,209,209,1) 80%, rgba(254,254,254,1) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(6%,rgba(219,219,219,1)), color-stop(9%,rgba(226,226,226,1)), color-stop(42%,rgba(219,219,219,1)), color-stop(80%,rgba(209,209,209,1)), color-stop(100%,rgba(254,254,254,1))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  rgba(219,219,219,1) 6%,rgba(226,226,226,1) 9%,rgba(219,219,219,1) 42%,rgba(209,209,209,1) 80%,rgba(254,254,254,1) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  rgba(219,219,219,1) 6%,rgba(226,226,226,1) 9%,rgba(219,219,219,1) 42%,rgba(209,209,209,1) 80%,rgba(254,254,254,1) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  rgba(219,219,219,1) 6%,rgba(226,226,226,1) 9%,rgba(219,219,219,1) 42%,rgba(209,209,209,1) 80%,rgba(254,254,254,1) 100%); /* IE10+ */
background: linear-gradient(to bottom,  rgba(219,219,219,1) 6%,rgba(226,226,226,1) 9%,rgba(219,219,219,1) 42%,rgba(209,209,209,1) 80%,rgba(254,254,254,1) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#dbdbdb', endColorstr='#fefefe',GradientType=0 ); /* IE6-9 */

}
 
.sel{
font-size: large;
font-weight: 600;
border-color: red;
height:30px;
border: red;
border-width: thin;
color:blue;

}
.sl1{

height:30px;
background: rgb(252,255,244); /* Old browsers */
background: -moz-linear-gradient(top,  rgba(252,255,244,1) 0%, rgba(223,229,215,1) 40%, rgba(179,190,173,1) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(252,255,244,1)), color-stop(40%,rgba(223,229,215,1)), color-stop(100%,rgba(179,190,173,1))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  rgba(252,255,244,1) 0%,rgba(223,229,215,1) 40%,rgba(179,190,173,1) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  rgba(252,255,244,1) 0%,rgba(223,229,215,1) 40%,rgba(179,190,173,1) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  rgba(252,255,244,1) 0%,rgba(223,229,215,1) 40%,rgba(179,190,173,1) 100%); /* IE10+ */
background: linear-gradient(to bottom,  rgba(252,255,244,1) 0%,rgba(223,229,215,1) 40%,rgba(179,190,173,1) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#fcfff4', endColorstr='#b3bead',GradientType=0 ); /* IE6-9 */
}
.sl2{
height:30px;
background: rgb(247,251,252); /* Old browsers */
background: -moz-linear-gradient(top,  rgba(247,251,252,1) 0%, rgba(217,237,242,1) 40%, rgba(173,217,228,1) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(247,251,252,1)), color-stop(40%,rgba(217,237,242,1)), color-stop(100%,rgba(173,217,228,1))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  rgba(247,251,252,1) 0%,rgba(217,237,242,1) 40%,rgba(173,217,228,1) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  rgba(247,251,252,1) 0%,rgba(217,237,242,1) 40%,rgba(173,217,228,1) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  rgba(247,251,252,1) 0%,rgba(217,237,242,1) 40%,rgba(173,217,228,1) 100%); /* IE10+ */
background: linear-gradient(to bottom,  rgba(247,251,252,1) 0%,rgba(217,237,242,1) 40%,rgba(173,217,228,1) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f7fbfc', endColorstr='#add9e4',GradientType=0 ); /* IE6-9 */

}

.tbl
{
    font-family: verdana,arial,sans-serif;
    font-size:11px;
    color:#333333;
    border-width: 1px;
    border-color: #999999;
    border-collapse: collapse;
}
.thl
{
    padding: 0px;
    background: #d5e3e4;
	font-weight:500;
    background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2Q1ZTNlNCIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjQwJSIgc3RvcC1jb2xvcj0iI2NjZGVlMCIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNiM2M4Y2MiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
    background: -moz-linear-gradient(top,  #d5e3e4 0%, #ccdee0 40%, #b3c8cc 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#d5e3e4), color-stop(40%,#ccdee0), color-stop(100%,#b3c8cc));
    background: -webkit-linear-gradient(top,  #d5e3e4 0%,#ccdee0 40%,#b3c8cc 100%);
    background: -o-linear-gradient(top,  #d5e3e4 0%,#ccdee0 40%,#b3c8cc 100%);
    background: -ms-linear-gradient(top,  #d5e3e4 0%,#ccdee0 40%,#b3c8cc 100%);
    background: linear-gradient(to bottom,  #d5e3e4 0%,#ccdee0 40%,#b3c8cc 100%);
    border: 1px solid #999999;
}
.tdl
{
    padding: 0px;
    background: #ebecda;
    height: 24px;
    background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2ViZWNkYSIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjQwJSIgc3RvcC1jb2xvcj0iI2UwZTBjNiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNjZWNlYjciIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
    background: -moz-linear-gradient(top,  #ebecda 0%, #e0e0c6 40%, #ceceb7 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ebecda), color-stop(40%,#e0e0c6), color-stop(100%,#ceceb7));
    background: -webkit-linear-gradient(top,  #ebecda 0%,#e0e0c6 40%,#ceceb7 100%);
    background: -o-linear-gradient(top,  #ebecda 0%,#e0e0c6 40%,#ceceb7 100%);
    background: -ms-linear-gradient(top,  #ebecda 0%,#e0e0c6 40%,#ceceb7 100%);
    background: linear-gradient(to bottom,  #ebecda 0%,#e0e0c6 40%,#ceceb7 100%);
    border: 1px solid #999999;
}
 .tdl1
{
    padding: 0px;
    background: #3ADF00;
    height: 24px;
    background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2ViZWNkYSIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjQwJSIgc3RvcC1jb2xvcj0iI2UwZTBjNiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNjZWNlYjciIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
    background: -moz-linear-gradient(top,  #3ADF00 0%, #e0e0c6 40%, #9AFE2E 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#3ADF00), color-stop(40%,#58FAD0), color-stop(100%,#9AFE2E));
    background: -webkit-linear-gradient(top,  #3ADF00 0%,#58FAD0 40%,#9AFE2E 100%);
    background: -o-linear-gradient(top,  #3ADF00 0%,#58FAD0 40%,#9AFE2E 100%);
    background: -ms-linear-gradient(top,  #3ADF00 0%,#58FAD0 40%,#9AFE2E 100%);
    background: linear-gradient(to bottom,  #3ADF00 0%,#58FAD0 40%,#9AFE2E 100%);
    border: 1px solid #999999;
}
.tdl9
{
    padding: 0px;
    background: #FF0000;
    height: 24px;
    background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2ViZWNkYSIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjQwJSIgc3RvcC1jb2xvcj0iI2UwZTBjNiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNjZWNlYjciIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
    background: -moz-linear-gradient(top,  #FF0000 0%, #e0e0c6 40%, #FF0000 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#FF99FF), color-stop(40%,#D358F7), color-stop(100%,#ceceb7));
    background: -webkit-linear-gradient(top,  #D358F7 0%,#e0e0c6 40%,#F6CEF5 100%);
    background: -o-linear-gradient(top,  #D358F7 0%,#e0e0c6 40%,#F6CEF5 100%);
    background: -ms-linear-gradient(top,  #D358F7 0%,#e0e0c6 40%,#F6CEF5 100%);
    background: linear-gradient(to bottom,  #FF0000 0%,#e0e0c6 40%,#F6CEF5 100%);
    border: 1px solid #FF0000;
}
.tdl10
{
    padding: 0px;
    background: #D358F7;
    height: 24px;
    background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2ViZWNkYSIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjQwJSIgc3RvcC1jb2xvcj0iI2UwZTBjNiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNjZWNlYjciIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
    background: -moz-linear-gradient(top,  #D358F7 0%, #e0e0c6 40%, #FF0000 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#FF99FF), color-stop(40%,#D358F7), color-stop(100%,#ceceb7));
    background: -webkit-linear-gradient(top,  #D358F7 0%,#e0e0c6 40%,#F6CEF5 100%);
    background: -o-linear-gradient(top,  #D358F7 0%,#e0e0c6 40%,#F6CEF5 100%);
    background: -ms-linear-gradient(top,  #D358F7 0%,#e0e0c6 40%,#F6CEF5 100%);
    background: linear-gradient(to bottom,  #00FFCC 0%,#e0e0c6 40%,#F6CEF5 100%);
    border: 1px solid white;
}
.tdl8
{
    padding: 0px;
    background: #D358F7;
    height: 24px;
    background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2ViZWNkYSIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjQwJSIgc3RvcC1jb2xvcj0iI2UwZTBjNiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNjZWNlYjciIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
    background: -moz-linear-gradient(top, #D358F7 0%, #e0e0c6 40%, #FF0000 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#FF99FF), color-stop(40%,#D358F7), color-stop(100%,#ceceb7));
    background: -webkit-linear-gradient(top,  #D358F7 0%,#e0e0c6 40%,#F6CEF5 100%);
    background: -o-linear-gradient(top,  #D358F7 0%,#e0e0c6 40%,#F6CEF5 100%);
    background: -ms-linear-gradient(top,  #D358F7 0%,#e0e0c6 40%,#F6CEF5 100%);
    background: linear-gradient(to bottom,    #0000FF 0%,#e0e0c6 40%,#F6CEF5 100%);
    border: 1px solid #999999;
}
.tdl13
{
    padding: 0px;
    background: #D358F7;
    height: 24px;
    background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2ViZWNkYSIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjQwJSIgc3RvcC1jb2xvcj0iI2UwZTBjNiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNjZWNlYjciIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
    background: -moz-linear-gradient(top, #D358F7 0%, #e0e0c6 40%, #FF0000 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#FF99FF), color-stop(40%,#D358F7), color-stop(100%,#ceceb7));
    background: -webkit-linear-gradient(top,  #D358F7 0%,#e0e0c6 40%,#F6CEF5 100%);
    background: -o-linear-gradient(top,  #D358F7 0%,#e0e0c6 40%,#F6CEF5 100%);
    background: -ms-linear-gradient(top,  #D358F7 0%,#e0e0c6 40%,#F6CEF5 100%);
    background: linear-gradient(to bottom,    #80FFFF 0%,#e0e0c6 40%,#F6CEF5 100%);
    border: 1px solid white;
}
.tdl12
{
    padding: 0px;
    background: #D358F7;
    height: 24px;
    background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2ViZWNkYSIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjQwJSIgc3RvcC1jb2xvcj0iI2UwZTBjNiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNjZWNlYjciIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
    background: -moz-linear-gradient(top, #D358F7 0%, #e0e0c6 40%, #FF0000 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#FF99FF), color-stop(40%,#D358F7), color-stop(100%,#ceceb7));
    background: -webkit-linear-gradient(top,  #D358F7 0%,#e0e0c6 40%,#F6CEF5 100%);
    background: -o-linear-gradient(top,  #D358F7 0%,#e0e0c6 40%,#F6CEF5 100%);
    background: -ms-linear-gradient(top,  #D358F7 0%,#e0e0c6 40%,#F6CEF5 100%);
    background: linear-gradient(to bottom,    #FF33FF 0%,#e0e0c6 40%,#F6CEF5 100%);
    border: 1px solid #999999;
}
.tdl11
{
    padding: 0px;
    background: #D358F7;
    height: 24px;
    background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2ViZWNkYSIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjQwJSIgc3RvcC1jb2xvcj0iI2UwZTBjNiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNjZWNlYjciIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
    background: -moz-linear-gradient(top,  #D358F7 0%, #e0e0c6 40%, #FF0000 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#FF99FF), color-stop(40%,#D358F7), color-stop(100%,#ceceb7));
    background: -webkit-linear-gradient(top,  #D358F7 0%,#e0e0c6 40%,#F6CEF5 100%);
    background: -o-linear-gradient(top,  #D358F7 0%,#e0e0c6 40%,#F6CEF5 100%);
    background: -ms-linear-gradient(top,  #D358F7 0%,#e0e0c6 40%,#F6CEF5 100%);
    background: linear-gradient(to bottom,  #00FF00 0%,#e0e0c6 40%,#F6CEF5 100%);
    border: 1px solid #999999;
}
.tdl3        
{
    padding: 0px;
    background: #D358F7;
    height: 24px;
    background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2ViZWNkYSIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjQwJSIgc3RvcC1jb2xvcj0iI2UwZTBjNiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNjZWNlYjciIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
    background: -moz-linear-gradient(top,  #D358F7 0%, #e0e0c6 40%, #F6CEF5 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#F6D8CE), color-stop(40%,#D358F7), color-stop(100%,#ceceb7));
    background: -webkit-linear-gradient(top,  #D358F7 0%,#e0e0c6 40%,#F6CEF5 100%);
    background: -o-linear-gradient(top,  #D358F7 0%,#e0e0c6 40%,#F6CEF5 100%);
    background: -ms-linear-gradient(top,  #D358F7 0%,#e0e0c6 40%,#F6CEF5 100%);
    background: linear-gradient(to bottom,  #D358F7 0%,#e0e0c6 40%,#F6CEF5 100%);
    border: 1px solid #999999;
}
.tdl2
{
    padding: 0px;
    background: #FAAC58;
    height: 24px;
    background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2ViZWNkYSIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjQwJSIgc3RvcC1jb2xvcj0iI2UwZTBjNiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNjZWNlYjciIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
    background: -moz-linear-gradient(top,  #FAAC58 0%, #e0e0c6 40%, #F6D8CE 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#F6D8CE), color-stop(40%,#F6D8CE), color-stop(100%,#ceceb7));
    background: -webkit-linear-gradient(top,  #FAAC58 0%,#e0e0c6 40%,#F6D8CE 100%);
    background: -o-linear-gradient(top,  #FAAC58 0%,#e0e0c6 40%,#F6D8CE 100%);
    background: -ms-linear-gradient(top,  #FAAC58 0%,#e0e0c6 40%,#F6D8CE 100%);
    background: linear-gradient(to bottom,  #FAAC58 0%,#e0e0c6 40%,#F6D8CE 100%);
    border: 1px solid #999999;
}
</style>
<?php
 echo "<div id='cssmenu'>
<ul>
     <li class='active '> <a href='bio_nwinstallationstatuses.php'><span>Post installation</span></a></li>
   <li ><a href='bio_marketingtask.php'><span>Marketing task</span></a></li>
    <li><a href='bio_warranty_amc.php'><span>AMC / Warrenty</span></a></li>
   <li><a href='bio_paypending.php'><span>Payment pending</span></a></li>
   <li><a href='bio_cdmsurvey.php'><span>CDM survey</span></a></li>
       <li><a href='bio_complaintfollow.php'><span>Complaint followup</span></a></li>
</ul>                                      
</div>";
$off=$_SESSION['officeid'];
if ($off==1)
{
    
}         
$ord=$_GET['ordno'];//
    
if($ord!=null)
{
    $sql_sel="SELECT *  FROM bio_installation_status WHERE orderno=".$ord;                                                   $result_all=DB_query($sql_sel,$db);
    $main=DB_fetch_array($result_all); 
 $sql="SELECT debtorno FROM salesorders WHERE orderno=".$ord;
     $resulte=DB_query($sql,$db);
      $delivrdate=DB_fetch_array($resulte);
$drno=$delivrdate['debtorno'];

 $main['due_date1'];
}


echo"<div id=fullbody>";
echo "<div id=cll ></div>";
echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post" style="background:#EBEBEB;">';
echo '<table class="selection" style="width:70%;">';
echo '<th colspan="2"><b><font size="+1" color="#000000">Post Installation Calls</font></b></th>' ;
echo '<tr><td align="left" style="width:470px">';
echo"<div id=insatal style='width:470px'>";
echo"<fieldset style='width:470px;height:300px '>"; 
echo"<legend>Customer Details</legend>";
echo"<table width=100%>";


if($ord!=NULL){
           $sql="SELECT deliverydate FROM salesorders 
           WHERE orderno=".$ord;
           $result=DB_query($sql,$db);
           $delivrdate=DB_fetch_array($result);
           $sql1="SELECT COUNT(*) as orderno FROM bio_calllog WHERE orderno=".$ord." AND status=1";                                  
           $result1=DB_query($sql1,$db);
           $calltime=DB_fetch_array($result1);                                                       
}





if($main['installed_date']!=NULL){
   echo '<input type="hidden" readonly name="installed_date" id="installed_date" value="'.$main['installed_date'].'">'; 
}else{
echo '<input type="hidden" name="installed_date" id="installed_date"  onblur="duedate();return false" class=date alt="'.$_SESSION['DefaultDateFormat']. '">';
}

if($main['due_date1']!=NULL){
echo '<input type="hidden" readonly name="duedate1" id="duedate1" value="'.$main['due_date1'].'">';
}else{
 echo '<input type="hidden" name="duedate1" id="duedate1" value="">';   
     }
   //echo $main['actual_date1'];
   if($main['actual_date1']!='0000-00-00' || $main['actual_date1']!=null)
   {
 echo '<input readonly type="hidden" name="actualdate1" id="actualdate1" value="'.$main['actual_date1'].'">'; 
    } 
        

     if($main['actual_date1']==NULL || $main['actual_date1']=='0000-00-00' )
     { 
       echo '<input readonly type="hidden" name="duedate2" id="duedate2" value="'.$main['due_date2'].'">'; 
}else{
    
  $act1=$main['actual_date1'];
      // echo $act1;
                $date = strtotime(date("Y-m-d", strtotime($act1)) . " +7 day");
        $date1=date('Y-m-d',$date);echo '<input readonly type="hidden" name="duedate2" id="duedate2" value="'.$date1.'">';  
}

//echo $main['actual_date2'];
if($main['installed_date']!='0000-00-00'){

echo '<input readonly type="hidden" name="actualdate2" id="actualdate2" value="'.$main['actual_date2'].'">';
}  
if($main['actual_date2']!=NULL && $main['actual_date2']!='0000-00-00'){
        $due=$main['actual_date2'];
        $date = strtotime(date("Y-m-d", strtotime($due)) . " +11 day");
        $date1=date('Y-m-d',$date);
echo '<input readonly type="hidden" name="duedate3" id="duedate3" value="'.$date1.'">';
}
if($main['actual_date2']!=NULL && $main['actual_date2']!=0){
echo '<input readonly type="hidden" name="actualdate3" id="actualdate3" value="'.$main['actual_date3'].'">';
}

   if($ord!=NULL){
$sql_ph="SELECT brname,concat(braddress1,'</BR>',braddress2) AS 'braddress2',bio_district.district,concat(faxno,'</br>',phoneno) as 'phoneno' 
                  FROM  custbranch,bio_district 
                  WHERE (custbranch.cid=bio_district.cid AND custbranch.stateid=bio_district.stateid AND custbranch.did=bio_district.did)
                  AND   debtorno=(SELECT debtorno FROM salesorders WHERE orderno='".$_GET["ordno"]."')";
                  $result_ph=DB_query($sql_ph,$db); 
                  $row_ph=DB_fetch_array($result_ph);
                           $add=$row_ph['braddress2'];
                           $nadd=str_replace(',',' ',$add);
                   $contactno=$row_ph['phoneno'];  
              $sqlpnt="select stockmaster.description,orderplant.branchcode,date_format(bio_installation_status.installed_date,'%d %b %Y') as 'ins_date' from bio_installation_status,stockmaster,orderplant where stockmaster.stockid=orderplant.stkcode and bio_installation_status.orderno=orderplant.orderno and orderplant.orderno='".$_GET["ordno"]."'";    
                   $result_pt=DB_query($sqlpnt,$db); 
                         $row_pt=DB_fetch_array($result_pt);
						 $sqlcallno="SELECT IFNULL( MAX( callno ) , 0 ) FROM bio_calllog WHERE STATUS =1 AND orderno ='".$_GET["ordno"]."'";  
						 $rst_cl=DB_query($sqlcallno,$db); 
						  $row_cl=DB_fetch_array($rst_cl);
                  echo'<tr style="height:25px; font-weight:600"><td style="width:15%">Name:</td>
<td style="width:14%">'.$row_ph['brname'].'</td></tr>';
 echo "</tr><tr></tr><tr></tr>";
 echo'<tr style="height:25px; font-weight:600"><td style="width:15%">Address:</td>
<td style="width:14%">'.$nadd.'</td></tr>';
 echo "</tr><tr></tr><tr></tr>";
 echo'<tr style="height:25px; font-weight:600"><td style="width:15%">District:</td>
<td style="width:14%">'.$row_ph['district'].'</tr>';
 echo "</tr><tr></tr><tr></tr>";
 echo'<tr style="height:25px; font-weight:600"><td style="width:15%">Contact No:</td>
<td style="width:14%">'.$contactno.'</td></tr>';
echo'<tr style="height:25px; font-weight:600"><td style="width:15%">Biogas Plant:</td>
<td style="width:14%">'.$row_pt[0].'</td></tr>';
echo'<tr style="height:25px; font-weight:600"><td style="width:15%">Customer Code:</td>
<td style="width:14%">'.$row_pt[1].'</td></tr>';
       echo'<tr style="height:25px; font-weight:600"><td style="width:15%">Installed Date:</td>
<td style="width:14%">'.$row_pt[2].'</td></tr>';      
 echo "</tr><tr></tr><tr></tr>";
 echo '<input hidden type="text" name="deno" id="deno" value="'.$drno.'" >'; 

  echo '<tr ><td colspan=2><a style=cursor:pointer; onclick=Customer_Maintenance(1)>' . _('Modify Customer Details') . '</a></td></tr>'; 
    echo '<tr ><td colspan=2><a style=cursor:pointer; onclick=createleads()>' . _('Customer Reference As Leadsource') . '</a></td></tr>'; 
$sqldoc="SELECT count( bio_documentlist.status ) , bio_document_master.document_name
FROM bio_documentlist, bio_document_master
WHERE bio_documentlist.orderno =". $_GET["ordno"] ."
AND bio_documentlist.docno = bio_document_master.doc_no
AND bio_documentlist.status <1";
echo "<input type='hidden' id=order value='".$_GET["ordno"]."'>";
$result_doc=DB_query($sqldoc,$db);
$row=DB_fetch_array($result_doc);
$num=$row[0];
    echo '<tr ><td colspan=2><a style=cursor:pointer; onclick=docview()>' . _('Document pending:') .''.$num.'</a></td></tr>';  
 $sql_bal=" SELECT
    `salesorders`.`orderno`
    , `custbranch`.`brname`
    , `orderamount`.`debtorno`
    , `orderamount`.`ordervalue`
    ,bio_installation_status.installed_date
    , `orderamount`.`orddate`
    , `debtorpaid`.`paid`
    , `orderplant`.`stkcode` as 'stockid'
    , IFNULL(orderamount.ordervalue- ifnull (`debtorpaid`.`paid`,0)-ifnull(ordersubsidy.totsubsidy,0),0) AS 'balance'
    , `salesorders`.`orderno`
    , `salesorders`.`orderno`
    , `salesorders`.`orderno`
   ,  `orderamount`.`ordervalue`- ifnull(ordersubsidy.totsubsidy,0) as 'netpayable'    ,  
  `salesorders`.`contactphone` , 
  `orderamount`.`debtorno` 
FROM
    `salesorders`
    INNER JOIN `custbranch` 
        ON (`salesorders`.`debtorno` = `custbranch`.`debtorno`)
        left JOIN debtortrans ON (custbranch.debtorno=debtortrans.debtorno)
    LEFT JOIN `ordersubsidy` 
        ON (`salesorders`.`orderno` = `ordersubsidy`.`orderno`)
    inner JOIN `orderamount` 
        ON (`salesorders`.`orderno` = `orderamount`.`orderno`)
    left JOIN `debtorpaid` 
        ON (`salesorders`.`debtorno` = `debtorpaid`.`debtorno`)
    inner JOIN `orderplant` 
        ON (`salesorders`.`orderno` = `orderplant`.`orderno`)
        left JOIN `bio_installation_status` 
        ON (`salesorders`.`orderno` = `bio_installation_status`.`orderno`)
                WHERE  orderamount.debtorno like 'D%' AND `salesorders`.`orderno`='".$_GET["ordno"]."' group by `salesorders`.`orderno`"; 
   $result_bal=DB_query($sql_bal,$db);
$row=DB_fetch_array($result_bal);
$balance=$row['balance'];
if($balance>0)
{
     echo '<td><span class="style1"><b>' . _('Payment pending:') .''.$balance.'</b></span></td></tr>';  
} }  echo '</fieldset>'; 
  echo '</table>';
                  
   echo '<td style="width:370px">';

echo"<div id=calllog style='width:370px;float:left'>";
echo"<fieldset style='width:370px;height:300px'>"; 
echo"<legend>Call Details</legend>";
echo"</legend>";
echo"<br />";
echo"<table width=100% id='cal_lgtb'>";

echo '<tr id="ordno"><td style="width:14%" >OrderNo</td>
<td style="width:14%" ><input  type="text" style="width:170px" readonly name="orderno" id="ordernum" value="'.$ord.'"></td></tr>';
   $caltime=$calltime['orderno'];                                                   
   
echo '<tr><td style="width:4%">Call NO*</td>
<td style="width:10%"><select name="fbcall" id="fbcall"  style="width:174px">
';

$a1=$main['actual_date1'];
$a2=$main['actual_date2'];
$a3=$main['actual_date3'];
if($row_cl[0]==0)
{

echo '<option value=1 selected="selected">First Call</option>';
}
else if($row_cl[0]==1)
{echo '<option value=2 selected="selected">Second Call</option>';
}
else if($row_cl[0]>1)
{
    echo '<option value=3 selected="selected">Third Call</option>';
}

echo '</select></td></tr>';

//onchange="changestatus(this.value)
echo '<tr><td style="width:30%">Plant Status*</td>
<td style="width:4%"><select name="plantstatus" id="plantstatus"  style="width:174px" onchange="changestatus(this.value)">
';
$sql3="SELECT * FROM bio_plant_status";
$result3=DB_query($sql3,$db);
 $f=0;
  while($myrow3=DB_fetch_array($result3))
  {  
  if ($myrow3['p_id']==$main['plant_status'])  
    {         //echo $myrow1['cid'];     
    echo '<option selected value="';
    } else 
    {
        echo '<option value="';
    }
      echo $myrow3['p_id'] . '">'.$myrow3['p_status'];
    echo '</option>';
  }
echo '</select></td><td id=cl>&nbsp;</td></tr>';



echo '<tr><td style="width:4%">Call Date*</td>
<td style="width:4%"><input  style="width:170px" type="text" name="caldate" id="caldate" class=date alt="'.$_SESSION['DefaultDateFormat']. '" value='.date("d/m/Y").'><td><td>&nbsp;</td></tr>';
echo '<tr><td style="width:4%">Remarks*</td>
<td style="width:4%"><textarea  style="width:169px"  onKeyPress="return charLimit(this)" onKeyUp="return characterCount(this)" name="remarks1" id="remarks1" tabindex=0></textarea></td><td id="charCount" ><b>100</b></td></tr>';
//onchange="changestatus(this.value)
echo '<tr><td style="width:4%">Status*</td>
<td style="width:4%" ><select name="status" id="status"  style="width:174px" >
<option value=0></option>
<option value=1>Success</option>       
<option value=2>Customer Busy</option>
<option value=3>Customer Not picking</option>
<option value=4>Line Busy</option>
<option value=5>Switch off</option>
</select></td><td>&nbsp;</td></tr>';//

echo '<td colspan="2" align="center"><br><br><input type="button" name="submit" id="submit" value="submit" onclick="closecall();"></td>';
echo '</table>';
echo '</tr>'; 
echo '</tr>';

echo '</table>';echo '</div>';
 

echo '<div id="doc"></div>';             
 echo '</div>'; 

//for show installation view
if($_GET['ordno']!=null){
echo "<div id='pcall'>";
echo "<fieldset style='width:90%;'>";     
     echo "<legend><h3>PREVIOUS CALL STATUS</h3>";
     echo "</legend>";         
     echo "<table style='border:1px solid ;width:90%;'>";
     echo '<tr>
    <th class="thl">SL No</th>   
                <th class="thl" >Call No</th>
                <th class="thl" >Call Date</th>
				<th class="thl">Remarks</th>
                <th class="thl" >Status</th>
                </tr>';
     if($_GET['ordno']!=NULL) {          
  $result="SELECT * FROM bio_calllog WHERE orderno=".$_GET['ordno'];
     }else{ $result="SELECT * FROM bio_calllog";}
                                                                                                                                
   $result_callog=DB_query($result,$db); 
   $sl=0;
 while($row=DB_fetch_array($result_callog)){
 $sl++;
     if($row['status']==1){$status="Success";}
     if($row['status']==2){$status="Customer busy";}
     if($row['status']==3){$status="Customer Not picking";}
     if($row['status']==4){$status="Line Busy";}
     if($row['status']==5){$status="Switch off";}
                printf("<tr style='background:#A8A4DB'>                
            <td class='tdl' width='70px'>%s<a></td>
            <td  class='tdl' width='100px'>%s</td>
            <td  class='tdl' width='100px'>%s</td>
            <td  class='tdl' width='150px'>%s</td>
            <td  class='tdl' width='130px'>%s</td>
                                   </tr>",
        $sl,
        $row['callno'],        
        $row['call_date'],
         $row['remark'],
        $status       
        ); 

           }
   echo "</table>";            




echo "</div>"; }
 echo"<div id=grid>";
echo"<fieldset style='width:90%;'>";
echo"<legend><h3></h3></legend>";
    echo"<table style='width:100%;'><tr><td>Name:</td><td><input type='text' name='custname'></td>";
    
    echo"<td>Call No</td><td><select name='call_no'>
        <option value=0></option>
    <option value=1>First Call</option>
    <option value=2>Second Call</option>
    <option value=3>Third Call</option>
     </select></td>";
    echo"<td>From Date:</td><td><input type='text' name='fr_date' class=date alt=".$_SESSION['DefaultDateFormat']."></td>"; 
    echo"<td>To Date:</td><td><input type='text' name='to_date' class=date alt=".$_SESSION['DefaultDateFormat']."></td>"; 
    echo"<td>Odrer No.:</td><td><input type='text' name='ordno'></td>"; 
    echo"<td><input type='submit' name='view' id='submit' value=search></td></tr></table></div>";
 echo"<div id='close'>";   
              
 if($_GET['status']==1){
          $heading="CALL STATUS VIEW";
 
echo '<center><font style="color: #333;
                           background:#fff;
                           font-weight:bold;
                           letter-spacing:0.10em;
                           font-size:16px;
                           font-family:Georgia;
                           text-shadow: 1px 1px 1px #666;">'.$heading.'</font></center>';    
 
echo "<table style='width:90%'><tr><td>";        
//echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>";

echo '<br><input type="hidden" name="Status" value='.$_GET['status'].' >';


echo "<div style='height:400px; overflow:auto;'>";
echo "<table style='border:1 solid #F0F0F0;width:100%'>";
echo "<tr><th style=width:5%><b>SL NO</b></th><th style=width:5%><b>ORDER No</b></th>
<th style=width:10%><b>INSTALLED DATE</b></th> 
<th style=width:10%><b>DUE DATE1</b></th>    
<th class='task' style=width:10%><b>ACTUAL DATE1</b></th>
<th class='team' style=width:10%><b>DUE DATE2</b></th>
<th style=width:10%><b>ACTUAL DATE2</b></th>
<th style=width:10%><b>DUE DATE3</b></th>
<th style=width:10%><b>ACTUAL DATE3</b></th>
<th style=width:10%><b>PLANT STATUS</b></th>
<th style=width:10%><b>CLOSE STATUS</b></th>
<th style=width:10%><b>REMARKS</b></th></tr>";
//$sql_date="SELECT max(actual_date3) as actual_date3  FROM bio_installation_status" ;  
//$result_date=DB_query($sql_date,$db);
//while($result_date1=DB_fetch_array($result_date)) 
// {
     $due=date('Y-m-d');
     $date = strtotime(date("Y-m-d", strtotime($due)) . " -15 day");
     $date1=date('Y-m-d',$date);

 //}
 
 $sql_selall="SELECT *  FROM bio_installation_status 
                                           WHERE actual_date3>".$date1; 
                                                
             $result_allsel=DB_query($sql_selall,$db);
         $is=0;
 while($result_tb=DB_fetch_array($result_allsel)) 
 {         $is++;
     echo '<tr> <a id="'.$result_tb['orderno'].'"  href="" onclick="ordernumber(this.id);return false" >';
       echo '<td>'.$is.'</td>';
  echo '<td><a id="'.$result_tb['orderno'].'"  href="" onclick="ordernumber(this.id);return false" >'.$result_tb["orderno"].'</a></td>';
  echo '<td>'.$result_tb["installed_date"].'</td>';
  echo '<td>'.ConvertSQLDate($result_tb["due_date1"]).'</td>';  
  echo '<td>'.$result_tb["actual_date1"].'</td>';
  echo '<td>'.$result_tb["due_date2"].'</td>';
  echo '<td>'.$result_tb["actual_date2"].'</td>';
  echo '<td>'.$result_tb["due_date3"].'</td>';
  echo '<td>'.$result_tb["actual_date3"].'</td>';
  $pl_status=$result_tb["plant_status"] ;
  $sql="SELECT  `p_status` FROM `bio_plant_status` WHERE `p_id`=".$pl_status;
  $result=DB_query($sql,$db);
  $row=DB_fetch_array($result);
  $plantstatus=$row[0];
  echo '<td>'.$plantstatus.'</td>';
    if($result_tb["close_status"]==0){$closestatus="No";}else{$closestatus="Yes";}
  echo '<td>'.$closestatus.'</td>';
   echo '<td>'.substr($result_tb["remarks"],0,20).'</td>';
   echo "</a></tr>" ;
 }

echo "</table>";
echo "</div>";
echo "</form>";
echo "<td></tr></table>";
echo "</div>";
}
// }
//for show installation view end
//for Calls Due Today

if($_GET['ordno']==null){
          $heading="Calls Due Today";
                                       
echo '<center><font style="color: #333;
                           background:#fff;
                           font-weight:bold;
                           letter-spacing:0.10em;
                           font-size:16px;
                           font-family:Georgia;
                           text-shadow: 1px 1px 1px #666;">'.$heading.'</font></center>';    
 
echo "<table style='width:90%'><tr><td>";        
//echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>";

echo '<br><input type="hidden" name="Status" value='.$_GET['status'].' >';


echo "<div style='height:400px; overflow:auto;'>";
echo "<table style='border:1 solid #000000;width:100%'>";
echo "<tr id='hd' class='thl'><th id='hd' class='thl'><b>SL No</b></th><th id='hd' class='thl'><b>ORDER No</b></th>
<th id='hd' class='thl'><b>Name</b></th> 
<th id='hd' class='thl'><b>CONTACT NO</b></th>    
<th id='hd' class='thl'><b>INSTALLED DATE</b></th> 
<th id='hd' class='thl'><b>DUE DATE1</b></th>    
<th id='hd' class='thl'><b>ACTUAL DATE1</b></th>
<th id='hd' class='thl'><b>DUE DATE2</b></th>
<th id='hd' class='thl'><b>ACTUAL DATE2</b></th>
<th id='hd' class='thl'><b>DUE DATE3</b></th>
<th  id='hd' class='thl'><b>ACTUAL DATE3</b></th>
<th  id='hd' class='thl'><b>PLANT STATUS</b></th>
<th  id='hd' class='thl'><b>CLOSE STATUS</b></th>
<th  id='hd' class='thl'><b>REMARKS</b></th></tr>";

//$sql_selall="SELECT *  FROM bio_installation_status" ;  

$sql_selall="SELECT * FROM bio_installation_status,salesorders,debtorsmaster 
             WHERE salesorders.orderno=bio_installation_status.orderno 
             AND  debtorsmaster.debtorno=salesorders.debtorno  ";
            // AND debtorsmaster.did IN($dist)";       
            if($_POST['view'])
              {
                  
                   if($_POST['custname']!=NULL) {
                     $sql_selall.=" AND debtorsmaster.name LIKE '".$_POST['custname']."%'";   
                  }
                  if($_POST['call_no']!=NULL) 
                  {
                      if($_POST['call_no']==1)
                      {
                          $sql_selall.=" AND bio_installation_status.actual_date1 = '0000-00-00'";
                          //$sql_selall.=" AND bio_installation_status.due_date2 = '0000-00-00'";
                         // $sql_selall.=" AND bio_installation_status.due_date3 = '0000-00-00'";
                      }
                      if($_POST['call_no']==2)
                      {
                          $sql_selall.=" AND bio_installation_status.actual_date2 = '0000-00-00'";
                          //$sql_selall.=" AND bio_installation_status.actual_date1 != '0000-00-00'";
                          //$sql_selall.=" AND bio_installation_status.due_date2 != '0000-00-00'";
                      }
                      if($_POST['call_no']==3)
                      {
                          //$sql_selall.=" AND  bio_installation_status.actual_date3 = '0000-00-00'";
                          //$sql_selall.=" AND bio_installation_status.due_date2 != '0000-00-00'";
                          $sql_selall.=" AND bio_installation_status.due_date3 != '0000-00-00'";
                      }
                        
                  } 
                  if($_POST['fr_date']!=NULL and $_POST['to_date']!=NULL) {
                     /*$sql_selall.=" AND bio_installation_status.brname BETWEEN '".$_POST['fr_date']."' AND '".$_POST['to_date']."'";  */ 
                      $sql_selall.=" AND bio_installation_status.installed_date BETWEEN '".FormatDateForSQL($_POST['fr_date'])."' AND '".FormatDateForSQL($_POST['to_date'])."'";
                  }
                 
                  if($_POST['ordno']!=NULL) {
                    $sql_selall.=" AND bio_installation_status.orderno LIKE '".$_POST['ordno']."%'"; 
                     //$sql_selall.="AND bio_installation_status.orderno='890'";  
                  }
              }
              
             
             if($_SESSION['officeid']==1) 
             {
                 $sql_selall=$sql_selall;
             }
             //|| ($_SESSION['officeid']==4)){
// $dist='6,11,12';   
elseif($_SESSION['officeid']==4){
    $dist='6,11,12';   
    $sql_selall.=" and debtorsmaster.stateid=14 AND debtorsmaster.did IN($dist)";}
    elseif($_SESSION['officeid']==2){
         $dist='1,2,3,7,13'; 
         $sql_selall.=" and debtorsmaster.stateid=14 AND debtorsmaster.did IN($dist)";}
  elseif($_SESSION['officeid']==3){
 $dist='4,5,8,9,10,14';  
    $sql_selall.=" and debtorsmaster.stateid=14 AND debtorsmaster.did IN($dist)";} 
elseif($_SESSION['officeid']==5){
    $sql_selall.=" and debtorsmaster.cid=1 and debtorsmaster.stateid!=14";} 

elseif ($_SESSION['officeid']==6){
$sql_selall.=" and debtorsmaster.cid!=1";
}
 $crrdate=date('Y-m-d');
  // $sql_selall.=" LIMIT 50"; 
 $result_allsel=DB_query($sql_selall,$db);
$slno=0;
 $ordno= $_GET['ordno'];   
 while($result_tb=DB_fetch_array($result_allsel)) 
 {
   //echo $result_tb['orderno']."<br>";
   
 $sql_cal="SELECT MAX(callno) as callno  FROM bio_calllog WHERE orderno=".$result_tb['orderno'];  
$result_cal=DB_query($sql_cal,$db); 
$result_cal_log=DB_fetch_array($result_cal);
$sql_name="SELECT deliverto FROM salesorders WHERE orderno=".$result_tb['orderno'];  
$result_name=DB_query($sql_name,$db); 
$result_cal_name=DB_fetch_array($result_name);
    $ordno= $_GET['ordno'];
 if( $result_tb["due_date1"]<=$crrdate && $result_tb["due_date2"]<=$crrdate && $result_tb["due_date2"]<=$crrdate  ) 
 {           
if(($result_tb["due_date1"]<= $crrdate && $result_tb["actual_date1"]== 0)  || ($result_tb["due_date2"]<=$crrdate && $result_tb["actual_date2"]== 0) || ($result_tb["due_date3"]!=0 && $result_tb["due_date3"]<=$crrdate && $result_tb["close_status"]==0)) {
 $slno++;     
  if($slno%2==0)
	 {       
         if($result_tb['orderno']==$ordno)
         {
               echo '<tr class="sel" id="'.$result_tb['orderno'].'"  href="" onclick="ordernumber(this.id);return false" >';
   
         }
         else
         {
	 echo '<tr  id="'.$result_tb['orderno'].'"  href="" onclick="ordernumber(this.id);return false" >';
         }
         }
else
{
     if($result_tb['orderno']==$ordno)
     {
echo '<tr id="'.$result_tb['orderno'].'"  href="" onclick="ordernumber(this.id);return false"  class="sel">';
         
     }
     else
     {
 echo '<tr id="'.$result_tb['orderno'].'"  href="" onclick="ordernumber(this.id);return false" >';
}
}  
//if(($result_tb["due_date1"] <= $crrdate && $result_tb["actual_date1"]== '0000-00-00')  || ($result_tb["due_date2"] <= $crrdate && $result_tb["actual_date2"]== '0000-00-00') || ($result_tb["actual_date3"]=='0000-00-00' && $result_tb["due_date3"]<=$crrdate && $result_tb["close_status"]==0)) {
  $sql="SELECT concat( `custbranch`.`phoneno` , '<br>', `custbranch`.`faxno` ) AS 'Contno'
FROM `custbranch`
WHERE `debtorno` ='".$result_tb['debtorno']."'";
$resul=DB_query($sql,$db);
$row6=DB_fetch_array($resul);
 $phon=$row6[0];
 
  echo '<td class="tdl">'.$slno.'</td><td class="tdl"><b>'.$result_tb["orderno"].'</b></td>';
  echo '<td  class="tdl">'.$result_cal_name["deliverto"].'</td>'; echo '<td  class="tdl">'.$phon.'</td>';
  echo '<td class="tdl">'.ConvertSQLDate($result_tb["installed_date"]).'</td>';
  if($result_tb["actual_date1"]==0){$actal1="---";}else{$actal1=ConvertSQLDate($result_tb["actual_date1"]); }
  if($result_tb["actual_date2"]==0){$actal2="---";}else{$actal2=ConvertSQLDate($result_tb["actual_date2"]);}
  if($result_tb["actual_date3"]==0){$actal3="---";}else{$actal3=ConvertSQLDate($result_tb["actual_date3"]);}
  if($result_tb["due_date2"]==0){$due2="---";}else{$due2=ConvertSQLDate($result_tb["due_date2"]);}
  if($result_tb["due_date3"]==0){$due3="---";}else{$due3=ConvertSQLDate($result_tb["due_date3"]);}
  if($actal1=="---" && $actal2=="---" && $actal3=="---")
  {
  echo '<td class="tdl2">'.ConvertSQLDate($result_tb["due_date1"]).'</td>';
 }
 else
 {
 echo '<td  class="tdl">'.ConvertSQLDate($result_tb["due_date1"]).'</td>';
 }
  echo '<td  class="tdl">'.$actal1.'</td>';
  if($actal2=="---" && $actal3=="---" && $actal1!="---")
  {
  echo '<td class="tdl3">'.$due2.'</td>';
  }
  else{
   echo '<td class="tdl">'.$due2.'</td>';
  }
  echo '<td  class="tdl">'.$actal2.'</td>';
    if($actal3=="---" && $actal2!="---" && $actal1!="---")
  {
  echo '<td class="tdl1">'.$due3.'</td>';
  }
  else{
  echo '<td class="tdl">'.$due3.'</td>';
  }echo '<td class="tdl">'.$actal3.'</td>';
  $sql="SELECT  `p_status` FROM `bio_plant_status` WHERE `p_id`=".$result_tb["plant_status"];
  $result=DB_query($sql,$db);
  $row=DB_fetch_array($result);
  $plantstatus=$row[0];
  if($result_tb["plant_status"]==1)
  {
      echo '<td class="tdl11">'.$plantstatus.'</td>';
  }
  else if($result_tb["plant_status"]==3)
  {
    echo '<td class="tdl10">'.$plantstatus.'</td>';
  }
  else  if($result_tb["plant_status"]==2)
  {
      echo '<td class="tdl9">'.$plantstatus.'</td>';
  }
    else  if($result_tb["plant_status"]==4)
  {
       echo '<td class="tdl8">'.$plantstatus.'</td>';
  }
    else  if($result_tb["plant_status"]==5)
  {
       echo '<td class="tdl13">'.$plantstatus.'</td>';    
  }  else  if($result_tb["plant_status"]==6)
  {
      echo '<td class="tdl12">'.$plantstatus.'</td>';
  }
  
    if($result_tb["close_status"]==0){$closestatus="No";}else{$closestatus="Yes";}
  echo '<td class="tdl">'.$closestatus.'</td>';
   echo '<td class="tdl">'.substr($result_tb["remarks"],0,20).'</td>';
   echo "</tr>" ;
 }
 }
 }    
echo "</table>";
echo "</div>";
//echo "</form>";
echo "<td></tr></table>";
echo "</div>";
}
//for calls Due today end
//if($_GET['status']==0){
//echo "<table style='width:60%'><tr><td>";  
//echo "<div style='height:400px; overflow:auto;'>"; 
//echo "<fieldset style='width:90%;'>";     
//     echo "<legend><h3>Sale order Registered</h3>";
//     echo "</legend>";         
//     echo "<table style='border:1 solid #F0F0F0;width:100%'>";
//     echo '<tr>
//     <td style="border-bottom:1px solid #000000">OrderNo</td> 
//                <td style="border-bottom:1px solid #000000">Customer Name</td>  
//                <td style="border-bottom:1px solid #000000">Contact No</td>
//                <td style="border-bottom:1px solid #000000">Delivery Date</td>
//                <td style="border-bottom:1px solid #000000">Customer Code</td>
//                </tr>';
//      $result="SELECT `custbranch`.`brname` , `custbranch`.`phoneno` , `custbranch`.`faxno` , `salesorders`.`orderno` , salesorders.branchcode, `salesorders`.`deliverydate`
//FROM `salesorders`
//INNER JOIN `custbranch` ON ( `salesorders`.`branchcode` = `custbranch`.`branchcode` )
//LEFT JOIN `bio_installation_status` ON ( `bio_installation_status`.`orderno` = `salesorders`.`orderno` )
//WHERE COALESCE( bio_installation_status.close_status, 0 ) =0
//AND COALESCE( bio_installation_status.installed_date, 0 ) =0
//ORDER BY salesorders.deliverydate ASC";
//                                                                                                                                
//   $result_saleorder=DB_query($result,$db); 
// while($row=DB_fetch_array($result_saleorder)){
//     $ph=$row[phoneno]."-<br>".$row[faxno];
//                printf("<tr style='background:#A8A4DB'>                
//            <td width='150px'><a id=".$row['orderno']."  href='' onclick='ordernumber(this.id);return false'>%s<a></td>
//            <td width='100px'>%s</td>
//            <td width='100px'>%s</td>
//            <td width='100px'>%s</td>
//            <td width='100px'>%s</td>
//                                   </tr>",
//        $row['orderno'],
//        $row['brname'],
//         $ph,
//        $row['deliverydate'],
//         $row['branchcode']      
//        ); 

//           }
//   echo "</table>";            

// 
// echo "</fieldset>";
//  echo "</div>";   
// echo "</td></tr></table>";
//}        
?>
<script type="text/javascript">
var maxLength=100;

function charLimit(el) {
    if (el.value.length > maxLength) return false;
    return true;
}
function characterCount(el) {
//var aa=0;
//var aa=el.value.length;
    var charCount = document.getElementById('charCount');
    if (el.value.length > maxLength) el.value = el.value.substring(0,maxLength);
    if (charCount) charCount.innerHTML = maxLength - el.value.length;
	
    return true;
}

function ordernumber(str)
{   //alert(str1);

window.location="bio_nwinstallationstatuses.php?ordno=" + str ;

}
 
function closecall()
{ 
if(document.getElementById('remarks1').value.length==0)
{
alert ("Please Enter a remarks!");

document.getElementById('remarks1').focus();
return;
}
if(document.getElementById('status').value==0)
{
alert ("Please select a status!");

document.getElementById('status').focus();
return;
}
 var statu=document.getElementById('status').value;
 var feedback=document.getElementById('fbcall').value;
 var x=0;
 if(feedback==3 && statu==1){
     var r=confirm("Do you want to close");
     if (r==true)
  {
  x=1;
  }
else
  {
  x=0;
  }
 }   
 var nowdate=document.getElementById('caldate').value;   
 var ordno=document.getElementById('ordernum').value;
 var pl_ststus=document.getElementById('plantstatus').value;
 var remark=document.getElementById('remarks1').value;
  

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
        
    document.getElementById("cll").innerHTML=xmlhttp.responseText;
	window.location.href="bio_nwinstallationstatus.php";
	self.location="bio_nwinstallationstatus.php";  
//      $('#dinhide').show(); 
    }
  } 
  
  
xmlhttp.open("GET","bio_installation_close.php?calno=" + feedback+"&cdate="+nowdate+"&ord="+ordno+"&p_ststus="+pl_ststus+"&remarkk="+remark+"&statuss="+statu+"&close="+x,true); 
xmlhttp.send();
}

function duedate()
{ 
 var str1=document.getElementById('installed_date').value;  

if (str1=="")
  {
  document.getElementById("duedate").innerHTML="";
  return;
  }

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {             
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    { 
    document.getElementById("duedate").innerHTML=xmlhttp.responseText;
    document.getElementById("duedate1").focus();
    }
  }
xmlhttp.open("GET","installationstatus.php?date=" + str1,true); 
xmlhttp.send();
}

function changestatus(str)
{ 

 var ordno=document.getElementById('ordernum').value;

if (str=="")
  {
  document.getElementById("call_log").innerHTML="";
  return;
  }

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {             
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    { 
   document.getElementById("cl").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","nwcall_log.php?status=" +str + "&order=" +ordno ,true);
xmlhttp.send();//
}
function showcalstatus(str)
{
    
   var ordno=document.getElementById('ordernum').value; 
   // window.open("call_log.php");
  controlWindow=window.open("vwcall_log.php?type="+str+"&ordno="+ordno,"viewlog", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=800,height=600");    
}
function showcalstatus1(str)
{
    
   var ordno=document.getElementById('ordernum').value; 
   // window.open("call_log.php");
  controlWindow=window.open("bio_incidentRegister.php?selectOrder="+ordno,"viewlog", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=800,height=600");    
}
function installationview(str)
{
    window.location="bio_nwinstallationstatus.php?status=" + str;
}
function Customer_Maintenance()
{
   var derno=document.getElementById('deno').value; 
   // window.open("call_log.php");
  controlWindow=window.open("Customers.php?DebtorNo="+derno,"viewlog", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=800,height=600");    
}


function createleads()
{
   var derno=document.getElementById('deno').value; 
  
  controlWindow=window.open("bio_createleads_ref.php?DebtorNo="+derno,"viewlog", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=1000,height=600");    
}
function docview()
  {
   var ord=document.getElementById('order').value ;
    if (ord=="")
  {
  return;
  }

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {             
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    { 
   document.getElementById("doc").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","bio_docviewajax.php?order=" +ord ,true);
xmlhttp.send();                                                         
  }
</script>