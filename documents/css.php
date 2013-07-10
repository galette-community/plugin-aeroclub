<?php

/***************************************************************************/
/*   CSS KUJUNDUSE MUUTMISEKS                                              */
/*                                                                         */
/*   CSS FOR TWEAKING THE DESIGN                                           */
/***************************************************************************/

function css()
{
?>
<style type="text/css">

/* General styles */

BODY {
	background-color:#FFFFFF;
	font-family:Verdana;
	font-size:small;
}

A {
	color: #000000;
	text-decoration: none;
}

A:hover {
	text-decoration: underline;
}

#top {
	width:100%;
	padding-bottom: 20px;
}

#top a span, #top a:hover, #top a span:hover{
	color:#68a9d2;
	font-weight:bold;
	text-align:center;
	font-size:large;
}

#top a {
	display:block;
	padding:20px 0 0 0;
}

#top span {
	display:block;
}

div.subtitle{
	width:80%;
	margin: 0 auto;
	color:#68a9d2;
	text-align:center;
}

#frame {
	border: 1px solid #CDD2D6;
	text-align:left;
	position: relative;
	margin: 0 auto;
	max-width:680px;
	overflow:hidden;
}

#error {
	max-width:450px;
	background-color:#FFE4E1;
	color:#000000;
	padding:7pt;
	position: relative;
	margin: 10pt auto;
	text-align:center;
	border: 1px dotted #CDD2D6;
}

input {
	border: 1px solid #CDD2D6;
}

.bar{
	width:100%;
	clear:both;
	height:1px;
}

/* File list */

table.table {
	width:100%;
	border-collapse: collapse; 
}

table.table td{
	padding:3px;
}

table.table tr.row.two {
	background-color:#fcfdfe;
}

table.table tr.row.one {
	background-color:#f8f9fa;
}

table.table tr.row td.icon {
	width:25px;
	padding-top:3px;
	padding-bottom:1px;
}

table.table td.del {
	width:25px;
}

table.table tr.row td.size {
	width: 100px; 
	text-align: right;
}

table.table tr.row td.changed {
	width: 150px;
	text-align: center;
}

table.table tr.header img {
	vertical-align:bottom;
}

table img{
	border:0;
}

/* Info area */ 

#info {
	color:#000000;
	font-family:Verdana;
	max-width:680px;
	position: relative;
	margin: 0 auto;
	text-align:center;
}

/* Thumbnail area */

#thumb {
	position:absolute;
	border: 1px solid #CDD2D6;
	background:#f8f9fa;
	display:none;
	padding:3px;
}

#thumb img {
	display:block;
}

/* Login bar (at the bottom of the page) */
#login_bar {
	margin: 0 auto;
	margin-top:2px;
	max-width:680px;
        visibility: hidden;
}

#login_bar input.submit{
	float:right;
}

/* Upload area */

#upload {
	margin: 0 auto;
	margin-top:2px;
	max-width:680px;
}

#upload #password_container {
	margin-right:20px;
}

#upload #newdir_container, #upload #password_container {
	float:left;
}

#upload #upload_container{
	float:right;
}

#upload input.upload_dirname, #upload input.upload_password{
	width:140px;
}

#upload input.upload_file{
	font-size:small;
}

/* Breadcrumbs */

div.breadcrumbs {
	display:block;
	padding:1px 3px;
	color:#cccccc;
	font-size:x-small;
}

div.breadcrumbs a{
	display:inline-block;
	color:#cccccc;
	padding:2px 0;
	font-size:small;
}

/* Login area */

#login {
	max-width:280px;
	text-align:right;
	margin:15px auto 50px auto;
}

#login div {
	display:block;
	width:100%;
	margin-top:5px;
}

#login label{
	width: 120px;
	text-align: right;
}

/* Mobile interface */

body.mobile #frame, body.mobile #info, body.mobile #upload {
	max-width:none;
}

body.mobile {
	font-size:medium;
}

body.mobile a.item {
	display:block;
	padding:10px 0;
}

body.mobile a.item span.size {
	float:right;
	margin-left:10px;
}

body.mobile table.table {
	margin-bottom:30px;
}

body.mobile table.table tr td {
	border-top: 1px solid #CDD2D6;
}

body.mobile table.table tr.last td {
	border-bottom: 1px solid #CDD2D6;
}

body.mobile #top {
	padding-bottom:3px;
}

body.mobile #top a {
	padding-top:3px;
}

body.mobile #upload #password_container, body.mobile #upload #upload_container, body.mobile #upload #newdir_container {
	float:none;
	margin-top:5px;
}

body.mobile #upload input.upload_dirname, body.mobile #upload input.upload_password{
	width:240px;
}

body.mobile #upload {
	margin-bottom:15px;
}

</style>

<?php
}

?>
