<?php

session_start();


?>
<style>
@font-face {
	font-family: Backfired;
	src: url(https://proyectojp.com/static/font/Stoner_Demo.ttf);
}
@font-face {
	font-family: VersalFont;
	src: url(https://proyectojp.com/static/font/Abstract.ttf);
}
@font-face {
	font-family: Impact;
	src: url(https://proyectojp.com/static/font/Impact.ttf);
}
@font-face {
	font-family: Serona;
	src: url(https://proyectojp.com/static/font/Serona.ttf);
}
@font-face {
	font-family: Yaroslav;
	src: url(https://proyectojp.com/static/font/Yaroslav.ttf);
}
* {
  box-sizing: border-box;
  scrollbar-width: thin;
  scrollbar-color: rgba(47, 91, 234, 0.7);
}

/* Works on Chrome, Edge, and Safari */
*::-webkit-scrollbar {
  width: 12px;
}

*::-webkit-scrollbar-track {
  background: transparent;
  border-radius: 20px;
}

*::-webkit-scrollbar-thumb {
  background-color: rgba(47, 91, 234, 0.175);
  border-radius: 20px;
  border: 2px solid rgba(47, 91, 234, 0.5);
}
body {
	background: <?php if ($_SESSION['theme'] == 'dark') { echo "#2b2d31"; } else { echo "#f2f3f5"; } ?> !important;
    height: 100vh;
    display: flex;
    flex-direction: column;
}

footer {
    margin-top: auto;
	background: linear-gradient(90deg, rgba(2,0,36,0.1) 0%, rgba(0,0,0,0.1) 35%, rgba(2,0,36,0.1) 100%); 
}

.container-uxs{ 
  position: relative;
  container: inline-size;
  top: 20%;
  left: 2.5%;
  right: 2.5%;
  width: 95%;
}

.unique {
	min-height: 85vh;
}
.border-top-unique {
	border-top: 3px solid <?php if ($_SESSION['theme'] == 'dark') { echo "red"; } else { echo "blue"; } ?>;
	border-radius: 5px;
}


.nav-link {
	color: <?php if ($_SESSION['theme'] == 'dark') { echo "#f2f3f5"; } else { echo "#38393b"; } ?> !important;
	margin-right: 10px;
	border-bottom: 2px solid transparent;
}
.nav-link:hover {
	color: <?php if ($_SESSION['theme'] == 'dark') { echo "#f2f3f5"; } else { echo "#38393b"; } ?> !important;
	margin-right: 10px;
	border-bottom: 2px solid <?php if ($_SESSION['theme'] == 'dark') { echo "#f2f3f5"; } else { echo "#2b2d31"; } ?>;
}
.active {
	color: <?php if ($_SESSION['theme'] == 'dark') { echo "#f2f3f5"; } else { echo "#2b2d31"; } ?> !important;
	border-bottom: 2px solid <?php if ($_SESSION['theme'] == 'dark') { echo "#f2f3f5"; } else { echo "#2b2d31"; } ?>;
	margin-right: 10px;
}


.t-tbody td {
	border: none;
}
.separate-btn {
	margin-right: 5px;
	margin-bottom: 5px;
}

.spinner {
  /* Spinner size and color */
  width: 1.5rem;
  height: 1.5rem;
  border-top-color: #444;
  border-left-color: #444;

  /* Additional spinner styles */
  animation: spinner 400ms linear infinite;
  border-bottom-color: transparent;
  border-right-color: transparent;
  border-style: solid;
  border-width: 2px;
  border-radius: 50%;  
  box-sizing: border-box;
  display: inline-block;
  vertical-align: middle;
}

/* Animation styles */
@keyframes spinner {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}


/* Optional — create your own variations! */
.spinner-large {
  width: 5rem;
  height: 5rem;
  border-width: 6px;
}

.spinner-slow {
  animation: spinner 1s linear infinite;
}

.spinner-blue {
  border-top-color: #09d;
  border-left-color: #09d;
}
.card-unixsystem {
	width: 97%;
	position: relative;
	background: <?php if ($_SESSION['theme'] == 'dark') { echo "#2b2d31"; } else { echo "#f2f3f5"; } ?>;
	border: 2px solid <?php if ($_SESSION['theme'] == 'dark') { echo "#313338"; } else { echo "#ccc"; } ?>;
	color: <?php if ($_SESSION['theme'] == 'dark') { echo "#fff"; } else { echo "#2b2d31"; } ?>;
	border-radius: 15px;
	margin-bottom: 15px;
	margin-right: 30px;
	padding: 10px;
}
.wrapper1 {
  display: grid;
  grid-template-columns: repeat(6, 1fr);
  grid-auto-rows: minmax(100px, auto);
}
.wrappers {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  grid-auto-rows: minmax(100px, auto);
}
.wrapper-leaderboard {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  grid-auto-rows: minmax(100px, auto);
}
.wrapper-auto {
  display: grid;
  grid-template-columns: repeat(7, auto);
}



.wrapper-staff {
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  gap: 10px 20px; /* row-gap column gap */
  row-gap: 10px;
  column-gap: 20px;
}
.card-admin {
	background: <?php if ($_SESSION['theme'] == 'dark') { echo "#313338"; } else { echo "#fff"; } ?>;
	padding: 15px 0px;
	border-radius: 3px;
}

.card-admin .card-staff {
	flex: 1;
	flex-basis: 30%;
	background: <?php if ($_SESSION['theme'] == 'dark') { echo "#404247"; } else { echo "#e7e3e1"; } ?>;
	padding: 15px;
	border-radius: 5px;
	margin-bottom: 10px;
	width: 100%;
}
.card-admin .amodal:hover {
	text-decoration: underline;
}


.card-staff-transparent {
	flex: 1;
	flex-basis: 32%;
	background: transparent !important;
	padding: 5px;
	border-radius: 2px;
	margin-bottom: 10px;
	width: 100%;
}


.card-staff-transparents {
	background: transparent !important;
	padding: 5px;
	border-radius: 2px;
	margin-bottom: 10px;
	width: 100%;
}




input[type="submit"] {
	margin-bottom: 5px;
}

.circle-info-danger {
	border: 0px solid #fff;
	border-radius: 15px;
	padding: 5px;
	background: #DC4C64;
	font-size: 12px;
}
.circle-info-success {
	border: 0px solid #fff;
	border-radius: 15px;
	padding: 5px;
	background: #14A44D;
	font-size: 12px;
}
.circle-info-warning {
	border: 0px solid #fff;
	border-radius: 15px;
	padding: 5px;
	background: #E4A11B;
	font-size: 12px;
}
.circle-info-primary {
	border: 0px solid #fff;
	border-radius: 15px;
	padding: 5px;
	background: #3B71CA;
	font-size: 12px;
}
.circle-info-secondary {
	border: 0px solid #fff;
	border-radius: 15px;
	padding: 5px;
	background: #9FA6B2;
	font-size: 12px;
}

.breadcrumb select{
	border: 0px solid #fff;
	background: transparent !important;
	padding: 6px;
}

.search-button-input {
	border: 0px;
	background: transparent !important;
	padding: 1px;
}

.bgs-success {
  /* circle shape, size and position */
  position: relative;
  right: -0.7em;
  top: -0.7em;
  min-width: 1.6em; /* or width, explained below. */
  height: 1.6em;
  border-radius: 0.8em; /* or 50%, explained below. */
  border: 0.05em solid green;
  background-color: green;

  /* number size and position */
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 0.8em;
  color: <?php if ($_SESSION['theme'] == 'dark') { echo "#e7e3e1"; } else { echo "#313338"; } ?>;
	
}
.bgs-danger {
  /* circle shape, size and position */
  position: relative;
  right: -0.7em;
  top: -0.7em;
  min-width: 1.6em; /* or width, explained below. */
  height: 1.6em;
  border-radius: 0.8em; /* or 50%, explained below. */
  border: 0.05em solid red;
  background-color: red;

  /* number size and position */
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 0.8em;
  color: <?php if ($_SESSION['theme'] == 'dark') { echo "#e7e3e1"; } else { echo "#313338"; } ?>;
	
}
.bg-confirm {
	font-size: 12px; padding: 2px 8px; border-radius: 4px; font-weight: 400; color: <?php if ($_SESSION['theme'] == 'dark') { echo "#e7e3e1"; } else { echo "#313338"; } ?>; background-color: #3F7EE4; display: inline-block; text-decoration: none;
}
.bg-confirm:hover {
	font-size: 12px; padding: 2px 8px; border-radius: 4px; font-weight: 400; color: <?php if ($_SESSION['theme'] == 'dark') { echo "#e7e3e1"; } else { echo "#313338"; } ?>; background-color: #2b579e; display: inline-block; text-decoration: none;
}
.bg-uxs {
	font-size: 12px; padding: 2px 8px; border-radius: 4px; font-weight: 400; color: <?php if ($_SESSION['theme'] == 'dark') { echo "#e7e3e1"; } else { echo "#313338"; } ?>; background-color: #8d19cf; display: inline-block; text-decoration: none;
}
.bg-uxs:hover {
	font-size: 12px; padding: 2px 8px; border-radius: 4px; font-weight: 400; color: <?php if ($_SESSION['theme'] == 'dark') { echo "#e7e3e1"; } else { echo "#313338"; } ?>; background-color: #a200ff; display: inline-block; text-decoration: none;
}

.bguxs-success {
	font-size: 12px; padding: 2px 8px; border-radius: 4px; font-weight: 400; color: <?php if ($_SESSION['theme'] == 'dark') { echo "#e7e3e1"; } else { echo "#313338"; } ?>; background-color: #14A44D; display: inline-block; text-decoration: none;
}
.bguxs-success:hover {
	font-size: 12px; padding: 2px 8px; border-radius: 4px; font-weight: 400; color: <?php if ($_SESSION['theme'] == 'dark') { echo "#e7e3e1"; } else { echo "#313338"; } ?>; background-color: #197c40; display: inline-block; text-decoration: none;
}

.bguxs-danger {
	font-size: 12px; padding: 2px 8px; border-radius: 4px; font-weight: 400; color: <?php if ($_SESSION['theme'] == 'dark') { echo "#e7e3e1"; } else { echo "#313338"; } ?>; background-color: #DC4C64; display: inline-block; text-decoration: none;
}
.bguxs-danger:hover {
	font-size: 12px; padding: 2px 8px; border-radius: 4px; font-weight: 400; color: <?php if ($_SESSION['theme'] == 'dark') { echo "#e7e3e1"; } else { echo "#313338"; } ?>; background-color: #ab3d50; display: inline-block; text-decoration: none;
}

.bguxs-info {
	font-size: 12px; padding: 2px 8px; border-radius: 4px; font-weight: 400; color: <?php if ($_SESSION['theme'] == 'dark') { echo "#e7e3e1"; } else { echo "#313338"; } ?>; background-color: #54B4D3; display: inline-block; text-decoration: none;
}
.bguxs-info:hover {
	font-size: 12px; padding: 2px 8px; border-radius: 4px; font-weight: 400; color: <?php if ($_SESSION['theme'] == 'dark') { echo "#e7e3e1"; } else { echo "#313338"; } ?>; background-color: #428ea6; display: inline-block; text-decoration: none;
}

.btn-transparent {
	border: 0px;
	background: transparent !important;
	
}

.bguxs-success-2 {
	font-size: 26px; padding: 2px 8px; border-radius: 4px; font-weight: 600; color: <?php if ($_SESSION['theme'] == 'dark') { echo "#e7e3e1"; } else { echo "#313338"; } ?>; background-color: #14A44D; display: inline-block; text-decoration: none;
}
.bguxs-success-2:hover {
	font-size: 26px; padding: 2px 8px; border-radius: 4px; font-weight: 800; color: <?php if ($_SESSION['theme'] == 'dark') { echo "#e7e3e1"; } else { echo "#313338"; } ?>; background-color: #197c40; display: inline-block; text-decoration: none;
}

.bguxs-danger-2 {
	font-size: 26px; padding: 2px 8px; border-radius: 4px; font-weight: 600; color: <?php if ($_SESSION['theme'] == 'dark') { echo "#e7e3e1"; } else { echo "#313338"; } ?>; background-color: #DC4C64; display: inline-block; text-decoration: none;
}
.bguxs-danger-2:hover {
	font-size: 26px; padding: 2px 8px; border-radius: 4px; font-weight: 800; color: <?php if ($_SESSION['theme'] == 'dark') { echo "#e7e3e1"; } else { echo "#313338"; } ?>; background-color: #ab3d50; display: inline-block; text-decoration: none;
}


.table-lp {
	width: 100%;
	padding: 250px;
	background: <?php if ($_SESSION['theme'] == 'dark') { echo "#313338"; } else { echo "#fff"; } ?>;
}

.table-lp .thead-lp tr {
	background: <?php if ($_SESSION['theme'] == 'dark') { echo "#313338"; } else { echo "#f2f3f5"; } ?>;
	padding: 30px;
	color: <?php if ($_SESSION['theme'] == 'dark') { echo "#f2f3f5"; } else { echo "#313338"; } ?>;
	border-radius: 20px 20px 0px 0px;
}
.table-lp .thead-lp th {
	background: <?php if ($_SESSION['theme'] == 'dark') { echo "#313338"; } else { echo "#f2f3f5"; } ?>;
	padding: 5px;
	color: <?php if ($_SESSION['theme'] == 'dark') { echo "#f2f3f5"; } else { echo "#313338"; } ?>;
}
.table-lp .tbody-lp tr {
	border-bottom: 1px solid #ccc;
}
.table-lp .tbody-lp td {
	padding: 15px;
}

.table-uxs {
	width: 49%;
	background: <?php if ($_SESSION['theme'] == 'dark') { echo "#2b2d31"; } else { echo "#f2f3f5"; } ?>;
	color: <?php if ($_SESSION['theme'] == 'dark') { echo "#f2f3f5"; } else { echo "#313338"; } ?>;
}

.table-uxs .thead-uxs tr {
	background: <?php if ($_SESSION['theme'] == 'dark') { echo "#2b2d31"; } else { echo "#f2f3f5"; } ?>;
	padding: 30px;
	color: <?php if ($_SESSION['theme'] == 'dark') { echo "#f2f3f5"; } else { echo "#313338"; } ?>;
	border-radius: 20px 20px 0px 0px;
}
.table-uxs .thead-uxs th {
	background: <?php if ($_SESSION['theme'] == 'dark') { echo "#404247"; } else { echo "#f2f3f5"; } ?>;
	padding: 5px;
	color: <?php if ($_SESSION['theme'] == 'dark') { echo "#f2f3f5"; } else { echo "#313338"; } ?>;
}
.table-uxs .tbody-uxs tr {
	border-bottom: 1px solid #ccc;
}
.table-uxs .tbody-uxs td {
	padding: 15px;
}


.alert-disabled {
	display: none;
}

.alert-group>.alert:first-child:not(:last-child){
    -webkit-border-top-left-radius:5px;
    -webkit-border-top-right-radius:5px;
    -webkit-border-bottom-right-radius:0;
    -webkit-border-bottom-left-radius:0;
       -moz-border-radius-topleft:5px;
       -moz-border-radius-topright:5px;
       -moz-border-radius-bottomright:0;
       -moz-border-radius-bottomleft:0;
            border-top-left-radius:5px;
            border-top-right-radius:5px;
            border-bottom-right-radius:0;
            border-bottom-left-radius:0;
    margin-bottom:0
}
.alert-group>.alert:not(:first-child):not(:last-child){
    -webkit-border-radius:0;
       -moz-border-radius:0;
            border-radius:0;
    border-top:0;
    margin-bottom:0
}
.alert-group>.alert:last-child:not(:first-child){
    -webkit-border-top-left-radius:0;
    -webkit-border-top-right-radius:0;
    -webkit-border-bottom-right-radius:5px;
    -webkit-border-bottom-left-radius:5px;
       -moz-border-radius-topleft:0;
       -moz-border-radius-topright:0;
       -moz-border-radius-bottomright:5px;
       -moz-border-radius-bottomleft:5px;
            border-top-left-radius:0;
            border-top-right-radius:0;
            border-bottom-right-radius:5px;
            border-bottom-left-radius:5px;
    border-top:0
}


@media (max-width: 1200px) {
	.table-uxs {
	  width: 100%;
	  background: <?php if ($_SESSION['theme'] == 'dark') { echo "#313338"; } else { echo "#e7e3e1"; } ?>;
	}
	.card-container {
		width: 30%;
	}
}

@media (max-width: 1000px) {
	.card-container {
		width: 30%;
	}
}

@media (max-width: 800px) {
	.wrapper-staff {
	  flex-direction: column;
	}
	  
	.wrapper1 {
	  display: grid;
	  grid-template-columns: repeat(1, 1fr);
	  grid-auto-rows: minmax(100px, auto);
	}
	.wrappers {
	  display: grid;
	  grid-template-columns: repeat(1, 1fr);
	  grid-auto-rows: minmax(100px, auto);
	}
	.wrapper-leaderboard {
	  display: grid;
	  grid-template-columns: repeat(1, 1fr);
	  grid-auto-rows: minmax(100px, auto);
	}
	.wrapper-auto {
	  display: grid;
	  grid-template-columns: repeat(1, auto);
	}
	.table-uxs {
	  width: 100%;
	  background: <?php if ($_SESSION['theme'] == 'dark') { echo "#313338"; } else { echo "#e7e3e1"; } ?>;
	}
	.card-container {
		width: 50%;
	}
}
.leaderboard-table {
	width: 100%;
	background: <?php if ($_SESSION['theme'] == 'dark') { echo "#313338"; } else { echo "#fff"; } ?>;
	padding: 30px;
	border-radius: 10px;
}
.leaderboard-table .lb-thead {
	border-radius: 10px;
}
.leaderboard-table .lb-tbody {
	border-radius: 10px;
}
.leaderboard-table .lb-thead th {
	padding: 10px 15px;
}
.leaderboard-table .lb-tbody tr:nth-last-child(odd) {
	background: <?php if ($_SESSION['theme'] == 'dark') { echo "#404247"; } else { echo "#f3f1f1"; } ?>;
}
.leaderboard-table .lb-tbody tr:nth-last-child(even) {
	background: <?php if ($_SESSION['theme'] == 'dark') { echo "#434549"; } else { echo "#e9e8e8"; } ?>;
}
.leaderboard-table .lb-tbody tr:nth-last-child(odd):hover {
	background: <?php if ($_SESSION['theme'] == 'dark') { echo "#313338"; } else { echo "#fff"; } ?>;
}
.leaderboard-table .lb-tbody tr:nth-last-child(even):hover {
	background: <?php if ($_SESSION['theme'] == 'dark') { echo "#313338"; } else { echo "#fff"; } ?>;
}
.leaderboard-table .nickname {
	color: <?php if ($_SESSION['theme'] == 'dark') { echo "#fff"; } else { echo "#313338"; } ?> !important;
}
.leaderboard-table .nickname:hover {
	color: #287ccf !important;
	text-decoration: underline;
}
.leaderboard-table .lb-tbody td {
	padding: 15px;
}
.leaderboard-table .lb-tbody .circle-bound-one {
	font-weight: 400;
	background: <?php if ($_SESSION['theme'] == 'dark') { echo "#dddd43"; } else { echo "#dddd43"; } ?>;
	border-radius: 50%;
	color: <?php if ($_SESSION['theme'] == 'dark') { echo "#000"; } else { echo "#fff"; } ?>;
	width: 100%;
	padding: 20% 35% 20% 35%;
}
.leaderboard-table .lb-tbody .circle-bound-two {
	font-weight: 400;
	background: <?php if ($_SESSION['theme'] == 'dark') { echo "#afaca7"; } else { echo "#afaca7"; } ?>;
	border-radius: 50%;
	color: <?php if ($_SESSION['theme'] == 'dark') { echo "#000"; } else { echo "#fff"; } ?>;
	width: 100%;
	padding: 20% 35% 20% 35%;
}
.leaderboard-table .lb-tbody .circle-bound-three {
	font-weight: 400;
	background: <?php if ($_SESSION['theme'] == 'dark') { echo "#8b643e"; } else { echo "#8b643e"; } ?>;
	border-radius: 50%;
	color: <?php if ($_SESSION['theme'] == 'dark') { echo "#000"; } else { echo "#fff"; } ?>;
	width: 100%;
	padding: 20% 35% 20% 35%;
}
.leaderboard-table .lb-tbody .circle-bound-other {
	font-weight: 400;
	background: <?php if ($_SESSION['theme'] == 'dark') { echo "#292a2e"; } else { echo "#fff"; } ?>;
	border-radius: 50%;
	color: <?php if ($_SESSION['theme'] == 'dark') { echo "#fff"; } else { echo "#292a2e"; } ?>;
	width: 100%;
	padding: 20% 35% 20% 35%;
}

.tooltipuxs {
  position: relative;
  display: inline-block;
}

.tooltipuxs .tooltiptext {
  visibility: hidden;
  width: 120px;
  background-color: <?php if ($_SESSION['theme'] == 'dark') { echo "#292a2e"; } else { echo "#fff"; } ?>;
  color: <?php if ($_SESSION['theme'] == 'dark') { echo "#fff"; } else { echo "#292a2e"; } ?>;
  text-align: center;
  border-radius: 6px;
  padding: 5px 0;
  position: absolute;
  z-index: 1;
  bottom: 150%;
  left: 50%;
  margin-left: -60px;
}

.tooltipuxs .tooltiptext::after {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  margin-left: -5px;
  border-width: 5px;
  border-style: solid;
  border-color: <?php if ($_SESSION['theme'] == 'dark') { echo "#292a2e"; } else { echo "#fff"; } ?> transparent transparent transparent;
}

.tooltipuxs:hover .tooltiptext {
  visibility: visible;
}

select {
   background: <?php if ($_SESSION['theme'] == 'dark') { echo "#313338"; } else { echo "#f3f1f1"; } ?>;
   border: 1px solid <?php if ($_SESSION['theme'] == 'dark') { echo "#292a2e"; } else { echo "#f3f1f1"; } ?>;
   border-radius: 5px;
   font-size: 14px;
   padding: 8px;
   color: <?php if ($_SESSION['theme'] == 'dark') { echo "#f3f1f1"; } else { echo "#292a2e"; } ?>
}
.select-config {
   width: 250px;
   height: 38px;
}
.select-width-100 {
   width: 100%;
}
select:focus{ outline: none;}

.button-trans {
	border: transparent !important;
}




.border-card {
  background: <?php if ($_SESSION['theme'] == 'dark') { echo "#303134"; } else { echo "#e9e8e8"; } ?>;
  margin-bottom: 30px;
  display: flex;
  align-items: center;
  font-family: "Roboto";
  font-size: 14px;
  padding: 12px 16px;
  cursor: pointer;
  border-radius: 4px;
  border: 1px solid <?php if ($_SESSION['theme'] == 'dark') { echo "#313338"; } else { echo "#ccc"; } ?>;
  box-shadow: 0px 2px 1px 0px rgba(0, 0, 0, 0.1);
  transition: all 0.25s ease;
}
.border-card:hover {
  -webkit-transform: translateY(-1px);
          transform: translateY(-1px);
  box-shadow: 0 5px 10px 0 rgba(0, 0, 0, 0.1), 0 5px 10px 0 rgba(0, 0, 0, 0.1);
}
.border-card.over {
  background: rgba(70, 222, 151, 0.15);
  padding-top: 24px;
  padding-bottom: 24px;
  border: 2px solid #47DE97;
  box-shadow: 0 5px 10px 0 rgba(0, 0, 0, 0), 0 5px 10px 0 rgba(0, 0, 0, 0);
}
.border-card.over .card-type-icon {
  color: <?php if ($_SESSION['theme'] == 'dark') { echo "#fff"; } else { echo "#47DE97"; } ?> !important;
}
.border-card.over p {
  color: <?php if ($_SESSION['theme'] == 'dark') { echo "#fff"; } else { echo "#47DE97"; } ?> !important;
}

.content-wrapper {
  display: flex;
  justify-content: flex-start;
  width: 100%;
  white-space: nowrap;
  overflow: hidden;
  transition: all 0.25s ease;
}

.min-gap {
  flex: 0 0 40px;
}

.card-type-icon {
  flex-grow: 0;
  flex-shrink: 0;
  margin-right: 16px;
  font-weight: 400;
  color: <?php if ($_SESSION['theme'] == 'dark') { echo "#fff"; } else { echo "#323232"; } ?>;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  text-align: center;
  line-height: 40px;
  font-size: 14px;
  transition: all 0.25s ease;
}
.card-type-icon.with-border {
  color: <?php if ($_SESSION['theme'] == 'dark') { echo "#fff"; } else { echo "#323232"; } ?>;
  border: 1px solid <?php if ($_SESSION['theme'] == 'dark') { echo "#fff"; } else { echo "#323232"; } ?>;
}
.card-type-icon i {
  line-height: 40px;
}

.label-group {
  white-space: nowrap;
  overflow: hidden;
}
.label-group.fixed {
  flex-shrink: 0;
}
.label-group p {
  margin: 0px;
  line-height: 21px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.label-group p.title {
  color: <?php if ($_SESSION['theme'] == 'dark') { echo "#fff"; } else { echo "#323232"; } ?>;
  font-weight: 500;
}
.label-group p.title.cta {
  text-transform: uppercase;
}
.label-group p.caption {
  font-weight: 400;
  color: <?php if ($_SESSION['theme'] == 'dark') { echo "#aeaeae"; } else { echo "#7d7d7d"; } ?>;
}

.end-icon {
  margin-left: 16px;
}


.forum-Creation {
  background-color: <?php if ($_SESSION['theme'] == 'dark') { echo "#333"; } else { echo "#fff"; } ?>;
  margin: 100px auto;
  font-family: Raleway;
  padding: 40px;
  width: 70%;
  min-width: 300px;
  border-radius: 15px;
}

.forum-Creation input {
  background-color: <?php if ($_SESSION['theme'] == 'dark') { echo "#333"; } else { echo "#fff"; } ?>;
  color: <?php if ($_SESSION['theme'] == 'dark') { echo "#fff"; } else { echo "#333"; } ?>;
  padding: 10px;
  width: 100%;
  font-size: 17px;
  font-family: Raleway;
  border: 2px solid #aaaaaa;
}

/* Mark input boxes that gets an error on validation: */
.forum-Creation input.invalid {
  background-color: #ffdddd;
}

/* Hide all steps by default: */
.tab {
  display: none;
}

.forum-Creation button {
  background-color: #04AA6D;
  color: <?php if ($_SESSION['theme'] == 'dark') { echo "#fff"; } else { echo "#000"; } ?>;
  border: none;
  padding: 10px 20px;
  font-size: 17px;
  font-family: Raleway;
  cursor: pointer;
}

button:hover {
  opacity: 0.8;
}

#prevBtn {
  background-color: #bbbbbb;
}

/* Make circles that indicate the steps of the form: */
.step {
  height: 15px;
  width: 15px;
  margin: 0 2px;
  background-color: #bbbbbb;
  border: none;  
  border-radius: 50%;
  display: inline-block;
  opacity: 0.5;
}

.step.active {
  opacity: 1;
}

/* Mark the steps that are finished and valid: */
.step.finish {
  background-color: #04AA6D;
}
</style>