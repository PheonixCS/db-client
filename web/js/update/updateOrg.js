$(document).ready(function () {
    var containerIndexO = 0;
    $('#contractor-add').on('click', function (e) {
        $('#contractor-data-container').remove();
        e.preventDefault();
        $(this).hide();
        $('.contractor-dell').show();
        $('.contractor-dell').click(function () {
            var $contractorSelect = $('.contractorSelect');
            var selectedId = $contractorSelect.val();
            // Удаляем и создаем заново контейнер данных
            $(this).closest('.form-group').next('#contractor-data-container').remove();
            $('<div id="contractor-data-container"></div>').insertAfter($(this).closest('.form-group'));
            // Отправляем AJAX-запрос на получение данных контрактора
            $.ajax({
                url: '/check/get-contractor',
                method: 'GET',
                data: {
                    id: selectedId
                },
                dataType: 'json',
                success: function (data) {
                    if (data) {
                        // Формируем html-разметку данных контрактора
                        var html = '<div class="contractor-form-exist card">';
                        html += '<div class="card-header">Данные контрактора</div>';
                        html += '<div class="card-body">';

                        html += '<div class="form-group">';
                        html += '<label for="inputType">Тип</label>';
                        html += '<input type="text" class="form-control" id="inputType" value="' + data.type + '" readonly>';
                        html += '</div>';

                        html += '<div class="form-group">';
                        html += '<label for="inputContactPerson">Contact Person</label>';
                        html += '<input type="text" class="form-control" id="inputContactPerson" value="' + data.contact_person + '" readonly>';
                        html += '</div>';

                        if (data.type === 'Юр. Лицо') {
                            html += '<div class="form-group">';
                            html += '<label for="inputCompany">Компания</label>';
                            html += '<input type="text" class="form-control" id="inputCompany" value="' + data.company + '" readonly>';
                            html += '</div>';

                            html += '<div class="form-group">';
                            html += '<label for="inputCompanyConsignee">Компания грузоперевозчик</label>';
                            html += '<input type="text" class="form-control" id="inputCompanyConsignee" value="' + data.company_consignee + '" readonly>';
                            html += '</div>';

                            html += '<div class="form-group">';
                            html += '<label for="inputCompanyConsignee">Адрес компании грузоперевозчика</label>';
                            html += '<input type="text" class="form-control" id="inputCompanyAddressConsignee" value="' + data.company_address_consignee + '" readonly>';
                            html += '</div>';

                            html += '<div class="form-group">';
                            html += '<label for="inputCompanyConsignee">ИИН</label>';
                            html += '<input type="text" class="form-control" id="inputIin" value="' + data.iin + '" readonly>';
                            html += '</div>';

                            html += '<div class="form-group">';
                            html += '<label for="inputCompanyConsignee">Юридический адрес</label>';
                            html += '<input type="text" class="form-control" id="inputLegalAddress" value="' + data.legal_address + '" readonly>';
                            html += '</div>';

                            html += '<div class="form-group">';
                            html += '<label for="inputCompanyConsignee">Фактический адрес</label>';
                            html += '<input type="text" class="form-control" id="inputActualAddress" value="' + data.actual_address + '" readonly>';
                            html += '</div>';

                            // Добавьте здесь остальные поля для юридического контрактора

                        }
                        html += '<div class="form-group">';
                        html += '<label for="inputPhone1">Phone 1</label>';
                        html += '<input type="text" class="form-control" id="inputPhone1" value="' + data.phone1 + '" readonly>';
                        html += '</div>';

                        html += '<div class="form-group">';
                        html += '<label for="inputPhone2">Phone 2</label>';
                        html += '<input type="text" class="form-control" id="inputPhone2" value="' + data.phone2 + '" readonly>';
                        html += '</div>';

                        html += '<div class="form-group">';
                        html += '<label for="inputPhone2">Email</label>';
                        html += '<input type="text" class="form-control" id="inputEmail" value="' + data.email + '" readonly>';
                        html += '</div>';

                        html += '</div>';

                        // Обновляем контейнер данных
                        $('#contractor-data-container').html(html);
                    }

                }
            });
            containerIndexO = containerIndexO - 1;
            console.log(containerIndexO);
            $('#contractor-add').show();
            $(this).hide();
            $('#w0').find('.field-check-organization').find('select').removeAttr('disabled');
            $('.contractorForm-active').remove();
        });
        //var contractorSelect = $('#w0 .contractorSelect').first();
        $('#w0').find('.field-check-organization').find('select').prop('disabled', true);
        console.log(containerIndexO);

        var contractorForm = $('#w0 .contractorForm').first().clone();
        contractorForm.addClass("contractorForm-active");
        // Обновляем индекс для каждого поля в клонированном контейнере
        contractorForm.find('input, select, textarea').each(function () {
            var inputName = $(this).attr('name');
            var newInputName = inputName.replace('[0]', '[' + containerIndexO + ']');
            $(this).attr('name', newInputName);
        });
        containerIndexO = containerIndexO + 1;
        contractorForm.appendTo('.contractor-container');
        contractorForm.find('.individualForm').show();
        contractorForm.find('.individualForm').find('input, textarea').removeAttr('disabled');
        contractorForm.find('.legalForm').find('input, textarea').prop('disabled', true);
        contractorForm.find('.legalForm').hide();
        contractorForm.show();
        // Обработчик события изменения выбранного радио-кнопки
        contractorForm.find('.radio').change(function () {
            var parentForm = $(this).closest('.contractorForm');
            var individualForm = parentForm.find('.individualForm');
            var legalForm = parentForm.find('.legalForm');

            if ($(this).val() === "individual") {
                individualForm.find('input, textarea').removeAttr('disabled');
                individualForm.show();
                legalForm.find('input, textarea').prop('disabled', true);
                legalForm.hide();
            } else if ($(this).val() === "legal") {
                individualForm.hide();
                individualForm.find('input, textarea').prop('disabled', true);
                legalForm.show();
                legalForm.find('input, textarea').removeAttr('disabled');
            }
        });

    });
});
