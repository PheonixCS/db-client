$(document).ready(function() {
  // При первом клике на кнопку "Добавить продукт"
	var containerIndexP = 0;
  $(".product-add").click(function() {
    // Клонируем шаблон продукта
    var productClone = $(".product-template").clone();

		// Обновляем индекс для каждого поля в клонированном контейнере
		productClone.find('input, select, textarea').each(function(){
      var inputName = $(this).attr('name');
      var newInputName = inputName.replace('[0]', '[' + containerIndexP + ']');
      $(this).attr('name', newInputName);
   });
	 containerIndexP = containerIndexP + 1;


    // Удаляем класс "product-template" и добавляем класс "product-active"
    productClone.removeClass("product-template").addClass("product-active");
    // Удаляем стили "display: none" у всех элементов внутри клонированного шаблона
    productClone.find("*").css("display", "");
		productClone.css("display", "");
    // Удаляем атрибуты "disabled" у всех input и textarea внутри клонированного шаблона
    productClone.find("input, textarea").removeAttr("disabled");
    // Вставляем клонированный шаблон в контейнер продуктов
    $(".conteiner-Product").append(productClone);
		// $amount = productClone.find('.p-amount');
		productClone.find(".p-price").on('change', function(){
			$p = $(this).val();
			$q = $(this).closest('tr').find('.p-quantity').val();
			$a = $q*$p;
			$(this).closest('tr').find('.p-amount').val($a);
		});
		productClone.find(".p-quantity").on('change', function(){
			$q = $(this).val();
			$p = $(this).closest('tr').find('.p-price').val();
			$a = $q*$p;
			$(this).closest('tr').find('.p-amount').val($a);
	 	});
  });

  // При клике на кнопку "Удалить продукт"
  $(document).on("click", ".product-delete", function() {
    // Находим ближайший родительский элемент с классом "product-active" и удаляем его
    $(this).closest(".product-active").remove();
		containerIndexP = containerIndexP - 1;
  });
});