$(document).ready(main);
$(document).ready(opciones);
var contador=1;
function main(){
	$('#imgMenu').click(function(){
		if(contador==1){
			
		$('ul').animate({
				left: '0'
			});		
			contador = 0;
		}
		else{
			contador=1;
		$('ul').animate({
				left: '-100%'
			});		
		}
	});

		$('#contenedor').click(function(){
		if(contador==0){
			$('ul').animate({
				left: '-100%'
			});	
			contador = 1;
		}

	});
	//Mostramos y ocultamos submenus

	
};
contador2=1;
function opciones(){
	$('#imgOpciones').click(function(){
		if(contador2==1){
			
		$('.btnsHerramientas').animate({
				right: '0'
			});		
			contador2 = 0;
		}
		else{
			contador2=1;
		$('.btnsHerramientas').animate({
				right: '-300px'
			});		
		}
	});

	$('#pblContieneMateriales').click(function(){
		if(contador2==0){
			$('.btnsHerramientas').animate({
				right: '-300px'
			});	
			contador2 = 1;
		}

	});
        	$('#bodypanelHerramientas').click(function(){
		if(contador2==0){
			$('.btnsHerramientas').animate({
				right: '-300px'
			});	
			contador2 = 1;
		}

	});
	//Mostramos y ocultamos submenus

	
};

