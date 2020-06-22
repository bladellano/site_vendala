
$(function(){

	setActiveMenu();
	/*Vendala*/

	/* Delete do produto */
	$('.btn-remove-product').click(function(e) {

		e.preventDefault();

		const c = confirm('Deseja excluir o produto?');
		const id = $(this).attr('idProduct');

		if(!c) return;

		$.ajax({
			url: '/products/'+id,
			method: 'DELETE',
			dataType: 'json',
			success: function(r) {
				if(r.data.success){
					document.location.reload(true);
					// return alert(r.data.msg)
				} else {
					return alert(r.data.msg)
				}
			}
		});			

	});

	/* Tela de login */
	$('#login-form').submit(function(e){

		e.preventDefault();

		const data = $(this).serializeArray();

		$.ajax({
			url: '/login',
			type: 'POST',
			dataType: 'json',
			data: data,	
			success: function(r){
				if(r.success ==true){
					alert(`${r.msg} Você será redirecionado.`);
					setTimeout(()=>{
						window.location = './';
					},800);
				} else {
					return alert(r.msg);
				}
			}
		}); 
	});
	/* Salvando produto */

	$('#form-product').submit(function(e) {

		e.preventDefault();

		const formData = new FormData($(this)[0]);

		if($('#kit').val()==1){			
			$.ajax({
				type: "POST",
				async: false,
				url: "/getting-ready-list",
				success: (kit)=>{
					formData.append('cod_product_kit', kit);
				}
			});	
		}

		$.ajax({ 
			url: '/save-product',
			type: 'POST',
			dataType: 'json',
			data: formData,
			processData: false,
			contentType: false,
			beforeSend: function(){
				$('#form-product')
				.find(':input').attr('disabled',true);
			},
			success: function(r){
				if(r.data.success){
					return alert(r.data.msg);
				} else {
					return alert(r.data.msg);
				}
			}
		}).always(function() {
			$('#form-product')
			.find(':input')
			.attr('disabled',false).val('');
			$('#show-tabela-itens').slideUp();
		});


	});

	/* Exibe entrada para produtos da lista. */

	$('#kit').change(function(e) {
		if($(this).val()==1){
			$('.wrap-kit').slideDown();
		} else {
			$('.wrap-kit').slideUp();
		}
	});

	/* Adiciona produtos da lista. */
	$('#add-product').click(function(e) {

		e.preventDefault();

		const qtd = $('#qtdProduct').val();
		const nameProduct = $('#listProducts').val();

		if(!qtd || !nameProduct)
			return alert('Por favor preencha os campos corretamente!');
		$('#show-tabela-itens').slideDown();
		
		const data = {
			idProduct:generateRandom(),
			qtd,
			nameProduct
		}

		$.post("/add-product-list",data,function(r) {
			$('#show-tabela-itens')
			.html(`<ul class="list-group">${generateRows(JSON.parse(r))}</u>`);
		});
	});

	/* Remove produtos da lista. */
	$("#show-tabela-itens").delegate(".btn-remove-item", "click",function(e){

		e.preventDefault();
		const idProduct = $(this).attr('idProduct');
		const c = confirm("Deseja realmente excluir?");

		if(c){
			$.ajax({
				url: 'remove-product-list',
				type: 'post',
				dataType: 'json',
				data: {idProduct},  			
				success:function(kit){  	
					$('#show-tabela-itens')
					.html(`<ul class="list-group">${generateRows(kit)}</u>`);
				}
			});
		}   	
	});

});//Fim

/* Gera linhas dos produtos adicionados na SESSION. */
function generateRows($obj){
	let row = "";
	for(item in $obj){
		row += `<li class="list-group-item">${$obj[item].qtd} x ${$obj[item].nameProduct} <button idProduct="${item}" 
		class="btn btn-danger btn-remove-item"> <i class="fa fa fa-trash"></i> </li>`;
	}
	return row;
}

/* Gera id aleatório */
function generateRandom(){
	let max = 2000;
	let min = 2;
	return Math.floor(Math.random() * (max - min) + min);
}

function setActiveMenu(){

	const pg = '/' + String(document.location)
	.split('/').pop();

	$('.nav-item > a').each((i,e)=>{
		if($(e).attr('href') == pg){
			$(e).parent().addClass('active');
		}
	});

}