!function(e){var r={};function t(a){if(r[a])return r[a].exports;var o=r[a]={i:a,l:!1,exports:{}};return e[a].call(o.exports,o,o.exports,t),o.l=!0,o.exports}t.m=e,t.c=r,t.d=function(e,r,a){t.o(e,r)||Object.defineProperty(e,r,{enumerable:!0,get:a})},t.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},t.t=function(e,r){if(1&r&&(e=t(e)),8&r)return e;if(4&r&&"object"==typeof e&&e&&e.__esModule)return e;var a=Object.create(null);if(t.r(a),Object.defineProperty(a,"default",{enumerable:!0,value:e}),2&r&&"string"!=typeof e)for(var o in e)t.d(a,o,function(r){return e[r]}.bind(null,o));return a},t.n=function(e){var r=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(r,"a",r),r},t.o=function(e,r){return Object.prototype.hasOwnProperty.call(e,r)},t.p="/",t(t.s=8)}({8:function(e,r,t){e.exports=t(9)},9:function(e,r){var t={series:[{name:"2020",type:"column",data:[23,42,35,27,43,22,17,31,22,22,12,16]},{name:"2019",type:"line",data:[23,32,27,38,27,32,27,38,22,31,21,16]}],chart:{height:280,type:"line",toolbar:{show:!1}},stroke:{width:[0,3],curve:"smooth"},plotOptions:{bar:{horizontal:!1,columnWidth:"20%"}},dataLabels:{enabled:!1},legend:{show:!1},colors:["#5664d2","#1cbb8c"],labels:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"]};new ApexCharts(document.querySelector("#line-column-chart"),t).render();t={series:[42,26,15],chart:{height:230,type:"donut"},labels:["Product A","Product B","Product C"],plotOptions:{pie:{donut:{size:"75%"}}},dataLabels:{enabled:!1},legend:{show:!1},colors:["#5664d2","#1cbb8c","#eeb902"]};new ApexCharts(document.querySelector("#donut-chart"),t).render();var a={series:[72],chart:{type:"radialBar",wight:60,height:60,sparkline:{enabled:!0}},dataLabels:{enabled:!1},colors:["#5664d2"],stroke:{lineCap:"round"},plotOptions:{radialBar:{hollow:{margin:0,size:"70%"},track:{margin:0},dataLabels:{show:!1}}}};new ApexCharts(document.querySelector("#radialchart-1"),a).render();a={series:[65],chart:{type:"radialBar",wight:60,height:60,sparkline:{enabled:!0}},dataLabels:{enabled:!1},colors:["#1cbb8c"],stroke:{lineCap:"round"},plotOptions:{radialBar:{hollow:{margin:0,size:"70%"},track:{margin:0},dataLabels:{show:!1}}}};new ApexCharts(document.querySelector("#radialchart-2"),a).render();t={series:[{data:[23,32,27,38,27,32,27,34,26,31,28]}],chart:{type:"line",width:80,height:35,sparkline:{enabled:!0}},stroke:{width:[3],curve:"smooth"},colors:["#5664d2"],tooltip:{fixed:{enabled:!1},x:{show:!1},y:{title:{formatter:function(e){return""}}},marker:{show:!1}}};new ApexCharts(document.querySelector("#spak-chart1"),t).render();t={series:[{data:[24,62,42,84,63,25,44,46,54,28,54]}],chart:{type:"line",width:80,height:35,sparkline:{enabled:!0}},stroke:{width:[3],curve:"smooth"},colors:["#5664d2"],tooltip:{fixed:{enabled:!1},x:{show:!1},y:{title:{formatter:function(e){return""}}},marker:{show:!1}}};new ApexCharts(document.querySelector("#spak-chart2"),t).render();t={series:[{data:[42,31,42,34,46,38,44,36,42,32,54]}],chart:{type:"line",width:80,height:35,sparkline:{enabled:!0}},stroke:{width:[3],curve:"smooth"},colors:["#5664d2"],tooltip:{fixed:{enabled:!1},x:{show:!1},y:{title:{formatter:function(e){return""}}},marker:{show:!1}}};new ApexCharts(document.querySelector("#spak-chart3"),t).render(),$("#usa-vectormap").vectorMap({map:"us_merc_en",backgroundColor:"transparent",regionStyle:{initial:{fill:"#e8ecf4",stroke:"#74788d","stroke-width":1,"stroke-opacity":.4}}}),$(document).ready((function(){$(".datatable").DataTable({lengthMenu:[5,10,25,50],pageLength:5,columns:[{orderable:!1},{orderable:!0},{orderable:!0},{orderable:!0},{orderable:!0},{orderable:!0},{orderable:!1}],order:[[1,"asc"]],language:{paginate:{previous:"<i class='mdi mdi-chevron-left'>",next:"<i class='mdi mdi-chevron-right'>"}},drawCallback:function(){$(".dataTables_paginate > .pagination").addClass("pagination-rounded")}})}))}});