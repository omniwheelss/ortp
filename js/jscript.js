/* #########

	Ajax Function For Change Email Address

#############
*/


function Ajax_Func_Change_Pass(Valid_Arr,Url,Img_Div,Output_Div){

	var Split_Arr = Valid_Arr.split(",");

	for(i = 0;i < Split_Arr.length; i++){
		var Field_Val = $("#"+Split_Arr[i]).val();
		var Field_Type = $("#"+Split_Arr[i]).get(0).type;
		if(Field_Type == 'text' || Field_Type == 'password' || Field_Type == 'textarea' ){
			if(Field_Val == ''){
				$("#"+Split_Arr[i]).css('background-color', '#F6DDD8');
				$("#"+Split_Arr[i]+"v").show('animate');
				$("#"+Split_Arr[i]+"v").html('Please enter a value.');
				$("#Main_Error_Div").show('slow');
				var E = 1;
			}
			
		}
		
		if(Field_Type == 'select-one'){
				if(Field_Val == '0'){
					$("#"+Split_Arr[i]).css('background-color', '#F6DDD8');
					$("#"+Split_Arr[i]+"v").show('animate');
					$("#"+Split_Arr[i]+"v").html('Select the Values');
					var E = 1;
				}
		}

	}

	if(E == 1){
			return false;
	}
	else{
		document.getElementById(Img_Div).style.display= 'block';

		var xmlhttp;
		if (window.XMLHttpRequest){
		  xmlhttp=new XMLHttpRequest();
		}
		else if (window.ActiveXObject){
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		else{
		  alert("Your browser does not support XMLHTTP!");
		}
		xmlhttp.onreadystatechange=function(){
			if(xmlhttp.readyState==4){
				$("#"+Img_Div).hide();
				$("#Change_Pass_Div").hide();
				$("#"+Output_Div).show();
				document.getElementById(Output_Div).innerHTML=xmlhttp.responseText;
			}
		}
		var Old_Pass = $("#Old_Pass").val();
		var Pass = $("#Pass").val();
		Url1 = Url+"?Old_Pass="+Old_Pass+"&Pass="+Pass;
		xmlhttp.open("GET",Url1,true);
		xmlhttp.send(null);
	}
		
}