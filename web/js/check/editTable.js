$(document).ready(function(){

	$(document).on('click','.editTable',function(){
		// теперь получить данные для определения ветви логики.
		// ветка логики для поставщиков готова.
		if($(this).hasClass('editTable-SuppCheck')){
			$thClasses = $(this).attr('class');
			$suppCheckId = $thClasses.split(' ')[4];
			$suppCheckId = $suppCheckId.split('_')[1];// получили нужный нам id.
			var checkNumber;
			var checkAmount;
			var paymentDate;
			$checkId = $(this).attr('checkid');
			console.log($checkId);
			var buttonWidth = $(this).outerWidth(); // получаем ширину поля по которому кликнули
			var buttonPosition = $(this).offset(); // получаем позицию элемента по которому кликнули(координаты левого верхнего угла)

			$.ajax({
				url: '/check/getsuppcheck',
				type: 'POST',
				data: {
					supplierId: $suppCheckId
				},
				success: function(response) {
					checkNumber = response.checkNumber;
					checkAmount = response.checkAmount;
					paymentDate = response.paymentDate;
					
					// Функция для преобразования формата даты
					function formatDate(date) {
						//date.setHours(date.getHours() - 3);
						date.setHours(date.getHours() - 3);
						var day = date.getDate();
						var month = date.getMonth() + 1;
						var year = date.getFullYear();
						var hours = date.getHours();
						var minutes = date.getMinutes();

						// Добавление нулей при необходимости
						day = day < 10 ? '0' + day : day;
						month = month < 10 ? '0' + month : month;
						hours = hours < 10 ? '0' + hours : hours;
						minutes = minutes < 10 ? '0' + minutes : minutes;

							return day + '-' + month + '-' + year + ' ' + hours + ':' + minutes;
					}
					// var paymentDate = new Date(parseInt(paymentDate * 1000));
					// var formattedDate = formatDate(paymentDate);
					//if(response.paymentDate != 0){
						console.log(response.paymentDate)
						var paymentDate = new Date(parseInt(paymentDate * 1000));
						var formattedDate = formatDate(paymentDate);
					//}
					// else {
					// 	formattedDate = "";
					// }

					var dateTimePickerHtml = '<div class="input-group date" id="checkDateContainer">';
					dateTimePickerHtml += '<input type="text" class="form-control" id="checkDate" name="checkDate" required value="'+formattedDate+'">';
					dateTimePickerHtml += '</div>';

					$form = '<form class="text-center supplierFormEdit supplierFormEdit-' + $suppCheckId + '" style="width: 210px; position: absolute;">';
          $form += '<div class="form-group">';
          $form += '<label for="checkNumber">Номер счета</label>';
          $form += '<input type="text" class="form-control" id="checkNumber" name="checkNumber" value="' + checkNumber + '" required>';
          $form += '</div>';
          $form += '<div class="form-group">';
          $form += '<label for="checkAmount">Сумма</label>';
          $form += '<input type="text" class="form-control" id="checkAmount" name="checkAmount" value="' + checkAmount + '" required>';
          $form += '</div>';
          $form += '<div class="form-group">';
          $form += '<label for="checkDate">Дата оплаты</label>';
          //form += '<input type="text" class="form-control" id="checkDate" name="checkDate" value="' + formattedDate + '" required>';
					$form += dateTimePickerHtml;
          // $form += '<div class="input-group datetimepicker">';
          // $form += '<input type="text" class="form-control" id="checkDate" name="checkDate" value="' + formattedDate + '" required>';
          // $form += '<span class="input-group-addon">';
          // $form += '<span class="glyphicon glyphicon-calendar"></span>';
          // $form += '</span>';
          // $form += '</div>';

          //$form += dateTimePickerHtml; // Заменить поле на виджет DateTimePicker
          $form += '</div>';
          $form += '<input type="hidden" name="suppCheckId" value="' + $suppCheckId + '">';
          $form += '<input type="hidden" name="check" value="' + $checkId + '">';
          $form += '</form>';
					if ($('.supplierFormEdit-' + $suppCheckId).length) {
						SendSupplForm($('.supplierFormEdit-' + $suppCheckId));
						$('.supplierFormEdit-' + $suppCheckId).remove();
						$.pjax.reload({
							container: '#my-gridview-pjax'
						});
					}
					else {
						$('body').append($form); // добавляем 
						var hiddenBlock = $('.supplierFormEdit-' + $suppCheckId); // получаем добавленный элемент
						hiddenBlock.find('#checkDate').datetimepicker({
							format: 'dd-mm-yyyy hh:ii', // Формат даты без букв
							buttons: {
								showToday: true,
							},
							locale: 'ru', // Указываем локаль для корректного отображения месяцев на русском языке (если необходимо)
							widgetParent: '#checkDateContainer', // Указываем родительский элемент виджета
							keepOpen: false, // Установите значение false для автоматического закрытия виджета
						}).on('change.datetimepicker', function() {
							$(this).datetimepicker('hide'); // Закрытие виджета после изменения значения
						});

						// hiddenBlock.find('.datetimepicker').datetimepicker({
						// 	format: 'dd-mm-yyyy hh:ii', // Формат даты без букв
						// 	buttons: {
						// 		showToday: true,
						// 	},
						// 	locale: 'ru', // Указываем локаль для корректного отображения месяцев на русском языке (если необходимо)
						// 	widgetParent: '#checkDateContainer', // Указываем родительский элемент виджета
						// 	keepOpen: false, // Установите значение false для автоматического закрытия виджета
						// }).on('change.datetimepicker', function() {
						// 	$(this).datetimepicker('hide'); // Закрытие виджета после изменения значения
						// });
						hiddenBlock.css({ // позиционируем.
							top: buttonPosition.top,
							left: buttonPosition.left + buttonWidth + 10 // добавляем 10 пикселей для отступа
						});
						$(document).on('click', function(e) {
							var div = $( '.supplierFormEdit-' + $suppCheckId );
							var div2 = $( '.editTable');
							if ( !div.is(e.target) && div.has(e.target).length === 0  && !div2.is(e.target) && div2.has(e.target).length === 0) { 
								if ($('.supplierFormEdit-' + $suppCheckId).length) {
									SendSupplForm(div);
									$.pjax.reload({
										container: '#my-gridview-pjax'
									});
									div.remove(); // скрываем его
								}
							}
						});
					}
				},
				error: function(xhr, status, error) {
					console.error(error);
				}
			});
		}
		// ветка логики для комментария к стадии
		if($(this).hasClass('editTable-stageComment')){
			$thClasses = $(this).attr('class');
			$checkId = $thClasses.split(' ')[2];
			$checkId = $checkId.split('-')[1];// получили нужный нам id.
			//var stage_comment;

			var buttonWidth = $(this).outerWidth(); // получаем ширину поля по которому кликнули
			var buttonPosition = $(this).offset();  // получаем позицию элемента по которому кликнули(координаты левого верхнего угла)
			$.ajax({
				url: '/check/get-value',
				type: 'POST',
				data: {
					checkId: $checkId,
					attr: "stage_comment",
				},
				success: function(response) {
					//console.log(response);
					$form = '<form class="text-center stageComment stageComment-' + $checkId + '" style="width: 210px; position: absolute;">';
					$form += '<div class="form-group">';
					$form += '<textarea type="text" class="form-control" id="checkNumber" name="stage_comment" required>' + response.value + '</textarea>' ;
					$form += '</div>';
					$form += '<input type="hidden" name="checkId" value="' + $checkId + '">';
					$form += '</form>';
					if ($('.stageComment-' + $checkId).length) {
						SendSupplForm($('.stageComment-' + $checkId));
						$.pjax.reload({
							container: '#my-gridview-pjax'
						});
						$('.stageComment-' + $checkId).remove();
					}
					else {
						$('body').append($form);
						var hiddenBlock = $('.stageComment-' + $checkId); // получаем добавленный элемент
						
						hiddenBlock.css({ // позиционируем.
							top: buttonPosition.top+40,
							left: buttonPosition.left // добавляем 10 пикселей для отступа
						});
						$(document).on('click', function(e) {
							var div = $( '.stageComment-' + $checkId );
							var div2 = $( '.editTable');
							if ( !div.is(e.target) && div.has(e.target).length === 0  && !div2.is(e.target) && div2.has(e.target).length === 0) { 
								if ($('.stageComment-' + $checkId).length) {
									SendSupplForm(div);
									$.pjax.reload({
										container: '#my-gridview-pjax'
									});
									div.remove(); // скрываем его
								}
							}
						});

					}
				}
			});
			//console.log($checkId);
		}
		// ветка логики для комментария
		if($(this).hasClass('editTable-comment')){
			$thClasses = $(this).attr('class');
			$checkId = $thClasses.split(' ')[2];
			$checkId = $checkId.split('-')[1];// получили нужный нам id.
			//var stage_comment;

			var buttonWidth = $(this).outerWidth(); // получаем ширину поля по которому кликнули
			var buttonPosition = $(this).offset();  // получаем позицию элемента по которому кликнули(координаты левого верхнего угла)
			$.ajax({
				url: '/check/get-value',
				type: 'POST',
				data: {
					checkId: $checkId,
					attr: "comment",
				},
				success: function(response) {
					//console.log(response);
					$form = '<form class="text-center stageComment comment-' + $checkId + '" style="width: 200px; position: absolute;">';
					$form += '<div class="form-group">';
					$form += '<textarea type="text" class="form-control" id="checkNumber" name="comment" required>' + response.value + '</textarea>' ;
					$form += '</div>';
					$form += '<input type="hidden" name="checkId" value="' + $checkId + '">';
					$form += '</form>';
					if ($('.comment-' + $checkId).length) {
						SendSupplForm($('.comment-' + $checkId));
						$.pjax.reload({
							container: '#my-gridview-pjax'
						});
						$('.comment-' + $checkId).remove();
					}
					else {
						$('body').append($form);
						var hiddenBlock = $('.comment-' + $checkId); // получаем добавленный элемент
						
						hiddenBlock.css({ // позиционируем.
							top: buttonPosition.top+40,
							left: buttonPosition.left-70 // добавляем 10 пикселей для отступа
						});
						$(document).on('click', function(e) {
							var div = $( '.comment-' + $checkId );
							var div2 = $( '.editTable');
							if ( !div.is(e.target) && div.has(e.target).length === 0  && !div2.is(e.target) && div2.has(e.target).length === 0) { 
								if ($('.comment-' + $checkId).length) {
									SendSupplForm(div);
									$.pjax.reload({
										container: '#my-gridview-pjax'
									});
									div.remove(); // скрываем его
								}
							}
						});

					}
				}
			});
		}

		// ветка логики для даты доставки
		if($(this).hasClass('editTable-dateDelivery')){
			$thClasses = $(this).attr('class');
			$checkId = $thClasses.split(' ')[2];
			$checkId = $checkId.split('-')[1];// получили нужный нам id.
			//var stage_comment;

			var buttonWidth = $(this).outerWidth(); // получаем ширину поля по которому кликнули
			var buttonPosition = $(this).offset();  // получаем позицию элемента по которому кликнули(координаты левого верхнего угла)
			$.ajax({
				url: '/check/get-value',
				type: 'POST',
				data: {
					checkId: $checkId,
					attr: "dateDelivery",
				},
				success: function(response) {
					//console.log(response);

					// Функция для преобразования формата даты
					function formatDate(date) {
						//date.setHours(date.getHours() - 3);
						date.setHours(date.getHours());
						var day = date.getDate();
						var month = date.getMonth() + 1;
						var year = date.getFullYear();
						var hours = date.getHours();
						var minutes = date.getMinutes();

						// Добавление нулей при необходимости
						day = day < 10 ? '0' + day : day;
						month = month < 10 ? '0' + month : month;
						hours = hours < 10 ? '0' + hours : hours;
						minutes = minutes < 10 ? '0' + minutes : minutes;

						return day + '-' + month + '-' + year + ' ' + hours + ':' + minutes;
					}

					var paymentDate = new Date(parseInt(response.value * 1000));
					var formattedDate = formatDate(paymentDate);

					//console.log(formattedDate);
					var dateTimePickerHtml = '<div class="input-group date" id="checkDateContainer">';
					dateTimePickerHtml += '<input type="text" class="form-control" id="checkDate" name="dateDelivery" required value="'+formattedDate+'">';
					dateTimePickerHtml += '</div>';

					$form = '<form class="text-center dateDelivery dateDelivery-' + $checkId + '" style="width: 200px; position: absolute;">';
					$form += '<div class="form-group">';
					//$form += '<input type="date" class="form-control" id="dateDelivery" name="dateDelivery" value="' + response.value + '" required></textarea>' ;
					$form += dateTimePickerHtml;
					$form += '</div>';
					$form += '<input type="hidden" name="checkId" value="' + $checkId + '">';
					$form += '</form>';
					if ($('.dateDelivery-' + $checkId).length) {
						SendSupplForm($('.dateDelivery-' + $checkId));
						$.pjax.reload({
							container: '#my-gridview-pjax'
						});
						$('.dateDelivery-' + $checkId).remove();
					}
					else {
						$('body').append($form);
						var hiddenBlock = $('.dateDelivery-' + $checkId); // получаем добавленный элемент
						hiddenBlock.find('#checkDate').datetimepicker({
							format: 'dd-mm-yyyy hh:ii', // Формат даты без букв
							buttons: {
								showToday: true,
							},
							locale: 'ru', // Указываем локаль для корректного отображения месяцев на русском языке (если необходимо)
							widgetParent: '#checkDateContainer', // Указываем родительский элемент виджета
							keepOpen: false, // Установите значение false для автоматического закрытия виджета
						}).on('change.datetimepicker', function() {
							$(this).datetimepicker('hide'); // Закрытие виджета после изменения значения
						});
						hiddenBlock.css({ // позиционируем.
							top: buttonPosition.top+30,
							left: buttonPosition.left-40 // добавляем 10 пикселей для отступа
						});
						$(document).on('click', function(e) {
							var div = $( '.dateDelivery-' + $checkId );
							var div2 = $( '.editTable');
							if ( !div.is(e.target) && div.has(e.target).length === 0  && !div2.is(e.target) && div2.has(e.target).length === 0) { 
								if ($('.dateDelivery-' + $checkId).length) {
									console.log(formattedDate);
									SendSupplForm(div);
									$.pjax.reload({
										container: '#my-gridview-pjax'
									});
									div.remove(); // скрываем его
								}
							}
						});

					}
				}
			});
		}
	});
});


function SendSupplForm($form){
	var formData = $form.serialize();
	var url = 'check/update-active-form'; // указываете URL, куда отправлять форму
	
	$.ajax({
		url: url,
		type: 'POST',
		data: formData,
		success: function(response) {
				// выполните здесь необходимые действия после успешной отправки формы
				// например, обновите данные на странице или закройте форму
				$('.supplierFormEdit').remove();
		},
		error: function(error) {
				console.log('Ошибка при отправке формы:', error);
				// выполните здесь необходимые действия при ошибке отправки формы
		}
	});
}