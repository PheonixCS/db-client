//providersEditForm - класс формы.
//provider-container - контейнер.
//provider-delete - кнопка удалить поставщика. корзина
//

$(document).ready(function() {
  var containerIndexS = 0;
  $('.provider-add').click(function(e) {
    e.preventDefault();
    var providersEditForm = $('#w0 .providersEditForm').first().clone();

		// // Инициализация DateTimePicker
		// function initDateTimePicker(form, containerIndex) {
		// 	alert(containerIndex);
		// 	var newField = form.find('#modelproviders-dateofpayment-' + containerIndex); // Ищем поле
		// 	var datepickerOptions = {
		// 		format: 'yyyy-mm-dd hh:ii',
		// 		todayHighlight: true,
		// 		autoclose: true
		// 	};
		// 	newField.datetimepicker(datepickerOptions); // Применяем виджет к полю
		// }

    // Обновляем индекс для каждого поля в клонированном контейнере
    providersEditForm.find('input, select, textarea').each(function() {
      var inputName = $(this).attr('name');
      var newInputName = inputName.replace('[0]', '[' + containerIndexS + ']');
      $(this).attr('name', newInputName);
    });
    containerIndexS = containerIndexS + 1;
    providersEditForm.appendTo('.provider-container');
    providersEditForm.show();
    providersEditForm.find('input, select').removeAttr('disabled');

		// Инициализировать виджет DateTimePicker для динамически добавленного поля
		providersEditForm.find('#suppcheck-dateofpayment-0')
		.datetimepicker({
			format: 'yyyy-mm-dd hh:ii',
			todayHighlight: true,
			autoclose: true,
			showButtonPanel: true
		});
		// // Инициализация DateTimePicker для нового элемента
    // initDateTimePicker(providersEditForm, containerIndexS);

    $('.provider-delete').click(function() {
      var providersEditForm = $(this).closest('.providersEditForm');
      providersEditForm.remove();
      containerIndexS = containerIndexS - 1;
    });
   
    
  });

});
